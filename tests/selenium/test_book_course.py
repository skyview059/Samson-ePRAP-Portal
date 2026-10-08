"""/book-course : new student (guest), existing student (guest + logged in), and bot protection."""
import pytest

from conftest import unique_email
from pages import (EXISTING_EMAIL_BOOKING_ERROR, EXPIRED_ERROR, GUARD_ERROR, IP_LIMIT_ERROR, NAME_ERROR,
                   SPAM_NAME, BookingPage, fill_honeypot, set_guard_timestamp)

pytestmark = pytest.mark.book_course
PATH = "book-course"


@pytest.mark.parametrize("iframe", [True, False], ids=["new_ui_iframe", "old_ui"])
def test_new_student_can_book_course(driver, db, course_slot, iframe):
    email = unique_email("course.new")
    page = BookingPage(driver, PATH, iframe=iframe).open()
    page.fill_guest("Selenium", "Coursebuyer", email)
    page.select_course(course_slot)
    page.accept_terms().submit()

    assert page.went_to_checkout(), f"no checkout redirect, errors: {page.errors()}"
    student = db.student(email)
    assert student, "student row was not created"
    assert student["fname"] == "Selenium" and student["lname"] == "Coursebuyer"
    assert student["created_at"] is not None and str(student["created_at"]) != "0000-00-00 00:00:00"
    assert student["tmp_ip_addr"], "registration IP should be stored"
    assert page.checkout_payment_id() in [p["id"] for p in db.payments_of(student["id"])]


def test_existing_email_as_guest_must_log_in(driver, db, course_slot, existing_student):
    """Account takeover fix: booking with someone's e-mail must not change or log into that account."""
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Attacker", "Person", existing_student["email"])
    page.select_course(course_slot)
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert EXISTING_EMAIL_BOOKING_ERROR in page.errors()
    after = db.student(existing_student["email"])
    assert after["fname"] == existing_student["row"]["fname"]
    assert after["lname"] == existing_student["row"]["lname"]
    assert after["password"] == existing_student["row"]["password"], "password must not be overwritten"
    assert db.payments_of(existing_student["id"]) == []


def test_logged_in_existing_student_can_book_course(driver, db, course_slot, existing_student):
    page = BookingPage(driver, PATH).open()
    page.login(existing_student["email"], existing_student["password"])
    assert not page.has_guest_fields(), "guest fields should be hidden once logged in"

    page.select_course(course_slot)
    page.accept_terms().submit()

    assert page.went_to_checkout(), f"no checkout redirect, errors: {page.errors()}"
    assert db.student_count(existing_student["email"]) == 1
    assert page.checkout_payment_id() in [p["id"] for p in db.payments_of(existing_student["id"])]
    after = db.student(existing_student["email"])
    assert after["password"] == existing_student["row"]["password"]


@pytest.mark.bot
def test_spam_name_is_rejected(driver, db, course_slot):
    email = unique_email("course.spamname")
    page = BookingPage(driver, PATH).open()
    page.fill_guest(SPAM_NAME, "dcpa1e", email)
    page.select_course(course_slot)
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert NAME_ERROR in page.errors()
    assert db.student(email) is None


@pytest.mark.bot
def test_honeypot_blocks_booking(driver, db, course_slot):
    email = unique_email("course.honeypot")
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Honey", "Pot", email)
    page.select_course(course_slot)
    fill_honeypot(driver)
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert GUARD_ERROR in page.errors()
    assert db.student(email) is None


@pytest.mark.bot
def test_too_fast_submit_blocks_booking(driver, db, course_slot):
    email = unique_email("course.fast")
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Fast", "Bot", email)
    page.select_course(course_slot)
    page.accept_terms()
    set_guard_timestamp(driver, "booking", seconds_ago=0)  # submitted within the same second as rendered
    page.submit(wait_like_human=False)

    assert not page.went_to_checkout()
    assert GUARD_ERROR in page.errors()
    assert db.student(email) is None


@pytest.mark.bot
def test_expired_page_blocks_booking(driver, db, course_slot):
    email = unique_email("course.expired")
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Expired", "Page", email)
    page.select_course(course_slot)
    page.accept_terms()
    set_guard_timestamp(driver, "booking", seconds_ago=13 * 3600)
    page.submit()

    assert not page.went_to_checkout()
    assert EXPIRED_ERROR in page.errors()
    assert db.student(email) is None


@pytest.mark.bot
def test_ip_limit_blocks_booking(driver, db, course_slot, local_ip_limit_reached):
    email = unique_email("course.limit")
    page = BookingPage(driver, PATH).open()
    page.fill_guest("Limit", "Reached", email)
    page.select_course(course_slot)
    page.accept_terms().submit()

    assert not page.went_to_checkout()
    assert IP_LIMIT_ERROR in page.errors()
    assert db.student(email) is None
