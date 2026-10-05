# Deployment and recovery runbook

Status: preparation only. No production deployment has been performed. Resolve the blockers in SECURITY_REVIEW.md before launch. Newsletter signup, reader accounts and comments remain disabled. Visitor analytics follows the configured privacy controls; advertising and mail require provider configuration.

## Release prerequisites

- Choose hosting, final HTTPS domain, supported PHP/framework versions, database and persistent media storage under public/uploads. The current PHP 8.1 / Laravel 10 stack requires an upgrade project before public launch.
- Owner/editor approval is required for financial claims, primary sources, real authors, publication dates, disclosures, image rights and privacy/terms/contact retention. The supplied design copy is not approved financial or legal content.
- Keep staging behind access control. `noindex` and robots rules are crawler hints, not privacy controls.
- Provision an administrator with `php artisan financershub:user owner@example.com`; enter the password interactively. Never seed demo content in production.

## Environment and web server

Serve only `public/`. Deny dotfiles and directory listings; never expose the repository, `.env`, database, logs or backups. Editorial uploads and responsive variants live in persistent `public/uploads/` and are publicly addressable: do not upload confidential documents. `/media/{id}` also provides checked original/variant delivery. Private application files remain outside `public/`; no storage symlink is required.

Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-final-domain`, `SESSION_SECURE_COOKIE=true`, keep HTTP-only cookies enabled in session configuration, and `FINANCERSHUB_DESIGN_PREVIEW=false` only after approval. Preserve a securely backed-up `APP_KEY`; generating a replacement breaks decryption of existing contact messages. Use least-privilege database credentials. Keep secrets outside source control and build artifacts.

The web server must send missing paths, including `/robots.txt` and `/sitemap.xml`, to Laravel. Redirect HTTP to HTTPS at the edge. Configure only the actual trusted reverse proxies in `TrustProxies`; verify Laravel sees HTTPS and secure cookies before enabling HSTS. Apply equivalent security headers to static responses at the edge. The application currently sends a limited CSP (base URI, objects and framing), not a complete script-source allowlist; a stricter policy requires testing TinyMCE and any future providers.

Use persistent, writable `storage/` and `bootstrap/cache/`. Retain media across releases. A single-node deployment may use file sessions/cache; multiple nodes require shared sessions/cache and shared media. Do not use array cache for production throttling. Set upload/body limits consistent with the application's 5 MB image limit and allow memory for image re-encoding. Do not expose Vite or `artisan serve` publicly.

## Build and release sequence

1. Take and verify a backup before any release with schema changes. Use the exact committed lockfiles.
2. In CI/staging, run `composer install`, `composer audit --locked`, `npm ci`, `npm audit`, `npm run build`, and `php vendor/phpunit/phpunit/phpunit`. Investigate advisories rather than applying forced major upgrades.
3. Package production dependencies using `composer install --no-dev --prefer-dist --optimize-autoloader`. Include `public/build/` (Vite manifest and hashed CSS/JS), `public/assets/` (static artwork), and separately managed `public/uploads/` media. Exclude tests, local SQLite/browser databases, screenshots, private credentials and original design source from public serving.
4. Put the site into maintenance mode during a single-node database/media snapshot and incompatible release: `php artisan down`. Pause the scheduler and any writers. Deploy the release with persistent storage attached.
5. Run `php artisan migrate --force`. These publication migrations are additive. Never run `migrate:fresh`, reset or seed demo data against production. Rehearse migrations against a restored staging database first.
6. Run `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`, then `php artisan financershub:preflight`. A passing preflight is configuration evidence only, not editorial/security approval.
7. Configure the scheduler, run smoke checks, then `php artisan up`. Resume scheduled work and monitor errors. A production release still requires explicit owner approval.

## Scheduling, queues and logs

Run `php artisan schedule:run` every minute from the release directory using the correct PHP binary. On Windows use Task Scheduler; on Linux use cron with an absolute application path. Schedule times are UTC. `articles:publish-due` uses a non-overlap lock; multiple application nodes must use a shared lock-capable cache and a single scheduler runner. Verify `php artisan schedule:list` and inspect failures.

There are currently no newsletter/email queue jobs; `QUEUE_CONNECTION=sync` is sufficient for the implemented flows. Add supervised workers, retries and failed-job monitoring only when asynchronous providers are implemented. Contact messages are stored privately. Optional email notifications use the database-backed outbox command `contacts:send-notifications`, scheduled each minute; configure the recipient and mail transport before expecting delivery. No queue worker is required for this outbox.

Use daily log rotation with an explicit retention policy and restricted access. Alert on repeated 500s, disk pressure, scheduler failure and backup failure. Never log passwords, cookies, APP_KEY or decrypted contact messages. Review contact retention/deletion with the owner before launch.

## Backup and restore

Back up the MySQL database (including translations and revisions), `public/uploads/`, deployment version/lockfiles and encrypted configuration secrets as a coordinated set. Package `public/build/` and its Vite manifest with each release. Keep APP_KEY in a separate encrypted secret backup. Store backups outside the web root, restrict access, encrypt offsite copies and define retention/recovery objectives with the owner.

For SQLite, use its online backup API (Python `sqlite3.Connection.backup`) or `.backup` while writers are paused. Do not copy only the main file while WAL writes are active. For MySQL use a version-compatible logical or managed snapshot including transaction consistency; test the restore on the selected hosting platform.

Restore into a new isolated directory/database first, never over the active production database. Restore public/uploads originals and the matching APP_KEY, run database integrity checks, compare table counts, decrypt a test contact record and verify image hashes. Rehearse application login, article preview and public routes against this restored environment. Only switch the application to a restored backup after reviewing the recovery point and expected lost writes.

Prefer a forward fix for a failed release. Roll back application code only when it remains compatible with the migrated schema. Database restoration can discard new writes and requires an explicit recovery decision; do not blindly run migration rollback commands.

## Launch sign-off

- Dependency audits clear or findings formally assessed; supported runtime/framework installed and tested.
- Owner approves content, source accuracy, authorship, disclosures, imagery and legal/privacy text.
- Final HTTPS origin, cookie/proxy behavior, access controls, error pages and private storage verified.
- Guest, author, editor and admin journeys verified on production-like staging; no demo publications.
- Backups restore successfully; scheduler, logs, disk monitoring and recovery ownership confirmed.
- Signup and comments remain disabled. First-party visitor traces follow VISITOR_ANALYTICS_ENABLED, opt-outs and retention settings. Mail and advertising require separately verified provider setup.

Responsive images: run `php artisan media:build-responsive` after deploying/restoring existing uploads. The command preserves originals and skips existing variants; new uploads generate variants automatically. Include `public/uploads` originals in every backup; variants can be regenerated.

Record who approved each item, when, and the tested release revision before deployment.
