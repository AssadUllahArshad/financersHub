from pathlib import Path
from html import escape
from article_content import GUIDES
from faq_content import FAQ_GROUPS

ROOT=Path(__file__).parent
OUT=ROOT/'dist'
categories=['Personal Finance','Investing','Banking','Credit','Business','Saving','Retirement','Insurance','Taxes','Fintech','Loans']
articles=[
('A clearer way to build an emergency fund','Personal Finance','A practical framework for choosing a target, setting a pace, and keeping cash available when you need it.','teal','emergency-fund'),
('How index funds work, in plain English','Investing','What an index tracks, how fund costs matter, and the trade-offs to understand before investing.','blue','index-funds'),
('What to check before opening a new bank account','Banking','A focused checklist for fees, access, deposit protection, and the terms that deserve a closer read.','ink','bank-account'),
('Credit card interest: the details that change your cost','Credit','Understand grace periods, balances, and why minimum payments stretch repayment.','gold','credit-interest'),
('A small business cash flow review you can do monthly','Business','See where money arrives, where it leaves, and what the next few weeks may require.','cyan','business-cash-flow'),
('The difference between saving and investing','Saving','Match each goal to a timeline, level of risk, and access to your money.','teal','saving-investing'),
('Planning for retirement when the timeline feels distant','Retirement','Start with the variables you control and revisit the plan as your circumstances change.','blue','retirement-planning'),
('How to compare insurance coverage fairly','Insurance','Look past the premium to limits, exclusions, deductibles, and claims experience.','ink','insurance-coverage'),
('A practical introduction to tax planning','Taxes','Organize the records and questions that make tax decisions easier to review.','gold','tax-planning'),
('How to assess a new financial app','Fintech','Check security, fees, data permissions, and what happens if you decide to leave.','cyan','financial-app'),
('Questions to ask before taking out a loan','Loans','Compare the total cost and repayment terms before relying on a monthly payment alone.','blue','loan-questions')]

def href_article(a): return '/articles/'+a[4]+'.html'
def ad(kind='feed'): return f'<div class="ad {kind}" role="complementary" aria-label="Advertisement placement"><div>Advertisement<small>Reserved placement · {"300 × 250" if kind=="side" else "Responsive"}</small></div></div>'
def visual(color='blue',class_=''):
    image={'teal':'editorial-personal-finance.webp','blue':'editorial-investing.webp','ink':'editorial-banking.webp','gold':'editorial-personal-finance.webp','cyan':'editorial-banking.webp'}.get(color,'editorial-investing.webp')
    return f'<div class="art {color} {class_}"><img src="/assets/images/{image}" alt="" loading="{"eager" if "article-hero" in class_ else "lazy"}" width="800" height="500"></div>' 
def card(a):
    return f'<article class="card"><a href="{href_article(a)}" aria-label="Read {escape(a[0])}">{visual(a[3])}</a><div class="body"><a class="eyebrow" href="/categories/{slug(a[1])}.html">{a[1]}</a><h3><a href="{href_article(a)}">{a[0]}</a></h3><p>{a[2]}</p><span class="meta">Guide</span></div></article>'
def slug(s): return s.lower().replace(' ','-')
def brand(light=False):
    return f'<a class="logo" href="/"><img src="/assets/{"logo-white" if light else "logo-primary"}.svg" width="323" height="62" alt="FinancersHub"></a>'

def header():
    links=''.join(f'<a href="/categories/{slug(c)}.html">{c}</a>' for c in categories[:6])
    return f'''<a class="skip" href="#main">Skip to content</a><header class="masthead"><div class="wrap masthead-main">{brand()}<div class="actions"><form class="header-search" role="search" action="/search.html"><label class="sr-only" for="header-query">Search guides</label><input id="header-query" type="search" name="q" placeholder="Search guides"/><button type="submit" aria-label="Search guides">⌕</button></form><button class="iconbtn theme-toggle" id="theme" type="button" aria-label="Switch to dark mode" title="Switch color theme">◐</button><button class="iconbtn menu-toggle" id="menu" type="button" aria-label="Open menu" aria-controls="mobile-nav" aria-expanded="false">☰</button></div></div><div class="nav-line"><div class="wrap nav-wrap"><nav class="nav" aria-label="Topics and pages">{links}<a href="/about.html">About</a><a href="/faq.html">FAQs</a><a href="/contact.html">Contact</a></nav><a class="nav-guide" href="/editorial-policy.html">How we write <span aria-hidden="true">↗</span></a></div></div><nav class="mobile-nav" id="mobile-nav" aria-label="Mobile navigation"><form class="mobile-search" role="search" action="/search.html"><label class="sr-only" for="mobile-query">Search guides</label><input id="mobile-query" name="q" type="search" placeholder="Search guides"><button type="submit">Search</button></form>{links}<a href="/about.html">About</a><a href="/faq.html">FAQs</a><a href="/contact.html">Contact</a><a href="/editorial-policy.html">Editorial policy</a></nav></header>'''
def footer():
    topics=''.join(f'<a href="/categories/{slug(c)}.html">{c}</a>' for c in categories[:5])
    return f'''<footer class="footer"><div class="wrap"><section class="footer-newsletter" id="newsletter" aria-labelledby="newsletter-title"><div class="footer-newsletter-copy"><span class="footer-kicker">THE FINANCERSHUB BRIEF</span><h2 id="newsletter-title">Good questions for<br><em>better money decisions.</em></h2><p>A short selection of practical guides, delivered when the newsletter launches.</p></div><div class="footer-newsletter-action"><form data-demo-form class="footer-signup"><label for="footer-email">Email address</label><div><input id="footer-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required><button type="submit">Get updates <span aria-hidden="true">↗</span></button></div></form><p class="signup-honesty">Signup is coming soon. This preview does not collect your email. <a href="/faq.html#newsletter-and-contact">How it will work</a></p></div></section><div class="footer-grid"><div class="footer-brand">{brand(True)}<p>Useful questions. Clear explanations. Room to make up your own mind.</p><a class="footer-library-link" href="/search.html">Explore the full guide library <span aria-hidden="true">↗</span></a></div><nav aria-label="Explore topics"><h3>Explore</h3>{topics}<a href="/search.html">All articles</a></nav><nav aria-label="Publication links"><h3>The publication</h3><a href="/about.html">About us</a><a href="/editorial-policy.html">Editorial policy</a><a href="/authors/editorial-team.html">Authors</a><a href="/faq.html">FAQs</a><a href="/contact.html">Contact</a></nav><nav aria-label="Information links"><h3>Information</h3><a href="/disclaimer.html">Financial disclaimer</a><a href="/privacy.html">Privacy</a><a href="/terms.html">Terms of use</a></nav></div><div class="footer-bottom"><span>© 2026 FinancersHub</span><span>Educational content only. Not personalized financial, investment, tax, or legal advice.</span><a href="#top">Back to top ↑</a></div></div></footer>'''

def layout(title,body,desc='Independent finance guides and practical explanations for better money decisions.',admin=False,info=False):
    shell=body if admin else header()+body+footer()
    return f'''<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="{escape(desc,quote=True)}"><meta name="color-scheme" content="light dark"><title>{escape(title)} | FinancersHub</title><link rel="icon" type="image/svg+xml" href="/assets/favicon.svg"><link rel="stylesheet" href="/assets/site.css"><link rel="stylesheet" href="/assets/editorial.css">{'<link rel="stylesheet" href="/assets/info.css">' if info else ''}{'<link rel="stylesheet" href="/assets/admin.css"><script src="/assets/admin.js" defer></script>' if admin else ''}<script src="/assets/site.js" defer></script></head><body id="top">{shell}</body></html>'''
def save(path,content):
    p=OUT/path;p.parent.mkdir(parents=True,exist_ok=True);p.write_text(content,encoding='utf-8')

def home():
    a=articles[0]
    top=''.join(f'<article class="lead-secondary"><a href="{href_article(x)}">{visual(x[3])}</a><div><a class="eyebrow" href="/categories/{slug(x[1])}.html">{x[1]}</a><h3><a href="{href_article(x)}">{x[0]}</a></h3><span class="meta">Explainer</span></div></article>' for x in articles[1:3])
    latest=''.join(f'<article class="editorial-row"><a href="{href_article(x)}">{visual(x[3])}</a><div><span class="eyebrow">{x[1]}</span><h3><a href="{href_article(x)}">{x[0]}</a></h3><p>{x[2]}</p><span class="meta">Guide</span></div></article>' for x in articles[3:8])
    topic_cards=''.join(f'<a class="topic-tile" href="/categories/{slug(c)}.html"><span class="topic-number">0{i+1}</span><strong>{c}</strong><span class="topic-arrow">↗</span></a>' for i,c in enumerate(categories[:8]))
    topic_shelves=''.join(f'<section class="wrap home-shelf" aria-labelledby="shelf-{slug(c)}"><div class="shelf-heading"><div><span class="eyebrow">EXPLORE / {c.upper()}</span><h2 id="shelf-{slug(c)}">{c}</h2></div><a class="text-link" href="/search.html">Explore the library <span aria-hidden="true">↗</span></a></div><div class="shelf-cards">{"".join(card(x) for x in picks)}</div></section>' for c,picks in [('Everyday decisions',[articles[0],articles[5],articles[2]]),('Planning ahead',[articles[1],articles[6],articles[7]])])
    return f'''<main id="main">
      <section class="wrap opening"><div class="opening-copy"><span class="eyebrow">THE FINANCERSHUB EDIT</span><h1>Understand money.<br><em>Move with confidence.</em></h1><p>Thoughtful guides on personal finance, investing, banking and the choices that shape your financial life.</p></div></section>
      <section class="wrap front-grid" aria-label="Featured stories"><article class="front-lead"><a class="lead-image" href="{href_article(a)}"><img src="/assets/images/editorial-personal-finance.webp" width="1600" height="1000" fetchpriority="high" alt="Quiet desktop with a notebook and pen"></a><div class="lead-copy"><span class="eyebrow">THE LEAD / PERSONAL FINANCE</span><h2><a href="{href_article(a)}">{a[0]}</a></h2><p>{a[2]}</p><a class="text-link" href="{href_article(a)}">Read the guide <span aria-hidden="true">↗</span></a></div></article><div class="front-rail"><div class="rail-heading"><span class="eyebrow">ALSO IN FOCUS</span><span class="meta">02—03</span></div>{top}</div></section>
      <section class="wrap section editorial-section"><div class="section-head"><div><span class="eyebrow">EXPLORE THE GUIDES</span><h2>Guides from the desk</h2></div><a class="text-link" href="/search.html">The full library <span aria-hidden="true">↗</span></a></div><div class="desk-grid"><div class="desk-list">{latest}</div></div></section>
      {topic_shelves}
      <section class="topic-band"><div class="wrap"><div class="section-head"><div><span class="eyebrow">EXPLORE THE LIBRARY</span><h2>Find your starting point.</h2></div><p>From everyday choices to long-term planning.</p></div><div class="topic-grid">{topic_cards}</div></div></section>
      <section class="wrap library-strip"><div><span class="eyebrow">THE FINANCERSHUB LIBRARY</span><h2>Find a guide for the question on your mind.</h2></div><a class="text-link" href="/search.html">Browse all guides ↗</a></section>
    </main>'''

def faq():
    groups=[]
    for index,(heading,entries) in enumerate(FAQ_GROUPS):
        ident=slug(heading)
        items=''.join(f'<details class="faq-item" {"open" if index==0 and i==0 else ""}><summary>{escape(question)}<span aria-hidden="true">+</span></summary><div class="faq-answer"><p>{escape(answer)}</p></div></details>' for i,(question,answer) in enumerate(entries))
        groups.append(f'<section id="{ident}" class="faq-group" aria-labelledby="faq-{ident}"><div class="faq-group-head"><span class="eyebrow">0{index+1} / FAQ</span><h2 id="faq-{ident}">{escape(heading)}</h2></div><div>{items}</div></section>')
    return f'''<main id="main" class="wrap faq-page"><nav class="bread" aria-label="Breadcrumb"><a href="/">Home</a> / <span aria-current="page">FAQs</span></nav><header class="faq-hero"><span class="eyebrow">HELP / COMMON QUESTIONS</span><h1>Answers that help<br><em>you find your way.</em></h1><p>How to use our guides, what the newsletter will offer, and where to find our editorial policies.</p></header><div class="faq-layout"><nav class="faq-jump" aria-label="FAQ topics"><strong>On this page</strong>{''.join(f'<a href="#{slug(h)}">{escape(h)} ↗</a>' for h,_ in FAQ_GROUPS)}</nav><div>{''.join(groups)}<section class="faq-contact"><span class="eyebrow">STILL LOOKING?</span><h2>Find the right next step.</h2><p>Explore the guide library or read about how this publication is being built.</p><div><a href="/search.html">Browse guides ↗</a><a href="/editorial-policy.html">Editorial policy ↗</a></div></section></div></div></main>'''

def article(a):
    title,cat,deck,color,key=a
    guide=GUIDES[key]
    article_sections=''.join(f'<section class="guide-section" id="section-{i}"><h2>{escape(heading)}</h2><p>{escape(paragraph)}</p></section>' for i,(heading,paragraph) in enumerate(guide['sections'],1))
    checks=''.join(f'<li><span>0{i}</span>{escape(text)}</li>' for i,text in enumerate(guide['checks'],1))
    toc=''.join(f'<a href="#section-{i}">{escape(heading)}</a>' for i,(heading,_) in enumerate(guide['sections'],1))
    source_label,source_url=guide['source']
    more_sources=''.join(f'<li><a href="{escape(url,quote=True)}" target="_blank" rel="noopener noreferrer">{escape(label)} ↗</a></li>' for label,url in guide.get('additional_sources',[]))
    related=''.join(card(x) for x in [x for x in articles if x!=a][:3])
    return f'''<main id="main" class="wrap"><nav class="bread" aria-label="Breadcrumb"><a href="/">Home</a> / <a href="/categories/{slug(cat)}.html">{cat}</a> / <span aria-current="page">{title}</span></nav><div class="article-layout"><article><header class="article-header"><span class="eyebrow">{cat} / EXPLAINER</span><h1>{title}</h1><p class="deck">{deck}</p><div class="byline"><span class="avatar" aria-hidden="true">FH</span><div><a href="/authors/editorial-team.html"><strong>FinancersHub editorial team</strong></a><br><span class="meta">Editorial preview · Educational guide</span></div></div><div class="share"><button class="btn ghost" type="button" data-copy-link>Copy link</button></div></header>{visual(color,'article-hero')}<p class="caption">Original editorial image for the FinancersHub design preview.</p><div class="prose"><p class="article-opening">{escape(guide['intro'])}</p><section class="article-takeaways" aria-label="Questions to keep in mind"><span class="eyebrow">KEEP IN MIND</span><h2>Three questions to ask</h2><ol>{checks}</ol></section>{article_sections}<section class="article-source" aria-labelledby="source-title"><span class="eyebrow">SOURCE &amp; FURTHER READING</span><h2 id="source-title">Check the primary source</h2><p>The guide is newly written for this preview and draws its factual background from <a href="{escape(source_url,quote=True)}" target="_blank" rel="noopener noreferrer">{escape(source_label)} ↗</a>. Check that source and current product terms before making a decision.</p>{f'<ul>{more_sources}</ul>' if more_sources else ''}</section><div class="disclosure"><strong>Educational preview.</strong> This draft is general information for U.S. readers, not personal financial, investment, tax, or legal advice. It needs final editorial and rights review before publication.</div></div><section class="article-author-card" aria-labelledby="author-title"><span class="avatar" aria-hidden="true">FH</span><div><span class="eyebrow">ABOUT THE AUTHOR</span><h2 id="author-title">FinancersHub editorial team</h2><p>Shared attribution for this design preview. Individual writer profiles and credentials will be added after verification.</p><a class="text-link" href="/authors/editorial-team.html">View profile ↗</a></div></section><section class="section article-more"><div class="section-head"><div><span class="eyebrow">FROM THE LIBRARY</span><h2>Continue reading</h2></div></div><div class="article-related-grid">{related}</div></section></article><aside><nav class="sidebar-box toc" aria-label="On this page"><h3>In this guide</h3>{toc}<a href="#source-title">Primary source</a></nav><div class="sidebar-box"><h3>More to explore</h3>{''.join(f'<a class="rank" href="{href_article(x)}">{x[0]}</a>' for x in articles[:3] if x!=a)}</div></aside></div></main>'''

CATEGORY_INTROS={
    'Personal Finance':'Make everyday money decisions with a clearer view of your cash, commitments, and options.',
    'Investing':'Understand what an investment owns, what it costs, and how it fits the time you have.',
    'Banking':'Compare accounts and services by the details that affect how you use your money.',
    'Credit':'Read borrowing terms carefully so the cost makes sense before you carry a balance.',
    'Business':'Keep financial decisions tied to the timing and needs of a working business.',
    'Saving':'Separate near-term money from longer-term goals and choose access accordingly.',
    'Retirement':'Turn a distant goal into decisions you can review as your circumstances change.',
    'Insurance':'Compare coverage by the protection it provides, the limits, and your potential costs.',
    'Taxes':'Get organized and check current guidance before making a tax-related decision.',
    'Fintech':'Look past an app interface to the data, costs, and services behind it.',
    'Loans':'Compare the full borrowing cost and repayment terms, not just the monthly payment.',
}

def category(c):
    matches=[a for a in articles if a[1]==c]
    others=[a for a in articles if a[1]!=c][:3]
    featured=matches[0]
    return f'''<main id="main" class="wrap"><nav class="bread" aria-label="Breadcrumb"><a href="/">Home</a> / <span aria-current="page">{c}</span></nav><section class="category-intro"><div><span class="eyebrow">TOPIC / {c.upper()}</span><h1>{c}</h1><p>{CATEGORY_INTROS[c]}</p><a class="text-link" href="{href_article(featured)}">Start with a guide ↗</a></div>{visual(featured[3])}</section><div class="content-grid section"><div><section class="category-featured"><div class="section-head"><div><span class="eyebrow">START HERE</span><h2>A guide to {c.lower()}</h2></div></div>{card(featured)}</section><section class="category-crossread"><div class="section-head"><div><span class="eyebrow">ACROSS THE LIBRARY</span><h2>Explore another question</h2></div><a class="text-link" href="/search.html">All guides ↗</a></div><div class="card-grid">{''.join(card(x) for x in others)}</div></section></div><aside><div class="sidebar-box"><h3>Browse all topics</h3><div class="topics">{''.join(f'<a class="pill {"active" if x==c else ""}" href="/categories/{slug(x)}.html" {"aria-current=page" if x==c else ""}>{x}</a>' for x in categories)}</div></div></aside></div></main>'''

def search():
    rows=''.join(f'<article class="result" data-search="{escape((a[0]+" "+a[1]+" "+a[2]).lower(),quote=True)}"><span class="eyebrow">{a[1]} / GUIDE</span><h3><a href="{href_article(a)}">{a[0]}</a></h3><p>{a[2]}</p><span class="meta">Guide · Editorial team</span></article>' for a in articles)
    return f'''<main id="main" class="wrap search-page"><div class="bread"><a href="/">Home</a> / Article library</div><section class="search-intro"><div><span class="eyebrow">THE FINANCERSHUB LIBRARY</span><h1>Find the right<br><em>starting point.</em></h1><p>Browse clear guides on everyday money decisions and the ideas behind them.</p></div><span class="search-index">INDEX / 01—11</span></section><form action="/search.html" class="search-form" role="search"><label for="query">Search the library</label><div><input type="search" name="q" id="query" placeholder="Try investing, banking, or credit" aria-label="Search articles"><button class="btn">Search ↗</button></div></form><div class="search-columns"><section><div class="section-head"><div><span class="eyebrow">EXPLORE / ALL GUIDES</span><h2>Articles</h2></div></div><div class="results" id="results">{rows}</div><div class="empty" id="no-results" hidden><h2>No matching articles</h2><p>Try a broader term or explore the topics to the right.</p></div></section><aside class="search-topics"><span class="eyebrow">BROWSE BY TOPIC</span>{''.join(f'<a href="/categories/{slug(c)}.html">{c}<span aria-hidden="true">↗</span></a>' for c in categories)}</aside></div></main>'''


from public_info import PAGES, render_info_page, render_contact

from admin_ui import NAV, render_admin
admin_nav = ['Overview'] + [name for _, group in NAV for key, name, _ in group if key != 'index']

def admin_page(section):
    return render_admin(section, articles, categories)

save(Path('index.html'),layout('Home',home()))
save(Path('search.html'),layout('Search',search()))
save(Path('faq.html'),layout('FAQs',faq(),'Answers about FinancersHub guides, the upcoming newsletter, contact, privacy, and editorial standards.'))
for a in articles:save(Path('articles')/(a[4]+'.html'),layout(a[0],article(a),a[2]))
for c in categories:save(Path('categories')/(slug(c)+'.html'),layout(c,category(c)))
for k in PAGES:save(Path(k+'.html'),layout(PAGES[k]['title'],render_info_page(k),info=True))
save(Path('contact.html'),layout('Contact',render_contact(),info=True))
author_articles=''.join(card(a) for a in articles[:6])
author_body=f'<main id="main" class="wrap author-page"><div class="bread"><a href="/">Home</a> / Authors / Editorial team</div><header class="author-intro"><div class="author-large-avatar" aria-hidden="true">FH</div><div><span class="eyebrow">AUTHOR PROFILE / DESIGN PREVIEW</span><h1>FinancersHub<br>editorial team</h1><p>Our sample guides use a shared byline while individual contributors and their credentials are being verified. Each finished story will make its authorship and editorial responsibility clear.</p><a class="text-link" href="/editorial-policy.html">How we approach our guides ↗</a></div></header><section class="author-feature"><span class="eyebrow">ABOUT THIS PROFILE</span><p>This is placeholder attribution for the design preview. Names, biographies, expertise, and publication histories should come from verified contributor records.</p></section><section class="section author-work"><div class="section-head"><div><span class="eyebrow">SELECTED GUIDES</span><h2>Articles by the editorial team</h2></div><span class="meta">A selection from the sample library</span></div><div class="author-card-grid">{author_articles}</div></section></main>'

save(Path('authors/editorial-team.html'),layout('Editorial team',author_body))
for section in [key for _, group in NAV for key, _, _ in group]:save(Path('admin')/(section+'.html'),layout(section.title(),admin_page(section),admin=True))
for status,title,copy in [('404','Page not found','That address may have changed. Search the publication or return to the homepage.'),('500','Something went wrong','Please try again shortly.')]:save(Path(status+'.html'),layout(title,f'<main id="main" class="wrap section empty"><span class="eyebrow">{status}</span><h1>{title}</h1><p>{copy}</p><a class="btn" href="/">Return home</a></main>'))
print('Generated',len(list(OUT.rglob('*.html'))),'screens')
