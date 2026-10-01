# Savings calculator and visitor analytics

Public tool: /tools/compound-interest-calculator. Linked from desktop/mobile navigation and the shared footer section; included in the public sitemap when design preview is off. Fixed-rate monthly compounding, contributions at month end, yearly breakdown and CSV download. Inputs remain in the browser. Currency is formatting only. No forecasting or guaranteed returns.

Admin report: /admin/analytics, restricted to manage-settings administrators. Seven, 30 and 90 day periods; approximate distinct visitor hashes, deduplicated views, popular pages, referrer domains, devices/browsers and paginated recent visits. No fabricated traffic is seeded.

visitor_traces stores HMAC-SHA256 of packed client IP using the private APP_KEY, page path (no query), domain-only referrer, coarse browser/device and timestamp. No raw IP, full user-agent, calculator values, location or account identity. Hashes are pseudonymous identifiers, not anonymous people. NAT/shared networks undercount and changing IPs overcount. Excludes recognized bots, signed-in staff, non-HTML/non-200/non-GET responses, DNT and Sec-GPC opt-outs. Same IP/page/minute is stored once using a unique database key. Tracking failures do not fail the page response.

VISITOR_ANALYTICS_ENABLED=false disables collection. analytics:prune removes records older than 90 days; scheduled daily. Configure Laravel's production scheduler. IP accuracy behind a proxy/CDN requires configuring only real trusted proxy addresses in TrustProxies. Do not blindly trust arbitrary forwarded headers. CDN-cached pages that never reach Laravel cannot be counted. APP_KEY changes reset visitor hashes and affect encrypted contact records.

The privacy page describes collection and opt-outs; review with final hosting/services before launch. Traffic growth is not guaranteed. Publish useful supporting guides and measure organic discovery after indexing.

Verification: 56 PHP tests / 738 assertions and 3 calculator math tests. MySQL migration applied; production build and Blade compilation passed. Browser default result: 17,175.24 from 1,000 starting balance plus 100/month at 5% for 10 years. No overflow at 320px.
