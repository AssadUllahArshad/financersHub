@extends('layouts.site')
@section('title', __('Contact'))
@section('description', __('Contact the FinancersHub editorial team with a question, topic suggestion or correction.'))
@section('main-class', 'wrap contact-page')
@section('content')
    <x-breadcrumbs :items="[['label' => 'Contact']]" />
    <header class="contact-hero">
        <div><span class="eyebrow">{{ __('CONTACT FINANCERSHUB') }} </span>
            <h1>{{ __('Start a useful') }} <br><em>{{ __('conversation.') }} </em></h1>
            <p>{{ __('Ask about a guide, flag a correction, or tell us what you would like us to explain next.') }} </p>
        </div>
        <div class="contact-hero-note"><span class="eyebrow">{{ __('THE RIGHT PLACE TO START') }} </span>
            <p>{{ __('For article corrections, include the page link and the detail that needs a second look. For general feedback, a short description is enough.') }} </p>
        </div>
    </header>
    <div class="contact-preview-note" role="note"><span aria-hidden="true">ⓘ</span>
        <p><strong>{{ __('Private editorial contact.') }} </strong> {{ __('Messages are stored securely for review by site administrators. The editorial team can also receive an email notification when delivery is configured.') }} </p>
    </div>
    <div class="contact-layout">
        <div class="contact-form-card">
            <div class="form-card-heading"><span class="eyebrow">{{ __('YOUR MESSAGE') }} </span>
                <h2>{{ __('What would you like to share?') }} </h2>
                <p>{{ __('Fields marked') }} <span aria-hidden="true">*</span> {{ __('are required.') }} </p>
            </div>
            @if (session('contact_status'))
                <p role="status" class="admin-sample">{{ session('contact_status') }} </p>
            @endif
            @if ($errors->any())<div role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }} </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="contact-form" method="post" action="{{ \App\Support\Localization::route('contact.store') }}">@csrf
                <div class="honeypot" aria-hidden="true"><label>{{ __('Website') }} <input class="form-control" name="website"
                            tabindex="-1" autocomplete="off"></label></div>
                <div class="contact-field-row"><label><span class="field-label">{{ __('Your name') }} <span
                                aria-hidden="true">*</span></span><input class="form-control" name="name"
                            value="{{ old('name') }}" autocomplete="name" required
                            placeholder="{{ __('Full name') }}"></label><label><span class="field-label">{{ __('Email address') }} <span
                                aria-hidden="true">*</span></span><input class="form-control" type="email" name="email"
                            value="{{ old('email') }}" autocomplete="email" required placeholder="you@example.com"></label>
                </div><label><span class="field-label">{{ __('What is this about?') }} <span aria-hidden="true">*</span></span><select
                        class="form-select" name="subject" required>
                        <option value="" disabled @selected(!old('subject', request('subject')))>{{ __('Choose a subject') }} </option>
                        <option value="Question about an article" @selected(old('subject', request('subject')) === 'Question about an article')>{{ __('Question about an article') }} </option>
                        <option value="Correction or update" @selected(old('subject', request('subject')) === 'Correction or update')>{{ __('Correction or update') }} </option>
                        <option value="Topic suggestion" @selected(old('subject', request('subject')) === 'Topic suggestion')>{{ __('Topic suggestion') }} </option>
                        <option value="General inquiry" @selected(old('subject', request('subject')) === 'General inquiry')>{{ __('General inquiry') }} </option>
                    </select></label><label><span class="field-label">{{ __('Related article link') }} <small>{{ __('Optional') }} </small></span><input class="form-control" type="url" name="article"
                        value="{{ old('article', request('article')) }}" inputmode="url" placeholder="https://..."><small
                        class="field-help">{{ __('A direct link helps us identify the page you mean.') }} </small></label><label><span
                        class="field-label">{{ __('Your message') }} <span aria-hidden="true">*</span></span>
                    <textarea class="form-control" name="message" rows="7" required
                        placeholder="{{ __('Tell us what you would like us to know...') }}">{{ old('message') }} </textarea>
                </label><label class="check-line"><input class="form-check-input" type="checkbox" name="consent"
                        value="1" required><span>{{ __('I agree that my name, email and message will be stored for editorial review.') }} </span></label>
                <div class="contact-submit">
                    <p>{{ __('Please do not include account numbers, passwords, or other sensitive financial details.') }} </p><button
                        class="btn" type="submit">{{ __('Send message') }} <span aria-hidden="true">↗</span></button>
                </div>
            </form>
        </div>
        <aside class="contact-aside">
            <div class="contact-side-intro"><span class="eyebrow">{{ __('BEFORE YOU WRITE') }} </span>
                <h2>{{ __('Help us understand the context.') }} </h2>
                <p>{{ __('A clear question or a specific page link makes feedback easier to review.') }} </p>
            </div>
            <div><span class="info-number">{{ __('01 / QUESTIONS') }} </span>
                <h3>{{ __('Ask about an article') }} </h3>
                <p>{{ __('Share the guide and the part you want clarified.') }} </p>
            </div>
            <div><span class="info-number">{{ __('02 / CORRECTIONS') }} </span>
                <h3>{{ __('Flag a detail') }} </h3>
                <p>{{ __('Include the page link, the detail in question, and a source if you have one.') }} </p>
            </div>
            <div><span class="info-number">{{ __('03 / SUGGESTIONS') }} </span>
                <h3>{{ __('Suggest a topic') }} </h3>
                <p>{{ __('Tell us the money decision you would like a guide to cover.') }} </p>
            </div>
            <div class="contact-aside-note"><span class="eyebrow">{{ __('LEARN MORE') }} </span>
                <p>{{ __('See how we intend to review and update content.') }} </p><a href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('Our editorial approach ↗') }} </a>
            </div>
        </aside>
    </div>
@endsection
