# HealthyLife / FinancersHub comparison

Reviewed 2 October 2026. Reference: `C:/xampp/htdocs/blog_site`. This project was read only; no reference files, credentials or database records were changed.

## Scope and evidence

Compared the reference's active public and web route files, inventoried its active Blade page families, and inspected key homepage, library, category, article, author, contact, legal, editor, account, settings, message and maintenance templates/controllers. Excluded the nested `resources/views/healthylife-remedy-laravel` design copy from the active-page inventory. Compared those features against FinancersHub's current routes, templates and implemented workflows.

The reference URL `http://localhost/blog_site/public/` returned HTTP 500. Its visual comparison is therefore based on source layout/styles, not a successful rendered-page or end-to-end audit. No claim is made that every reference feature works. FinancersHub's changed pages were verified in the browser after restarting its local preview server.

## Page and feature matrix

| Area | HealthyLife reference | FinancersHub finding / decision |
|---|---|---|
| Homepage hero | Large image-led hero, prominent toolkit and topic sections | Existing editorial typography retained; added a prominent savings-tool panel and topic discovery cards. |
| Homepage content | Latest articles, thematic sections, trust pillars, testimonials, signup | Published-article lead and rail already exist. Do not copy fixed testimonials, founding history, audience numbers or professional-review claims without evidence. |
| Main navigation | Topic and toolkit access | Calculator, categories, search and information pages already linked on desktop/mobile. |
| Footer | Topic/company/legal links, newsletter | Equivalent useful links exist; newsletter intentionally disabled. No reader registration was enabled. |
| Article library | Search, category pills, clear search, empty states | Added combined search/topic filtering, newest/oldest/title sorting, reset links and actionable empty states. Secondary categories are included. |
| Category pages | Image hero, category switching, article cards | FinancersHub has category intro, first-article visual, topic sidebar and pagination. Avoid copying fixed stock hero images that imply actual article artwork. |
| Article header | Category, byline, dates, read time, social sharing | Core metadata present. Current copy-link sharing avoids third-party widgets. Reference social-share links remain optional, not a missing publication requirement. |
| Article content | Rich text, images, related stories, editorial sidebar | FinancersHub additionally has generated contents navigation, source list, corrections link, author bio and reading progress. Existing image containment remains intact. |
| Author index/detail | Doctor profiles, photographs and qualifications | Team profile and published guides are available. Individual portraits/credentials should only be added for real contributors who supply them. |
| About | Founding story, workflow and clinician team | Finance purpose, team and editorial process exist. Do not transfer the reference's health-specific history or experience claims. |
| Contact | Form and alternate contact channels | Finance form, reference numbers, encrypted inbox and delivery status exist. SMTP still needs configuration. |
| FAQ | Tool-specific answers in some reference pages | Finance has a dedicated managed FAQ page and matching structured data. |
| Calculator | BMI/nutrition, peptide and wellness tools | Finance has a savings/compound-interest tool with CSV. Added homepage prominence instead of unrelated medical tools. A budgeting or debt-payoff calculator is a possible later feature. |
| Remedy finder | Symptom/topic search and missing-topic reports | Health-specific. Finance equivalents could be a glossary and topic suggestions; existing contact form already accepts topic suggestions. |
| Reader dashboard/profile | Saved calculations, logs, weight/food history | Intentionally deferred in Finance with reader accounts disabled. Not treated as an accidental omission. |
| Reader login/register | Separate reader flows | Intentionally absent per owner decision. |
| Forgot/reset password | Email-based account recovery templates/routes | Useful future addition once reliable email delivery is configured. Admin profile currently supports authenticated password changes. |
| Privacy | Data and cookie notices | Finance notice reflects its own contact storage, hashed visitor measurement and calculator privacy. Never copy health-data statements. |
| Cookie policy | Separate page | Finance's privacy page already describes relevant cookies/technology; a separate page is optional. Reassess if advertising or analytics vendors change. |
| Disclaimer/terms | Medical disclaimer and site terms | Finance has its own educational disclaimer and operational usage copy; owner/legal review remains a launch gate. |
| Advertising | Inquiry form/media kit with fixed audience statistics | Defer a media kit until real inventory, audience data and a commercial contact process exist. No audience claims copied. |
| Sitemap/robots/ads | Discovery and publisher configuration | Already implemented in Finance with staging indexing controls and published-content filtering. |
| Error pages | Reference 404/503 templates | Finance also has 403/419/429/500 templates. |
| Admin dashboard | Recent articles, quick actions, category breakdown | Finance dashboard already includes real publication counts, inbox priorities and traffic summaries. |
| Admin articles | Two-column rich editor, publication sidebar, image/SEO fields | Finance has these plus revisions, previews, concurrency protection and multi-category assignments. |
| Admin categories/authors | CRUD plus doctor visibility/photo fields | Equivalent taxonomy/author CRUD exists; real author portraits would be a future enhancement. |
| Admin media | Upload endpoint and image previews | Finance has an independent media library, alt/rights fields, WebP optimization and reference-aware deletion protection. |
| Admin inbox | Message detail, mailto quick reply, deletion | Finance offers unread/reviewed states, filtering and notification retry. A mailto link is not server-side email delivery; neither should be described as a full support desk. |
| Admin subscribers/users | Reader management | Intentionally deferred with signup. Finance should not show fabricated audience records. |
| Admin account | Name/email and password forms | Finance profile page is now implemented, with current-password verification and password-change sign-out. |
| Admin settings | General copy, social URLs, integrations, maintenance | Finance has publication/contact/SEO/ad settings and maintenance. Social-profile fields are optional when genuine URLs are supplied. |
| Admin maintenance | Grouped allowlisted Artisan actions | Finance already has command controls/history. Do not add unrestricted shell execution merely to match a reference UI. |
| Analytics | Wellness-specific events and user histories | Finance has privacy-conscious visitor measurement. Logged-in financial histories are out of current scope. |
| Performance | Critical/deferred styles, external scripts | Finance already bundles public styles and avoids copying reference tracking/advertising identifiers. No new external dependency added by this work. |

## Changes made in FinancersHub

- Added a responsive homepage calculator panel with explanatory steps and a clear link to the existing working calculator.
- Added database-backed homepage topic cards linking to canonical category pages.
- Added library topic and sort controls alongside search, preserving filter state through pagination.
- Applied category filtering to primary and secondary assignments, using the existing published-only scope.
- Added deterministic sorting, request validation, clear/reset controls, descriptive result text and a calculator link in empty states.
- Styled the controls and panels for the existing light/dark palette and mobile layouts. Preserved the recent non-cropping image treatment.

## Priorities after this comparison

1. Complete the deferred supported runtime/security upgrade before production approval.
2. Approve editorial content and configure SMTP; then implement/test password recovery if wanted.
3. Add real contributor portraits, biographies and verified social links as contributors join.
4. Consider a finance glossary or a second carefully tested calculator based on actual visitor/search demand.
5. Add reader accounts/saved plans only as a separate approved feature, with an appropriate data/privacy design.
6. Revisit a live visual comparison when HealthyLife's HTTP 500 is resolved. Its deployment and database were not modified during this review.

## Validation

- Full suite: 70 tests, 810 assertions, passed.
- New regression tests cover primary/secondary topic discovery, draft exclusion, all sort options, malformed filters and homepage links.
- Homepage and filtered library loaded at 320, 768 and 1440 pixels: one H1 each, expected controls/panels present, no horizontal overflow, no reported browser errors.
- Inspected the desktop library screenshot; rebuilt shared CSS after refining input/select styling.
- Initial browser attempt failed because the preview server was stopped; those error-page measurements were discarded and the real pages retested.

This review identifies functional/design differences. It does not certify either site's content claims, legal compliance, production speed or absence of all bugs.
