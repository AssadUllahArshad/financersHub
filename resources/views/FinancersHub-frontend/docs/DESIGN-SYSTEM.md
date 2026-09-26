# FinancersHub frontend design system

## Direction

Independent finance editorial: a two-level publication masthead, precise navy rules, teal wayfinding, asymmetric feature composition, Newsreader headlines, and a comfortable sans-serif reading interface. The logo pairs a custom open-ledger `FH` mark with a serif wordmark and compact `HUB` lockup. The supplied reference informs topic navigation, article discovery, and trust hierarchy; this implementation has its own visual language. All article text, bylines, and admin metrics are explicitly sample material.

## Tokens

| Role | Light | Dark |
|---|---|---|
| Brand navy | `#102b49` | `#dce9f5` |
| Deep navy | `#0a2038` | `#10263d` |
| Action teal | `#08776d` | `#5bcabc` |
| Text | `#152638` | `#e9eff5` |
| Secondary text | `#596977` | `#a9bac8` |
| Canvas | `#f5f7f7` | `#101c29` |
| Surface | `#ffffff` | `#192b3b` |
| Subtle panel | `#e9f3f1` | `#1c3d43` |
| Border | `#d9e1e6` | `#34485b` |
| Warning | `#b16a13` | `#e2ac60` |
| Error | `#aa3944` | `#ed9299` |

Typography: Newsreader for display, headlines, and long-form prose; DM Sans for body; Manrope for navigation, labels, and metadata. Display 48–93/1.1; H1 45–83/1.1; H2 35–56/1.1; H3 21–27/1.2; body 16/1.65; article body 20/1.7; small 14/1.5; metadata 12/1.5; overline 11/1.4. Heading tracking `-.04em`, overline `+.16em`. Fonts fall back to Georgia and Arial. Production may self-host font subsets to reduce third-party dependencies.

Spacing scale: 4, 8, 12, 16, 24, 32, 48, 64 px. Main container 1200 px; article reading column 690 px; contextual sidebar 290–300 px; gutters 20 px desktop, 14 px small screens. Breakpoints 1050, 760, and 520 px. Cards use 12 px radius, inputs 8 px, pill tags fully rounded. One shadow is reserved for raised content; most separation uses borders. Icon controls have 38 px minimum rendered box; enlarge touch targets to 44 px in production if required by the target accessibility standard.

## Editorial components

Global: utility bar, header/navigation/search, mobile menu, footer, breadcrumbs, article card, ad slot, newsletter. Article: byline, hero/caption, table of contents, callout, comparison table, quote, financial disclaimer, and related content. Editorial studio: grouped sidebar, workflow metrics, publishing queue, article table with local filters, rich-text editor mount, media library, taxonomy, author profiles, moderation, SEO preview, ad inventory, newsletter planning, and settings. The studio has its own responsive shell and light/dark palette. Frontend interactions include navigation, theme, local search and status filters, share-link copy, editor-formatting preview, and honest demo notices for actions that require backend services.

Final reader-facing pass: the homepage removes repeated promotional blocks, a fake popularity ranking, and unfilled ad placeholders. Ad slot components remain available for future configured placements; they are not shown as empty inventory to readers. Contact, About, Editorial policy, Disclaimer, Privacy, and Terms now use their own spacious reading system. Legal and privacy text is clearly a draft pending review against the final service. The public footer no longer exposes a link to the CMS concept.

Category art palette: personal finance and saving teal; investing and retirement blue; banking blue; credit and taxes warm amber; business and insurance slate; fintech cyan. The initial photographic system uses three original still-life and architectural images, selectively cropped and color-treated across routes. Article card imagery is decorative and uses `alt=""` when equivalent title context is adjacent.

## Trust and accessibility

No invented credentials or financial claims. Authorship is marked as sample data. Published articles need checked bylines, dated updates, named sources, corrections, and disclosures. Ads use explicit labels and reserved height. Semantic landmarks, visible focus, logical heading hierarchy, keyboard controls, reduced-motion handling, dark theme, and horizontal table scrolling are included. Avoid raw article HTML unless it has passed an allowlist sanitizer on the trusted server side.

## SEO and performance handoff

Blade layout exposes title, description, canonical, and head stacks for structured data and Open Graph metadata. Article dates use `<time>` and breadcrumb links are semantic. At integration time, add server-generated `Article` and `BreadcrumbList` JSON-LD only for verified real records; generate sitemap and robots policy from actual routes; use absolute canonical URLs; map results to indexable GET search pages as appropriate. Lazy-load below-the-fold media, reserve aspect ratios, set article hero `fetchpriority="high"`, preload or self-host critical font subsets, and reserve ad slots for the expected format. The current static preview uses Google Fonts and illustrative content, so Lighthouse performance and factual editorial quality are not certified.

## Screen map

Static preview has 45 HTML screens: homepage, 11 article details, 11 category pages, search/results, author profile, six publication pages, 404, 500, and 12 purpose-specific admin concepts. The `resources/views` directory provides starter Blade templates for the core routes, contact and information pages, representative studio screens, and reusable primitives. Other generated static screens are visual specifications to translate into Blade as Laravel routing and content sources are added.
