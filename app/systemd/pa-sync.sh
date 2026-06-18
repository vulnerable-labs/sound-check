#!/usr/bin/env bash
set -e

# Simple PA sync loop – runs as root
PLAYLIST_DIR="/var/www/soundcheck/playlists"
while true; do
    for f in "$PLAYLIST_DIR"/*; do
        [ -e "$f" ] || continue
        # Re‑encode the file to ensure metadata is processed (simulating PA sync)
        ffmpeg -y -loglevel error -i "$f" -metadata title="$(ffprobe -v error -show_entries format_tags=title -of default=noprint_wrappers=1:nokey=1 "$f")" "$f.processed" && mv "$f.processed" "$f"
    done
    sleep 30
done
