# Public localization

English remains the default at the existing URLs. Spanish uses `/es` (for example, `/es/contact`). The public language switch preserves the current page and supported filters. Admin screens always use English. Locale selection is URL-based, so shared links and cached responses have predictable languages.

## Article translations

Save an English article first. Open its editor and select **Article languages → Edit Spanish translation**. The Spanish editor supports title, excerpt, rich body content, SEO title/description, featured-image alt text, and disclosure. It uses the same TinyMCE configuration and server-side HTML sanitizer as English content.

Select **Publish Spanish translation** when editorial review is complete. It becomes public only if the parent article is also published. Authors can prepare drafts where their existing article permissions allow it; only users permitted to publish the parent article may publish translations. Version checks reject stale edits.

English remains the fallback when a translation is missing or unpublished. Missing optional Spanish metadata falls back to the Spanish title/excerpt; missing optional disclosures and image descriptions use English. Images, source URLs, categories, author identity, scheduling, and the article slug are shared. English and Spanish content are stored separately. A translation can be withdrawn by clearing its publication checkbox and saving.

## Search and SEO

Spanish searches include published Spanish translations and English source text. Draft translations are excluded. Translated articles appear in the same categories and homepage placements as their English originals.

Pages have locale-specific canonical URLs, language attributes and reciprocal `hreflang` links. English is `x-default`. A Spanish article falling back to English canonicalizes to the English URL and is not advertised as a Spanish article alternate. The sitemap includes Spanish article URLs only for published translations of public articles.

Reference: [Google's localized-page guidance](https://developers.google.com/search/docs/specialty/international/localized-versions).

## Maintaining copy

Public interface translations are in `lang/es.json`, with English source text as the default. Spanish contact validation messages are in `lang/es/validation.php`. Calculator and interactive control messages are in `resources/js/public-i18n.js`. Standard FAQ and editorial-team copy are translated by their source text; new or changed CMS text falls back to English until its translation is added. Article body translations are entered through the admin editor, not generated automatically.

The additive `article_translations` migration preserves existing content. Deploy with `php artisan migrate --force` and `npm ci && npm run build`. Include `public/build` in the deployment. Database backups must include `article_translations`.

Verification covers language isolation, missing/draft fallback, translated search and sitemap entries, admin permissions, HTML sanitization, stale saves, localized contact submissions and error pages. Existing English routes and application tests remain covered.
