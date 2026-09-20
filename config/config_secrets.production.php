<?php
// SECRETS — PRODUCTION DRAFT (InfinityFree). This file is gitignored.
// Fill in the values below, then upload it to the server as config/config_secrets.php.
// Never commit or push this file with real keys.

// REQUIRED — production site URL with https (InfinityFree free domains are HTTPS via Cloudflare).
// Email verification links + Google OAuth redirects use this.
// e.g. https://yoursite.infinityfreeapp.com
define('APP_BASE_URL', 'https://REPLACE.infinityfreeapp.com');

// MySQL database credentials — get these from the InfinityFree control panel
// (Control Panel > MySQL Databases > "MySQL credentials" / phpMyAdmin).
// Host looks like sql303.infinityfree.com; user and db are prefixed like if0_12345678_...
define('DB_HOST', 'sqlXXX.infinityfree.com');
define('DB_USER', 'if0_REPLACE');
define('DB_PASS', 'REPLACE');
define('DB_NAME', 'if0_REPLACE_rental_db');

// Brevo (Sendinblue) transactional API v3 — same key type as dev.
// FROM_EMAIL must be a SENDER ADDRESS you verified in Brevo
// (Settings > Senders & IPs), e.g. noreply@yourdomain.com.
define('BREVO_API_KEY', 'xkeysib-REPLACE');
define('BREVO_FROM_EMAIL', 'noreply@REPLACE');
define('BREVO_FROM_NAME', 'Adama Rental Houses');

// Google Sign-In (OAuth 2.0).
// In Google Cloud Console add the redirect URI:
//   https://REPLACE.infinityfreeapp.com/google_callback.php
// and paste the client id + secret for that audience.
define('GOOGLE_CLIENT_ID', 'REPLACE.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'REPLACE');