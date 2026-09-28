<?php

namespace App\Services\Newsletter;

interface NewsletterProvider
{
    /** Implement only after owner selects a provider; must initiate consent-aware confirmation. */
    public function requestConfirmation(string $email, string $confirmationUrl): void;

    public function subscribe(string $email): string;

    public function unsubscribe(string $providerId): void;
}
