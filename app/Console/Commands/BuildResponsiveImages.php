<?php

namespace App\Console\Commands;

use App\Models\MediaAsset;
use App\Services\ResponsiveImages;
use Illuminate\Console\Command;

class BuildResponsiveImages extends Command
{
    protected $signature = 'media:build-responsive';

    protected $description = 'Create missing responsive WebP variants without changing original uploads';

    public function handle(ResponsiveImages $images): int
    {
        $count = 0;
        foreach (MediaAsset::lazyById() as $media) {
            $count += $images->generate($media);
        }
        $this->info('Generated '.$count.' responsive image variants. Originals preserved.');

        return self::SUCCESS;
    }
}
