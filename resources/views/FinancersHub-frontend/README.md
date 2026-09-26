# FinancersHub frontend design preview

Run `python3 build.py`, then serve `dist/` from a static web server. Example: `python3 -m http.server 8000 --directory dist`.

`dist/` contains the browsable design preview. `resources/views/` contains Blade-ready public layouts, information and contact views, and representative editorial studio views; copy `dist/assets/` to the Laravel application's `public/assets/`. This package is not a runnable Laravel application. No controller, model, migration, database, authentication, newsletter delivery, contact delivery, or CMS publishing is included.

## Article copy and imagery

`article_content.py` contains newly written, topic-specific educational drafts for all 11 guides. Each generated article links to the primary U.S. source used for its factual background. These drafts still need editorial fact checking, similarity screening, legal review where relevant, verified writer attribution, and review of rights before public publication. The generated editorial images and SVG brand assets were made for this project; their originality cannot be guaranteed to a percentage or substitute for a formal rights review. See `docs/ASSET-INVENTORY.md`.

## TinyMCE 8 in the admin editor

Open `/admin/editor.html`, enter your Tiny Cloud API key in the setup field, and choose **Activate editor**. The script loads from Tiny Cloud only after a key is supplied. The key is stored in that browser's local storage for this owner-private design preview; **Forget key** removes it. Tiny Cloud must list the Site's domain as an approved domain for that key. If the script or key cannot load, the underlying HTML textarea stays editable.

**Save to this browser** stores the title, summary, and body only in that browser's local storage. It does not publish or send a draft to a server. The preview control uses TinyMCE's preview plugin after activation. Do not use this browser-local save as a backup for important work.

For a Laravel integration, copy `config/financershub.php`, set `TINYMCE_API_KEY` in the Laravel environment, and use the included `resources/views/admin/editor.blade.php`. The Blade textarea accepts an optional configured key. A Tiny Cloud key appears in the client's script URL by design, so register only approved domains in Tiny Cloud. Connect the Save and Publish controls to authenticated backend endpoints, validate source URLs and metadata, and sanitize article HTML on the server before storing or rendering it. The app should enforce editorial roles, review history, and image rights separately.

The Blade article view expects an `$article` array with title, slug, category, category_slug, excerpt, author_name, published_at (Carbon date), read_time, and **server-sanitized** HTML. It can also receive related articles and optional image, canonical, TOC, source, and disclaimer fields.

See `docs/DESIGN-SYSTEM.md` for design notes and `docs/ASSET-INVENTORY.md` for asset status.

## FAQ and newsletter preview

The FAQ content lives in `faq_content.py` and generates `/faq.html`. The admin `/admin/faq.html` and `/admin/newsletter.html` screens support browser-local drafts; changes do not alter published pages. The pre-footer newsletter form shows the intended design but does not collect email addresses. Connect a consent-aware subscriber service, confirmation/unsubscribe flow, and authenticated CMS before enabling collection or publishing admin edits. The Blade component examples mirror the public navigation and footer; wire corresponding routes/controllers when integrating into Laravel.
