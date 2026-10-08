# Browser tests: registration forms

Selenium + pytest tests for the three public forms that create students:
`/sign-up`, `/book-course` and `/book-subscription`.

They cover new students, existing students (as a guest and logged in), and the anti-spam
protection: name filter, honeypot, time trap, signed token, the per-IP limit, and direct POSTs.

## Setup (once)

```bash
python -m venv tests/selenium/.venv
tests/selenium/.venv/Scripts/python -m pip install -r tests/selenium/requirements.txt
```

You need Chrome installed. Selenium Manager downloads the matching chromedriver by itself.

## Run

```bash
cd tests/selenium && .venv/Scripts/python -m pytest
```

Useful options:

- `HEADLESS=0`: show the browser while the tests run.
- `-m signup`, `-m book_course`, `-m book_subscription` or `-m bot`: run only that group.
- `BASE_URL=...`: point at another host. The database settings come from the project `.env` (`DB_*`).

A failing browser test saves a screenshot to `tests/selenium/screenshots/`.

## Test data

- Every student the tests create has an e-mail like `selenium.*@example.test`. These students,
  with their `course_payments` and `course_booked` rows, are deleted before and after the run.
- `/book-course` needs a course with a future date. The tests add a temporary `course_dates` row
  for the first course listed on the page and delete it at the end.
- The per-IP limit is 5 registrations per hour. Students created by the tests are released from
  that quota before each test. Registrations you made by hand from `127.0.0.1` in the last hour
  still count, so a run can fail if you made several of them.
