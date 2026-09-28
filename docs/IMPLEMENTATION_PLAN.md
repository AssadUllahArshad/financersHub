# FinancersHub implementation plan

## Audit (26 September 2026)

- Fresh Laravel 10 skeleton, PHP requirement ^8.1; installed CLI PHP 8.1.25. Composer dependencies are installed. No application changes or existing CMS were found.
- Vite 5, Laravel Vite plugin 1, Axios; standard resources/css/app.css and resources/js/app.js entry points. No frontend framework required.
- Only GET / renders welcome. User is the sole model; stock users, password reset, failed jobs and Sanctum token migrations. No login UI, editorial policies, publishing scheduler or application deployment configuration.
- PHPUnit 10 with skeleton feature/unit tests. Test database configuration initially commented out; never run destructive test migrations against the configured development database.
- Authoritative visual source: resources/views/FinancersHub-frontend/dist, including all public/admin HTML and assets. Nested resources/views contains incomplete representative views. Python generators and source package remain untouched as references.
- Assets are prebuilt CSS, JS, SVG and WebP. Serve an explicitly curated public/assets copy, as recommended by the package; do not depend on Python or a Vite dev server at runtime. Existing Vite pipeline remains available for future source modules.
- Static admin actions include browser-local drafts; public signup/contact are previews. Sample figures and editorial copy require prominent labeling and must not be treated as verified publication records.

## Route inventory

All application links use named routes; legacy .html paths redirect to canonical paths.

| Area | Routes |
| --- | --- |
| Public | / (home), /search (search), /articles/{slug}, /categories/{slug}, /authors/{slug} |
| Information | /about, /contact, /faq, /editorial-policy, /disclaimer, /privacy, /terms |
| Errors | genuine HTTP 404 and 500 templates; local preview paths /404 and /500 |
| Studio | /admin, /admin/articles, /admin/editor, /admin/media, /admin/categories, /admin/tags, /admin/authors, /admin/faq, /admin/comments, /admin/seo, /admin/advertisements, /admin/newsletter, /admin/settings |
| Authentication (phase 2) | GET/POST /login, POST /logout; no public registration |
| Editorial (phase 3) | article store/update, edit, authorized preview, trash/restore, revision restore and state transition endpoints |
| Supporting CMS (phase 4) | media upload/update/delete; category/tag/author/FAQ/settings writes behind authorization |
| Reader interactions (phase 5) | POST /contact, subscription confirmation and unsubscribe endpoints after provider selection |
| Operations (phase 6) | /sitemap.xml, /robots.txt; optional moderated comment endpoints only if requested |

## Data model and migration rules

Users have an admin/editor/author role and one author profile. Author profiles and categories have many articles. Articles belong to an author and category, optionally reference a media asset, have many revisions and many tags through article_tag. Revisions retain a content snapshot and actor. Media retain uploader, disk/path, MIME/size, alternative text and provenance. FAQs have order and draft/published state. Settings use unique keys. Redirects retain old published slugs. Contact messages are private, retained according to the approved policy. Subscriber records need consent timestamps, provider identifiers, confirmation/unsubscribe state and protected access. Audit events retain actor/action/target.

Use additive migrations, foreign keys, unique slugs, publishing indexes, timestamps and article soft deletion. Restrict deletion of referenced authors/categories/media. Never seed sample content as published. Never reset a populated database; inspect migration status before applying changes.

## Sequential delivery checklist

### Phase 1 — complete design integration
- [x] Audit repository and create full plan.
- [x] Extract every supplied screen into Blade views with shared public/studio layouts, navigation and footer.
- [x] Copy authoritative assets without overwriting the reference; named routes and legacy redirects.
- [x] Preserve mobile menus, themes, FAQ accordions, reading layout and search filtering.
- [x] Label fixtures and disable unconnected writes. Configure TinyMCE through TINYMCE_API_KEY with textarea fallback; no browser-local CMS persistence.
- [x] Test all pages, internal links/assets and error status codes; compile views; visually inspect representative desktop/mobile pages and record screenshots.

Dependency: audit. Acceptance: every supplied screen renders, no broken internal links/assets or console errors, responsive layout matches reference, honest form states. Verification: php artisan route:list; php artisan view:cache; php artisan test; browser checks at desktop and mobile sizes. Credential: Tiny Cloud key and approved domain for live rich-editor validation; fallback can be verified independently.

### Phase 2 — authentication and data foundation
- [x] Add session login/logout with rate limits, session regeneration and no self-registration.
- [x] Define role gates/policies and protect studio/write/file routes.
- [x] Add schema/models/relationships and isolated SQLite tests; development fixtures explicitly marked demo.
- [x] Add secure administrator provisioning command; verify clean migrations and permission matrix.

Dependency: verified phase 1. Acceptance: guests cannot access studio, roles are enforced, migrations and relationships pass tests. Verification: php artisan migrate --pretend; isolated migration tests; php artisan test --filter=Authentication. Decisions: owner admin identity/password provisioned interactively; production database selected by owner. No passwords in source.

### Phase 3 — article CMS and workflow
- [x] Article CRUD, filters, pagination, trash/restore, authorized previews and HTML sanitization.
- [x] Persist metadata, category/tags/author, featured image/alt, citations, disclosures and SEO.
- [x] Enforce draft/review/scheduled/published/unpublished transitions, revision snapshots/restoration and audit trail.
- [x] Scheduler publishes due approved articles; public pages query only published real records; accurate dates and related content.
- [x] Test transitions, draft isolation, ownership, sanitization, revisions and scheduling.

Dependency: phase 2. Acceptance: complete editorial journey works and public output is safe. Verification: targeted workflow tests, php artisan schedule:list, browser article creation/publication. Decisions: editorial staff identities and content approval; TinyMCE credential remains optional for textarea editing.

### Phase 4 — supporting content management
- [x] Secure raster image uploads, metadata, provenance and reference-safe deletion.
- [x] Category/tag/author/FAQ CRUD, FAQ order and publication controls.
- [x] Persistent selected site/footer/newsletter copy and stable slug redirects.
- [x] Test upload rejection, permissions, relationships and public FAQ behavior.

Dependency: phase 3. Acceptance: changes persist across sessions, referenced content cannot be silently broken. Verification: storage fake tests, CMS feature tests, browser edits. Decisions: production storage and approved image rights; local storage can support independent implementation.

### Phase 5 — search, contact and newsletter
- [x] Published-only server search with pagination and empty states.
- [x] Validated, rate-limited, spam-protected contact storage with honest receipt wording and private admin access.
- [x] Newsletter provider abstraction, consent/state model and protected administration.
- [x] Owner decision: keep newsletter/signup disabled; reader accounts and provider integration are deferred to a later phase.
- [x] Search/contact and disabled-subscription tests; provider delivery tests deferred with the feature.

Dependency: phase 4. Acceptance: messages really persist, subscription promises reflect actual integration state. Verification: search/contact tests; provider sandbox tests after configuration. Required decision: newsletter provider/account; retention/privacy policy. Finish independent work before requesting credentials. Owner explicitly deferred newsletter activation on September 27, 2026; phase 5 is complete for the revised scope. No delivery integration is claimed.

### Phase 6 — SEO and publication operations
- [x] Real metadata/canonical/OG/structured data and sitemap/robots output.
- [x] Functional SEO settings and database-backed dashboard counts.
- [x] Analytics/ad slots explicitly unavailable until owner chooses providers.
- [x] Comments disabled unless owner requests moderated comments; implement abuse controls before activation.
- [x] Document optional integrations and test SEO/publication visibility.

Dependency: phase 5 acceptance. Decisions: analytics provider, advertising provider and whether comments are required. Acceptance: no invented traffic/subscriber data, SEO matches real records, no unmoderated public comments. Verification: XML and structured-data tests, admin count tests, browser checks.

### Phase 7 — security, content review and launch
- [ ] Review CSRF/XSS/auth/uploads/rate limits/headers, dependency health and error logging.
- [ ] Optimize queries/assets/cache; desktop/mobile/accessibility checks.
- [ ] Owner approves fact-checked finance copy, authors/dates/disclosures, image rights, policies and similarity review.
- [ ] Full suite/build and reader/author/editor/admin end-to-end verification.
- [x] Deployment/environment guide, additive migration procedure, backup/restore, scheduler/queue setup and launch checklist (docs/DEPLOYMENT.md). Hosting execution remains pending.

Dependency: all earlier phases. Acceptance: all critical journeys verified and no misleading public placeholders. Verification: php artisan test; npm run build; dependency audits; documented deployment rehearsal and backup restore. Decisions: hosting/domain/mail/database/storage, approved legal policies and verified publication content. No claim of launch readiness or guaranteed originality without independent review.

## Risks and open decisions

- PHP/Laravel versions need a separate compatibility and supported-runtime review before public launch; do not silently upgrade the framework during design alignment.
- Static financial content is an editorial draft, not verified advice. Design previews must be noindex and labeled.
- Never use localStorage as production persistence. Theme preference storage is acceptable.
- Credentials and owner approvals block only dependent actions; document exactly what was verified and what remains.
- Do not deploy, publish sample content, or replace live data as part of design integration.

## Verification log

Phase 1: 47 supplied screens converted; named routes and original assets verified by automated crawl. Desktop/mobile home, FAQ, article, editor and dashboard screenshots in docs/screenshots. All checked screens fit 390px mobile viewport; menu/Escape behavior checked. Original source package retained. TinyMCE textarea fallback/config tested; provider-key/domain acceptance remains dependent on owner configuration.

Phase 2: isolated SQLite migrations and session/role tests pass. Local MySQL had no tables and could not write because C: was full. Per owner direction to use D:, .env now points to database/financershub.sqlite on D:; original MySQL database retained. Account provisioning command: php artisan financershub:user owner@example.com. No default password or owner account created. DemoSeeder is opt-in/local only and never publishes fixtures.

Phase 3: article create/edit/list/filter/trash/restore, authorized preview, source/SEO metadata, strict HTML allowlist, revision snapshots and stale-editor protection implemented. Workflow requires review before schedule/publish; authors cannot publish. Scheduled command tested with time travel. Article slug history redirects only to published content; revision/trash restore returns to draft. Public real records take precedence; explicitly labeled design fixtures remain available only when FINANCERSHUB_DESIGN_PREVIEW is enabled. Disable before launch.

Phase 4: raster images validated and re-encoded into private local storage, alt/rights metadata, protected media endpoints and reference-safe deletion. Categories/tags/authors/FAQs and footer settings persist in database; referenced taxonomy slugs are immutable. Article slug redirects preserve published links. Newsletter invitation remains unavailable pending provider selection. Primary tests: php vendor/phpunit/phpunit/phpunit --testdox. Phase 4-specific tests recorded below once run.

Windows tool helper logs still depend on C: outside this project. Project files, PHP temp files, browser profile/cache, SQLite DB and screenshots use D:. Elevated tool execution was required intermittently because sandbox helper logging failed on full C:.


Phase 4 verification: upload MIME rejection, private media visibility, metadata persistence, reference-safe deletion, FAQ publication/group/order and settings permissions tested.

Phase 5 independent work complete: published-only search, encrypted private contact storage with consent/honeypot/rate limiting, traceable receipt, protected administrator inbox; disabled newsletter provider abstraction and consent schema. Newsletter collects no addresses. Awaiting owner choice: keep newsletter disabled for now or select an existing provider. Analytics, advertising and comments choices also requested; no provider assumed.

Browser verification: isolated database staff login, native form draft save, in-review → published transition, public article rendering, contact receipt → administrator inbox and FAQ publish → public accordion verified. Live CMS/editor/article mobile width 390: scroll width 375 (no overflow). Tiny Cloud rejected the local origin/key: automatic disabled-editor detection and explicit HTML fallback added and browser-tested. Valid rich-editor credential/domain verification remains external.

Latest suite before final reading-navigation check: 21 tests, 436 assertions. Final verification also includes generated heading anchors and the complete compiled-view check. Screenshots include the final database-backed editor/article/contact/FAQ plus source-design homepage. No deployment performed.

Final verification: 21 tests, 438 assertions passed; Blade compilation and node --check for both active JavaScript files passed. Scheduler lists articles:publish-due every minute. Final FAQ/category/article mobile screenshots refreshed after restoring original page containers. Phase 5 remains pending the owner's newsletter choice; phases 6 and 7 have not been executed.


September 27, 2026 ? owner approved keeping signup disabled and deferring reader accounts. Phase 5 closed for this revised scope. Phase 6 implemented: persisted admin-only publication SEO defaults; trusted APP_URL canonical links, Open Graph/Twitter metadata, safe Article JSON-LD with actual author/dates; dynamic sitemap and robots output excluding private/unpublished/demo records. Preview mode requests no indexing and emits an empty sitemap. Dashboard now shows scoped database counts and recent articles. Advertising/comments screens show inactive states with no fake metrics or provider calls. No analytics provider assumed.

UI maintenance: standalone responsive staff login, password visibility and error focus; corrected stylesheet order, panel/form/table/editor spacing, sign-out button, mobile breadcrumbs, dark mode, role-aware sidebar and actual staff identity. Mobile sidebar supports Escape, focus containment and an overlay; closed offscreen navigation is inert. Public homepage avoids empty rails/sections; library has result counts and responsive search spacing. Newsletter wording matches the deferred decision. Original design source remains unchanged.

Phase 7 remains separate: production runtime/dependency review, editorial/legal approval, full deployment rehearsal, backups and hosting configuration have not been completed. No deployment or real content publication performed.

Phase 6 automated verification: 25 tests, 487 assertions passed. Compiled Blade views and syntax checks for site.js, studio.js and login.js passed. Browser verification used the isolated database on D:, including staff sign-in, responsive dashboard/editor, mobile menu/Escape and SEO dark mode. Screenshots: login-desktop.png, login-mobile.png, operations-dashboard.png, operations-dashboard-mobile.png, operations-menu-mobile.png, editor-spacing-mobile.png and seo-dark-desktop.png. Shared public-header overflow at 320px and low-contrast dark-mode panel headings were identified and corrected during the final visual pass.
Final visual recheck: home, library, contact, FAQ and article pages have document scroll width equal to the viewport at 320px (305px content area with scrollbar). Dark-mode panel heading contrast corrected. Focused Pint check passed for all five phase-6 PHP files.


September 28, 2026 - Phase 7 independent security/preparation work: added safe response headers and authenticated/staff no-store caching; regression tests for real CSRF rejection, login throttle and cache protection. Added read-only financershub:preflight with tests. Local preflight correctly fails production-only settings and missing administrator without modifying configuration or records.

Verification: 30 tests / 522 assertions pass. npm run build, Blade compilation, route caching and scheduler registration pass. npm installation and caches use D:, and package-lock.json now captures the tested build tree. composer audit reports three Laravel advisory records (two distinct findings); npm audit reports Vite high and esbuild moderate. These remain launch blockers requiring supported PHP/Laravel and compatible Vite/plugin upgrade work; see docs/SECURITY_REVIEW.md. Do not mark the full security/dependency or launch phase complete.

SQLite backup rehearsal used only .browser-runtime/verification.sqlite and a new isolated restore destination. All 17 table counts match, integrity_check returns ok, the restored homepage returns 200 and one encrypted synthetic contact record decrypts using the existing key. Production storage/secrets/media restore and host-specific deployment remain pending. No working publication data, account or environment settings changed. No deployment performed.
Final browser verification: authenticated /admin/editor returns 200, editor textarea is present, CSP and private no-store headers are applied, and no browser JavaScript errors were reported. Automation navigation wait timed out once; subsequent state and response checks succeeded.


September 28, 2026 - owner-requested enhancements after phase-7 preparation:
- Admin routes moved into routes/admin.php under shared auth/studio middleware; stronger per-operation gates retained.
- Added contact read/unread/reviewed states, filters, unread navigation count, notification state and retry controls. Contact recipient is editable in admin settings, blank until the owner supplies it; SMTP secrets remain in .env. Database-backed notification outbox is processed by contacts:send-notifications each minute with three attempts and five-minute retry delay. Sending is tested with fake transport; no real recipient/provider has been configured or emailed.
- Added admin-only maintenance console with per-run password confirmation, throttle, lock and persistent audit results. It accepts a fixed command list, never arbitrary shell/PHP/arguments or destructive commands.
- Added idempotent AdminSeeder and local-only TestingContentSeeder. Took a consistent D: SQLite backup, applied the additive migration and populated the requested working development database. Generated admin@financerhub.com credentials are private in .browser-runtime/admin-credentials.txt.
- Removed the requested public preview strip while retaining preview noindex/robots protections. Fixed duplicate navbar hover underline. Consolidated font requests, removed chained CSS imports, added hashed/minified production CSS bundles, Apache compression/cache guidance and resized WebP image uploads.
- Added validated AdSense publisher settings, ownership meta tag and ads.txt output. No ad scripts, analytics, subscription collection or public comments activated. AdSense approval and privacy/CMP work remain external.
- Browser: seeded login succeeds; inbox read-state POST persists; maintenance schedule:list records exit 0; no browser JS errors. Production CSS homepage at 320px has a 305px content/scroll width, zero loaded broken images and one generated CSS bundle. Local response/DOMContentLoaded observations were 351/464ms; these are local observations, not PageSpeed or field Core Web Vitals scores.

Final enhancement verification: 38 tests / 591 assertions passed, including mail success/failure retry bounds, read states, console password/command restrictions, idempotent seeders, AdSense validation and resized-image MIME/dimensions. Production asset build, Blade compilation and route cache compilation passed. No live mail or ad delivery was activated. OPERATIONS_FEATURES.md documents activation requirements and operating limits.

XAMPP switch attempt: connected successfully to MariaDB 10.4.32, empty financershub database. Migration repository and first two migrations were created; failed_jobs migration stopped with errno 135 (no room in record file). C: free bytes = 0 and XAMPP datadir/tmpdir are on that drive. Working .env remains SQLite on D: to preserve a functioning application; data transfer and final MySQL switch are pending space recovery. Resume with normal migrate, never migrate:fresh. No existing SQLite records were removed.
TinyMCE: configured .env key reaches the editor. Tiny Cloud rejects 127.0.0.1 as an unregistered domain. Browser verification on http://localhost:8767/admin/editor passes: initialized=true, readOnly=false, no notifications. Use localhost or register the intended hostname in Tiny Cloud. Key was not logged.

Checkbox/form polish: excluded checkbox/radio controls from text-field sizing in shared contact/admin CSS, added aligned 18px native controls with readable wrapping labels, 44px label hit area and keyboard focus, and fixed contact subject selection after validation. Checked 11 public/admin routes at 320px with no page overflow or clipped controls. Contact checkbox toggles with Space. Contact and FAQ controls remain 18x18 in dark mode; production CSS bundle verified. npm build and Blade compilation pass; 11 targeted existing tests / 365 assertions pass. Screenshots saved for desktop/mobile contact and dark-mode contact/FAQ.


### Admin management refresh (28 September 2026)

- Categories, tags, authors and FAQs now use searchable lists with separate create/edit pages, sorting, reference counts, visibility badges and delete confirmation. Referenced content remains protected; author profiles use a staff dropdown.
- Article lists support category/status/search filters and preserve the trash selection. Media supports filename/alternative-text search and format filtering.
- Shared admin controls have consistent dropdowns, button states, spacing and responsive layouts. Sidebar highlights follow content create/edit pages and hide media management from unauthorized authors.
- Maintenance includes explained task selection, live cache indicators, filterable execution history, actor/outcome details and copyable output. Existing admin/password/CSRF/throttling/command restrictions remain enforced.
- Run `php artisan db:seed --class=AdminDemoSeeder` locally for repeatable article workflow examples, taxonomy, media, draft FAQs and synthetic inbox records. It preserves edited and soft-deleted records and does not publish sample articles or send mail. `AdminSeeder` remains the separate account seeder and preserves existing credentials.
- The local database was backed up to ignored `.browser-runtime/before-admin-ui-seeding.sqlite` before seeding. Database configuration was not changed.
- Verification: production asset build, Blade compilation, route caching, desktop console/mobile form screenshots, task selection and delete cancellation; nine admin screens checked at 320px with no document overflow or browser errors.


### MySQL activation and command catalog (28 September 2026)

Switched .env to mysql / financershub on the existing local XAMPP server. All eight migrations are applied. Transferred all 17 data tables from the D: SQLite database into the previously empty MySQL data tables; normalized row values match across both databases, existing administrator credentials are preserved and contact decryption succeeds. Source SQLite retained, with a timestamped VACUUM backup in ignored .browser-runtime/before-mysql-*.sqlite. The XAMPP database remains on C:, which had approximately 30 MB free at migration time; more database-drive capacity is needed.

Maintenance now catalogs every registered Artisan command and offers 21 browser actions. Pending migrations require password and explicit backup confirmation and run noninteractively with production confirmation. Interactive, destructive, secret-revealing and long-running commands remain terminal-only. Existing throttling, audit history and lock remain. Verification: 43 tests / 644 assertions, Blade compilation, local browser console load with no browser errors, and full transferred-row comparison.
