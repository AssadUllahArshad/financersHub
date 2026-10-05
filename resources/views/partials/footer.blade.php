@php($siteCopy = isset($exception) ? collect() : \App\Models\SiteSetting::pluck('value', 'key'))
<footer class="footer">
    <div class="wrap">
        <section class="footer-newsletter" id="newsletter" aria-labelledby="newsletter-title">
            <div class="footer-newsletter-copy"><span class="footer-kicker">{{ __('THE FINANCERSHUB BRIEF') }}</span>
                <h2 id="newsletter-title">
                    @if (isset($siteCopy['newsletter_title']))
                        {{ __($siteCopy['newsletter_title']) }}
                    @else
                        {{ __('Good questions for') }}
                        <br><em>{{ __('better money decisions.') }}</em>
                    @endif
                </h2>
                <p>{{ __($siteCopy['newsletter_description'] ?? 'A short selection of practical guides, delivered when the newsletter launches.') }}
                </p>
            </div>
            <div class="footer-newsletter-action">
                <form data-demo-form class="footer-signup"><label for="footer-email">{{ __('Email address') }}</label>
                    <div><input class="form-control" disabled id="footer-email" name="email" type="email"
                            autocomplete="email" placeholder="you@example.com" required><button disabled
                            type="submit">{{ __('Get updates') }}<span aria-hidden="true">↗</span></button></div>
                </form>
                <p class="signup-honesty">{{ __('Newsletter signup is currently disabled. No email addresses are collected.') }}<a
                        href="{{ \App\Support\Localization::route('faq') }}#newsletter-and-contact">{{ __('Newsletter information') }}</a></p>
            </div>
        </section>
        <div class="footer-grid">
            <div class="footer-brand"><a class="logo" href="{{ \App\Support\Localization::route('home') }}"><img
                        src="{{ asset('assets/logo-white.svg') }}" width="323" height="62" alt="FinancersHub"></a>
                <p>{{ __($siteCopy['footer_copy'] ?? 'Useful questions. Clear explanations. Room to make up your own mind.') }}
                </p><a class="footer-library-link" href="{{ \App\Support\Localization::route('search') }}">{{ __('Explore the full guide library') }}<span
                        aria-hidden="true">↗</span></a>
            </div>
            <nav aria-label="{{ __('Explore topics') }}">
                <h3>{{ __('Explore') }}</h3><a href="{{ \App\Support\Localization::route('categories.personal-finance') }}">{{ __('Personal Finance') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.investing') }}">{{ __('Investing') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.banking') }}">{{ __('Banking') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.credit') }}">{{ __('Credit') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.business') }}">{{ __('Business') }}</a><a href="{{ \App\Support\Localization::route('search') }}">{{ __('All articles') }}</a>
            </nav>
            <nav aria-label="{{ __('Publication links') }}">
                <h3>{{ __('The publication') }}</h3><a href="{{ \App\Support\Localization::route('about') }}">{{ __('About us') }}</a><a
                    href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('Editorial policy') }}</a><a
                    href="{{ \App\Support\Localization::route('authors.index') }}">{{ __('Authors') }}</a><a href="{{ \App\Support\Localization::route('faq') }}">{{ __('FAQs') }}</a><a
                    href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact') }}</a>
            </nav>
            <nav aria-label="{{ __('Information links') }}">
                <h3>{{ __('Information') }}</h3><a href="{{ \App\Support\Localization::route('disclaimer') }}">{{ __('Financial disclaimer') }}</a><a
                    href="{{ \App\Support\Localization::route('privacy') }}">{{ __('Privacy') }}</a><a href="{{ \App\Support\Localization::route('terms') }}">{{ __('Terms of use') }}</a>
            </nav>
        </div>
        <div class="footer-bottom"><span>{{ __('© 2026 FinancersHub') }}</span><span>{{ __('Educational content only. Not personalized financial, investment, tax, or legal advice.') }}</span><a href="#top">{{ __('Back to top ↑') }}</a></div>
    </div>
</footer>
