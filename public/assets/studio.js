(() => {
  const studio = document.querySelector('.studio');
  if (!studio) return;
  const menu = document.getElementById('studio-menu');
  const theme = document.getElementById('studio-theme');
  const sidebar = document.getElementById('studio-sidebar');
  const mobile = window.matchMedia('(max-width: 820px)');
  const backdrop = document.createElement('button');
  backdrop.type = 'button';
  backdrop.className = 'studio-backdrop';
  backdrop.setAttribute('aria-label', 'Close studio menu');
  backdrop.tabIndex = -1;
  studio.append(backdrop);
  menu?.setAttribute('aria-controls', 'studio-sidebar');
  function setMenu(open) {
    studio.classList.toggle('sidebar-open', open);
    menu?.setAttribute('aria-expanded', String(open));
    menu?.setAttribute('aria-label', open ? 'Close studio menu' : 'Open studio menu');
    if (sidebar) sidebar.inert = mobile.matches && !open;
    if (open) sidebar?.querySelector('a')?.focus();
  }
  backdrop.addEventListener('click', () => { setMenu(false); menu?.focus(); });
  mobile.addEventListener('change', () => setMenu(false));
  setMenu(false);
  try { document.documentElement.dataset.adminTheme = localStorage.getItem('financershub-studio-theme') || 'light'; } catch (_) {}
  theme?.addEventListener('click', () => {
    const value = document.documentElement.dataset.adminTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.adminTheme = value;
    try { localStorage.setItem('financershub-studio-theme', value); } catch (_) {}
  });
  menu?.addEventListener('click', () => setMenu(!studio.classList.contains('sidebar-open')));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && studio.classList.contains('sidebar-open')) {
      setMenu(false);
      menu?.focus();
    }
    if (event.key === 'Tab' && mobile.matches && studio.classList.contains('sidebar-open')) {
      const links = [...sidebar.querySelectorAll('a[href],button')];
      const first = links[0], last = links[links.length - 1];
      if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
      if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    }
  });
  const search = document.getElementById('admin-search');
  let activeStatus = 'all';
  const filters = [...document.querySelectorAll('[data-status-filter]')];
  function filter() {
    let count = 0;
    document.querySelectorAll('[data-row-status]').forEach(row => {
      row.hidden = !((activeStatus === 'all' || row.dataset.rowStatus === activeStatus) && row.dataset.rowSearch.includes(search?.value.trim().toLowerCase() || ''));
      if (!row.hidden) count++;
    });
    const counter = document.getElementById('article-count');
    if (counter) counter.textContent = `Showing ${count} sample articles`;
  }
  search?.addEventListener('input', filter);
  filters.forEach(button => button.addEventListener('click', () => {
    activeStatus = button.dataset.statusFilter;
    filters.forEach(item => { item.classList.toggle('active', item === button); item.setAttribute('aria-pressed', String(item === button)); });
    filter();
  }));
  const body = document.getElementById('article-body');
  if (!body) return;
  const status = document.getElementById('tinymce-status');
  function countWords() {
    const markup = window.tinymce?.get('article-body')?.getContent() || body.value;
    const doc = new DOMParser().parseFromString(markup, 'text/html');
    const words = (doc.body.textContent || '').trim().split(/\s+/).filter(Boolean).length;
    const counter = document.getElementById('word-count');
    if (counter) counter.textContent = `${words} words`;
  }
  body.addEventListener('input', countWords);
  countWords();
  document.getElementById('editor-title')?.addEventListener('input', event => {
    const title = document.getElementById('search-preview-title');
    if (title) title.textContent = event.target.value;
  });
  const key = body.dataset.tinymceKey;
  if (!key) { if (status) status.textContent = 'Visual editor needs TINYMCE_API_KEY in .env. HTML editing is available below.'; return; }
  if (status) status.textContent = 'Loading visual editor...';
  let useHtml = false;
  let healthTimer;
  const htmlButton = document.createElement('button');
  htmlButton.type = 'button';
  htmlButton.id = 'use-html-editor';
  htmlButton.className = 'a-button secondary';
  htmlButton.textContent = 'Use HTML editor';
  body.before(htmlButton);
  const script = document.createElement('script');
  script.src = `https://cdn.tiny.cloud/1/${encodeURIComponent(key)}/tinymce/8/tinymce.min.js`;
  script.referrerPolicy = 'origin';
  const fallback = () => {
    useHtml = true;
    clearInterval(healthTimer);
    const editor = window.tinymce?.get('article-body');
    if (editor) { body.value = editor.getContent(); editor.remove(); }
    htmlButton.hidden = false;
    htmlButton.textContent = 'Retry visual editor';
    body.required = true;
    body.hidden = false;
    body.style.display = '';
    if (status) status.textContent = 'HTML editor active. If TinyMCE is unavailable, check its API key and approved domain in Tiny Cloud.';
  };
  htmlButton.addEventListener('click', () => { if (useHtml) { useHtml = false; if (window.tinymce) script.onload(); else { script.remove(); document.head.append(script); } } else { fallback(); body.focus(); } });
  script.onerror = fallback;
  const loadTimer = setTimeout(() => { if (!window.tinymce?.get('article-body')) fallback(); }, 15000);
  body.form?.addEventListener('submit', event => {
    window.tinymce?.triggerSave();
    if (!body.value.trim()) { event.preventDefault(); if (status) status.textContent = 'Write some article content before saving.'; window.tinymce?.get('article-body')?.focus(); }
  });
  script.onload = async () => {
    if (useHtml) return;
    try {
      await window.tinymce.init({
        selector: '#article-body', height: 590, menubar: 'edit view insert format table tools',
        plugins: 'lists link image table code preview wordcount fullscreen searchreplace charmap visualblocks',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image table | subscript superscript charmap | removeformat | code preview fullscreen',
        toolbar_mode: 'sliding',
        image_caption: true, image_advtab: true, image_description: true,
        automatic_uploads: false, paste_data_images: false,
        convert_urls: false,
        image_list: Array.from(document.querySelectorAll('#featured-media option[data-url]')).map(option => ({title: option.textContent, value: new URL(option.dataset.url, location.href).pathname})),
        content_style: 'body{font-family:Arial,sans-serif;line-height:1.7;padding:12px}img{max-width:100%;height:auto}table{border-collapse:collapse;max-width:100%}td,th{border:1px solid #ccd5d5;padding:8px}figure{max-width:100%;margin:1em 0}pre{white-space:pre-wrap}',

        setup(editor) { editor.on('init input change undo redo', countWords); editor.on('input change undo redo', () => editor.save()); },
      });
      clearTimeout(loadTimer);
      if (useHtml) { fallback(); return; }
      const editor = window.tinymce.get('article-body');
      body.required = false;
      htmlButton.textContent = 'Use HTML editor';
      if (status) status.textContent = 'Visual editor ready. Your content is saved when you select Save article.';
      let checks = 0;
      healthTimer = setInterval(() => {
        if (editor.options.get('disabled') || editor.mode.isReadOnly()) fallback();
        if (++checks >= 60) clearInterval(healthTimer);
      }, 500);
      window.addEventListener('pagehide', () => clearInterval(healthTimer), {once: true});
    } catch (_) { fallback(); }
  };
  document.head.append(script);
})();
