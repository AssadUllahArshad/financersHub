<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\FaqEntry;
use App\Models\MediaAsset;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestingContentSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Test records are local/testing only.');
        }
        $this->call(TaxonomySeeder::class);
        $writer = User::firstOrCreate(['email' => 'demo-author@example.invalid'], ['name' => 'Sample writer', 'password' => Str::random(48), 'role' => 'author']);
        $author = AuthorProfile::firstOrCreate(['user_id' => $writer->id], ['slug' => 'sample-editor', 'name' => 'Sample editorial author', 'user_id' => $writer->id, 'bio' => 'Local test profile. Replace with a verified author before publishing.', 'is_demo' => true]);
        $tags = collect(['Getting started', 'Planning', 'Explainers'])->map(fn ($name) => Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Testing tag']));
        $path = 'images/demo/testing-editorial.png';
        if (! Storage::disk('uploads')->exists($path)) {
            $image = imagecreatetruecolor(1200, 675);
            imagefill($image, 0, 0, imagecolorallocate($image, 21, 99, 89));
            imagestring($image, 5, 40, 40, 'FinancersHub - local test image', imagecolorallocate($image, 255, 255, 255));
            ob_start();
            imagepng($image);
            $bytes = ob_get_clean();
            imagedestroy($image);
            Storage::disk('uploads')->put($path, $bytes);
        }
        $media = MediaAsset::firstOrCreate(['path' => $path], ['user_id' => $writer->id, 'original_name' => 'testing-editorial.png', 'mime_type' => 'image/png', 'size' => Storage::disk('uploads')->size($path), 'alt_text' => 'Green test cover for the editorial workflow', 'rights' => 'Generated locally for application testing.']);
        foreach (Category::orderBy('id')->get() as $index => $category) {
            $article = Article::withTrashed()->firstOrCreate(['slug' => 'sample-'.$category->slug.'-guide'], ['user_id' => $writer->id, 'author_profile_id' => $author->id, 'category_id' => $category->id, 'media_asset_id' => $media->id, 'title' => 'Sample '.$category->name.' guide', 'excerpt' => 'A test record for checking layouts, images, tags and the editorial workflow.', 'body' => '<h2>Questions to explore</h2><p>This is a local editorial test fixture, not financial guidance.</p><h2>Review checklist</h2><ul><li>Check the layout and image.</li><li>Replace this text and verify sources before publication.</li></ul>', 'status' => $index % 2 ? 'in-review' : 'draft', 'is_demo' => true, 'sources' => []]);
            $article->categories()->syncWithoutDetaching([$category->id]);
            $article->tags()->syncWithoutDetaching($tags->pluck('id')->all());
            if (! $article->revisions()->exists()) {
                app(\App\Services\ArticleWorkflow::class)->snapshot($article, $writer, 'seed:test-fixture');
            }
        }
        foreach (['How do I search for a guide?' => 'Use the search field in the header or open the article library.', 'Is newsletter signup available?' => 'Newsletter signup is currently disabled.', 'How can I contact the editors?' => 'Use the contact form. Your message is stored for the editorial team.'] as $question => $answer) {
            FaqEntry::firstOrCreate(['question' => $question], ['answer' => $answer, 'position' => 10, 'published' => false, 'group' => 'using-financershub']);
        }
        foreach (range(1, 3) as $i) {
            $contact = ContactMessage::firstOrCreate(['reference' => '00000000-0000-4000-8000-'.str_pad((string) $i, 12, '0', STR_PAD_LEFT)], ['name' => 'Test reader '.$i, 'email' => 'reader'.$i.'@example.invalid', 'subject' => 'Topic suggestion', 'message' => 'This is a synthetic inbox message for testing unread and reviewed states.']);
            if ($contact->wasRecentlyCreated) {
                $contact->forceFill(['read_at' => $i > 1 ? now() : null, 'resolved_at' => $i === 3 ? now() : null, 'notification_status' => 'not_configured'])->save();
            }
        }
        foreach (['seo_site_name' => 'FinancersHub', 'contact_recipient' => null, 'adsense_publisher_id' => null] as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        $this->command?->info('Test content created without publishing articles or sending notifications. Existing records preserved.');
    }
}
