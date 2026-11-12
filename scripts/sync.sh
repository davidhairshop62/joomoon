#!/bin/bash
# Auto Sync Script for joomoon
# Syncs local changes (add, update, delete) to GitHub and updates the production server (/var/www/joomoon)

COMMIT_MSG="${1:-Auto sync update $(date '+%Y-%m-%d %H:%M:%S')}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "========================================="
echo "1. Syncing files to server (/var/www/joomoon)..."
echo "========================================="
bash "$SCRIPT_DIR/deploy.sh"

echo "========================================="
echo "2. Staging and committing changes (including deletions)..."
echo "========================================="
git add -A
if git diff-index --quiet HEAD --; then
    echo "No local changes to commit."
else
    git commit -m "$COMMIT_MSG"
    echo "========================================="
    echo "3. Pushing to GitHub (origin main)..."
    echo "========================================="
    git push origin main
fi

echo "========================================="
echo "4. Syncing git repository on server (/var/www/joomoon)..."
echo "========================================="
ssh -i ~/.ssh/id_ed25519_davidhair root@187.127.96.101 "cd /var/www/joomoon && git fetch origin main && git reset --hard origin/main"

echo "========================================="
echo "✅ Full Sync (Server + Git) Complete!"
echo "========================================="
