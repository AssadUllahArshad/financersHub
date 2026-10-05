# Working locally

Current state (5 October 2026): the application uses XAMPP MySQL `financershub`. The earlier SQLite fallback and disk-full migration blockage are resolved. Project files and browser artifacts remain on D:; XAMPP database files may still reside on C:. See OUTSTANDING_WORK.md for the current backlog. Dated entries below are historical.

## Run

- PHP 8.1+ and installed Composer dependencies are required for the existing Laravel 10 application. Framework/runtime support must be reviewed before deployment.
- Run `php artisan migrate`, then `php artisan db:seed --class=TaxonomySeeder` to initialize only the approved topic names.
- Create your own staff account: `php artisan financershub:user you@example.com`. The command prompts for a name and password. No shared/default owner password exists. Use `--role=editor` or `--role=author` for other staff.
- Start `php artisan serve --port=8765` and open http://127.0.0.1:8765. Sign in at /login.
- Set `TINYMCE_API_KEY` in .env and approve your development/production domains in Tiny Cloud. If loading or domain validation fails, the HTML textarea remains usable. An explicit Use HTML editor button is available while the visual editor is active. See [TinyMCE domain/API-key documentation](https://www.tiny.cloud/docs/tinymce/latest/invalid-api-key/).
- Run `npm ci` and `npm run build` for Vite assets in `public/build`. Static artwork lives in `public/assets`, and uploads in `public/uploads`. Prototype directories were removed during cleanup; active public views are in `publication` and `pages`. Run `php artisan media:build-responsive` to backfill smaller delivery sizes for existing uploads.
- `FINANCERSHUB_DESIGN_PREVIEW` defaults on only for local environments. Set it to false to see only database-backed publication content. Preview pages are labeled and noindex. Do not launch with preview enabled or unreviewed policy/content copy.

## Editorial workflow

Create author profiles and categories before saving an article. Articles save as drafts, then move into review. Admins/editors can publish or schedule reviewed articles. Sources and real authorship are required. Demo records cannot be published. Authors can edit their own drafts but cannot publish. Revision and trash restores return to draft. Published slug changes create redirects.

Run `php artisan articles:publish-due` to process due articles, or run Laravel's scheduler every minute in the deployment environment. All scheduled times are UTC.

Uploads use private storage/app/media. Public media URLs only expose images used by published articles. Uploads are resized to a 1600px maximum edge and re-encoded as WebP (PNG fallback), and require alt text and rights notes. Referenced media/taxonomy cannot be deleted. FAQ topic groups preserve the supplied FAQ layout.

Contact messages are stored with encrypted name/email/body and shown only to admins at /admin/contacts. Notification delivery is available after setting an inbox recipient and SMTP transport; see OPERATIONS_FEATURES.md. Back up APP_KEY securely: it is needed to decrypt existing submissions. The final retention/privacy policy requires owner approval before launch.

Newsletter signup is disabled and collects no addresses. A provider interface and consent-state schema are prepared; provider selection and sandbox credentials are still required for confirmation/unsubscribe delivery tests. Do not put credentials into chat or source files.

## Verification

`php vendor/phpunit/phpunit/phpunit --testdox` uses an isolated in-memory SQLite database. It never refreshes the working SQLite database. `php artisan view:cache` compiles Blade templates. Temporary verification screenshots were removed during cleanup.


The Windows sandbox helper still writes its own logs on C:. To avoid full-drive errors for project processes, point TEMP and TMP to a directory on D: before running PHP tests/browser tools. Browser cache/profile paths used here are .browser-cache and .browser-runtime (both ignored).

## Pending launch work

See IMPLEMENTATION_PLAN.md. Phase 5 is complete for the owner-approved scope: signup stays disabled and reader accounts are deferred. Phase 6 now supplies real dashboard counts, SEO settings, metadata, sitemap and robots routes; advertising and comments remain inactive. Phase 7 security/dependency/performance review, content approval and deployment rehearsal remain. This is not a launch-ready production deployment.


## Publication operations

`/login` (also reachable through `/admin/login`) is staff-only. There is no reader registration endpoint. Admins edit default publication name/description at `/admin/seo`; per-article metadata overrides the defaults. Authors see only their own dashboard activity.

Set `APP_URL` to the final HTTPS origin before launch: canonical, sharing, schema and sitemap URLs deliberately use this trusted configuration rather than the incoming Host header. Set `FINANCERSHUB_DESIGN_PREVIEW=false` only after content approval. With preview enabled, robots disallows crawling, pages request no indexing and the sitemap is empty. Search and private previews remain noindex. `public/robots.txt` was replaced by a dynamic route so preview restrictions cannot become stale. Ensure the web server routes `/robots.txt` and `/sitemap.xml` to Laravel. The single streamed sitemap is suitable below the protocol limit of 50,000 URLs; add a sitemap index before exceeding it.

SEO defaults do not configure analytics or advertisements. Newsletter signup, reader accounts, public comments, analytics and ads remain inactive. Revisit provider/privacy/moderation decisions only when those features are scheduled.


## Launch preparation

Run `php artisan financershub:preflight` for read-only production configuration checks. Local defaults should fail these checks; this is expected, and the command does not change them. See [DEPLOYMENT.md](DEPLOYMENT.md) for release/recovery steps and [SECURITY_REVIEW.md](SECURITY_REVIEW.md) for unresolved dependency findings. Phase 7 verification currently passes 30 tests / 522 assertions and the frontend build; dependency/runtime migration and owner/hosting approvals remain necessary.

Frontend dependencies are now locked: use `npm ci` followed by `npm run build`. On this workstation keep `TEMP`, `TMP`, `npm_config_cache` and `COMPOSER_CACHE_DIR` on D:. No development server is needed to serve the active static publication assets.


## Admin account and test data

The requested local account is `admin@financerhub.com`. Its generated password is in the ignored `.browser-runtime/admin-credentials.txt` file on D:. Sign in at `/login` or `/admin/login`. The file is private and is not part of source control. The existing account/password is preserved when `AdminSeeder` runs again.

Run `php artisan db:seed --class=TestingContentSeeder` only in local/testing. It adds repeatable draft/review articles, author, tags, image, unpublished FAQs and synthetic inbox messages. It does not publish test finance copy or send email. `DatabaseSeeder` seeds taxonomy only; `AdminSeeder` is explicit. Production admin creation requires `ADMIN_SEED_EMAIL` and `ADMIN_SEED_PASSWORD` (at least 16 characters); remove the password setting after seeding.

See [OPERATIONS_FEATURES.md](OPERATIONS_FEATURES.md) for contact delivery, maintenance-console limits, AdSense verification and performance changes.

TinyMCE local origin: use http://localhost:8767/admin/editor when using the running preview server. The current key was verified editable on localhost; Tiny Cloud rejects 127.0.0.1 until that hostname is approved in the customer portal. The pending XAMPP switch requires free space on C: for MySQL's own data files, despite the Laravel project and temporary files being on D:.


### Local server with temporary uploads on D:

Run `powershell -NoProfile -ExecutionPolicy Bypass -File scripts/serve-local.ps1 -Port 8000`, then open `http://localhost:8000`. This uses your existing .env and MySQL database, but overrides PHP upload/system temporary directories for this process to the ignored project `.browser-runtime` folder. This avoids XAMPP relative upload-temp notices corrupting JSON upload responses. No global PHP or XAMPP configuration changes are made. Use localhost for the configured Tiny Cloud domain.
