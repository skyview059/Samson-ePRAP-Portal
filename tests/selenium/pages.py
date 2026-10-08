"""Small page helpers for the sign-up and booking forms."""
import re
import time

import pytest
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select, WebDriverWait

from conftest import BASE_URL, guard_signature, wait_human_delay

GUARD_ERROR = "We could not verify your submission"
EXPIRED_ERROR = "This page has expired"
NAME_ERROR = "may only contain letters, spaces, apostrophes, dots and hyphens"
IP_LIMIT_ERROR = "Too many registrations from your network"
EXISTING_EMAIL_BOOKING_ERROR = "This email is already registered. Please log in to continue with your booking."
EXISTING_EMAIL_SIGNUP_ERROR = "This email already in used"
SPAM_NAME = "???? New message from Binance # P9043. SIGN IN ->>"


def js_click(driver, element):
    driver.execute_script("arguments[0].scrollIntoView({block:'center'}); arguments[0].click();", element)


def set_value(driver, css, value):
    element = driver.find_element(By.CSS_SELECTOR, css)
    element.clear()
    element.send_keys(value)


def set_guard_timestamp(driver, form, seconds_ago):
    """Re-sign the form token as if the page was rendered `seconds_ago` (valid signature, chosen age)."""
    timestamp = int(time.time()) - seconds_ago
    driver.execute_script(
        "document.querySelector('[name=_fg_ts]').value = arguments[0];"
        "document.querySelector('[name=_fg_sig]').value = arguments[1];",
        str(timestamp), guard_signature(form, timestamp))


def fill_honeypot(driver):
    driver.execute_script("document.querySelector('[name=user_ref_code]').value = 'http://spam.example';")


# ---------------------------------------------------------------- sign-up
class SignUpPage:
    def __init__(self, driver):
        self.driver = driver
        self.loaded_at = None

    def open(self):
        self.driver.get(f"{BASE_URL}/sign-up")
        WebDriverWait(self.driver, 20).until(EC.presence_of_element_located((By.ID, "sign_up")))
        self.loaded_at = time.monotonic()
        return self

    def captcha_answer(self):
        label = self.driver.find_element(By.CSS_SELECTOR, "label[for=answer]").text
        a, op, b = re.search(r"(\d+)\s*([+-])\s*(\d+)", label).groups()
        return int(a) + int(b) if op == "+" else int(a) - int(b)

    def fill(self, fname, lname, email, password="Test@1234", captcha=None):
        set_value(self.driver, "#fname", fname)
        set_value(self.driver, "#lname", lname)
        set_value(self.driver, "#your_email", email)
        set_value(self.driver, "#whatsapp", "7700900123")
        set_value(self.driver, "#password", password)
        set_value(self.driver, "#passconf", password)
        set_value(self.driver, "#answer", str(self.captcha_answer() if captcha is None else captcha))
        agree = self.driver.find_element(By.ID, "agree")
        if not agree.is_selected():
            js_click(self.driver, agree)
        return self

    def submit(self, wait_like_human=True):
        if wait_like_human:
            wait_human_delay(self.loaded_at)
        form = self.driver.find_element(By.ID, "sign_up")
        self.driver.execute_script("sign_up();")
        # the form posts and the browser navigates (redirect to login, or the form re-rendered with errors)
        WebDriverWait(self.driver, 30).until(EC.staleness_of(form))
        WebDriverWait(self.driver, 30).until(lambda d: d.execute_script("return document.readyState") == "complete")
        return self

    def field_errors(self):
        return " | ".join(e.text for e in self.driver.find_elements(By.CSS_SELECTOR, "#sign_up p.error"))


# ---------------------------------------------------------------- booking (course + subscription)
class BookingPage:
    """/book-course and /book-subscription share the same form (posted to course-booking-action)."""

    def __init__(self, driver, path, iframe=True):
        self.driver = driver
        self.path = path
        self.iframe = iframe
        self.loaded_at = None

    def open(self):
        self.driver.get(f"{BASE_URL}/{self.path}" + ("?iframe=1" if self.iframe else ""))
        WebDriverWait(self.driver, 20).until(EC.presence_of_element_located((By.ID, "bookingForm")))
        self.loaded_at = time.monotonic()
        return self

    # -- guest details
    def fill_guest(self, first_name, last_name, email, phone="7700900123"):
        set_value(self.driver, "#bookingForm [name=first_name]", first_name)
        set_value(self.driver, "#bookingForm [name=last_name]", last_name)
        set_value(self.driver, "#bookingForm [name=email]", email)
        set_value(self.driver, "#bookingForm [name=phone]", phone)
        return self

    def has_guest_fields(self):
        return bool(self.driver.find_elements(By.CSS_SELECTOR, "#bookingForm [name=first_name]"))

    # -- log in from the booking page: same guest-login ajax call as the page's login(),
    #    which reloads with setInterval(1s) and loops forever on a slow local server
    def login(self, email, password):
        result = self.driver.execute_async_script(
            "const done = arguments[arguments.length - 1];"
            "$.post('guest-login', {email: arguments[0], password: arguments[1]})"
            " .always(r => done(typeof r === 'string' ? r : JSON.stringify(r)));", email, password)
        assert '"OK"' in result, f"guest-login failed: {result}"
        return self.open()

    # -- cart
    def select_course(self, course_id):
        boxes = self.driver.find_elements(By.CSS_SELECTOR, f'#bookingForm input[name="id[{course_id}]"]')
        if not boxes or not boxes[0].is_enabled():
            pytest.skip(f"Course {course_id} is not bookable on this page")
        box = boxes[0]
        if not box.is_selected():
            js_click(self.driver, box)
        selects = self.driver.find_elements(By.CSS_SELECTOR, f'select[name="slot_id[{course_id}]"]')
        if selects:
            options = [o for o in selects[0].find_elements(By.TAG_NAME, "option")
                       if o.get_attribute("value") and o.is_enabled()]
            Select(selects[0]).select_by_value(options[0].get_attribute("value"))
        else:
            radio = self.driver.execute_script(
                f"return [...document.querySelectorAll('input[name=\"slot_id[{course_id}]\"]')].find(e => !e.disabled) || null;")
            if radio is None:
                pytest.skip(f"Course {course_id} has no selectable slot")
            js_click(self.driver, radio)
        return course_id

    def select_package(self):
        box = self.driver.execute_script("return document.querySelector('#bookingForm .practice2buy');")
        if box is None:
            pytest.skip("No subscription package in the local DB")
        if not box.is_selected():
            js_click(self.driver, box)
        return box.get_attribute("value")

    def accept_terms(self):
        terms = self.driver.find_element(By.ID, "terms_and_conditions")
        if not terms.is_selected():
            js_click(self.driver, terms)
        return self

    # -- submit
    def submit(self, wait_like_human=True):
        if wait_like_human:
            wait_human_delay(self.loaded_at)
        js_click(self.driver, self.driver.find_element(By.ID, "guestBookingSave"))
        try:
            WebDriverWait(self.driver, 30).until(
                lambda d: "booking/checkout" in d.current_url
                or d.find_elements(By.CSS_SELECTOR, "#bookingForm .error-message"))
        except TimeoutException:
            pass
        return self

    def went_to_checkout(self):
        return "booking/checkout" in self.driver.current_url

    def checkout_payment_id(self):
        match = re.search(r"booking/checkout/(\d+)", self.driver.current_url)
        return int(match.group(1)) if match else None

    def errors(self):
        return " | ".join(e.text for e in self.driver.find_elements(By.CSS_SELECTOR, "#bookingForm .error-message"))
