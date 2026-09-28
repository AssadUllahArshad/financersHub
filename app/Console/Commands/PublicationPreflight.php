<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\User;
use Illuminate\Console\Command;

class PublicationPreflight extends Command
{
    protected $signature = 'financershub:preflight';

    protected $description = 'Read-only checks for publication deployment configuration (does not approve launch)';

    public function handle(): int
    {
        $checks = [
            'Production environment' => app()->environment('production'),
            'Debug output disabled' => ! config('app.debug'),
            'Application key configured' => filled(config('app.key')),
            'HTTPS canonical origin configured' => filter_var(config('app.url'), FILTER_VALIDATE_URL) && parse_url(config('app.url'), PHP_URL_SCHEME) === 'https' && ! in_array(parse_url(config('app.url'), PHP_URL_HOST), ['localhost', '127.0.0.1', '::1'], true),
            'Design preview disabled' => ! config('financershub.design_preview'),
            'Secure session cookies enabled' => (bool) config('session.secure'),
            'HTTP-only session cookies enabled' => (bool) config('session.http_only'),
            'Persistent session driver configured' => ! in_array(config('session.driver'), ['array', 'cookie', null], true),
            'Persistent rate-limit cache configured' => ! in_array(config('cache.default'), ['array', null], true),
            'Development asset server absent' => ! file_exists(public_path('hot')),
        ];
        try {
            $checks['Administrator provisioned'] = User::where('role', 'admin')->exists();
            $checks['No public demo records'] = ! Article::where('status', 'published')->where(fn ($query) => $query->where('is_demo', true)->orWhereHas('authorProfile', fn ($author) => $author->where('is_demo', true)))->exists();
        } catch (\Throwable $error) {
            $checks['Database and publication tables accessible'] = false;
        }
        $this->table(['Check', 'Result'], collect($checks)->map(fn ($passed, $label) => [$label, $passed ? 'PASS' : 'FAIL'])->values()->all());
        $this->warn('Configuration checks only. Dependency audits, content/legal approval, HTTPS/proxy checks and a restore rehearsal are still required.');

        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
