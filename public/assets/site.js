(() => {
 const root=document.documentElement;
 try { const saved=localStorage.getItem('financershub-theme'); if(saved==='dark' && !document.querySelector('.studio')) root.dataset.theme='dark'; } catch (_) {}
 const themeButton=document.getElementById('theme');const syncTheme=()=>{if(themeButton)themeButton.setAttribute('aria-label',root.dataset.theme==='dark'?'Switch to light mode':'Switch to dark mode')};syncTheme();themeButton?.addEventListener('click',()=>{root.dataset.theme=root.dataset.theme==='dark'?'light':'dark';syncTheme();try{localStorage.setItem('financershub-theme',root.dataset.theme)}catch(_){}});
 const menu=document.getElementById('menu'),nav=document.getElementById('mobile-nav');
 menu?.addEventListener('click',()=>{const open=nav.classList.toggle('open');menu.setAttribute('aria-expanded',String(open));menu.setAttribute('aria-label',open?'Close menu':'Open menu')});document.addEventListener('keydown',event=>{if(event.key==='Escape'&&nav?.classList.contains('open')){nav.classList.remove('open');menu.setAttribute('aria-expanded','false');menu.setAttribute('aria-label','Open menu');menu.focus()}});
 document.querySelectorAll('[data-copy-link]').forEach(button=>button.addEventListener('click',async()=>{try{await navigator.clipboard.writeText(location.href);button.textContent='Link copied'}catch(_){button.textContent='Copy unavailable'}}));
 document.querySelectorAll('[data-demo-form]').forEach(form=>form.addEventListener('submit',event=>{event.preventDefault();let status=form.nextElementSibling;if(!status?.matches('[role=status]')){status=document.createElement('p');status.setAttribute('role','status');status.className='notice';form.after(status)}status.textContent=form.classList.contains('contact-form')?'Preview only — your message has not been sent.':'Preview only — this form is not connected.'}));
 const query=document.getElementById('query');if(query){const params=new URLSearchParams(location.search),q=(params.get('q')||'').trim();query.value=q;const rows=[...document.querySelectorAll('[data-search]')];let visible=0;rows.forEach(row=>{const match=row.dataset.search.includes(q.toLowerCase());row.hidden=!match;if(match)visible++});document.getElementById('no-results').hidden=visible>0;document.title=(q?'Search: '+q:'Search')+' | FinancersHub'}
})();

(() => {
 const body = document.querySelector('#article-reading-body'), bar = document.querySelector('#reading-progress-bar');
 if(!body || !bar) return;
 let scheduled = false;
 const update = () => { scheduled=false; const rect=body.getBoundingClientRect(); const range=Math.max(1,rect.height-innerHeight); const progress=Math.min(1,Math.max(0,-rect.top/range)); bar.style.transform=`scaleX(${progress})`; };
 const queue = () => { if(!scheduled){scheduled=true;requestAnimationFrame(update);} };
 addEventListener('scroll',queue,{passive:true}); addEventListener('resize',queue); update();
})();
