<?php

return [
    'analytics_enabled' => env('VISITOR_ANALYTICS_ENABLED', true),
    'contact_recipient' => env('CONTACT_MAIL_TO'),
    'admin_seed_email' => env('ADMIN_SEED_EMAIL', env('APP_ENV') === 'local' ? 'admin@financerhub.com' : null),
    'admin_seed_password' => env('ADMIN_SEED_PASSWORD'),
    'design_preview' => env('FINANCERSHUB_DESIGN_PREVIEW', env('APP_ENV') === 'local'),
    'tinymce_api_key' => env('TINYMCE_API_KEY', ''),
];
