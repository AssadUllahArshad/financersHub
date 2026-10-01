# Runtime, SEO and article content

Updated 30 September 2026.

## Runtime decision

Per the owner's PHP 8.1 requirement, the final application uses Laravel 10.50.3 and PHP 8.1.25. The Laravel 13/PHP 8.4 trial was reverted. composer.json and composer.lock retain the PHP 8.1-compatible dependency set. Use the existing PHP command and scripts/serve-local.ps1. Do not deploy the temporary trial runtime.

PHP 8.1 and Laravel 10 are unsupported. The security upgrade is not complete: the current Composer audit reports debug-page XSS, temporary signed URL path confusion, and email CRLF advisories (two records describe the same CRLF issue). Contact and recipient validation already reject CR/LF; login and console account validation now do so too. These application checks do not make the framework audit clean. Production debug must be disabled. To obtain a supported framework among the requested versions, provision PHP 8.2+ and upgrade to a current Laravel 12 patch. Never disable Composer security checks to conceal advisories.

Frontend dependencies upgraded to Vite 8, compatible Laravel Vite plugin, axios and explicit esbuild. npm install audit reports zero vulnerabilities. Node 22.19 was used for the production build; Node is needed during building, not PHP request serving.

## Editor and images

TinyMCE supports image insertion from the media library or an HTTP(S) URL, captions, alternative text, headings, lists, links, tables, cell spans, alignment, emphasis, superscript/subscript and code blocks. Upload local images using the form's image uploader with alternative text and rights information, then select the image in TinyMCE's image dialog. Newly uploaded images update that list immediately. Files are re-encoded and stored under public/uploads/images/YYYY/MM.

Server sanitization preserves supported markup and safe formatting, strips executable attributes/scripts/embedded players, and rejects data/blob/javascript image URLs. Pasted clipboard images are disabled: upload the file first so the article never saves temporary image data. Remote image availability depends on its external host; uploaded images are recommended. Saved inline media references prevent deletion through the media library.

Article images scale within the content width, captions remain visible, wide tables scroll within their container, and code wraps. Rich content is sanitized again on rendering. Automated coverage checks sanitizer idempotence, unsafe content removal and save/preview/public rendering of images and merged table cells.

## SEO

Existing canonical URLs, descriptions, social metadata, public-only XML sitemap, robots controls, redirects and article structured data remain. Added published-article breadcrumb structured data, category/language metadata and max-image-preview:large for indexable pages. Preview, search and error pages remain noindex. Heading anchors work with alignment formatting.

This is technical SEO support, not a ranking or rich-result guarantee. Before launch, set the final HTTPS APP_URL, disable design preview, publish reviewed content, verify the live sitemap/canonical URLs and submit the sitemap through Search Console. Validate structured data against the hosted pages.

References: https://laravel.com/framework/docs/12.x/releases ; https://developers.google.com/search/docs/appearance/structured-data/breadcrumb ; https://www.tiny.cloud/docs/tinymce/latest/image/

## Final verification

PHP 8.1.25 / Laravel 10.50.3: 53 tests, 721 assertions passed. Composer platform requirements pass. Production assets and Blade compilation pass. Browser verification confirms an editable TinyMCE instance with image/table plugins, captions and colspan retained; the uploaded sample image loads at 1200px natural width and scales to 183px inside the 207px mobile editor. No browser JavaScript errors. Test content was not saved to the working database.
