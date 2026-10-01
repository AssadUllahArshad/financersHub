<?php

namespace App\Console\Commands;

use App\Models\MediaAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveMediaToPublic extends Command
{
    protected $signature = 'media:move-to-public';

    protected $description = 'Copy legacy media to public/uploads, verify bytes, and update paths; keep originals';

    public function handle(): int
    {
        $source = Storage::disk('local');
        $target = Storage::disk('uploads');
        foreach (MediaAsset::cursor() as $media) {
            if ($target->exists($media->path)) {
                continue;
            }
            if (! $source->exists($media->path)) {
                $this->error('Missing source for media #'.$media->id);

                return self::FAILURE;
            }
            $path = $media->path === 'media/testing-editorial.png' ? 'images/demo/testing-editorial.png' : 'images/'.$media->created_at->format('Y/m').'/'.basename($media->path);
            $bytes = $source->get($media->path);
            if ($target->exists($path) && hash('sha256', $target->get($path)) !== hash('sha256', $bytes)) {
                $this->error('Destination collision for media #'.$media->id);

                return self::FAILURE;
            }
            if (! $target->put($path, $bytes) || hash('sha256', $target->get($path)) !== hash('sha256', $bytes)) {
                $this->error('Copy verification failed.');

                return self::FAILURE;
            }
            $media->update(['path' => $path]);
            $this->info('Moved media #'.$media->id.'; original retained.');
        }

        return self::SUCCESS;
    }
}
