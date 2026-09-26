(() => {
  const studio = document.querySelector('.studio');
  if (!studio) return;

  const faqForm = document.getElementById('faq-admin-form');
  if (faqForm) {
    const list = document.getElementById('faq-edit-list');
    const status = document.getElementById('faq-admin-status');
    const bindRemove = () => list.querySelectorAll('.faq-remove').forEach(button => {
      button.onclick = () => button.closest('.faq-edit-item').remove();
    });
    try {
      const draft = JSON.parse(localStorage.getItem('financershub-faq-draft-v1') || 'null');
      if (Array.isArray(draft) && draft.length) {
        list.replaceChildren();
        draft.forEach(({ question, answer }) => appendFaq(question, answer));
      }
    } catch (_) { status.textContent = 'Saved FAQ draft could not be loaded.'; }
    function appendFaq(question = '', answer = '') {
      const item = document.createElement('article');
      item.className = 'faq-edit-item';
      const qLabel = document.createElement('label'); qLabel.className = 'admin-field';
      const qName = document.createElement('span'); qName.textContent = 'Question';
      const qInput = document.createElement('input'); qInput.className = 'faq-question'; qInput.required = true; qInput.value = question;
      qLabel.append(qName, qInput);
      const aLabel = document.createElement('label'); aLabel.className = 'admin-field';
      const aName = document.createElement('span'); aName.textContent = 'Answer';
      const aInput = document.createElement('textarea'); aInput.className = 'faq-answer-edit'; aInput.rows = 4; aInput.required = true; aInput.value = answer;
      aLabel.append(aName, aInput);
      const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'a-button secondary faq-remove'; remove.textContent = 'Remove question';
      item.append(qLabel, aLabel, remove); list.append(item); bindRemove();
      return qInput;
    }
    bindRemove();
    document.getElementById('faq-add')?.addEventListener('click', () => appendFaq().focus());
    faqForm.addEventListener('submit', event => {
      event.preventDefault();
      const entries = [...list.querySelectorAll('.faq-edit-item')].map(item => ({ question: item.querySelector('.faq-question').value.trim(), answer: item.querySelector('.faq-answer-edit').value.trim() }));
      try { localStorage.setItem('financershub-faq-draft-v1', JSON.stringify(entries)); status.textContent = 'FAQ draft saved in this browser. The published page has not changed.'; }
      catch (_) { status.textContent = 'Could not save the FAQ draft in this browser.'; }
    });
  }
  const newsletterForm = document.getElementById('newsletter-admin-form');
  if (newsletterForm) {
    const fields = ['newsletter-title-edit', 'newsletter-description-edit', 'newsletter-button-edit'].map(id => document.getElementById(id));
    const status = document.getElementById('newsletter-admin-status');
    try {
      const draft = JSON.parse(localStorage.getItem('financershub-newsletter-draft-v1') || 'null');
      if (Array.isArray(draft) && draft.length === fields.length) fields.forEach((field, index) => field.value = draft[index]);
    } catch (_) { status.textContent = 'Saved newsletter draft could not be loaded.'; }
    newsletterForm.addEventListener('submit', event => {
      event.preventDefault();
      try { localStorage.setItem('financershub-newsletter-draft-v1', JSON.stringify(fields.map(field => field.value.trim()))); status.textContent = 'Newsletter copy saved in this browser. The published site has not changed.'; }
      catch (_) { status.textContent = 'Could not save the newsletter draft in this browser.'; }
    });
  }

  try { if (localStorage.getItem('financershub-studio-theme') === 'dark') document.documentElement.dataset.adminTheme = 'dark'; } catch (_) {}
  document.getElementById('studio-theme')?.addEventListener('click', () => {
    const dark = document.documentElement.dataset.adminTheme !== 'dark';
    document.documentElement.dataset.adminTheme = dark ? 'dark' : 'light';
    try { localStorage.setItem('financershub-studio-theme', dark ? 'dark' : 'light'); } catch (_) {}
  });

  const menu = document.getElementById('studio-menu');
  menu?.addEventListener('click', () => {
    const open = studio.classList.toggle('sidebar-open');
    menu.setAttribute('aria-expanded', String(open));
    menu.setAttribute('aria-label', open ? 'Close studio menu' : 'Open studio menu');
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && studio.classList.contains('sidebar-open')) {
      studio.classList.remove('sidebar-open');
      menu?.setAttribute('aria-expanded', 'false');
    }
  });

  let toastTimer;
  function toast(message) {
    document.querySelector('.admin-toast')?.remove();
    const el = document.createElement('div');
    el.className = 'admin-toast';
    el.setAttribute('role', 'status');
    el.textContent = message;
    document.body.append(el);
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.remove(), 4000);
  }

  document.querySelectorAll('[data-admin-action]').forEach(button => button.addEventListener('click', () => {
    const action = button.dataset.adminAction;
    if (action === 'preview' && document.getElementById('article-body')) {
      const editor = window.tinymce?.get('article-body');
      if (editor) editor.execCommand('mcePreview');
      else toast('Activate TinyMCE for a formatted preview.');
    } else if (action === 'save' && document.getElementById('article-body')) {
      saveLocalDraft();
    } else if (action === 'save') {
      toast('This control is a frontend design preview.');
    } else if (action === 'publish') {
      toast('Publishing will be available after the CMS is connected.');
    } else {
      toast('This control is a frontend design preview.');
    }
  }));

  const search = document.getElementById('admin-search');
  const filters = [...document.querySelectorAll('[data-status-filter]')];
  const articleRows = [...document.querySelectorAll('[data-row-status]')];
  let activeStatus = 'all';
  function filterArticles() {
    const q = search?.value.toLowerCase().trim() || '';
    let count = 0;
    articleRows.forEach(row => {
      const match = (activeStatus === 'all' || row.dataset.rowStatus === activeStatus) && row.dataset.rowSearch.includes(q);
      row.hidden = !match;
      if (match) count++;
    });
    const counter = document.getElementById('article-count');
    if (counter) counter.textContent = `Showing ${count} sample article${count === 1 ? '' : 's'}`;
  }
  search?.addEventListener('input', filterArticles);
  filters.forEach(button => button.addEventListener('click', () => {
    activeStatus = button.dataset.statusFilter;
    filters.forEach(item => item.classList.toggle('selected', item === button));
    filterArticles();
  }));
  document.getElementById('select-all')?.addEventListener('change', e => {
    articleRows.filter(row => !row.hidden).forEach(row => { row.querySelector('input[type=checkbox]').checked = e.target.checked; });
  });

  const commentButtons = [...document.querySelectorAll('[data-comment-filter]')];
  commentButtons.forEach(button => button.addEventListener('click', () => {
    commentButtons.forEach(item => item.classList.toggle('selected', item === button));
    document.querySelectorAll('[data-comment-status]').forEach(card => {
      card.hidden = button.dataset.commentFilter !== 'all' && card.dataset.commentStatus !== button.dataset.commentFilter;
    });
  }));

  const articleTitle = document.getElementById('editor-title');
  articleTitle?.addEventListener('input', () => {
    const preview = document.getElementById('search-preview-title');
    if (preview) preview.textContent = articleTitle.value || 'Untitled article';
  });
  const bodyField = document.getElementById('article-body');
  const keyField = document.getElementById('tinymce-key');
  const keyStatus = document.getElementById('tinymce-status');
  const draftState = document.getElementById('draft-state');
  const draftKey = 'financershub-article-draft-v1';
  const cloudKey = 'financershub-tinymce-cloud-key';
  function editorText() {
    const active = window.tinymce?.get('article-body');
    if (active) return active.getContent({ format: 'text' });
    const holder = document.createElement('div');
    holder.innerHTML = bodyField?.value || '';
    return holder.textContent || '';
  }
  function updateWords() {
    if (!bodyField) return;
    const count = editorText().trim().split(/\s+/).filter(Boolean).length;
    document.getElementById('word-count').textContent = `${count} word${count === 1 ? '' : 's'}`;
  }
  function saveLocalDraft() {
    const draft = {
      title: articleTitle?.value || '',
      summary: document.getElementById('editor-summary')?.value || '',
      body: window.tinymce?.get('article-body')?.getContent() || bodyField?.value || ''
    };
    try {
      localStorage.setItem(draftKey, JSON.stringify(draft));
      if (draftState) draftState.textContent = 'Saved on this browser · not published';
      toast('Draft saved on this browser only.');
    } catch (_) { toast('Browser storage is unavailable. Copy your work before leaving.'); }
  }
  if (bodyField) {
    try {
      const saved = JSON.parse(localStorage.getItem(draftKey) || 'null');
      if (saved && typeof saved.body === 'string') {
        if (articleTitle) articleTitle.value = saved.title || '';
        const summary = document.getElementById('editor-summary');
        if (summary) summary.value = saved.summary || '';
        bodyField.value = saved.body;
        if (draftState) draftState.textContent = 'Browser-local draft restored · not published';
      }
      const savedKey = localStorage.getItem(cloudKey);
      if (savedKey && keyField) keyField.value = savedKey;
    } catch (_) {}
    bodyField.addEventListener('input', updateWords);
    updateWords();
    articleTitle?.dispatchEvent(new Event('input'));
    document.getElementById('editor-summary')?.addEventListener('input', e => {
      const seo = document.querySelector('.seo-preview p');
      if (seo) seo.textContent = e.target.value;
    });
    document.getElementById('editor-summary')?.dispatchEvent(new Event('input'));
    const activate = async () => {
      const key = (keyField?.value || bodyField.dataset.tinymceKey || '').trim();
      if (!/^[a-zA-Z0-9_-]{8,128}$/.test(key)) {
        if (keyStatus) keyStatus.textContent = 'Enter a valid Tiny Cloud API key to activate the editor.';
        return;
      }
      if (window.tinymce?.get('article-body')) return;
      if (keyStatus) keyStatus.textContent = 'Loading the visual editor…';
      try {
        localStorage.setItem(cloudKey, key);
      } catch (_) {}
      try {
        if (!window.tinymce) {
          await new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = `https://cdn.tiny.cloud/1/${encodeURIComponent(key)}/tinymce/8/tinymce.min.js`;
            script.referrerPolicy = 'origin';
            script.crossOrigin = 'anonymous';
            script.onload = resolve;
            script.onerror = () => reject(new Error('Tiny Cloud could not be loaded'));
            document.head.append(script);
          });
        }
        await window.tinymce.init({
          selector: '#article-body',
          height: 590,
          menubar: 'edit insert format tools',
          plugins: 'lists link table code preview wordcount fullscreen searchreplace',
          toolbar: 'undo redo | blocks | bold italic | bullist numlist | link table | removeformat | code preview fullscreen',
          toolbar_mode: 'sliding',
          content_style: 'body{font-family:Georgia,serif;font-size:18px;line-height:1.7;color:#183042;max-width:750px;margin:28px auto;padding:0 20px}h2{font-size:1.55em}a{color:#0f766e}',
          setup(instance) {
            instance.on('init input change undo redo keyup', updateWords);
          }
        });
        document.getElementById('editor-mode').textContent = 'TinyMCE visual editor';
        document.getElementById('tinymce-setup')?.classList.add('configured');
        if (keyStatus) keyStatus.textContent = 'Visual editor active. Save stores the draft on this browser only.';
        updateWords();
      } catch (_) {
        if (keyStatus) keyStatus.textContent = 'TinyMCE could not start. Check your key, approved domain, and network connection.';
      }
    };
    document.getElementById('activate-tinymce')?.addEventListener('click', activate);
    document.getElementById('forget-tinymce-key')?.addEventListener('click', () => {
      try { localStorage.removeItem(cloudKey); } catch (_) {}
      if (keyField) keyField.value = '';
      if (keyStatus) keyStatus.textContent = 'Saved key removed from this browser. Reload to leave the active editor.';
    });
    if (keyField?.value || bodyField.dataset.tinymceKey) activate();
  }

})();
