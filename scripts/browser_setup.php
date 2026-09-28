<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$database = base_path('.browser-runtime/verification.sqlite');
if (! file_exists($database)) {
    touch($database);
}
config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => $database]);
Illuminate\Support\Facades\DB::purge('sqlite');
Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TaxonomySeeder', '--force' => true]);
$password = Illuminate\Support\Str::random(36);
$user = App\Models\User::firstOrNew(['email' => 'browser-check@example.invalid']);
$user->forceFill(['name' => 'Browser Check', 'role' => 'admin', 'password' => $password])->save();
App\Models\AuthorProfile::firstOrCreate(['user_id' => $user->id], ['name' => 'Browser Check', 'slug' => 'browser-check']);
file_put_contents(base_path('.browser-runtime/login.json'), json_encode(['email' => $user->email, 'password' => $password]));
echo "Isolated browser database ready.\n";
