<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ArticleWorkflow;
use Illuminate\Console\Command;

class PublishDueArticles extends Command
{
    protected $signature = 'articles:publish-due';

    protected $description = 'Publish approved articles whose scheduled time has arrived';

    public function handle(ArticleWorkflow $workflow): int
    {
        Article::where('status', 'scheduled')->where('is_demo', false)->where('scheduled_at', '<=', now())->eachById(function ($article) use ($workflow) {
            try {
                $workflow->transition($article, null, 'published');
            } catch (\Throwable $error) {
                report($error);
                $this->error('Could not publish article '.$article->id);
            }
        });

        return self::SUCCESS;
    }
}
