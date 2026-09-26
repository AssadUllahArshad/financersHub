"""Reader-facing information pages for the static frontend design preview."""
from html import escape

PAGES = {
    'about': {
        'title': 'About', 'eyebrow': 'THE PUBLICATION',
        'headline': 'Finance deserves a clearer read.',
        'intro': 'Money decisions deserve explanations you can actually use. FinancersHub is a developing publication for readers in the United States who want the details, the trade-offs, and a clear place to start.',
        'keynote': ('OUR PURPOSE','Help readers ask better questions before they choose a financial product, plan a goal, or change course.'),
        'cards': [('Understand the question','Start with the choice a reader is actually making.'),('See the trade-offs','Make costs, limits, and uncertainty visible.'),('Know what comes next','Point to current terms and useful questions to ask.')],
        'sections': [
            ('01','What you will find','Practical guides, explainers, and comparisons across personal finance, investing, banking, credit, and business. Each article should begin with a question readers actually face.'),
            ('02','How we want to write','Plain language, visible sources, clear limits, and a useful next step. Readers should be able to see who prepared a piece and when it was last reviewed.'),
            ('03','What comes next','The example articles on this design preview demonstrate layout and reading patterns. Researched content and verified author profiles are needed before public launch.'),
        ],
        'aside': ('A note about this preview','The current pages show a frontend design. Article text and profiles are sample material, not published financial guidance.'),
    },
    'editorial-policy': {
        'title': 'Editorial policy', 'eyebrow': 'HOW WE WORK',
        'headline': 'A standard readers can see.',
        'intro': 'Readers should be able to see where a claim comes from, what it means, and when it needs another look. These are the standards intended for the future publication.',
        'keynote': ('EDITORIAL COMMITMENT','Show sources and limits clearly. Label commercial material. Correct meaningful errors.'),
        'sections': [
            ('01','Sources and verification','Use checkable primary material where relevant. Attribute figures, explain assumptions, and distinguish established information from interpretation.'),
            ('02','Authors and review','Show the writer and any relevant reviewer with an accurate biography. Do not imply professional qualifications that have not been verified.'),
            ('03','Updates and corrections','Show publication and update dates. Correct material errors clearly and review time-sensitive information when it changes.'),
            ('04','Commercial separation','Label sponsored content and advertising. Keep commercial placements visually distinct from editorial stories.'),
        ],
        'aside': ('Implementation status','These are intended standards for the future publication. The sample content on this preview has not gone through an editorial review process.'),
    },
    'disclaimer': {
        'title': 'Financial disclaimer', 'eyebrow': 'IMPORTANT CONTEXT',
        'headline': 'Information for learning, not a personal plan.',
        'intro': 'FinancersHub is for general education. An article can help you understand a decision, but it cannot account for your finances, goals, taxes, or tolerance for risk.',
        'keynote': ('PLEASE READ BEFORE ACTING','Check current product terms and speak with a qualified professional when a decision depends on your personal circumstances.'),
        'checklist': ['Check the date and source of the information.','Verify rates, fees, eligibility, and terms with the provider.','Get qualified guidance when your own circumstances determine the answer.'],
        'sections': [
            ('01','No individualized advice','Nothing on this site should be treated as personalized investment, financial, tax, legal, or other professional advice.'),
            ('02','Check current details','Rates, fees, eligibility, rules, and product terms can change. Verify important information with the relevant provider or official source before acting.'),
            ('03','Understand the risk','Financial decisions can have costs and risks. Consider your circumstances and seek qualified professional guidance when a decision depends on them.'),
            ('04','About this design preview','The example articles shown here demonstrate layout. They have not been reviewed as financial guidance and should not be used to make financial decisions.'),
        ],
        'aside': ('Before you act','Use published material as a starting point for questions. Check the current terms and the information that applies to you.'),
    },
    'privacy': {
        'title': 'Privacy', 'eyebrow': 'YOUR INFORMATION',
        'headline': 'Clear about data, too.',
        'intro': 'Readers should know what information a site collects and what happens to it. This page is a design draft for that explanation, pending the final services and data practices.',
        'keynote': ('DESIGN DRAFT','The contact and newsletter forms in this preview do not submit information. Final privacy terms require review against the working site.'),
        'sections': [
            ('01','Information you provide','The contact and newsletter forms in this design preview do not deliver messages or subscribe an email address. Their final operation will require a clear explanation of how submitted information is used.'),
            ('02','Site technology','A final policy should identify analytics, cookies, advertising partners, retention periods, and relevant user choices once these services are selected.'),
            ('03','Your choices','Readers should be able to find instructions for privacy requests and newsletter unsubscribe options when those features become available.'),
        ],
        'aside': ('Preview status','This is a design draft, not a complete policy for an operational service. It requires a review against the final implementation.'),
    },
    'terms': {
        'title': 'Terms of use', 'eyebrow': 'SITE TERMS',
        'headline': 'A fair framework for using the site.',
        'intro': 'The terms page should explain how readers may use the publication in plain language. This design draft will need to match the services available at launch.',
        'keynote': ('DESIGN DRAFT','These are layout and content examples. They are not the final terms for an operating publication.'),
        'sections': [
            ('01','Using the content','Explain the permitted use of articles, visual assets, and tools once the publication is operational.'),
            ('02','Availability and changes','Describe the site features, when content may change, and any limits that apply to the service.'),
            ('03','Important limitations','State how educational content, third-party links, and advertising are handled in the final terms.'),
            ('04','Questions about terms','Provide the correct contact route when the publication has a working support channel.'),
        ],
        'aside': ('Draft status','This is a content and layout draft. It should not be presented as the final legal terms of an operating website.'),
    },
}

def render_info_page(key):
    data=PAGES[key]
    sections=''.join(f'<section class="info-section" id="section-{i}"><span class="info-number">{num}</span><div><h2>{heading}</h2><p>{text}</p></div></section>' for i,(num,heading,text) in enumerate(data['sections'],1))
    nav=''.join(f'<a href="#section-{i}"><span>{num}</span>{heading}</a>' for i,(num,heading,_) in enumerate(data['sections'],1))
    aside_heading,aside_body=data['aside']
    kicker,statement=data['keynote']
    extra=''
    if 'cards' in data:
        cards=''.join(f'<div><span class="info-number">0{i}</span><h2>{title}</h2><p>{copy}</p></div>' for i,(title,copy) in enumerate(data['cards'],1))
        extra=f'<section class="info-cards" aria-label="What readers can expect">{cards}</section>'
    if 'checklist' in data:
        items=''.join(f'<li><span>0{i}</span>{copy}</li>' for i,copy in enumerate(data['checklist'],1))
        extra=f'<section class="info-checklist" aria-labelledby="checklist-title"><div><span class="eyebrow">A PRACTICAL CHECK</span><h2 id="checklist-title">Before relying on an article</h2></div><ol>{items}</ol></section>'
    related=''.join(f'<a href="/{route}.html">{label}<span aria-hidden="true">↗</span></a>' for route,label in [('editorial-policy','Editorial policy'),('disclaimer','Financial disclaimer'),('contact','Contact')] if route!=key)
    return f'''<main id="main" class="wrap info-page info-{key}"><nav class="bread" aria-label="Breadcrumb"><a href="/">Home</a> / <span aria-current="page">{data['title']}</span></nav><header class="info-hero"><span class="eyebrow">{data['eyebrow']}</span><h1>{data['headline']}</h1><p>{data['intro']}</p></header><div class="info-keynote"><span class="eyebrow">{kicker}</span><p>{statement}</p></div>{extra}<div class="info-layout"><article class="info-body">{sections}</article><aside class="info-aside"><nav class="info-toc" aria-label="On this page"><span class="eyebrow">IN THIS SECTION</span>{nav}</nav><div class="info-aside-note"><span class="eyebrow">PLEASE NOTE</span><h3>{aside_heading}</h3><p>{aside_body}</p></div></aside></div><div class="info-end"><div><span class="eyebrow">KEEP EXPLORING</span><h2>Find what you need next.</h2><p>More context about the publication and the content you read here.</p></div><div class="info-next-links">{related}<a href="/search.html">Article library<span aria-hidden="true">↗</span></a></div></div></main>'''

def render_contact():
    return '''<main id="main" class="wrap contact-page"><nav class="bread" aria-label="Breadcrumb"><a href="/">Home</a> / <span aria-current="page">Contact</span></nav><header class="contact-hero"><div><span class="eyebrow">CONTACT FINANCERSHUB</span><h1>Start a useful<br><em>conversation.</em></h1><p>Ask about a guide, flag a correction, or tell us what you would like us to explain next.</p></div><div class="contact-hero-note"><span class="eyebrow">THE RIGHT PLACE TO START</span><p>For article corrections, include the page link and the detail that needs a second look. For general feedback, a short description is enough.</p></div></header><div class="contact-preview-note" role="note"><span aria-hidden="true">ⓘ</span><p><strong>Design preview.</strong> This form demonstrates the contact experience; messages are not delivered yet.</p></div><div class="contact-layout"><div class="contact-form-card"><div class="form-card-heading"><span class="eyebrow">YOUR MESSAGE</span><h2>What would you like to share?</h2><p>Fields marked <span aria-hidden="true">*</span> are required.</p></div><form class="contact-form" data-demo-form><div class="contact-field-row"><label><span class="field-label">Your name <span aria-hidden="true">*</span></span><input name="name" autocomplete="name" required placeholder="Full name"></label><label><span class="field-label">Email address <span aria-hidden="true">*</span></span><input type="email" name="email" autocomplete="email" required placeholder="you@example.com"></label></div><label><span class="field-label">What is this about? <span aria-hidden="true">*</span></span><select name="subject" required><option value="" disabled selected>Choose a subject</option><option>Question about an article</option><option>Correction or update</option><option>Topic suggestion</option><option>General inquiry</option></select></label><label><span class="field-label">Related article link <small>Optional</small></span><input type="url" name="article" inputmode="url" placeholder="https://..."><small class="field-help">A direct link helps us identify the page you mean.</small></label><label><span class="field-label">Your message <span aria-hidden="true">*</span></span><textarea name="message" rows="7" required placeholder="Tell us what you would like us to know..."></textarea></label><div class="contact-submit"><p>Please do not include account numbers, passwords, or other sensitive financial details.</p><button class="btn" type="submit">Preview form <span aria-hidden="true">↗</span></button></div></form></div><aside class="contact-aside"><div class="contact-side-intro"><span class="eyebrow">BEFORE YOU WRITE</span><h2>Help us understand the context.</h2><p>A clear question or a specific page link makes feedback easier to review.</p></div><div><span class="info-number">01 / QUESTIONS</span><h3>Ask about an article</h3><p>Share the guide and the part you want clarified.</p></div><div><span class="info-number">02 / CORRECTIONS</span><h3>Flag a detail</h3><p>Include the page link, the detail in question, and a source if you have one.</p></div><div><span class="info-number">03 / SUGGESTIONS</span><h3>Suggest a topic</h3><p>Tell us the money decision you would like a guide to cover.</p></div><div class="contact-aside-note"><span class="eyebrow">LEARN MORE</span><p>See how we intend to review and update content.</p><a href="/editorial-policy.html">Our editorial approach ↗</a></div></aside></div></main>'''
