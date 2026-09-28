<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('financershub.admin_seed_email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Set ADMIN_SEED_EMAIL before seeding.');
        }
        $existing = User::where('email', $email)->first();
        if ($existing) {
            if ($existing->role !== 'admin') {
                throw new \RuntimeException('An existing non-admin account uses that email. No role changed.');
            }
            $this->command?->info('Administrator already exists. Password preserved.');

            return;
        }
        $password = config('financershub.admin_seed_password');
        $generated = false;
        if (! $password && app()->environment('local')) {
            $password = Str::random(32);
            $generated = true;
        }
        if (! is_string($password) || strlen($password) < 16) {
            throw new \RuntimeException('Set ADMIN_SEED_PASSWORD to a unique password of at least 16 characters.');
        }
        if ($generated) {
            $directory = base_path('.browser-runtime');
            if (! is_dir($directory)) {
                mkdir($directory, 0700, true);
            }
            if (file_put_contents($directory.'/admin-credentials.txt', 'Email: '.$email."\nPassword: ".$password."\nLogin: /login\nKeep this file private; it is excluded from version control.\n") === false) {
                throw new \RuntimeException('Could not save generated credentials.');
            }
        }
        User::forceCreate(['name' => 'FinancersHub Administrator', 'email' => $email, 'password' => $password, 'role' => 'admin']);
        $this->command?->info($generated ? 'Administrator created. Credentials saved in .browser-runtime/admin-credentials.txt.' : 'Administrator created using the configured password.');
    }
}
