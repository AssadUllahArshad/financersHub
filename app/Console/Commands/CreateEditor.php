<?php

namespace App\Console\Commands;

use App\Models\AuthorProfile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateEditor extends Command
{
    protected $signature = 'financershub:user {email} {--role=admin}';

    protected $description = 'Create an editorial account with an interactively supplied password';

    public function handle(): int
    {
        $data = ['email' => $this->argument('email'), 'role' => $this->option('role'), 'name' => $this->ask('Name'), 'password' => $this->secret('Password (at least 12 characters)')];
        $validator = Validator::make($data, ['email' => 'required|email|unique:users', 'role' => 'required|in:admin,editor,author', 'name' => 'required|string|max:255', 'password' => 'required|string|min:12']);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }
        DB::transaction(function () use ($data) {
            $user = new User;
            $user->forceFill($data)->save();
            AuthorProfile::create(['user_id' => $user->id, 'name' => $user->name, 'slug' => Str::slug($user->name).'-'.$user->id]);
        });
        $this->info('Editorial account created.');

        return self::SUCCESS;
    }
}
