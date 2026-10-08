"""
Bots that POST straight to the endpoints without ever loading the form
(this is how the Binance/Coinbase spam rows were created). No browser needed.
"""
import pytest
import requests
import urllib3

from conftest import BASE_URL, unique_email

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
pytestmark = pytest.mark.bot


def post(path, data):
    return requests.post(f"{BASE_URL}/{path}", data=data, allow_redirects=False, verify=False, timeout=30)


def test_direct_post_to_course_booking_action_creates_nothing(db):
    email = unique_email("direct.booking")
    response = post("course-booking-action", {
        "first_name": "New message from Binance", "last_name": "vwltme", "email": email,
        "phone_code": "44", "phone": "7700900123", "terms_and_conditions": "on",
        "id[1]": "1", "slot_id[1]": "1",
    })

    assert response.status_code in (302, 303)
    assert "booking/checkout" not in response.headers.get("Location", "")
    assert db.student(email) is None


def test_direct_post_to_course_booking_action_with_existing_email_does_not_log_in(db, existing_student):
    response = post("course-booking-action", {
        "first_name": "Attacker", "last_name": "Person", "email": existing_student["email"],
        "phone_code": "44", "phone": "7700900123", "terms_and_conditions": "on",
        "id[1]": "1", "slot_id[1]": "1",
    })

    assert "booking/checkout" not in response.headers.get("Location", "")
    assert "student_data" not in response.cookies
    after = db.student(existing_student["email"])
    assert after["password"] == existing_student["row"]["password"]
    assert after["fname"] == existing_student["row"]["fname"]


def test_direct_post_to_sign_up_action_creates_nothing(db):
    email = unique_email("direct.signup")
    response = post("sign_up_action", {
        "fname": "You have ONE MESSAGE(S) from COINBASE", "lname": "zzmzxt", "email": email,
        "password": "Spam@1234", "passconf": "Spam@1234", "answer": "1",
    })

    assert response.status_code in (302, 303)
    assert response.headers.get("Location", "").rstrip("/").endswith("sign-up")
    assert db.student(email) is None
