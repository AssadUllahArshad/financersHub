# Architecture and Bootstrap refactor

Updated 3 October 2026.

## Decisions

The old application combined a prototype distribution, duplicate Blade pages, editable public CSS/JS, a bespoke stylesheet manifest, and an unused Vite scaffold. The active system now has one view tree and one Vite build pipeline.

Bootstrap is a set of reusable primitives, not a replacement visual theme. Selective Sass imports provide form controls, select/check inputs, buttons, grid, alerts, breadcrumbs and pagination. Reboot, generic typography, navigation/card styles and JavaScript plugins are deliberately not loaded because they would reset existing publication layouts or add unused code. Theme rules preserve the visual identity and dark-mode variables. The actual form, pagination, breadcrumb, alert and profile-grid markup uses Bootstrap classes.

The frontend has not been rewritten into a stock Bootstrap template. A publication still needs its own typography, reading widths, cards and dashboard layouts. Those styles are readable source files rather than repeated one-line additions to public output.

## Removed/reorganized

- Removed `resources/views/FinancersHub-frontend` from the runtime tree after backing it up.
- Removed the `design` view tree; moved the six active information pages into `pages` first.
- Removed database-empty fallbacks that returned sample design content. Empty states now always use real publication views, and nonexistent articles return 404.
- Renamed active `cms` views to `admin` and updated references.
- Moved admin-only article, content, maintenance, operations and profile controllers into the Admin namespace. Public FAQ rendering has its own controller.
- Extracted staff authentication routes and compatibility routes. Existing canonical URLs and named compatibility routes are retained.
- Removed the unused welcome screen, unused duplicate Blade components, old design-import script and custom asset build script.
- Moved CSS/JS sources out of `public/assets` and removed stale generated-manifest assets.
- Removed the unused Axios scaffold and direct esbuild dependency. Vite owns bundling.
- Removed 568 prototype-only CSS rules after checking selectors against active views, application code and JavaScript. Dynamic dashboard/TinyMCE selectors were explicitly preserved. Browser regression checks remain essential for future cleanup.
- Formatted active Blade, CSS, JavaScript and changed PHP sources. The one-time Blade formatter was removed afterward because its development dependencies introduced advisories.

## Boundaries retained

Publication domain logic stays in the existing services and policies. Media delivery and reader interactions currently retain their shared controllers; their authorization rules remain intact. No speculative repository abstraction or new framework layer was added. Database records, article URLs, upload paths, credentials and signup policy were not changed by this refactor.

The legacy `design_preview` configuration flag is retained for existing indexing/preflight compatibility. It no longer enables sample-template rendering. Use `SEARCH_INDEXING_ENABLED` deliberately when launching.

## Build/deployment contract

Run `npm ci && npm run build` in deployment and ship `public/build` with the application. Blade `@vite` resolves hashed assets, including calculator/editor scripts; production does not require a Node process. Do not edit or copy old `public/assets/*.css` files. Keep the public document root pointed at `public/` and preserve uploaded media separately.

The generated Vite output includes bundled Bootstrap, so comparing its total size with the old custom-only stylesheet is not a like-for-like custom-CSS measurement. Source cleanup reduced the custom theme; Bootstrap adds its reusable controls. No claim of improved Lighthouse score is made without a new measurement.

## Recovery

A private pre-refactor source archive is stored at `.browser-runtime/pre-bootstrap-source-backup.zip`. Additional removed components and pre-pruning CSS are stored in that ignored local directory. These are recovery material, not runtime dependencies. Never expose the runtime directory through the web server.

## Verification

The original prototype-directory-dependent integration test was replaced with active-route rendering and compiled-asset checks. Existing business, access-control and editor tests remain. The source migration also exposed and fixed a missing author-bio description that left a Blade output buffer open.

The PHP/Laravel security-support issue remains unresolved as previously deferred. This frontend/organization refactor must not be presented as a supported-runtime upgrade or blanket production approval.

Final validation: 70 PHP tests / 808 assertions passed; 3 calculator math tests passed; Blade and route caches compile; npm audit reports zero vulnerabilities. Public homepage, library, FAQ, contact, authors and calculator plus authenticated dashboard, article list, profile, editor and maintenance were checked at 390px and 1440px without horizontal overflow or browser errors. TinyMCE initialized successfully from its new bundled script.
