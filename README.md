# SoundCheck Lab

## Lab Identity
- **Name:** SoundCheck
- **Difficulty:** Medium
- **Theme:** Automated background music and PA system for a retail chain / fitness studio.
- **Environment:** Ubuntu 22.04 LTS (single VM)
- **Deployment Model:** Single virtual machine (cloud‑init / startup script)
- **Flag Format:** `VulnOS{...}`

## Objectives
- Service enumeration & reconnaissance
- Web application assessment methodology
- Vulnerability identification & validation (authenticated RCE)
- Privilege‑boundary analysis (root escalation via systemd service)
- Post‑exploitation situational awareness
- Defensive detection & remediation concepts

## Repository Layout
```
SoundCheck-Lab/
│
├─ infra/                # startup script & optional cloud‑init yaml
│   ├─ startup.sh
│   └─ README.md         # provisioning notes (this file)
│
├─ app/                  # vulnerable application
│   ├─ nginx/            # Nginx config + marketing site
│   │   ├─ sites-available/soundcheck.conf
│   │   └─ html/index.html
│   ├─ soundcheck/       # PHP panel (login, dashboard, upload)
│   │   ├─ login.php
│   │   ├─ index.php
│   │   ├─ upload.php
│   │   └─ users.db
│   └─ systemd/          # PA‑sync service (runs as root)
│       ├─ pa-sync.service
│       └─ pa-sync.sh
│
├─ scripts/              # helper scripts
│   └─ generate_flags.sh # creates static `VulnOS{}` flags (random tokens)
│
├─ .gitignore
└─ README.md             # top‑level lab description (this file)
```

## Deployment Instructions
1. **Create a VM (Ubuntu 22.04 LTS).** Enable cloud‑init / startup script support.
2. **Set a startup script** that clones this repository (replace `<REPO_URL>` with your Git URL) and executes `infra/startup.sh`:
   ```bash
   #!/usr/bin/env bash
   REPO_URL="<REPO_URL>"
   git clone $REPO_URL /opt/soundcheck
   cd /opt/soundcheck
   bash infra/startup.sh
   ```
3. The startup script will:
   - Install required packages (nginx, php‑fpm, icecast2, ffmpeg, ufw, git).
   - Deploy Nginx configuration for ports 80 and 8000.
   - Place the PHP panel under `/var/www/soundcheck`.
   - Install and start the **pa‑sync.service** (runs as root).
   - Generate random `VulnOS{}` flags (`/var/www/soundcheck/flags/user.txt` and `/root/root.txt`).
   - Open firewall ports 80, 8000, 22.
4. **Access the lab:**
   - Marketing site: `http://<VM_IP>/`
   - SoundControl panel: `http://<VM_IP>:8000/`
   - Default credentials: `admin:adminroot` (root flag) or `staff:staffpass` (user flag).
5. **Objective:** Enumerate services, discover the vulnerable upload endpoint, craft a malicious title (e.g., `; cat /root/root.txt ;`), trigger RCE, and capture both flags.

## Hints (for the author’s hint system)
1. Run `nmap -sV -p- <IP>` to discover port 8000.
2. The upload form’s **Title** field is unsanitised.
3. The `pa-sync.service` runs as **root**, so any command injected via metadata executes with root privileges.
4. Flags are stored in `VulnOS{}` format; look in `/var/www/soundcheck/flags/user.txt` and `/root/root.txt` after exploitation.

---
*Enjoy the lab and happy hacking!*
