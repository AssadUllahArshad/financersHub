# Outstanding work — 5 October 2026

Reviewed the available conversation, original attached brief, project notes, routes/views/services and read-only local database state. PHP, Laravel and security upgrades are excluded as requested. This is a backlog review, not a fresh exhaustive browser audit. The follow-up implementation below supersedes the original review where noted.

## Confirmed remaining work

### Follow-up implementation, 5 October 2026

- [Keyword/content plan](KEYWORD_CONTENT_PLAN.md) prepared without invented volume, difficulty or traffic estimates. Publishing and translation review remain editorial tasks.
- Broken draft link repaired with revision history; draft publication states preserved.
- Generated 18 responsive WebP variants. Existing originals stay intact; a repeat rebuild creates zero duplicates. Featured, card and inline article images now advertise appropriate sizes. Translation-only media references are supported and protected against accidental deletion.
- Mobile Lighthouse (local server, simulated mobile): homepage **92**, article **94**, calculator **99**. CLS: **0.001 / 0 / 0**. LCP: **2.7s / 2.5s / 1.8s**. These are local lab results, not production or field guarantees. JSON reports are retained in ignored `.browser-runtime/lighthouse-current-*.json`.
- Verification: full suite **77 tests / 912 assertions**, then localization suite **7 tests / 60 assertions**, including the additional translation-media regression. Blade compilation and idempotent image rebuild passed. Mobile article browser check showed no console errors.
- Local setup and deployment documentation now reflect MySQL, persistent public uploads, Vite assets and first-party analytics.

| Priority | Item | Evidence and next action |
|---|---|---|
| Done | Link to an unpublished article | Removed the unavailable anchor while preserving its text and saving before/after revisions. Original finding: Published article 14 (`emergency-fund-first-target`) links to `monthly-compound-interest-explained`, article 15, which is a draft. The public destination returns 404 by design. Replace/remove this link or approve and publish the target; do not publish automatically just to repair a link. |
| High | Finish launch content | Current DB: article 14 published, 15 and 16 drafts; all are non-demo. Review the two drafts, sources, disclosures and illustrations before publishing. Independent similarity/originality review is not complete. User approved AI-assisted review drafts, not certified human-only or plagiarism-free content. |
| Partly done | Keyword research and planning | Source-backed English/Spanish query mapping and six future briefs are now in KEYWORD_CONTENT_PLAN.md. Search-volume/provider enrichment remains optional and unmeasured. Original finding: No keyword-research service, keyword inventory or imported search-volume dataset was found. The original brief does not specify a keyword provider. SEO title/description fields and site search already exist; those are not keyword research. Prepare US English/Spanish topic clusters, search intent and article-to-query mapping. If using volume/difficulty estimates, record the source and date. A focus-keyword field or provider API would be new implementation. |
| Medium | Spanish article content | Localization and the Spanish admin editor work, but the local DB contains zero Spanish article translations. Enter/review them and enable publication individually. English fallback is intentional. Newly edited FAQ, author and site-setting text also needs dictionary entries if Spanish copy is desired. |
| Done | Responsive image delivery | Added 320/640/960px variants, automatic upload generation, rebuild command and srcset/sizes on publication and body images. Originals preserved. Original finding: Historical notes describe responsive image variants, but current publication templates have no `srcset` or `sizes` attributes. WebP, dimensions and fitting remain implemented. Verify and wire appropriately sized variants into the active templates; do not assume the historical optimization survived the template refactor. |
| Local done | Fresh performance measurement | Current mobile scores: home 92, article 94, calculator 99. Production still requires testing; LCP is 2.7s/2.5s/1.8s respectively, so a universal sub-two-second target is not achieved. Original finding: Last recorded local mobile score: 95, before later Bootstrap/localization changes. Retest current home/article/calculator pages and the production host. The requested score above 80 and sub-two-second load are not freshly verified for this release. |
| Medium | Production operations | Deployment instructions exist, but live-host execution is not evidenced. Configure final HTTPS origin, indexing, scheduler, mail, retention cleanup and backups; rehearse restoration and verify live redirects/crawling/performance. |
| Done | Documentation reconciliation | Current MySQL, upload, build and analytics instructions reconciled in setup/deployment notes. Original finding: Historical plan/setup notes still describe completed MySQL/storage tasks as pending. Deployment notes also mention private media and no analytics despite public uploads and first-party traces. Reconcile current deployment packaging: DB (including translations), public/uploads, public/build, and configuration. Private backups/credentials must stay outside public directories. |
| Low | Rendered HealthyLife comparison | Source-level comparison was completed. The reference site returned HTTP 500 during that review, so a working browser comparison remains unverified. This review did not retest or modify blog_site. |

## External setup and approval

| Item | Current state | Needed |
|---|---|---|
| Contact notifications | Inbox/unread states and notification outbox implemented; local delivery configuration reports false. | Recipient in admin, provider credentials in environment, and actual delivery/retry verification. User intentionally postponed setup. |
| AdSense | Publisher ID empty. Admin verification metadata/ads.txt support exists; live ad rendering is inactive. | Real account ID and provider review, then intended ad placements and applicable consent behavior. Verification metadata is not live advertising. |
| Search Console | No verified property/submission is evidenced in the repo; local indexing is disabled intentionally. | Verify production domain, submit sitemap and inspect English/Spanish URLs after publication/indexing approval. |
| Forbes backlink | Not obtained; no outreach sent. | Independent editorial acceptance. A pitch can be prepared; a backlink cannot be guaranteed or created by changing this site's code. |
| Editorial/policy approval | Truthful team biography and policy copy exist. | Owner approval of finance content, originality review, image rights, disclosures and site policies. |

## Intentional deferrals, not missing implementation

- Reader accounts and newsletter registration remain disabled by the user's decision. Provider delivery, confirmation and unsubscribe belong to that later phase.
- Public comments remain inactive, as permitted by the original brief.
- Maintenance lists registered Artisan commands but executes reviewed browser-safe actions. Interactive, destructive and long-running commands remain terminal operations under the documented console scope.
- A finance glossary or additional calculators were suggestions, not agreed unfinished deliverables. Compound-interest calculation and visitor analytics already work.

## Completed despite older checklist entries

MySQL migration/data transfer and the translation migration are complete locally (`mysql` is the current driver). Admin profile/password editing, multi-category articles, TinyMCE, public uploads, inbox, dashboard, maintenance console and seeders are implemented. Sitemap/robots/canonicals/metadata, error pages, breadcrumbs, structured data, WebP uploads and truthful authorship are present. Bootstrap integration, prototype cleanup, theme switches and public English/Spanish localization are implemented.

Last completed localization suite: 76 tests / 890 assertions. No new test run was necessary for this documentation-only review. Earlier statements that all three articles are drafts are superseded by the current DB state above.

## Keyword sources and suggested sequence

[Google Keyword Planner](https://support.google.com/google-ads/answer/6325025) supplies keyword ideas and targeting filters. Evaluate US English and Spanish intent separately; advertising metrics are not an organic-ranking guarantee. [Search Console performance reports](https://support.google.com/webmasters/answer/7576553) provide the verified site's actual queries, impressions and clicks once data is available. No account connected, data purchased or search-volume figures invented in this review.

Recommended sequence: repair the draft link; finish editorial approval; prepare the keyword/topic map; add reviewed Spanish articles; verify responsive images and performance; complete provider and production setup when accounts are available.
