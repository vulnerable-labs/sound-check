#!/usr/bin/env bash
set -e

# Generate random 12‑byte base64 tokens for flags
USER_TOKEN=$(openssl rand -base64 12)
ROOT_TOKEN=$(openssl rand -base64 12)

# Ensure flag directory exists
mkdir -p /var/www/soundcheck/flags

# Write static flag files
echo "VulnOS{$USER_TOKEN}" > /var/www/soundcheck/flags/user.txt
echo "VulnOS{$ROOT_TOKEN}" > /root/root.txt

# Restrict permissions
chmod 644 /var/www/soundcheck/flags/user.txt /root/root.txt
