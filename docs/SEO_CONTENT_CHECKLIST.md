# SEO and editorial launch checklist

Verified locally on 1 October 2026. This is a record of evidence and remaining launch work, not a guarantee of ranking, originality or production performance.

| # | Item | Result and limits |
|---|---|---|
| 1 | Sitemap | `/sitemap.xml` dynamically lists canonical public pages, published articles and relevant taxonomy/author pages. Drafts, future publications and deleted articles are excluded. XML and inclusion rules have regression coverage. |
| 2 | Robots | `/robots.txt` is generated from the launch indexing setting. Local installation intentionally disallows crawling. Enable `SEARCH_INDEXING_ENABLED=true` only on the approved production site. |
| 3 | Noindex | Login, search, private previews and error responses remain noindex. The environment-wide switch protects staging. Do not rely on robots alone to remove an already indexed URL; blocked crawlers cannot read its noindex tag. |
| 4 | Redirects | Legacy `.html` aliases use permanent redirects to canonical routes. Saved article slug redirects resolve to the current article slug. Automated route/slug tests cover these behaviors. Final HTTP/HTTPS and www redirect behavior requires production-host testing. |
| 5 | Errors | Branded 403, 404, 419, 429, 500 and 503 views exist. Missing articles return 404 rather than substitute content. |
| 6 | Canonicals | Canonical URLs use APP_URL, omit tracking queries and preserve paginated page identity. Set the final HTTPS origin at deployment. |
| 7 | Metadata | Homepage, information pages, author profiles and FAQs have descriptive metadata; article drafts include editable SEO fields. |
| 8 | Headings | Each of the three article previews has one H1, followed by body H2 sections. Previous route audit checked 153 page/viewport combinations. One H1 is a useful publishing convention, not a strict Google ranking requirement. |
| 9 | FAQ schema | Published visible answers emit matching FAQPage JSON-LD, covered by regression tests. Google discontinued FAQ rich results on 7 May 2026; do not promise a search-result enhancement. |
| 10 | Breadcrumbs | Visible article breadcrumbs and published Article/BreadcrumbList structured data are implemented. Team bylines use Organization rather than a fabricated Person. |
| 11 | Orphan pages | Global navigation, footer, author index, category lists and related-article links provide discovery. The team profile is linked even before its first publication. The three drafts cross-link; approve/publish them together or remove links to drafts that remain unpublished. |
| 12 | Image alternatives | All six new editorial images have descriptive alternatives; featured uploads require alt text. All four images on each tested preview, including logos, have alt attributes. Continue reviewing descriptions editorially. |
| 13 | WebP | Six original 1400x800 WebP diagrams created. New raster uploads are optimized to WebP when GD supports it. SVG logos stay vector. Rebuild generated editorial images with `php scripts/build-editorial-images.php` or deploy the existing uploads directory. |
| 14 | Layout shifts | Media dimensions are persisted after upload/resizing and rendered in article/card markup. Existing local media were backfilled. Inline illustrations include dimensions. Previous mobile Lighthouse measured CLS 0; all three drafts passed mobile overflow/image-loading checks. |
| 15 | Under 2 seconds | Not guaranteed. Last local Lighthouse report: performance 95, FCP 1.3s, LCP approximately 2.0s, speed index 2.6s, CLS 0. Test the final published articles on the production host and monitor real-user data. |
| 16 | Content | Removed 13 flagged demo articles, the unused demo author, synthetic contact/FAQ/media records after a database backup. Prepared three AI-assisted, source-checked drafts with original diagrams, tables and citations. They are NOT published and are NOT represented as human-written or certified plagiarism-free. |
| 17 | Author bio | User-approved FinancersHub Editorial Team profile created with a truthful publication biography and no invented personal qualifications. |
| 18 | Forbes backlink | Not obtained. Only Forbes can publish a link on its site. No outreach sent, paid-link scheme used or affiliation claimed. |

## Editorial review queue

Sign into the local admin panel to review:

- `/admin/articles/14/preview`: How to choose a first emergency fund target you can actually track.
- `/admin/articles/15/preview`: Monthly compound interest explained: a savings example you can check.
- `/admin/articles/16/preview`: How to compare investment fees without overlooking the small print.

Edit at `/admin/articles/{id}/edit`. IDs above refer to this local database; other installations may assign different IDs.

Each draft has an AI-assistance disclosure, primary-source links, illustrative calculations, one featured illustration and one body illustration. An editor must check factual accuracy, wording originality, usefulness, assumptions and image captions before publishing. Do not remove the disclosure to imply human authorship. No external plagiarism certification has been performed.

Primary sources consulted:

- CFPB: https://www.consumerfinance.gov/an-essential-guide-to-building-an-emergency-fund/
- Investor.gov calculator: https://www.investor.gov/financial-tools-calculators/calculators/compound-interest-calculator
- Investor.gov fees bulletin: https://www.investor.gov/introduction-investing/general-resources/news-alerts/alerts-bulletins/investor-bulletins/updated
- Google Search updates (FAQ retirement): https://developers.google.com/search/updates

## Reproducible content setup

Run the normal migrations and taxonomy/admin provisioning first. Then run `php scripts/build-editorial-images.php` and `php artisan db:seed --class=LaunchEditorialSeeder`. The image generator needs GD with WebP and Arial or DejaVu Sans. The seeder preserves existing article edits, including trashed articles with the same slug; it does not publish drafts or reset admin credentials. Public uploads are ignored by Git and need deployment/backup or regeneration. Existing image dimensions were backfilled locally; copy both the database and media when migrating this installation.

Private pre-cleanup backup: `.browser-runtime/before-editorial-content-20261001.sql`. Keep it outside the public document root. Keep APP_KEY securely for encrypted contact records. Test fixture seeders remain available for local development; they are not live editorial content.

## Outstanding launch gates

- Supported PHP/Laravel runtime and outstanding Composer security advisories: deferred by the owner, still unresolved.
- Human approval of the three drafts and the site's editorial/usage/privacy wording. The usage page is plain operational copy, not jurisdiction-specific legal certification.
- Production HTTPS/APP_URL, indexing switch, scheduler, mail configuration, backup/restore rehearsal and final performance/crawl tests.
- SMTP is intentionally unconfigured; contact messages are stored in the inbox, but external delivery needs provider settings.
- Search Console submission after publication and indexing approval. Production preflight now fails if search indexing remains disabled.

## Ethical backlink work

After editorial approval, publish a useful calculator methodology page or a carefully sourced original analysis with downloadable calculations. A human editor can assess whether it offers a genuinely newsworthy angle for an appropriate Forbes journalist. Any pitch must disclose who created the material and avoid claiming research, credentials or relationships that do not exist. Linking to Forbes from this site does not create a backlink. No guaranteed backlink or ranking outcome is available.

## Validation

62 PHP tests / 767 assertions passed before the final preflight flag addition; targeted preflight and SEO suites rerun after it. Draft previews checked at 390px, plus a 1440px check; all article images decoded, tables rendered and no horizontal page overflow was found. Local author page and mobile screenshot visually inspected. Prior broad responsiveness/performance findings are in `docs/COMPREHENSIVE_AUDIT.md`; those are historical measurements, not a fresh production audit.
