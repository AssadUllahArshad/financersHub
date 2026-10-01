# Phase 7 security and release review

Reviewed September 28, 2026. This is a scoped application review, not an external penetration test or production launch approval.

## Implemented and verified

- Staff authentication regenerates sessions; logout invalidates the session and token. Guest/reader/author/editor/admin permissions are exercised by the feature suite.
- CSRF protection remains enabled. Tests explicitly enable CSRF during testing and reject tokenless login, contact and SEO writes with 419 responses.
- Login throttling rejects the sixth attempt in its configured minute. Public contact has a separate throttle, validation, consent requirement and honeypot.
- Staff responses and authenticated page responses now request private, no-store caching. Responses served through the web middleware add nosniff, same-origin framing, referrer and permissions policies, and a limited CSP restricting base URI, embedded objects and framing. This CSP does not claim to block all script injection. Static/error responses generated outside this middleware also need hosting-level header configuration.
- Article body HTML is reconstructed from an allowlist. Active markup, event handlers, unsafe link schemes and embeds are discarded. Structured data uses safe JSON encoding. Existing workflow/XSS tests remain part of the suite.
- Raster uploads are validated, bounded and re-encoded. Uploads now intentionally live under public/uploads and are accessible by direct URL, including images for unpublished articles. The media controller still gates its own route, but does not make these public files private; referenced assets cannot be deleted. Image rights and editorial claims still require human review.
- Contact names, email addresses and message bodies are encrypted at rest. Admin-only inbox access is tested. APP_KEY retention and owner-approved message retention are launch requirements.
- The read-only `financershub:preflight` command reports unsafe deployment settings without printing keys, credentials or personal records. It deliberately does not claim that passing configuration checks establishes launch readiness.

## Dependency findings: launch blockers

Installed PHP is 8.1.25 and Laravel is 10.50.3. Laravel 10 security support ended February 4, 2025 according to the [official support table](https://laravel.com/docs/10.x/releases#support-policy). PHP 8.1 is absent from the [currently supported PHP branches](https://www.php.net/supported-versions.php). Plan and test a supported runtime/framework migration against the selected host before public launch.

`composer audit --locked` returned three advisory records for Laravel, representing two distinct findings (the email finding is listed by two advisory sources):

- [Temporary signed URL path confusion, GHSA-crmm-hgp2-wgrp](https://github.com/advisories/GHSA-crmm-hgp2-wgrp), medium.
- [CRLF injection in the default email rule, GHSA-5vg9-5847-vvmq](https://github.com/laravel/framework/security/advisories/GHSA-5vg9-5847-vvmq), high in the GitHub record; also listed as CVE-2026-48019.

The application does not use temporary signed URLs. Contact email functionality is now implemented but no real recipient/provider is configured; contact email input additionally rejects CR/LF. Newsletter delivery remains disabled. These facts do not resolve the unsupported dependencies. Do not enable those features as a workaround for the upgrade requirement. No advisory suppression or forced framework update was applied.

`npm audit` reported two vulnerable packages: Vite (high) and its esbuild dependency (moderate). The installed compatible versions are Vite 5.4.21 and esbuild 0.21.5; the audit proposes a major Vite upgrade. The current public pages use static `public/assets` and do not require an exposed Vite development server. Keep development servers local and upgrade Vite together with its Laravel plugin after checking Node/plugin compatibility. Do not use `npm audit fix --force` without that review. The new package-lock.json records the tested dependency tree, not a claim of a clean audit.

Audit outputs are retained locally in ignored `.browser-runtime/composer-audit.json` and `.browser-runtime/npm-audit.json`; rerun before release because advisories change. The active publication assets and stock Vite build are separate: both must be included when packaging the application.

## Review limits and next actions

The functional suite covers publication visibility, authorization, revisions, concurrency conflicts, slug redirects, media restrictions, contact privacy, disabled signup, metadata and configuration checks. Desktop/mobile and dark-mode UI checks were completed in Phase 6. Production HTTPS/proxy behavior, realistic load testing and production-like deployment/restore checks still depend on the chosen host and supported runtime.

The local SQLite rehearsal copies only the isolated browser-test database into a new ignored directory, checks integrity and table counts, and verifies encrypted contacts using the existing local key. It does not replace or write the working publication database. A complete hosting rehearsal must also restore private media and externally backed-up secrets.

See DEPLOYMENT.md for the release/backup procedure. Keep the overall Phase 7 open until dependency upgrades, content/legal approval, hosting verification and a full recovery rehearsal are complete.


## Project cleanup ? 30 September 2026
Temporary verification scripts, raw audit reports and screenshots referenced above have been removed. Historical results remain documented; rerun audits before release. Database backups, admin credentials, source assets and regression tests are preserved. Fixed seven unnamed article image links and topic-section text contrast. All ten MySQL migrations are applied; 51 tests / 700 assertions pass, and production assets build successfully. PHP/Laravel upgrade and deployment/provider configuration remain launch requirements.


## PHP 8.1 compatibility and rich content ? 30 September 2026
Owner confirmed PHP 8.1 or unknown hosting version. Laravel 13 trial reverted to Laravel 10.50.3; the runtime security upgrade remains blocked by the hosting version. Frontend dependencies updated with zero npm audit findings. See RUNTIME_AND_EDITOR.md for current security limitations, editor/image support and SEO changes.
