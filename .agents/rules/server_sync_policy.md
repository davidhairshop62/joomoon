# joomoon — Server SSH & Git Auto-Sync Policy

## 1. Server & Git Connection Info
- **SSH Server**: `root@187.127.96.101` (Port `22`, SSH Alias: `ssh joomoon`, Key: `~/.ssh/id_ed25519_davidhair`, Password: `Gudangrambut5535@`)
- **Remote Project Path**: `/var/www/joomoon`
- **Local Project Path**: `/Users/davidheonchang/joomoon`
- **Git Remote (`origin`)**: `https://github.com/davidhairshop62/joomoon.git` (branch: `main`)

## 2. Automatic Upload, Update, and Delete Rule (MANDATORY)
Whenever any system/code changes are made, new files are created, existing files are modified, or files are deleted in `/Users/davidheonchang/joomoon`:
1. **Server Sync (Upload / Update / Delete)**:
   - Immediately run `./scripts/deploy.sh` (which runs `rsync -avz --delete` to `root@187.127.96.101:/var/www/joomoon/`) so that newly created files are uploaded, modified files are updated, and deleted local files are also deleted on `/var/www/joomoon`.
2. **Git Sync (Commit & Push + Remote Git Sync)**:
   - Or run `./scripts/sync.sh "commit message"` to both sync files via `rsync --delete`, commit & push all additions/modifications/deletions (`git add -A && git commit && git push origin main`), and keep `/var/www/joomoon` git state aligned with `origin/main`.
