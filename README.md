# SoundCheck

## Lab Storyline
A premium retail chain and exclusive fitness studio uses an automated background-music and public-address (PA) system to stream curated playlists to every location. The "SoundCheck" web portal is meant for authorized staff to upload playlists and schedule announcements. However, a recent internal audit discovered that the outsourced development team deployed the backend infrastructure with critical security oversights. You have been hired to assess the external perimeter, compromise the web application, and demonstrate the impact of a full system takeover.

## Attack Chain Path
1. **Reconnaissance & Enumeration:** Perform a full port scan to discover a hidden administrative portal on a non-standard port (`8000`), separate from the public marketing site (`80`).
2. **Information Disclosure:** Enumerate the hidden web directory to discover an exposed `users.db` file containing cleartext credentials.
3. **Authentication:** Log in to the SoundCheck staff dashboard using the leaked credentials.
4. **Command Injection:** Identify a vulnerability in the playlist upload feature. The application unsafely passes the `title` metadata field directly to the `ffmpeg` binary. Inject shell commands into the `title` parameter to achieve Remote Code Execution (RCE) and capture the User flag.
5. **Privilege Escalation:** Discover that the backend web service (PHP-FPM) was misconfigured to run with high system privileges (root) out of the box. Use the existing RCE payload to execute commands as the root user and capture the Root flag.
