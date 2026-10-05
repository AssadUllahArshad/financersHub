@extends('layouts.site')
@section('title', __('Privacy'))
@section('description', __('How FinancersHub handles contact messages, basic visitor analytics and calculator inputs.'))
@section('main-class', 'wrap info-page info-privacy')
@section('content')
    <x-breadcrumbs :items="[['label' => 'Privacy']]" />
    <header class="info-hero"><span class="eyebrow">{{ __('YOUR INFORMATION') }} </span>
        <h1>{{ __('Clear about data, too.') }} </h1>
        <p>{{ __('How the current website handles information you provide and basic visit measurements.') }} </p>
    </header>
    <article class="prose">
        <h2>{{ __('Contact messages') }} </h2>
        <p>{{ __('When you send the contact form, we store your name, email address, message, topic and any article link you provide for editorial review. Authorized administrators can review these messages. Email notifications may be sent to our configured recipient when email delivery is enabled.') }} </p>
        <h2>{{ __('Basic visitor analytics') }} </h2>
        <p>{{ __('We record the page path, visit time, referring website domain, and broad browser and device categories. We use a keyed hash of your IP address as an approximate visitor reference; the analytics table does not store your raw IP address. Shared IPs can count as one visitor and changing IPs can count as different visitors.') }} </p>
        <p>{{ __('We do not store URL query strings, calculator inputs or full browser identification strings in these records. We do not add an analytics cookie. Repeated visits to the same page within one minute count once. Staff sessions, recognized bots, and requests with Do Not Track or Global Privacy Control enabled are excluded. Visitor records are retained for up to 90 days, with expired records removed by our daily scheduled cleanup.') }} </p>
        <h2>{{ __('Calculator inputs') }} </h2>
        <p>{{ __('Calculations run in your browser. Amounts entered in the savings calculator are not sent to our analytics system or saved by the calculator. CSV downloads are generated on your device.') }} </p>
        <h2>{{ __('Site technology') }} </h2>
        <p>{{ __('The site uses session and security cookies for form protection and staff sign-in. External font requests may expose connection information to the font provider. Hosting providers may keep separate access and security logs. If you load externally hosted article images, your browser connects to those image hosts.') }} </p>
        <h2>{{ __('Your choices') }} </h2>
        <p>{{ __('You can enable Do Not Track or Global Privacy Control in a supported browser to opt out of our visitor measurements. For questions or requests concerning information you submitted,') }} <a
                href="{{ \App\Support\Localization::route('contact') }}">{{ __('contact the editorial team') }} </a>.</p>
        <h2>{{ __('Future services') }} </h2>
        <p>{{ __('Reader accounts and newsletter subscriptions are currently disabled. This page will be updated when new services or advertising practices are introduced.') }} </p>
    </article>
@endsection
