# Site audit - 1 October 2026

Scope: source review, automated application/security tests, production asset build, database migration status, and Chromium responsive/interaction checks. This is a bounded engineering audit, not proof that every possible bug has been eliminated.

## Findings and work list

| ID | Priority | Finding | Status |
|---|---|---|---|
| A01 | High | Public /media images only recognize featured references; inline references can return 404 | Fixed and regression-checked |
| A02 | High | Media deletion ignores historical article revisions and can break restored articles | Fixed and regression-checked |
| A03 | Medium | Sanitizer drops internal/root-relative and fragment links | Fixed and regression-checked |
| A04 | Medium | Image URL checks do not reject a single backslash or normalize local path traversal | Fixed and regression-checked |
| A05 | Medium | Sanitization can leave an empty saved body after stripping executable-only input | Fixed and regression-checked |
| A06 | Medium | Calculator result/download stays available after inputs change without indicating it is outdated | Fixed and regression-checked |
| A07 | Medium | Related articles only consider the primary category despite multi-category support | Fixed and regression-checked |
| A08 | Medium | Structured metadata is missing social-image alternative text and article time tags | Fixed and regression-checked |
| A09 | High | PHP 8.1 / Laravel 10 unsupported with known framework advisories | Deferred by owner; runtime upgrade required |
| A10 | Launch | Production domain/HTTPS, SMTP, scheduler, backup restoration and real reviewed content | Deployment/owner configuration required |
| A11 | Operational | C: has intermittently exhausted space; XAMPP MySQL still resides there | Host disk capacity remains external to code |

Signup, newsletter and reader accounts remain intentionally disabled. No unrelated feature expansion, fake analytics or fabricated publication records are part of this audit.

## Verified results

- 59 PHP tests, 752 assertions passed; covers role scoping, article save/review/publication, sanitization, media permissions and retention, inbox behavior, analytics, sitemap and SEO.
- Three calculator math tests passed: zero rate, comparison with closed-form compound growth, invalid/boundary inputs.
- 51 static public/admin routes at 320, 768 and 1440px: 153 Chromium frame checks. No remaining document overflow greater than 2px, missing main headings, error titles or broken loaded image sources. The editor's hidden empty featured-image preview has no source and is not a broken image. A browser-reset batch was discarded and rerun from the local site.
- Signed-out login directly verified at 320px. Calculator input changes hide CSV and clear stale results; recalculation produces updated values and restores download.
- Production asset build, Blade compilation, route caching and JavaScript syntax checks passed. Route cache cleared afterward for local development. All 11 MySQL migrations are applied.
- Fresh npm audit: zero vulnerabilities. Composer: four advisory records for Laravel, representing three issues (email CRLF is listed twice). No advisories were suppressed. PHP/Laravel upgrade remains deferred by owner.
- Local preflight correctly flags non-production environment, debug enabled, HTTP canonical URL, design preview enabled and non-secure local cookies. It passes key configuration, HTTP-only cookies, persistent sessions/cache, administrator setup and no published demo data.

## Fix details

- Public media authorization recognizes inline article images while unpublished articles remain private through the media controller. Static public uploads remain URL-accessible by design.
- Deletion checks include saved revisions, scanned lazily to avoid loading all snapshots into memory. Restorable history keeps its images.
- Article sanitizer preserves root-relative/fragment links and numeric media routes; rejects unsafe schemes, backslashes and local dot-segment image paths. Empty executable-only article bodies receive validation errors after sanitization.
- Related articles match primary or secondary categories.
- Public article metadata now includes published/modified timestamps and social-image alternative text.
- Calculator edits visibly invalidate old results and disable their CSV export until recalculated.

## Limits and remaining requirements

This audit does not certify every browser, screen reader, physical device, future content combination or load level. Dynamic record paths are covered by application tests, not an exhaustive visual sweep of every saved record. Private author/admin permission boundaries were checked by tests; no real mail was sent and no content was published for this audit.

Production still requires supported PHP/framework versions, reviewed real content/legal text, HTTPS/proxy configuration, SMTP delivery verification, scheduler activation, database/uploads/key backups with restore verification, and sufficient disk capacity. CDN cache-hit analytics limits and IP-based approximate uniqueness remain documented in TOOLS_AND_ANALYTICS.md. Search rankings and rich-result eligibility are not guaranteed by technical SEO changes.

## Fresh mobile Lighthouse result

Local homepage, 1 October 2026: Performance 95, Accessibility 100, Best Practices 100, SEO 69. No runtime error or run warnings. The only failing scored SEO audit is `is-crawlable`, caused by intentional preview noindex/robots restrictions. Keep those protections locally; disable preview and verify crawling on the approved production domain. These are single-run lab results, not production field measurements or full accessibility certification. Raw report: ignored `.browser-runtime/final-mobile-audit.json`.

## Outcome

All eight actionable application findings A01-A08 were fixed. A09-A11 remain explicitly open because they require the deferred runtime upgrade, deployment configuration/content, or host disk capacity. No claim is made that all conceivable bugs have been eliminated.
