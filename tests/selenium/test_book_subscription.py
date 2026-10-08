"""/book-subscription : new student (guest), existing student (guest + logged in), and bot protection."""
import pytest

from conftest import unique_email
from pages import EXISTING_EMAIL_BOOKING_ERROR, GUARD_ERROR, NAME_ERROR, SPAM_NAME, BookingPage, fill_honeypot

pytestmark = pytest.mark.book_subscription
PATH = "book-subscription"


@pytest.mark.parametrize("iframe", [True, False], ids=["new_ui_iframe", "old_ui"])
def test_new_student_can_book_subscription(driver, db, iframe):
    email = unique_email("subscription.new")
    page = BookingPage(driver, PATH, iframe=iframe).open()
    page.fill_guest("Selenium", "Subscriber", email)
    page.select_package()
    page.accept_terms().submit()

    assert page.went_to_checkout(), f"no checkout redirect, errors: {page.errors()}"
    student = db.student(email)
    assert student, "student row was not created"
    assert student["created_at"] is not None and str(student["created_at"]) != "0000-00-00 00:00:00"
    assert page.checkout_payment_id() in [p["id"] for p in db.payments_of(student["id"])]


def test_existing_email_as_guest_must_log_in(driver, db, existing_student):
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Attacker", "Person", existing_student["email"])
    page.select_package()
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert EXISTING_EMAIL_BOOKING_ERROR in page.errors()
    after = db.student(existing_student["email"])
    assert after["fname"] == existing_student["row"]["fname"]
    assert after["password"] == existing_student["row"]["password"], "password must not be overwritten"
    assert db.payments_of(existing_student["id"]) == []


def test_logged_in_existing_student_can_book_subscription(driver, db, existing_student):
    page = BookingPage(driver, PATH).open()
    page.login(existing_student["email"], existing_student["password"])
    assert not page.has_guest_fields(), "guest fields should be hidden once logged in"

    page.select_package()
    page.accept_terms().submit()

    assert page.went_to_checkout(), f"no checkout redirect, errors: {page.errors()}"
    assert db.student_count(existing_student["email"]) == 1
    assert page.checkout_payment_id() in [p["id"] for p in db.payments_of(existing_student["id"])]


@pytest.mark.bot
def test_spam_name_is_rejected(driver, db):
    email = unique_email("subscription.spamname")
    page = BookingPage(driver, PATH).open()
    page.fill_guest(SPAM_NAME, "zzmzxt", email)
    page.select_package()
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert NAME_ERROR in page.errors()
    assert db.student(email) is None


@pytest.mark.bot
def test_honeypot_blocks_booking(driver, db):
    email = unique_email("subscription.honeypot")
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Honey", "Pot", email)
    page.select_package()
    fill_honeypot(driver)
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert GUARD_ERROR in page.errors()
    assert db.student(email) is None
