<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\FaqEntry;
use Illuminate\Database\Seeder;

class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Admin demo records are local/testing only.');
        }

        $this->call(TestingContentSeeder::class);
        $source = Article::where('is_demo', true)->firstOrFail();
        foreach (['unpublished' => 'Unpublished editorial example', 'trash' => 'Archived editorial example'] as $state => $title) {
            $slug = 'sample-workflow-'.$state;
            if (Article::withTrashed()->where('slug', $slug)->exists()) {
                continue;
            }
            $article = $source->replicate();
            $article->forceFill(['slug' => $slug, 'title' => $title, 'status' => $state === 'trash' ? 'draft' : $state, 'published_at' => null, 'scheduled_at' => null]);
            $article->save();
            if ($state === 'trash') {
                $article->delete();
            }
        }
        foreach (['newsletter-and-contact' => 'Can I suggest a topic to the editors?', 'privacy-and-editorial-standards' => 'How should I report a correction?'] as $group => $question) {
            FaqEntry::firstOrCreate(['question' => $question], ['answer' => 'Use the contact form and include the relevant article link. This sample answer needs editorial review.', 'group' => $group, 'position' => 20, 'published' => false]);
        }
    }
}
