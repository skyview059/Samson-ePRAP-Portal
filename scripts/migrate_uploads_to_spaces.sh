#!/usr/bin/env bash
#
# One-time bulk transfer of the live uploads/ folder (20i VPS) to a
# DigitalOcean Spaces bucket, using rclone. Run this ON THE VPS
# (public_html/portal), not on your laptop, so the 95GB moves
# server-to-server instead of through your local connection.
#
# Skips *.zip files under uploads/ (the yearly files/YYYY.zip backups,
# root.zip, certificate.zip, many.zip, vendor.zip) since these are
# stale backup/checkpoint copies, not live data the app serves.
#
# Usage:
#   1. Fill in the four DO_SPACES_* values below (or export them
#      before running this script instead of editing it).
#   2. chmod +x scripts/migrate_uploads_to_spaces.sh
#   3. ./scripts/migrate_uploads_to_spaces.sh
#
# Safe to re-run: rclone only copies new/changed files on subsequent
# runs, so you can do a first pass now and a final delta pass right
# before cutover.

set -euo pipefail

DO_SPACES_KEY="${DO_SPACES_KEY:-}"
DO_SPACES_SECRET="${DO_SPACES_SECRET:-}"
DO_SPACES_BUCKET="${DO_SPACES_BUCKET:-eprap}"
DO_SPACES_REGION="${DO_SPACES_REGION:-lon1}"
DO_SPACES_ENDPOINT="${DO_SPACES_ENDPOINT:-https://${DO_SPACES_REGION}.digitaloceanspaces.com}"

UPLOADS_DIR="${UPLOADS_DIR:-$(pwd)/uploads}"
RCLONE_REMOTE="spaces"

if [[ -z "$DO_SPACES_KEY" || -z "$DO_SPACES_SECRET" ]]; then
    echo "Set DO_SPACES_KEY and DO_SPACES_SECRET (env vars or edit this script) before running." >&2
    exit 1
fi

if [[ ! -d "$UPLOADS_DIR" ]]; then
    echo "uploads/ not found at $UPLOADS_DIR — run this from the portal/ directory, or set UPLOADS_DIR." >&2
    exit 1
fi

if ! command -v rclone >/dev/null 2>&1; then
    echo "Installing rclone..."
    curl -fsSL https://rclone.org/install.sh | sudo bash
fi

mkdir -p ~/.config/rclone
cat > ~/.config/rclone/rclone.conf <<EOF
[$RCLONE_REMOTE]
type = s3
provider = DigitalOcean
access_key_id = $DO_SPACES_KEY
secret_access_key = $DO_SPACES_SECRET
endpoint = $DO_SPACES_ENDPOINT
acl = public-read
EOF

echo "Transferring $UPLOADS_DIR -> $RCLONE_REMOTE:$DO_SPACES_BUCKET/uploads (excluding *.zip)..."

rclone copy "$UPLOADS_DIR" "$RCLONE_REMOTE:$DO_SPACES_BUCKET/uploads" \
    --exclude "*.zip" \
    --transfers 16 \
    --checkers 16 \
    --progress \
    --log-file "$HOME/spaces-migration.log" \
    --log-level INFO

echo "Done. Log at ~/spaces-migration.log"
echo "Verify counts with: rclone size $RCLONE_REMOTE:$DO_SPACES_BUCKET/uploads"
