# 🏠 AdamaRent — Property Rental Marketplace

A PHP/MySQL rental house marketplace for **Adama City, Ethiopia** (plain PHP, no framework). Landlords list properties and tenants browse/search them; there is a landlord dashboard and an admin console for managing users, houses, and approvals.

**Stack:** PHP · Apache (XAMPP / LAMPP) · MySQL (mysqli) · HTML/CSS/JS

---

## ⚙️ Requirements

- **XAMPP** (Windows / macOS) or **LAMPP** (Linux) with Apache + MySQL running
- PHP **7.4+** (developed on **8.2 / 8.3**)
- The project folder inside the web root:

| OS | Installer | Default path |
|----|-----------|--------------|
| Windows | [apachefriends.org](https://www.apachefriends.org/) (installer) | `C:\xampp\htdocs\` |
| Linux | LAMPP tarball | `/opt/lampp/htdocs/` |
| macOS (Intel) | [apachefriends.org](https://www.apachefriends.org/) `.dmg` | `/Applications/XAMPP/htdocs/` |
| macOS (Apple Silicon / M1-M4) | `brew install --cask xampp` or Rosetta `.dmg` | `/Applications/XAMPP/htdocs/` |
| macOS (alternative) | MAMP from [mamp.info](https://www.mamp.info/) | `/Applications/MAMP/htdocs/` |

> **macOS note:** the built-in `php` in Terminal is *not* the XAMPP one. Use version checks via the Control Panel or `/Applications/XAMPP/xamppfiles/bin/php -l`, and start servers from the **XAMPP app** (manager-osx) or `sudo /Applications/XAMPP/xamppfiles/ctlscript.sh start apache/mysql`.

---

## 🚀 Installation (First-Time Setup)

### 1️⃣ Start XAMPP
- **Windows:** XAMPP Control Panel → Start **Apache** + **MySQL**.
- **Linux:** `sudo /opt/lampp/start` (or `./ctlscript.sh start apache`).
- **macOS:** open the **XAMPP Control** app → "Manage Servers" → Start **Apache** + **MySQL**.

Copy the project folder into the web root (path from the table above).

### 2️⃣ Create the secrets file
Copy the template and fill it in — **never** edit the template itself:

```
config/config_secrets.example.php  →  config/config_secrets.php
```

Fill in (see the file for a full breakdown):
```php
define('APP_BASE_URL', '');               // set for email/Google links that must work from other devices,
                                          // e.g. http://192.168.1.50/udmp_adama_rental_house (empty = auto-detect)
define('DB_HOST', 'localhost');           // XAMPP default
define('DB_USER', 'root');
define('DB_PASS', '');                    // default empty in XAMPP — change if yours has a password
define('DB_NAME', 'rental_db');

define('BREVO_API_KEY', 'xkeysib-...');   // email (see "Email setup" below)
define('BREVO_FROM_EMAIL', 'you@example.com');
```

> `config/config_secrets.php` is **gitignored** — real keys never enter the repo.

### 3️⃣ Run the one-click setup
Browse to:
```
http://localhost/udmp_adama_rental_house/setup.php
```
This:
- connects using your `config_secrets.php` credentials,
- creates the database if it doesn't exist (safe on shared hosts that pre-create it, e.g. InfinityFree),
- creates all tables and seeds base amenities,
- displays the **Admin Setup Key** (only when the database has no admin yet).

### 4️⃣ Create the first Super Admin
1. Go to `register.php`.
2. Fill in name, email, and password.
3. Tick **"I'm the site administrator"**.
4. Paste the **Admin Setup Key** from `setup.php`.
5. Submit → that account becomes the **Super Admin**.
6. The setup key is **destroyed** after this — it cannot be reused.

> Everyone else who registers normally (no key) becomes a **landlord**.

### 5️⃣ View your site
```
http://localhost/udmp_adama_rental_house/
```

---

## 📧 Email setup (Brevo — required for verification & invites)

The app sends verification/invite emails through the [Brevo](https://www.brevo.com/) API (300 free emails/day). Works fine on any host, including free shared hosting, because emails are sent **from your server to Brevo's API** (outbound is always allowed).

1. Create a free Brevo account → copy your **API v3 key** (`Settings → SMTP & API → API Keys`).
2. **Verify a sender address** (this is the part most people miss):
   - `Settings → Senders & IPs → Add sender` → enter an email you actually own (e.g. `noreply@yourdomain.com` or any personal email).
   - Brevo emails you a **confirmation link** — click it. The sender now shows "Verified".
   - **No DNS records are needed** if you verify a single address. Ignore Brevo's "Authenticate domain / add NS/TXT records" step — free subdomains (like `*.infinityfreeapp.com`) aren't yours to change DNS for, and single-sender verification is enough to send.
3. Put the key + verified address into `config/config_secrets.php`:
   ```php
   define('BREVO_API_KEY', 'xkeysib-...');
   define('BREVO_FROM_EMAIL', 'noreply@yourdomain.com'); // must = verified sender
   define('BREVO_FROM_NAME', 'AdamaRent');
   ```
4. Register a test account and check the email arrives. Sending problems are logged in `logs/mail_log.txt` (look for `BREVO ERROR (code)` lines).

---

## 🔐 Google Sign-In (optional)

1. In [Google Cloud Console](https://console.cloud.google.com/) → *Credentials* → create an **OAuth 2.0 Web Client**.
2. Add an **Authorized redirect URI**: `http://localhost/udmp_adama_rental_house/google_callback.php`
   (production: `https://yourdomain/google_callback.php`).
3. Put the **Client ID** and **Client Secret** into `config/config_secrets.php`.
4. Leave them empty to disable "Sign in with Google".

---

## 🗂 Project structure

```
├── Home.php, index.php, login.php, register.php …   # public pages & entry points
├── manage_houses.php, post_house.php, edit_house.php # landlord pages
├── admin_panel.php, admin_invite.php … …            # admin pages
├── includes/        # shared layout + logic (header, sidebar, db, session, mail, security)
├── config/          # config_secrets(.example).php  (secrets file is gitignored)
├── database/        # setup.sql (manual import optional — setup.php is easier)
├── uploads/         # property images (gitignored — populated at runtime)
└── logs/            # mail_log etc. (gitignored)
```

---

## 🌍 Deploying to free hosting (InfinityFree)

1. Push your finished work to GitHub, then download the **ZIP of your deployment branch** (gitignored files like photos/secrets are automatically excluded).
2. Upload into the web root (`htdocs/`) via **File Manager** or FTP (host `ftpupload.net`). Files must sit **directly** in `htdocs/` (no extra nested folder).
3. On the server, make sure `config/config_secrets.php` exists with the **production** values:
   - `APP_BASE_URL` → `https://yourname.infinityfreeapp.com`
   - `DB_HOST` → e.g. `sql102.infinityfree.com`, `DB_USER`/`DB_PASS`/`DB_NAME` from the InfinityFree **MySQL Databases** panel
   - Brevo + Google values (above)
4. Create an empty `uploads/` folder on the server.
5. Visit `/setup.php` once, then register your first admin with the setup key. Delete `setup.php` afterwards.
6. Use **https://** — InfinityFree serves free subdomains over HTTPS via Cloudflare.

> **Note:** InfinityFree blocks bots/APIs with a JS challenge — normal browsers are fine, but cURL/scheduled scripts will get 403 or a challenge page.

---

## 🔧 Troubleshooting

| Issue | Solution |
|-------|----------|
| **"Cannot connect to MySQL"** | Check `DB_*` values in `config/config_secrets.php` and that MySQL is started. |
| **Database/tables missing** | Run `setup.php` once. On MySQL 8.x remember `mysqli_report(MYSQLI_REPORT_OFF)` keeps setup from aborting if a statement fails. |
| **White screen** | A PHP fatal with error display off. Enable `display_errors=On` (add `display_errors=On` to `.user.ini` at web root on InfinityFree) or check `logs/`. PHP 8.1+ throws `mysqli_sql_exception` on query errors — usually a missing table or wrong query. |
| **Can't log in as admin** | Register the **first Super Admin** via the `setup.php` key. |
| **Setup key not showing** | The key only shows while **no admin exists**. |
| **"Email sent" but nothing arrives** | (1) `BREVO_API_KEY` must be a real key, not the `xkeysib-REPLACE` placeholder; (2) `BREVO_FROM_EMAIL` must be a **verified sender** in Brevo (do the single-address verification, not domain auth); (3) check `logs/mail_log.txt` for `BREVO ERROR` codes; (4) send to a real inbox, not self-sent/seen-as-spam. |
| **"Invalid or expired form token"** | Old page open in a stale tab. Reload the page and retry — every state-changing form/request carries a CSRF token. |
| **Port 80/443 in use** | Stop Skype/Teams/IIS or change Apache ports in XAMPP. |
| **Uploads not saving on local** | Ensure `uploads/` exists and Apache can write to it. |
| **Can't upload on shared host** | `uploads/` is gitignored — create it empty on the server, then add images via the site or FTP. |

---

**Need help?** Check the Apache/MySQL logs via the XAMPP Control Panel, or open an issue on the repository.