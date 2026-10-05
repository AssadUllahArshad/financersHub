@extends('layouts.studio')
@section('live', '1')
@section('title', 'Settings')
@section('content')
    <div class="studio-heading">
        <div><span class="admin-kicker">PUBLICATION</span>
            <h1>Site settings</h1>
            <p>Manage contact notifications, the newsletter invitation and footer copy.</p>
        </div>
    </div>
    <section class="studio-panel">
        <form method="post" action="{{ route('admin.settings.save') }}">
            @csrf
            <label class="admin-field">Contact notification recipient<input class="form-control" type="email"
                    name="contact_recipient" placeholder="editor@example.com"
                    value="{{ old('contact_recipient', $settings['contact_recipient'] ?? config('financershub.contact_recipient')) }}"><small>Leave
                    blank until ready. SMTP credentials stay in .env. Messages are always kept in the private
                    inbox.</small></label>
            <label class="admin-field">Newsletter title<input class="form-control" name="newsletter_title" required
                    value="{{ old('newsletter_title', $settings['newsletter_title'] ?? 'Good questions for better money decisions.') }}"></label>
            <label class="admin-field">Newsletter description
                <textarea class="form-control" name="newsletter_description" required>{{ old('newsletter_description', $settings['newsletter_description'] ?? 'A short selection of practical guides, delivered when the newsletter launches.') }}</textarea>
            </label>
            <label class="admin-field">Footer copy
                <textarea class="form-control" name="footer_copy" required>{{ old('footer_copy', $settings['footer_copy'] ?? 'Useful questions. Clear explanations. Room to make up your own mind.') }}</textarea>
            </label>
            <button class="a-button primary">Save settings</button>
        </form>
    </section>
@endsection
