#!/usr/bin/env bash
# Black-box tests for the split booking pages:
#   /book-course        -> live courses only
#   /book-subscription  -> subscription (practice) packages only
#
# Run from the project root in Git Bash (or any bash):
#   bash tests/booking-pages.test.sh
# Optional: BASE_URL=https://eprapportal.test bash tests/booking-pages.test.sh
#
# Nothing here writes to the database. The only POST goes to the
# validation-only endpoint (course-booking-validate), which never inserts.

set -u

BASE_URL="${BASE_URL:-$(grep -E '^BASE_URL=' .env 2>/dev/null | cut -d= -f2- | tr -d '\r' | sed 's:/*$::')}"
BASE_URL="${BASE_URL:-https://eprapportal.test}"

JAR="$(mktemp)"
trap 'rm -f "$JAR"' EXIT
CURL="curl -sk -m 15 -c $JAR -b $JAR"

pass=0; fail=0
ok()   { pass=$((pass+1)); printf '  \033[32mPASS\033[0m %s\n' "$1"; }
bad()  { fail=$((fail+1)); printf '  \033[31mFAIL\033[0m %s\n     %s\n' "$1" "${2:-}"; }
count(){ printf '%s' "$1" | grep -c -- "$2"; }

check_eq() { # label actual expected
    if [ "$2" = "$3" ]; then ok "$1"; else bad "$1" "expected [$3], got [$2]"; fi
}
check_gt0() { # label actual
    if [ "$2" -gt 0 ]; then ok "$1"; else bad "$1" "expected > 0, got $2"; fi
}
check_has() { # label haystack needle
    if printf '%s' "$2" | grep -q -- "$3"; then ok "$1"; else bad "$1" "missing: $3"; fi
}
check_not() { # label haystack needle
    if printf '%s' "$2" | grep -q -- "$3"; then bad "$1" "should not contain: $3"; else ok "$1"; fi
}

echo "Base URL: $BASE_URL"

# ---------------------------------------------------------------------------
echo; echo "1. Static checks on source"
check_has "route book-subscription defined" \
    "$(cat application/config/routes-student.php)" "\$route\['book-subscription'\] *= *'frontend/book_subscription'"
check_has "Frontend controller has book_subscription()" \
    "$(cat application/controllers/Frontend.php)" "public function book_subscription()"
check_has "dashboard 'Subscription' link points to book-subscription" \
    "$(grep 'Subscription' application/helpers/plab_helper.php)" "site_url('book-subscription')"
check_not "dashboard 'Subscription' link no longer points to book-course" \
    "$(grep 'Subscription' application/helpers/plab_helper.php)" "site_url('book-course')"
for f in book_subscription_as_guest.php book_subscription_new_ui.php book_subscription_old_ui.php; do
    if [ -f "application/views/frontend/booking/$f" ]; then ok "view exists: $f"; else bad "view exists: $f" "file missing"; fi
done
check_not "course new UI has no package checkboxes" \
    "$(cat application/views/frontend/booking/book_course_new_ui.php)" "practice_package_id"
check_not "course old UI has no package checkboxes" \
    "$(cat application/views/frontend/booking/book_course_old_ui.php)" "practice_package_id"
check_not "subscription new UI has no course rows" \
    "$(cat application/views/frontend/booking/book_subscription_new_ui.php)" "course_plans"
check_not "subscription old UI has no course rows" \
    "$(cat application/views/frontend/booking/book_subscription_old_ui.php)" "course_plans"

# ---------------------------------------------------------------------------
echo; echo "2. Page rendering (guest, both UI modes)"
declare -A PAGES=(
    ["book-course"]="course"
    ["book-course?iframe=1"]="course"
    ["book-subscription"]="subscription"
    ["book-subscription?iframe=1"]="subscription"
)
SUB_HTML=""
for p in "book-course" "book-course?iframe=1" "book-subscription" "book-subscription?iframe=1"; do
    kind="${PAGES[$p]}"
    code=$($CURL -o /dev/null -w '%{http_code}' "$BASE_URL/$p")
    html=$($CURL "$BASE_URL/$p")
    check_eq "$p -> HTTP 200" "$code" "200"
    check_eq "$p -> no PHP error/notice in output" "$(count "$html" 'A PHP Error\|Severity:\|Undefined variable\|Undefined index')" "0"
    courses=$(count "$html" 'name="id\[')
    packages=$(count "$html" 'name="practice_package_id\[')
    if [ "$kind" = "course" ]; then
        check_gt0 "$p -> renders live course rows ($courses)" "$courses"
        check_eq  "$p -> renders NO subscription rows" "$packages" "0"
    else
        check_gt0 "$p -> renders subscription rows ($packages)" "$packages"
        check_eq  "$p -> renders NO live course rows" "$courses" "0"
        SUB_HTML="$html"
    fi
    check_has "$p -> guest personal info form present" "$html" 'name="first_name"'
    check_has "$p -> terms checkbox present" "$html" 'name="terms_and_conditions"'
    check_has "$p -> form posts to course-booking-action" "$html" 'course-booking-action'
    check_has "$p -> 'Continue to Payment' button present" "$html" 'id="guestBookingSave"'
    if [[ "$p" != *iframe* ]]; then
        check_has "$p -> header links to book-course" "$html" 'book-course"'
        check_has "$p -> header links to book-subscription" "$html" 'book-subscription"'
    fi
done

# ---------------------------------------------------------------------------
echo; echo "3. Validation endpoint (course-booking-validate) as guest"
PKG_ID=$(printf '%s' "$SUB_HTML" | grep -o 'name="practice_package_id\[[0-9]*\]' | head -1 | grep -o '[0-9]*')
COURSE_HTML=$($CURL "$BASE_URL/book-course?iframe=1")
COURSE_ID=$(printf '%s' "$COURSE_HTML" | grep -o 'name="id\[[0-9]*\]' | head -1 | grep -o '[0-9]*')
echo "  using practice_package_id=$PKG_ID course_id=$COURSE_ID"

GUEST="first_name=Test&last_name=User&email=test.user%40example.com&phone_code=44&phone=7700900000"
VALIDATE="$BASE_URL/course-booking-validate"

# 3a. subscription only, all required fields -> OK (no course/slot demanded)
r=$($CURL -X POST "$VALIDATE" -d "$GUEST&terms_and_conditions=on&practice_package_id[$PKG_ID]=$PKG_ID&total_amount=0")
check_has "subscription-only + terms -> Status OK" "$r" '"Status":"OK"'
check_not "subscription-only -> no 'course required' error" "$r" 'id\[\]'
check_not "subscription-only -> no 'course slot required' error" "$r" 'slot_id\[\]'

# 3b. subscription only, terms missing -> only terms error
r=$($CURL -X POST "$VALIDATE" -d "$GUEST&practice_package_id[$PKG_ID]=$PKG_ID&total_amount=0")
check_has "subscription-only, no terms -> terms error" "$r" 'terms_and_conditions'
check_not "subscription-only, no terms -> still no course error" "$r" 'id\[\]'

# 3c. subscription only, guest info missing -> personal info errors
r=$($CURL -X POST "$VALIDATE" -d "terms_and_conditions=on&practice_package_id[$PKG_ID]=$PKG_ID&total_amount=0")
check_has "subscription-only, no guest info -> first_name error" "$r" '"first_name"'
check_has "subscription-only, no guest info -> email error" "$r" '"email"'

# 3d. nothing selected -> course required (course page behaviour unchanged)
r=$($CURL -X POST "$VALIDATE" -d "$GUEST&terms_and_conditions=on&total_amount=0")
check_has "nothing selected -> course required error" "$r" 'id\[\]'
check_not "nothing selected -> Status not OK" "$r" '"Status":"OK"'

# 3e. course selected without a seat -> slot required
if [ -n "$COURSE_ID" ]; then
    r=$($CURL -X POST "$VALIDATE" -d "$GUEST&terms_and_conditions=on&id[$COURSE_ID]=$COURSE_ID&total_amount=0")
    check_has "course without seat -> course slot required error" "$r" 'slot_id\[\]'
fi

# ---------------------------------------------------------------------------
echo; printf '\nResult: %d passed, %d failed\n' "$pass" "$fail"
[ "$fail" -eq 0 ]
