@extends('layouts.studio')
@section('live', '1')
@section('title', 'My profile')
@section('content')
    <div class="studio-heading">
        <div><span class="admin-kicker">YOUR ACCOUNT</span>
            <h1>My profile</h1>
            <p>Manage your sign-in details and keep your account secure.</p>
        </div>
    </div>
    <div class="profile-grid row">
        <div class="profile-column col-12 col-xl-6">
            <section class="composer-card">
                <h2><x-icon name="user" /> Account details</h2>
                <p class="profile-note">Signed in as {{ $user->email }} &middot; {{ ucfirst($user->role) }}. Your public
                    author biography is managed separately under Authors.</p>
                <form method="post" action="{{ route('admin.profile.update') }}">@csrf @method('PUT')
                    <label class="admin-field">Full name<input class="form-control" name="name" autocomplete="name"
                            value="{{ old('name', $user->name) }}" maxlength="255" required></label>
                    <label class="admin-field">Email address<input class="form-control" name="email" type="email"
                            autocomplete="username" value="{{ old('email', $user->email) }}" maxlength="254"
                            required><small>This is the address you use to sign in.</small></label>
                    <label class="admin-field">Current password<input class="form-control" name="current_password"
                            type="password" autocomplete="current-password" required><small>Confirm your password to save
                            account changes.</small></label>
                    <div class="form-actions"><button class="a-button primary" type="submit"><x-icon name="save" /> Save
                            profile</button></div>
                </form>
            </section>
        </div>
        <div class="profile-column col-12 col-xl-6">
            <section class="composer-card">
                <h2><x-icon name="lock" /> Change password</h2>
                <p class="profile-note">Choose a unique password with at least 12 characters, uppercase and lowercase
                    letters, a number and a symbol. You will sign in again after saving.</p>
                <form method="post" action="{{ route('admin.profile.password') }}">@csrf @method('PUT')
                    <label class="admin-field">Current password<input class="form-control" name="current_password"
                            type="password" autocomplete="current-password" required></label>
                    <label class="admin-field">New password<input class="form-control" name="password" type="password"
                            autocomplete="new-password" minlength="12" maxlength="72" required></label>
                    <label class="admin-field">Confirm new password<input class="form-control" name="password_confirmation"
                            type="password" autocomplete="new-password" minlength="12" maxlength="72" required></label>
                    <div class="form-actions"><button class="a-button primary" type="submit"><x-icon name="lock" />
                            Update password</button></div>
                </form>
            </section>
        </div>
    </div>
@endsection
