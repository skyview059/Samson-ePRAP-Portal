"""
Shared fixtures for the browser tests of the public registration forms.

Configuration (environment variables, all optional):
    BASE_URL   default: BASE_URL from the project .env, else https://eprapportal.test
    HEADLESS   "0" to watch the browser, default "1"
    DB_*       default: DB_HOSTNAME / DB_USERNAME / DB_PASSWORD / DB_DATABASE from the project .env

Every row these tests create uses an e-mail like  selenium.<something>@example.test
and is deleted again (with its course_payments / course_booked rows) before and after the run.
"""
import os
import re
import subprocess
import time
import uuid
from datetime import datetime, timedelta
from pathlib import Path

import pymysql
import urllib3
import pytest
from selenium import webdriver

PROJECT_ROOT = Path(__file__).resolve().parents[2]
TEST_EMAIL_DOMAIN = "example.test"
TEST_EMAIL_LIKE = f"selenium.%@{TEST_EMAIL_DOMAIN}"
SCREENSHOT_DIR = Path(__file__).parent / "screenshots"


def _read_dotenv():
    values = {}
    env_file = PROJECT_ROOT / ".env"
    if env_file.exists():
        for line in env_file.read_text(encoding="utf-8", errors="ignore").splitlines():
            match = re.match(r"^\s*([A-Z_][A-Z0-9_]*)\s*=\s*(.*)$", line)
            if match:
                values[match.group(1)] = match.group(2).strip().strip("'\"")
    return values


DOTENV = _read_dotenv()


def setting(name, default=None):
    return os.environ.get(name) or DOTENV.get(name) or default


BASE_URL = setting("BASE_URL", "https://eprapportal.test").rstrip("/")
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def unique_email(tag):
    return f"selenium.{tag}.{uuid.uuid4().hex[:10]}@{TEST_EMAIL_DOMAIN}"


def php_password_hash(password):
    """Hash exactly like the app does (password_encription() -> password_hash BCRYPT)."""
    return subprocess.check_output(
        ["php", "-r", "echo password_hash($argv[1], PASSWORD_BCRYPT);", password], text=True
    ).strip()


# ---------------------------------------------------------------- database
class Db:
    def __init__(self):
        host = setting("DB_HOSTNAME", "127.0.0.1")
        self.conn = pymysql.connect(
            host="127.0.0.1" if host == "localhost" else host,
            user=setting("DB_USERNAME", "root"),
            password=setting("DB_PASSWORD", ""),
            database=setting("DB_DATABASE", "eprap_portal"),
            autocommit=True,
            cursorclass=pymysql.cursors.DictCursor,
            connect_timeout=10,
            # same as the app (database.php 'stricton' => FALSE)
            init_command="SET SESSION sql_mode = ''",
        )

    def all(self, sql, params=None):
        with self.conn.cursor() as cur:
            cur.execute(sql, params)
            return list(cur.fetchall())

    def one(self, sql, params=None):
        rows = self.all(sql, params)
        return rows[0] if rows else None

    def execute(self, sql, params=None):
        with self.conn.cursor() as cur:
            cur.execute(sql, params)
            return cur.lastrowid

    def student(self, email):
        return self.one("SELECT * FROM students WHERE email = %s", (email,))

    def student_count(self, email):
        return self.one("SELECT COUNT(*) c FROM students WHERE email = %s", (email,))["c"]

    def payments_of(self, student_id):
        return self.all("SELECT * FROM course_payments WHERE student_id = %s ORDER BY id", (student_id,))

    def insert_student(self, email, password, fname="Selenium", lname="Existing",
                       ip=None, created_at=None):
        now = datetime.now()
        created_at = created_at or (now - timedelta(days=30))
        return self.execute(
            "INSERT INTO students (fname, lname, email, password, phone_code, phone, status, "
            "tmp_ip_addr, created_at, updated_at) VALUES (%s,%s,%s,%s,'44','7700900000','Active',%s,%s,%s)",
            (fname, lname, email, php_password_hash(password), ip,
             created_at.strftime("%Y-%m-%d %H:%M:%S"), created_at.strftime("%Y-%m-%d %H:%M:%S")),
        )

    def cleanup_test_data(self):
        ids = [r["id"] for r in self.all("SELECT id FROM students WHERE email LIKE %s", (TEST_EMAIL_LIKE,))]
        if not ids:
            return
        marks = ",".join(["%s"] * len(ids))
        self.execute(
            f"DELETE FROM course_booked WHERE course_payment_id IN "
            f"(SELECT id FROM course_payments WHERE student_id IN ({marks}))", ids)
        self.execute(f"DELETE FROM course_payments WHERE student_id IN ({marks})", ids)
        self.execute(f"DELETE FROM students WHERE id IN ({marks})", ids)


@pytest.fixture(scope="session")
def db():
    database = Db()
    database.cleanup_test_data()
    yield database
    database.cleanup_test_data()
    database.conn.close()


# ---------------------------------------------------------------- browser
@pytest.fixture
def driver():
    options = webdriver.ChromeOptions()
    if setting("HEADLESS", "1") != "0":
        options.add_argument("--headless=new")
    options.add_argument("--window-size=1400,1000")
    options.add_argument("--ignore-certificate-errors")
    options.add_argument("--disable-gpu")
    options.add_argument("--no-sandbox")
    browser = webdriver.Chrome(options=options)
    browser.set_page_load_timeout(180)
    browser.set_script_timeout(60)
    yield browser
    browser.quit()


@pytest.hookimpl(hookwrapper=True)
def pytest_runtest_makereport(item, call):
    outcome = yield
    report = outcome.get_result()
    if report.when == "call" and report.failed and "driver" in item.fixturenames:
        browser = item.funcargs.get("driver")
        if browser:
            SCREENSHOT_DIR.mkdir(exist_ok=True)
            browser.save_screenshot(str(SCREENSHOT_DIR / f"{item.name}.png"))


# ---------------------------------------------------------------- test data
@pytest.fixture
def existing_student(db):
    """An already registered, active student with a known password."""
    email = unique_email("existing")
    password = "Exist@1234"
    student_id = db.insert_student(email, password)
    row = db.student(email)
    return {"id": student_id, "email": email, "password": password, "row": row}


@pytest.fixture(scope="session")
def course_slot(db):
    """
    A bookable live course: the first course listed on /book-course gets a temporary
    future date (the local DB usually has no upcoming dates). Removed after the run.
    """
    import requests
    html = requests.get(f"{BASE_URL}/book-course?iframe=1", verify=False, timeout=30).text
    match = re.search(r'name="id\[(\d+)\]"', html)
    if not match:
        pytest.skip("No live course is listed on /book-course")
    course_id = int(match.group(1))
    start = datetime.now() + timedelta(days=30)
    date_id = db.execute(
        "INSERT INTO course_dates (course_id, start_date, end_date, status) VALUES (%s, %s, %s, 1)",
        (course_id, start.strftime("%Y-%m-%d 09:00:00"), (start + timedelta(days=1)).strftime("%Y-%m-%d 17:00:00")))
    yield course_id
    db.execute("DELETE FROM course_booked WHERE course_date_id = %s", (date_id,))
    db.execute("DELETE FROM course_dates WHERE id = %s", (date_id,))


@pytest.fixture(autouse=True)
def release_ip_quota(db):
    """Students created by earlier tests must not eat the 5/hour per IP quota of the next test."""
    db.execute("UPDATE students SET tmp_ip_addr = NULL WHERE email LIKE %s AND email NOT LIKE %s",
               (TEST_EMAIL_LIKE, f"selenium.iplimit.%@{TEST_EMAIL_DOMAIN}"))


@pytest.fixture
def local_ip_limit_reached(db):
    """Fill the per IP registration quota (5/hour) for the local IPs the browser connects from."""
    now = datetime.now()
    for ip in ("127.0.0.1", "::1"):
        for _ in range(5):
            db.insert_student(unique_email("iplimit"), "Limit@1234", ip=ip, created_at=now)
    yield
    db.execute("DELETE FROM students WHERE email LIKE %s", (f"selenium.iplimit.%@{TEST_EMAIL_DOMAIN}",))


def guard_signature(form, timestamp):
    """Same HMAC as form_guard_signature() in application/helpers/form_guard_helper.php."""
    import hashlib
    import hmac
    config = (PROJECT_ROOT / "application/config/config.php").read_text(encoding="utf-8")
    key = re.search(r"\$config\['form_guard_key'\]\s*=\s*'([^']*)'", config).group(1)
    return hmac.new(key.encode(), f"{form}|{timestamp}".encode(), hashlib.sha256).hexdigest()


def wait_human_delay(loaded_at, seconds=3.6):
    """The time trap rejects submits faster than 3s after the page was rendered."""
    remaining = seconds - (time.monotonic() - loaded_at)
    if remaining > 0:
        time.sleep(remaining)
