"""/sign-up : new student, existing student, and bot protection."""
import pytest

from conftest import unique_email
from pages import (EXISTING_EMAIL_SIGNUP_ERROR, EXPIRED_ERROR, GUARD_ERROR, IP_LIMIT_ERROR, NAME_ERROR,
                   SPAM_NAME, SignUpPage, fill_honeypot, set_guard_timestamp)

pytestmark = pytest.mark.signup


def test_new_student_can_sign_up(driver, db):
    email = unique_email("signup")
    SignUpPage(driver).open().fill("Selenium", "Newstudent", email).submit()

    assert "login" in driver.current_url, f"expected redirect to login, got {driver.current_url}"
    student = db.student(email)
    assert student, "student row was not created"
    assert student["fname"] == "Selenium" and student["lname"] == "Newstudent"
    assert str(student["created_at"]) != "0000-00-00 00:00:00" and student["created_at"] is not None
    assert student["tmp_ip_addr"], "registration IP should be stored"


def test_existing_student_cannot_sign_up_again(driver, db, existing_student):
    SignUpPage(driver).open().fill("Someone", "Else", existing_student["email"]).submit()
    page = SignUpPage(driver)

    assert "login" not in driver.current_url
    assert EXISTING_EMAIL_SIGNUP_ERROR in page.field_errors()
    assert db.student_count(existing_student["email"]) == 1
    after = db.student(existing_student["email"])
    assert after["fname"] == existing_student["row"]["fname"]
    assert after["password"] == existing_student["row"]["password"]


def test_unicode_name_is_accepted(driver, db):
    email = unique_email("signup.unicode")
    SignUpPage(driver).open().fill("José", "O'Brien-Smith", email).submit()

    assert "login" in driver.current_url, SignUpPage(driver).field_errors()
    assert db.student(email)["lname"] == "O'Brien-Smith"


@pytest.mark.bot
def test_spam_name_is_rejected(driver, db):
    email = unique_email("signup.spamname")
    SignUpPage(driver).open().fill(SPAM_NAME, "dcpa1e", email).submit()

    assert NAME_ERROR in SignUpPage(driver).field_errors()
    assert db.student(email) is None


@pytest.mark.bot
def test_honeypot_blocks_sign_up(driver, db):
    email = unique_email("signup.honeypot")
    page = SignUpPage(driver).open().fill("Honey", "Pot", email)
    fill_honeypot(driver)
    page.submit()

    assert driver.current_url.rstrip("/").endswith("sign-up")
    assert GUARD_ERROR in driver.page_source
    assert db.student(email) is None


@pytest.mark.bot
def test_too_fast_submit_blocks_sign_up(driver, db):
    email = unique_email("signup.fast")
    page = SignUpPage(driver).open().fill("Fast", "Bot", email)
    set_guard_timestamp(driver, "sign_up", seconds_ago=0)  # submitted within the same second as rendered
    page.submit(wait_like_human=False)

    assert GUARD_ERROR in driver.page_source
    assert db.student(email) is None


@pytest.mark.bot
def test_expired_page_blocks_sign_up(driver, db):
    email = unique_email("signup.expired")
    page = SignUpPage(driver).open().fill("Expired", "Page", email)
    set_guard_timestamp(driver, "sign_up", seconds_ago=13 * 3600)
    page.submit()

    assert EXPIRED_ERROR in driver.page_source
    assert db.student(email) is None


@pytest.mark.bot
def test_tampered_token_blocks_sign_up(driver, db):
    email = unique_email("signup.token")
    page = SignUpPage(driver).open().fill("Token", "Tamper", email)
    driver.execute_script("document.querySelector('[name=_fg_ts]').value = '1000000000';")
    page.submit()

    assert GUARD_ERROR in driver.page_source
    assert db.student(email) is None


@pytest.mark.bot
def test_ip_limit_blocks_sign_up(driver, db, local_ip_limit_reached):
    email = unique_email("signup.limit")
    SignUpPage(driver).open().fill("Limit", "Reached", email).submit()

    assert IP_LIMIT_ERROR in driver.page_source
    assert db.student(email) is None
