<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Demo data is restricted to local/testing.');
        }
        $user = User::firstOrCreate(['email' => 'demo-author@example.invalid'], ['name' => 'Demo author', 'password' => \Illuminate\Support\Str::random(64)]);
        $author = AuthorProfile::firstOrCreate(['slug' => 'demo-author'], ['user_id' => $user->id, 'name' => 'Demo author', 'is_demo' => true]);
        $category = Category::firstOrCreate(['slug' => 'personal-finance'], ['name' => 'Personal Finance']);
        Article::firstOrCreate(['slug' => 'demo-editorial-draft'], ['user_id' => $user->id, 'author_profile_id' => $author->id, 'category_id' => $category->id, 'title' => 'Demo editorial draft', 'excerpt' => 'Development fixture; never public.', 'body' => '<p>Sample draft for local review.</p>', 'is_demo' => true, 'status' => 'draft']);
    }
}
