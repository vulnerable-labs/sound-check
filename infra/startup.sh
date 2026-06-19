#!/usr/bin/env bash
set -e

# -----------------------------------------------------------------
# SoundCheck Lab – Self‑sufficient startup script (clones repo if needed)
# -----------------------------------------------------------------
# Variables – adjust if you fork the repo to a different URL
REPO_URL="https://github.com/vulnerable-labs/sound-check.git"
DEST_DIR="/opt/soundcheck"

# ------------------------------------------------------------
# 0. Install required packages (must happen before git clone)
# ------------------------------------------------------------
apt-get update
DEBIAN_FRONTEND=noninteractive apt-get install -y nginx php-fpm php-cli icecast2 ffmpeg ufw git

# ------------------------------------------------------------
# 1. Clone the repository (or pull latest changes)
# ------------------------------------------------------------
if [ ! -d "$DEST_DIR/.git" ]; then
    echo "[+] Cloning SoundCheck lab repository..."
    git clone "$REPO_URL" "$DEST_DIR"
else
    echo "[+] Repository already present – pulling latest updates..."
    (cd "$DEST_DIR" && git pull)
fi

cd "$DEST_DIR"

# ------------------------------------------------------------
# 2. Deploy Nginx configuration
# ------------------------------------------------------------
cp -r $(pwd)/app/nginx/sites-available/* /etc/nginx/sites-available/
ln -sf /etc/nginx/sites-available/soundcheck.conf /etc/nginx/sites-enabled/soundcheck.conf
# Remove default site if present
rm -f /etc/nginx/sites-enabled/default
systemctl restart nginx

# ------------------------------------------------------------
# 3. Deploy PHP application & Marketing Site
# ------------------------------------------------------------
mkdir -p /var/www/soundcheck/html
mkdir -p /var/www/soundcheck/playlists

# Copy PHP application
cp -r $(pwd)/app/soundcheck/* /var/www/soundcheck/
# Copy Marketing site (HTML)
cp -r $(pwd)/app/nginx/html/* /var/www/soundcheck/html/

# Configure PHP-FPM to run as root (Intentional vulnerability as per spec)
sed -i 's/user = www-data/user = root/g' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = root/g' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/ExecStart=\(.*\)/ExecStart=\1 -R/g' /lib/systemd/system/php8.1-fpm.service
systemctl daemon-reload
systemctl restart php8.1-fpm

chown -R root:root /var/www/soundcheck
chmod -R 777 /var/www/soundcheck

# ------------------------------------------------------------
# 4. Deploy systemd service for PA sync (runs as root)
# ------------------------------------------------------------
cp $(pwd)/app/systemd/pa-sync.service /etc/systemd/system/
cp $(pwd)/app/systemd/pa-sync.sh /usr/local/bin/pa-sync.sh
chmod +x /usr/local/bin/pa-sync.sh
systemctl daemon-reload
systemctl enable pa-sync.service
systemctl start pa-sync.service

# ------------------------------------------------------------
# 5. Generate static flags (random tokens) – executed once on first boot
# ------------------------------------------------------------
$(pwd)/scripts/generate_flags.sh

# ------------------------------------------------------------
# 6. Open firewall for required ports
# ------------------------------------------------------------
ufw allow 80/tcp
ufw allow 8000/tcp
ufw allow 22/tcp
ufw --force enable

echo "[+] SoundCheck lab provisioning complete."
