<?php

namespace App\Services\Newsletter;

class UnconfiguredProvider implements NewsletterProvider
{
    public function requestConfirmation(string $email, string $confirmationUrl): void
    {
        throw new \RuntimeException('Newsletter delivery is not configured.');
    }

    public function subscribe(string $email): string
    {
        throw new \RuntimeException('Newsletter delivery is not configured.');
    }

    public function unsubscribe(string $providerId): void
    {
        throw new \RuntimeException('Newsletter delivery is not configured.');
    }
}
