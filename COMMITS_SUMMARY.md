# Commit Summary — Personal Contribution

A narrative of the project work, written from the authored commit history (merges excluded).

## Foundation and core platform
Initial commits established the application baseline: defaulting the directory index to `Home.php` via `.htaccess`, wiring session management with a user dropdown for navigation, and fixing the early login and account-creation problem by introducing the initial database tables. The property-posting and registration pages were then rebuilt for a modern layout, with an auto-generated Google Maps link and hardened file-upload error handling, accompanied by a new sidebar and branding icon.

## Home page and navigation polish
Dynamic statistics for properties, landlords, kebeles, and tenants were added to the Home page, and redundant navigation links were stripped out for a cleaner UI. The "List Your Property" action was made session-aware, redirecting guests to register/login and signed-in users straight to the listing form.

## Database setup and admin tooling
A setup script was introduced to initialize the database structure, and an admin setup key secured the registration flow for super-admins. The admin panel gained a flash-message system for user and request management, a shared `admin_style.php` stylesheet to centralize styling, user deletion with appropriate checks, an improved user-editing screen, and an invite feature restricted to super admins.

## User feedback and house management
Confirmation modals were added for destructive delete actions plus toast notifications, a success popup confirmed property submission, house status values in admin actions were clarified, and the listings grid was tuned for responsiveness. Image management and a fully wired house-deletion process followed, along with a status-toggle feature that drives notifications.

## Rental request and notification system
A complete tenant rental-request feature shipped: a `rental_requests` table, per-owner notifications, request display inside `manage_houses.php`, accept/reject popup modals, and a dedicated handler. Post-login redirects honor the intended action, and `mark_notifications_read.php` closes the notification loop. Later iterations added cancellation support with AJAX responses.

## Profile, UX, and mobile responsiveness
A profile management screen let users update their details, unused action buttons were removed, smooth scrolling and richer navbar styles were applied across pages, and mobile-specific styles made the navbar and sidebar responsive.

## Email verification and Google Sign-In
An end-to-end verification flow was implemented: registration guarded by verified email, a mail-helper for sending verification emails, DB fields for verification state, dedicated verify/pending pages, and Google Sign-In with proper error handling — supported by a config template for secrets management.

## Security and restructuring
A repository-wide hardening pass moved all scripts onto the `includes/` directory for session, database, and mail helpers, added CSRF token validation to forms and actions, sanitized input and output against XSS and SQL injection, guarded `logs/` and `uploads/` with `.htaccess`, and sealed rental-request and house-management handlers behind proper CSRF and session checks. `.gitignore` was tightened to exclude the production secrets template, and the database setup was made compatible with shared hosts.

## Review workflow
An admin house-review process was built with change tracking and request-type differentiation (new listing vs. pending edit), a pending-edits counter and styling on the admin dashboard, an updated review button icon, and subsequent UI refinements to the modal markup, request-card styling, and button spacing.

## Docs and repo hygiene
The README was rewritten with improved setup instructions plus a production-config template. Housekeeping removed erroneous schema comments, untracked the secrets template and duplicate SQL/junk files (while updating ignore rules), and finally made HEIC photo conversion cross-platform via an ImageMagick fallback for shared hosts.