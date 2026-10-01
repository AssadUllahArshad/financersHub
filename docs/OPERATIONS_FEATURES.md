# Inbox, maintenance, test data and monetization preparation

## Contact inbox and email delivery

Set the recipient in **Admin > Settings > Contact notification recipient**. It is intentionally blank for now, with an example placeholder. `CONTACT_MAIL_TO` is an optional environment fallback. SMTP/API credentials belong in `.env` or deployment secrets, never in the admin database or source control. Configure Laravel's `MAIL_MAILER`, host/port/encryption, username/password and a verified `MAIL_FROM_ADDRESS`. Reader email is used as Reply-To, not as the sender.

Every accepted form submission is stored first, with encrypted reader name, email and message. When delivery is configured, a persistent outbox state is set on the message. The response does not wait for SMTP. No message is lost solely because the mail provider is unavailable.

Run Laravel's scheduler every minute. It invokes `contacts:send-notifications`, processing at most 20 eligible records per run. Alternatively run that command from the deployment terminal. This outbox does not require a queue worker. Automatic retries are bounded to three attempts, with five minutes between failures. Stale sending claims are recoverable after ten minutes. If a process stops after the provider accepts mail but before the database update, a duplicate notification is possible; the stable message reference identifies duplicates. Exactly-once email delivery is not promised.

Inbox filters include all, unread, reviewed and delivery failed. Opening the list does not mark every message read. Use **Mark read**, **Mark unread** or **Mark reviewed** explicitly. The sidebar shows the unread count. **Queue notification** becomes available for failed/unconfigured records when delivery is configured. It cannot resend an already sent or actively queued message.

`Sent` means the configured mail transport accepted the message, not proof of final delivery. Domain verification, SPF/DKIM/DMARC, bounce handling and mailbox delivery checks depend on the chosen provider. Do not use log/array mail drivers for live personal-data notifications. No real email was sent during implementation; delivery and failure handling were tested with fake transports.

## Admin account and repeatable records

Local account: `admin@financerhub.com`. The generated password is in `.browser-runtime/admin-credentials.txt`, excluded from version control and outside the public web root. Keep it private. The login page is `/login`; `/admin/login` redirects there.

`php artisan db:seed --class=AdminSeeder` creates the configured administrator only if absent. It preserves an existing administrator's password and refuses to promote an existing non-admin with the same email. On production set `ADMIN_SEED_EMAIL` and `ADMIN_SEED_PASSWORD` explicitly before seeding, then remove the password environment value. There is no built-in production password. If reusing the locally generated password as requested, supply it privately through deployment secrets.

`php artisan db:seed --class=TestingContentSeeder` is restricted to local/testing. It adds eleven topic articles in draft/review states, an author, three tags, a generated image, three unpublished FAQs, three synthetic contact messages and selected settings. It preserves existing content and is repeatable. Test articles are marked demo and cannot enter the public publication workflow. Preview them from the article list; replace fixtures with reviewed real content before launch. Newsletter subscribers and ad accounts are not fabricated.

Admin routes are maintained in `routes/admin.php`, loaded with the `/admin` prefix, `admin.` name prefix and auth/studio middleware. Individual routes retain stronger content/settings permissions. Authentication routes remain in `routes/web.php` so guests can reach sign-in.

## Maintenance console

Only administrators can access `/admin/maintenance`. Each run requires the current account password, passes CSRF and a rate limit, takes an execution lock and records actor, command, exit status and bounded output in `maintenance_runs`.

Available tasks: deployment preflight, schedule listing, view clear/cache, route clear/cache and configuration clear. Task failures remain visible in history. A preflight exit code of 1 indicates failed readiness checks, not necessarily a broken console.

This is deliberately a reviewed-task console. Arbitrary commands/arguments, Tinker/PHP evaluation, database resets/migrations, shell execution and long-running workers remain deployment-terminal operations. No web console can promise arbitrary commands will leave service uninterrupted. Add new tasks only after reviewing side effects, execution time and whether output can expose secrets. Do not run configuration-cache generation from a web request, where runtime mutations could be captured.

## Frontend and performance

The visible public preview banner was removed at the owner's request. Design preview mode still controls fixture fallbacks, noindex and robots restrictions. Turning off a visual notice does not approve the sample content for publication.

Navbar hover/focus now uses one underline. Font loading is consolidated into one stylesheet request with preconnects and `display=swap`, replacing duplicate chained CSS imports. Text retains local fallback fonts if Google Fonts is unavailable.

`npm run build` now builds the stock Vite assets and hashed/minified publication, studio and login CSS bundles in `public/assets/generated`. Include that directory in the production artifact even though it is ignored by Git. Production uses one CSS bundle per layout; local development uses individual source stylesheets so edits remain visible. Missing bundles fall back to source styles rather than breaking the site. Hashed bundles have Apache immutable-cache rules; configure equivalent headers and Brotli/gzip on other hosts. Do not cache authenticated HTML or contact submissions at a CDN.

New uploads are bounded to 12 megapixels, resized to a maximum 1600px edge, and re-encoded as WebP quality 82 when GD supports it, otherwise PNG. Existing images remain unchanged. Authorization and private media storage remain intact. Public media keeps a short cache lifetime so withdrawn content does not remain available indefinitely. The source design already uses WebP images and lazy loading below the fold.

Local browser verification used 320px and desktop layouts, checking overflow, images, navigation and console errors. The production CSS homepage loaded a single generated stylesheet with no overflow and no loaded broken images. Local timing is not a PageSpeed score. Before launch, measure the final HTTPS site with Lighthouse/PageSpeed Insights and Search Console field data, on mobile networks and after consent/ad scripts are introduced. Assess LCP, CLS and INP; use realistic hosting and content volume.

## AdSense preparation

**Admin > Advertising** accepts a validated real `pub-` identifier. When supplied, the site exposes the matching ownership meta tag and `/ads.txt` seller entry. Empty configuration produces an explanatory ads.txt comment, not a fake seller. This follows Google's [site connection guide](https://support.google.com/adsense/answer/7584263) and [ads.txt guide](https://support.google.com/adsense/answer/12171612).

No ad JavaScript or tracking is injected. Account/site approval, original reviewed content, privacy disclosures and applicable consent/CMP requirements must be completed before enabling advertising. A working ads.txt file does not guarantee AdSense approval. When ad placements are introduced, reserve dimensions, avoid obstructing navigation and remeasure mobile performance. Serve `/ads.txt` through Laravel and verify it on the final public domain.

The outstanding PHP/Laravel and Vite dependency findings in SECURITY_REVIEW.md remain launch blockers. Signup, reader accounts and public comments remain deferred.


### Admin management refresh (28 September 2026)

- Categories, tags, authors and FAQs now use searchable lists with separate create/edit pages, sorting, reference counts, visibility badges and delete confirmation. Referenced content remains protected; author profiles use a staff dropdown.
- Article lists support category/status/search filters and preserve the trash selection. Media supports filename/alternative-text search and format filtering.
- Shared admin controls have consistent dropdowns, button states, spacing and responsive layouts. Sidebar highlights follow content create/edit pages and hide media management from unauthorized authors.
- Maintenance includes explained task selection, live cache indicators, filterable execution history, actor/outcome details and copyable output. Existing admin/password/CSRF/throttling/command restrictions remain enforced.
- Run `php artisan db:seed --class=AdminDemoSeeder` locally for repeatable article workflow examples, taxonomy, media, draft FAQs and synthetic inbox records. It preserves edited and soft-deleted records and does not publish sample articles or send mail. `AdminSeeder` remains the separate account seeder and preserves existing credentials.
- The local database was backed up to ignored `.browser-runtime/before-admin-ui-seeding.sqlite` before seeding. Database configuration was not changed.
- Verification: production asset build, Blade compilation, route caching, desktop console/mobile form screenshots, task selection and delete cancellation; nine admin screens checked at 320px with no document overflow or browser errors.


### Contact inbox follow-up

Admin inbox supports exact message-reference lookup, topic filters and an Awaiting review view. Filter tabs retain reference/topic criteria and pagination retains all filters. Reviewed messages can be reopened by administrators without changing read status or requeuing email. Names, email addresses and message bodies remain encrypted; search operates only on reference/topic metadata. Verified mobile layout and regression coverage for filtering, reopening and editor denial.


### MySQL activation and command catalog (28 September 2026)

Switched .env to mysql / financershub on the existing local XAMPP server. All eight migrations are applied. Transferred all 17 data tables from the D: SQLite database into the previously empty MySQL data tables; normalized row values match across both databases, existing administrator credentials are preserved and contact decryption succeeds. Source SQLite retained, with a timestamped VACUUM backup in ignored .browser-runtime/before-mysql-*.sqlite. The XAMPP database remains on C:, which had approximately 30 MB free at migration time; more database-drive capacity is needed.

Maintenance now catalogs every registered Artisan command and offers 21 browser actions. Pending migrations require password and explicit backup confirmation and run noninteractively with production confirmation. Interactive, destructive, secret-revealing and long-running commands remain terminal-only. Existing throttling, audit history and lock remain. Verification: 43 tests / 644 assertions, Blade compilation, local browser console load with no browser errors, and full transferred-row comparison.


### Multiple categories, editor recovery and public uploads

Articles retain a primary category and support additional category checkboxes. All selected category pages list the published article, admin category filtering includes secondary categories, referenced categories are protected, and revisions include category membership. Migration 2026_09_28_000002 backfills existing primary categories and is applied to MySQL.

TinyMCE remains connected to the configured Tiny Cloud key. Local GET requests for login/admin on 127.0.0.1 redirect to localhost (same port/path/query) because the configured Tiny Cloud account accepts localhost. Production hosts must be registered with Tiny Cloud. Improved loading/retry controls and hidden-textarea validation prevent the visual editor from blocking saves. Desktop/mobile browser verification confirms initialized, editable content synchronized into the form.

Maintenance task choices have a bounded scrolling panel; the full searchable command catalog is collapsed by default. No existing command permissions changed.

Uploaded media now uses the uploads filesystem disk rooted at public/uploads, with new images in images/YYYY/MM. Existing demo image copied and checksum-verified into images/demo; original retained. Run php artisan media:move-to-public for legacy media on other environments; it is repeatable, verifies copies and preserves original files. Public uploads are accessible by their URL, including unpublished images. Only public-intended media belongs here. Application logs, sessions, compiled views, secrets and backups remain private. Include public/uploads in deployment backups; no storage symlink is required for uploaded media.

Validation: 46 tests / 670 assertions; production assets and Blade/route compilation; browser checks for TinyMCE, 320px layouts, compact console and direct public image access. Existing production-launch and dependency-review items remain separate.


### Reference-based article composer and reader improvements ? 29 September 2026

The article editor now follows the supplied reference: title/content/image/source/SEO cards, publish and organize sidebar, category checklist, featured toggle, and fixed save actions. Includes generated slugs for new articles, unsaved-change warnings, live SEO preview, automatic reading estimates, and image upload/selection without leaving the editor. TinyMCE remains connected to the configured key.

Save and apply status is transactional: a failed publication rolls back the content change. Admin/editor publication still requires real authorship, sources and content; author accounts cannot publish or feature articles. Scheduling and rescheduling use UTC. New migration adds indexed is_featured and has been applied to MySQL; published featured articles sort first on the homepage. Revisions preserve featured state.

Reader pages now show reading estimates, section navigation on mobile, a reading-progress indicator, and a correction link that prefills the contact form with the article URL and topic. Shared publication footer introduces editorial standards, author and contact links. Signup remains disabled.

Verification: 51 tests / 700 assertions; production asset build and Blade compilation; desktop and 320px mobile editor/reader layouts, TinyMCE editability, live MySQL JSON image upload/selection/cleanup, and isolated browser publishing with two categories. A temporary SQLite database on D: was used for publication tests; no sample article was published into the working MySQL publication.

XAMPP temporarily returned disk-full errors, then live MySQL recovered. PHP's relative upload_tmp_dir also emitted a notice into JSON responses. scripts/serve-local.ps1 explicitly places upload/sys temp files on D: without altering the global XAMPP configuration. It uses the existing .env database connection. Run powershell -NoProfile -ExecutionPolicy Bypass -File scripts/serve-local.ps1 -Port 8000 and open http://localhost:8000. The verified session currently runs on port 8770. C: still needs sufficient free space for XAMPP's database files.


### Mobile performance and storage recovery ? 30 September 2026

Same local homepage and Lighthouse mobile settings: performance improved from 72 to 96. FCP 3.7s -> 1.38s; LCP 4.9s -> 2.04s; CLS 0.0013; TBT 188ms. This is a local lab result, not a hosted PageSpeed/field guarantee. Before/after reports remain in .browser-runtime/lighthouse-mobile.report.html and lighthouse-mobile-optimized.report.html.

Generated 320/640/876px WebP delivery variants (quality 72) without modifying originals; design templates now offer responsive srcsets. Rebuild with php scripts/build-responsive-images.php after changing source editorial images. Three full-size delivery variants total approximately 108 KB versus 478 KB originals. Font stylesheets load without blocking first paint, with a noscript fallback. Fresh compiled CSS bundles now work locally too; stale bundles fall back to source styles so edits are not hidden. npm run build refreshes bundles.

Validation: homepage mobile screenshot, no browser errors; 51 tests / 696 assertions; asset build and Blade compilation. MySQL metadata queries and a rolled-back write probe pass, encrypted contacts decrypt, public upload read/write/delete probe passes, and all ten migrations are applied. C: free space measured approximately 1.69 GB. No remaining migration/data-transfer task is blocked by the prior storage shortage. Mail-provider configuration and dependency/launch review remain separate existing items.


## Project cleanup ? 30 September 2026
Temporary verification scripts, raw audit reports and screenshots referenced above have been removed. Historical results remain documented; rerun audits before release. Database backups, admin credentials, source assets and regression tests are preserved. Fixed seven unnamed article image links and topic-section text contrast. All ten MySQL migrations are applied; 51 tests / 700 assertions pass, and production assets build successfully. PHP/Laravel upgrade and deployment/provider configuration remain launch requirements.
