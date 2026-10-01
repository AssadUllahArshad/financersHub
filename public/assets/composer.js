(() => {
  const form = document.querySelector('#article-composer');
  if (!form) return;
  const title = form.querySelector('[name=title]'), slug = form.querySelector('[name=slug]');
  let manualSlug = !!slug.value, dirty = false, uploading = false;
  const status = document.querySelector('#composer-save-status');
  const markDirty = () => { dirty = true; status.textContent = 'Unsaved changes'; };
  form.addEventListener('input', markDirty); form.addEventListener('change', markDirty);
  window.addEventListener('beforeunload', event => { if (dirty) { event.preventDefault(); event.returnValue = ''; } });
  form.addEventListener('submit', event => {
    if (uploading) { event.preventDefault(); status.textContent = 'Wait for the image upload to finish.'; return; }
    if (!event.defaultPrevented) { dirty = false; status.textContent = 'Saving article...'; }
  });
  slug.addEventListener('input', () => { manualSlug = true; });
  title.addEventListener('input', () => {
    if (!manualSlug && form.dataset.existing === '0') slug.value = title.value.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').slice(0,200).replace(/-$/,'');
  });
  const updatePreview = () => {
    document.querySelector('#search-preview-title').textContent = document.querySelector('#seo-title').value || title.value || 'Your article title';
    const desc = document.querySelector('#seo-description').value || document.querySelector('#editor-summary').value;
    document.querySelector('#search-preview-description').textContent = desc || 'Your description will appear here.';
    document.querySelector('#search-preview-slug').textContent = slug.value || 'article-slug';
    document.querySelector('#seo-counter').textContent = `${document.querySelector('#seo-description').value.length} characters. Aim for a clear, concise summary.`;
  };
  form.addEventListener('input', updatePreview); updatePreview();
  const words = document.querySelector('#word-count');
  const reading = () => { const count = parseInt(words.textContent,10) || 0; document.querySelector('#reading-estimate').textContent = `${Math.max(1, Math.ceil(count/200))} min read`; };
  new MutationObserver(reading).observe(words,{childList:true,characterData:true,subtree:true}); reading();
  const media = document.querySelector('#featured-media'), preview = document.querySelector('#featured-preview');
  const showImage = () => { const option = media.selectedOptions[0]; preview.hidden = !option?.dataset.url; if (!preview.hidden) { preview.src = option.dataset.url; preview.alt = option.dataset.alt || ''; } else { preview.removeAttribute('src'); } };
  media.addEventListener('change', showImage); showImage();
  const publish = document.querySelector('#publish-status');
  const updateAction = () => { const labels = {'published':'Save & publish','scheduled':'Save & schedule','in-review':'Submit for review','unpublished':'Save & unpublish','draft':'Save as draft'}; const apply = document.querySelector('#apply-publication'); const same = publish.value === form.dataset.currentStatus && publish.value !== 'scheduled'; apply.textContent = labels[publish.value] || 'Save & apply status'; apply.hidden = same; const save = document.querySelector('#save-article'); save.classList.toggle('primary', same); save.classList.toggle('secondary', !same); };
  publish.addEventListener('change', updateAction); updateAction();
  const file = document.querySelector('#featured-file'), drop = document.querySelector('#image-dropzone'), upload = document.querySelector('#upload-featured');
  if (upload) {
    ['dragenter','dragover'].forEach(name => drop.addEventListener(name,event=>{event.preventDefault();drop.classList.add('dragging');}));
    ['dragleave','drop'].forEach(name => drop.addEventListener(name,event=>{event.preventDefault();drop.classList.remove('dragging');}));
    drop.addEventListener('drop', event=>{ if(event.dataTransfer.files.length) file.files = event.dataTransfer.files; });
    upload.addEventListener('click', async () => {
      const notice = document.querySelector('#upload-status'), image = file.files[0], alt = document.querySelector('#upload-alt').value.trim(), rights = document.querySelector('#upload-rights').value.trim();
      if(!image || !alt || !rights) { notice.textContent='Choose an image and enter alternative text and rights information.'; return; }
      if(image.size > 5*1024*1024) { notice.textContent='Choose an image smaller than 5 MB.'; return; }
      const data = new FormData(); data.append('image',image); data.append('alt_text',alt); data.append('rights',rights); data.append('_token',form.querySelector('[name=_token]').value);
      uploading = true; upload.disabled = true; notice.textContent='Uploading and optimizing image...';
      try {
        const response = await fetch(upload.dataset.url,{method:'POST',body:data,headers:{Accept:'application/json'}});
        const result = await response.json().catch(() => { throw new Error('The upload could not be confirmed. Check the media library before trying again.'); });
        if(!response.ok) throw new Error(Object.values(result.errors || {}).flat().join(' ') || result.message || 'Upload failed.');
        const option = new Option(result.name,result.id,true,true); option.dataset.url=result.url; option.dataset.alt=result.alt; media.add(option); const editor = window.tinymce?.get('article-body'); if(editor) editor.options.set('image_list', Array.from(media.options).filter(item=>item.dataset.url).map(item=>({title:item.textContent,value:new URL(item.dataset.url,location.href).pathname}))); media.value=String(result.id); showImage(); markDirty(); file.value=''; notice.textContent='Image uploaded and selected. Save the article to attach it.';
      } catch(error) { notice.textContent=error.message || 'Upload failed. Please retry.'; }
      finally { uploading=false; upload.disabled=false; }
    });
  }
  const connectEditor = () => { const editor = window.tinymce?.get('article-body'); if(editor) { editor.on('input change undo redo',markDirty); return true; } return false; };
  if(!connectEditor()) { let count=0; const timer=setInterval(()=>{if(connectEditor() || ++count>60)clearInterval(timer);},500); }
})();
