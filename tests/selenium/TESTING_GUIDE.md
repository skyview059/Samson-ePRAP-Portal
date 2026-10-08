# টেস্ট গাইডলাইন: স্প্যাম-প্রতিরোধ আর registration ফর্ম

এই গাইডে তিনটি public ফর্মের টেস্ট পদ্ধতি আছে: `/sign-up`, `/book-course` আর `/book-subscription`।
যে সুরক্ষাগুলো টেস্ট করা হয়: নামের filter, honeypot, time-trap, signed token, IP limit, আর account takeover fix।

## ১. প্রস্তুতি (একবারই করতে হবে)

**যা লাগবে:**
- Local সাইট চালু থাকতে হবে: `https://eprapportal.test` (nginx, PHP, MySQL চলছে)
- Google Chrome install করা
- Python 3.12

**Setup** (project root থেকে):

```bash
python -m venv tests/selenium/.venv
```

```bash
tests/selenium/.venv/Scripts/python -m pip install -r tests/selenium/requirements.txt
```

---

## ২. Automated টেস্ট চালানো

**সব টেস্ট** (~৬ মিনিট):

```bash
cd tests/selenium && .venv/Scripts/python -m pytest
```

**শুধু একটা ফর্ম বা গ্রুপ:**

```bash
cd tests/selenium && .venv/Scripts/python -m pytest -m signup
```

`signup`-এর জায়গায় দিতে পারেন: `book_course`, `book_subscription`, `bot`

**ব্রাউজার চোখে দেখে টেস্ট করতে** (PowerShell):

```powershell
cd tests/selenium; $env:HEADLESS="0"; .venv/Scripts/python -m pytest -m book_course
```

**শুধু একটা নির্দিষ্ট টেস্ট:**

```bash
cd tests/selenium && .venv/Scripts/python -m pytest "test_book_course.py::test_logged_in_existing_student_can_book_course"
```

### টেস্ট ফাইলগুলো

| ফাইল | কী টেস্ট করে |
|---|---|
| `test_signup.py` | নতুন student, আগে থেকে থাকা email, unicode নাম, spam নাম, honeypot, খুব দ্রুত submit, পুরনো পেজ, বদলানো token, IP limit |
| `test_book_course.py` | Guest booking (new আর old UI), existing email দিয়ে guest, login করা student, spam নাম, honeypot, খুব দ্রুত submit, পুরনো পেজ, IP limit |
| `test_book_subscription.py` | Guest booking (new আর old UI), existing email দিয়ে guest, login করা student, spam নাম, honeypot |
| `test_direct_post.py` | পেজ না খুলে সরাসরি POST (স্প্যাম bot-এর পদ্ধতি) আর existing email দিয়ে takeover-এর চেষ্টা |

### ফলাফল কীভাবে বুঝবেন
- শেষ লাইনে `27 passed` এলে সব ঠিক আছে।
- `FAILED` এলে:
  1. টেস্টের নাম আর `assert` লাইনটা দেখুন। কোন check ভেঙেছে সেটা ওখানেই লেখা থাকে।
  2. Browser টেস্ট হলে screenshot দেখুন: `tests/selenium/screenshots/<test-name>.png`
  3. Server-এর দিক দেখতে `application/logs/`-এ `[form_guard]` খুঁজুন। কেন reject হয়েছে সেটা লেখা থাকে: `honeypot`, `token`, `too_fast`, `expired` বা `ip limit`।
- `SKIPPED` মানে দরকারি data নেই, যেমন কোনো subscription package নেই। এটা fail না।

### Test data
- টেস্টে তৈরি সব student-এর email `selenium.*@example.test` ধরনের। Run-এর আগে আর পরে এগুলো, সাথে তাদের `course_payments` আর `course_booked` row, মুছে ফেলা হয়।
- `/book-course`-এর জন্য টেস্ট চলার সময় একটা অস্থায়ী `course_dates` row যোগ হয়, আর শেষে মুছে যায়।
- "খুব দ্রুত submit" আর "পুরনো পেজ" টেস্টে `config.php`-এর `form_guard_key` দিয়ে timestamp নতুন করে sign করা হয়।

---

## ৩. হাতে-কলমে (manual) টেস্ট চেকলিস্ট

Deploy-এর আগে একবার নিজে ব্রাউজারে দেখে নিন। প্রতিটা টেস্টের জন্য একটা **নতুন incognito window** ব্যবহার করুন, আর email দিন `yourname+test1@...` ধরনের।

### A. `/sign-up`

| # | কী করবেন | প্রত্যাশিত ফলাফল |
|---|---|---|
| 1 | সব ঠিকমতো পূরণ করে submit | Login পেজে যাবে, "Registration Successful" দেখাবে |
| 2 | আগে থেকে থাকা email দিয়ে | "This email already in used" |
| 3 | First name: `Binance ->> click` | "may only contain letters…" error |
| 4 | নাম বাংলায় (`রাশেদ`) বা `O'Brien` | গ্রহণ করবে |
| 5 | পেজ খুলেই খুব দ্রুত submit (৩ সেকেন্ডের কম) | হাতে এত দ্রুত করা কঠিন। এটা automated টেস্টে cover করা আছে। |

### B. `/book-course` (এই ঠিকানায় এবং `?iframe=1` যোগ করে, দুইভাবেই)

| # | কী করবেন | প্রত্যাশিত ফলাফল |
|---|---|---|
| 1 | Guest হিসেবে নতুন email, course আর slot বেছে, Terms টিক দিয়ে "Continue to Payment" | Checkout পেজে যাবে |
| 2 | Guest হিসেবে **আগে থেকে থাকা** email দিয়ে | Email-এর নিচে লেখা আসবে: "This email is already registered. Please log in…"। Checkout-এ যাবে না। |
| 3 | ২ নম্বরের পর ঐ student-এর আসল password দিয়ে login করুন | আগের password-এই login হবে, অর্থাৎ password বদলায়নি |
| 4 | Login করা অবস্থায় booking | Personal info ফর্ম দেখাবে না, সরাসরি checkout-এ যাবে |
| 5 | নামে spam লেখা | নামের নিচে error দেখাবে |

### C. `/book-subscription`
B-এর ১, ২, ৪ আর ৫ নম্বর একইভাবে করুন, শুধু course-এর বদলে একটা package বেছে নিন।

### D. Developer tools দিয়ে bot-এর মতো চেষ্টা (ঐচ্ছিক)
- **Honeypot:** DevTools Console-এ লিখুন `document.querySelector('[name=user_ref_code]').value='x'`, তারপর submit করুন। ফলাফল: "We could not verify your submission"
- **Token বদলানো:** Console-এ লিখুন `document.querySelector('[name=_fg_ts]').value='1'`, তারপর submit করুন। একই error আসবে।
- **সরাসরি POST:**

```bash
curl -sk -X POST https://eprapportal.test/course-booking-action -d "first_name=Spam&last_name=Bot&email=bot@example.test" -o /dev/null -w "%{http_code} -> %{redirect_url}\n"
```

প্রত্যাশিত: `303 -> .../book-course`। DB-তে কোনো নতুন row তৈরি হবে না।

---

## ৪. DB-তে যাচাই

Manual টেস্টের পর এই query চালান:

```sql
SELECT id, fname, lname, email, tmp_ip_addr, created_at
FROM students ORDER BY id DESC LIMIT 10;
```

- নতুন row-এ `created_at` এখন **আসল তারিখ** থাকবে, আগের মতো `0000-00-00` না।
- `tmp_ip_addr` পূরণ থাকবে।
- Reject হওয়া কোনো চেষ্টার row থাকবে না।

---

## ৫. সাধারণ সমস্যা ও সমাধান

| সমস্যা | কারণ ও সমাধান |
|---|---|
| সব registration-এ "Too many registrations…" | গত ১ ঘণ্টায় একই IP থেকে ৫টির বেশি account তৈরি হয়েছে। ১ ঘণ্টা অপেক্ষা করুন, অথবা টেস্টের জন্য বানানো row-গুলোর `tmp_ip_addr` NULL করে দিন। |
| "This page has expired" | পেজটা ১২ ঘণ্টার বেশি খোলা ছিল। Reload করুন। |
| Course test `SKIPPED` | `/book-course`-এ কোনো course দেখাচ্ছে না। কোনো active course আছে কিনা দেখুন। |
| Chrome বা driver error | Chrome update করুন। Selenium নিজেই মিলিয়ে driver নামিয়ে নেয়। |
| টেস্ট timeout | Local server ধীর, কখনো পেজ লোড হতে ৬০ সেকেন্ডের বেশি লাগে। আবার চালান। |

---

## ৬. Production-এ deploy-এর পর

1. **Proxy চেক:** server Cloudflare বা load balancer-এর পেছনে থাকলে আগে `config.php`-এ `proxy_ips` সেট করুন। নইলে সব user-এর IP একই দেখাবে আর IP limit সবাইকে আটকে দেবে।
2. **নতুন key:** production-এ আলাদা একটা `form_guard_key` দিন।
3. **Smoke test:** নিজে একবার sign-up আর একবার guest booking করুন (চেকলিস্টের A-1 আর B-1)।
4. **কয়েকদিন নজর রাখুন:**
   - Log-এ `[form_guard]` লাইনগুলো দেখুন। এগুলো আটকানো স্প্যাম।
   - এই query চালান:

```sql
SELECT COUNT(*) FROM students
WHERE created_at >= NOW() - INTERVAL 1 DAY
  AND (fname REGEXP '[0-9?{}<>]|Binance|COINBASE' OR created_at = '0000-00-00');
```

   ফলাফল `0` থাকা উচিত।
5. **আসল user-দের অভিযোগ:** কেউ "We could not verify your submission" পাচ্ছে বললে log দেখুন কোন কারণে reject হচ্ছে। প্রয়োজনে `application/helpers/form_guard_helper.php`-এ `FORM_GUARD_MIN_SECONDS` কমানো যায়।

---

> **সতর্কতা:** Automated টেস্ট শুধু **local DB**-তে চালাবেন, production-এ কখনো না। টেস্ট নিজে data তৈরি করে আবার মুছে ফেলে, তাই production-এ চালালে সেখানে data ঢুকবে আর মুছবে।
