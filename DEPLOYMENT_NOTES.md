# ePRAP Migration Notes — 2026-09-10

Summary of work done moving ePRAP off the 20i VPS toward DigitalOcean
(via Laravel Forge), adding S3-compatible file storage, and switching
config to `.env`.

## Context / Goal

- Old setup: single 20i VPS, CodeIgniter 3, PHP, local disk storage for
  `uploads/` (~95GB), config hardcoded in `application/config/*.php`.
- Traffic is seasonal — spikes hard during exam periods (2-3 months/year),
  currently handled by manually resizing the single VPS every year.
- Decided against a full load-balanced multi-droplet setup for now —
  moving to a single larger DigitalOcean droplet provisioned via Forge
  instead. Kept the Spaces/S3 work anyway since it's useful even on one
  server (offloads disk, gives redundancy, room to add a second droplet
  later without redoing this).
- Chose PHP 8.4 (Forge's minimum available version) — confirmed
  compatible with everything built here; app was smoke-tested locally
  on PHP 8.4.23 with no fatal issues, only harmless CodeIgniter-core
  deprecation notices (`E_STRICT`, `session.sid_length`).

## Code changes (PR #2, merged to `main`)

**DigitalOcean Spaces (S3-compatible) file storage**
- New `application/libraries/Spaces.php` — thin wrapper around
  `aws/aws-sdk-php`'s S3 client (put/delete/url), supports path-style
  vs virtual-hosted addressing, and an optional bucket subfolder
  prefix for sharing one bucket across projects.
- New `application/config/spaces.php` — all `.env`-driven, off by
  default (`DO_SPACES_ENABLED=false` keeps original local-disk
  behavior with zero config needed).
- Upload/read/delete helpers in `global_helper.php` and
  `plab_helper.php` (`uploadPhoto`, `uploadFile`, `uploadAttachment`,
  `getPhoto`, `getPhoto_v2`, `getPhoto_v3`, `getPhotoWithTimThumb`,
  `download_attachment`, `filePreviewBtn`, `removeImage`,
  `removeFile`) now push to / read from / delete on Spaces when
  enabled, unchanged local-disk behavior when not.
- Files still process on local disk first (the `verot/class.upload.php`
  library needs a filesystem path to resize images into), then get
  pushed to Spaces under the same relative path already stored in the
  DB, with the local temp copy deleted after a successful push.
- Dropped the unused `mauricesvay/php-facedetection` composer
  dependency (confirmed zero references in the codebase).

**`.env` support**
- Added `vlucas/phpdotenv`, loaded at the very top of `index.php`
  before any config file runs.
- Added a project-wide `env($key, $default)` helper (also defined in
  `index.php`) — required because `phpdotenv` only populates
  `$_ENV`/`$_SERVER` by default, not `getenv()` (unsafe across
  PHP-FPM workers), so plain `getenv()` calls silently returned
  nothing.
- Converted `database.php`, `config.php` (base_url, Stripe, PayPal,
  Flutterwave keys), `constants.php` (`Backend_URL`), and the mail
  controllers (`Mail.php`, `Mail.ci.php` — SMTP host/user/pass/port)
  to read from `.env` via `env()`, with the previous hardcoded values
  kept as fallback defaults so nothing breaks on a server with no
  `.env` yet.
- Stripped several live secrets that were hardcoded directly in
  tracked/gitignored files (Stripe keys, Flutterwave keys, SMTP
  password) — they now live only in `.env`, which is gitignored and
  never committed.
- `config.php` and `database.php` were originally gitignored (secrets
  baked in); since they no longer contain secrets, they're now
  tracked in git so a fresh clone/deploy ships them automatically
  instead of needing manual symlinking.
- Added `.env.example` documenting every variable.

**Bulk migration tooling**
- `scripts/migrate_uploads_to_spaces.sh` — one-time `rclone`-based
  transfer of the local `uploads/` folder to a Spaces bucket, run
  server-to-server (not through a laptop), excludes `.zip` files
  (confirmed to be stale backup/checkpoint copies, not live data).

## Bugs found and fixed during local testing (MinIO)

Tested the whole flow locally against MinIO (S3-compatible, already
running via EnvKit) before touching production:

1. **`use_path_style_endpoint` missing** — S3 client defaulted to
   virtual-hosted-style addressing (`bucket.host`), which doesn't
   resolve against local MinIO (no wildcard DNS). Made it
   configurable via `DO_SPACES_PATH_STYLE` instead of hardcoding.
2. **`phpdotenv` doesn't populate `getenv()`** — root cause of Spaces
   silently staying "disabled" despite a correct `.env`. Fixed with
   the `env()` helper described above.
3. **CI3 config-loading bug** — `$ci->config->load('spaces', true)`
   used `$use_sections=true`, which double-nests the config array
   when the file's own array key matches the filename (`spaces.php`
   defines `$config['spaces']`). Every field read back as `null`.
   Fixed by dropping the second argument (`false` is CI3's default).

## Data migration

- Live `uploads/` folder on the 20i VPS was ~95GB total; a large chunk
  was stale `.zip` backups sitting next to live folders (confirmed
  fine to skip).
- Dumped the live MySQL database (`mysqldump | gzip`), imported into
  the new server via TablePlus.
- Ran `rclone copy` from the 20i VPS directly to the DigitalOcean
  Spaces bucket (`geniusclass`, region `lon1`, folder prefix `eprap`)
  — **53.911 GiB / 106,672 files transferred, 0 errors**, excluding
  `.zip` files as planned.

## Forge deployment setup

- Deploy script updated to rely on Forge's **Shared Paths** feature
  (Settings → Deployments → Shared paths) instead of manual `ln -nfs`
  commands — links `.env` and `uploads/` into every new release
  automatically. `config.php`/`database.php` no longer need this since
  they're tracked in git now.
- Nginx config: confirmed Forge's default template already replicates
  the old Apache `.htaccess` front-controller routing
  (`try_files $uri $uri/ /index.php?$query_string;`) and already
  blocks dotfiles (`.env`, `.git`, etc.) via a wildcard `deny all`
  rule — no changes needed there for routing/security.
- Added `client_max_body_size 50M;` to the nginx site config (default
  1MB limit was causing `413 Request Entity Too Large` on file
  uploads) — also needs the matching PHP-side bump
  (`upload_max_filesize`, `post_max_size` in `php.ini`).

## Live deploy debugging (500 error)

Root cause chain, in order discovered:
1. First deploy attempt failed because the `.env`-support commit
   (`e1adfbb`, tracking `config.php`/`database.php` in git) had been
   committed locally but not yet pushed to `origin/main` — Forge's
   clone was missing both files entirely. Fixed by pushing.
2. `config.php` had `error_reporting(0);` hardcoded at the top
   (added outside this work), which suppressed **all** PHP error
   output globally — including fatal errors — making the real cause
   invisible in both the browser and CLI. Loosened to
   `error_reporting(E_ALL & ~E_DEPRECATED)` for debugging.
3. With `CI_ENV=production`, CodeIgniter's own `db_debug` config is
   `(ENVIRONMENT !== 'production')` → `false`, which **intentionally
   hides database connection errors** by design — explains why
   nothing showed even with PHP error display forced on. Temporarily
   set `CI_ENV=development` to unlock CI3's own DB error page.
4. Actual root cause: `.env` on the new server had DB credentials
   copied over from the old 20i server's `.env`, never updated to
   match the new server's actual database. Fixed by creating/locating
   the real DB user+database on the new server and updating `.env`.
5. Also caught and fixed along the way: a stray `';` accidentally
   pasted into `BASE_URL` (copy-pasted from PHP code syntax, not
   valid in a plain `.env` file).

**Remember to**: set `CI_ENV` back to `production` and remove any
temporary `.user.ini` debug override once the site is confirmed
stable — both were only meant for this debugging session.

## Still outstanding / next steps

- [ ] Confirm PHP `upload_max_filesize`/`post_max_size` bumped to
      match the new `client_max_body_size`.
- [ ] Full smoke test on the live server: payment flows (Stripe,
      PayPal, Flutterwave), PDF generation (mpdf), CKEditor admin
      content image uploads — only the upload/Spaces path has been
      thoroughly tested so far.
- [ ] Decide when to flip `DO_SPACES_ENABLED=true` on the live server
      (bulk data transfer is done, code is ready — just needs a final
      delta `rclone` pass right before cutover to catch anything
      uploaded to the old VPS since the bulk transfer).
- [ ] `DB_sync` module (mysqldump-based backups + `backup_logs.sqlite`)
      still writes to local disk — fine on a single server, would need
      rework if ever moving to multiple app servers later.
- [ ] `application/modules/email_templates` still writes PHP view
      files directly to disk at runtime when someone edits an email
      layout — same single-server caveat as above, noted for future
      reference.
