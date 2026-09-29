<?php

namespace App\Console\Commands;

use App\Services\FacebookService;
use Illuminate\Console\Command;

class SyncFacebookPosts extends Command
{
    protected $signature = 'facebook:sync {--limit=10 : Number of posts to sync}';
    protected $description = 'Sync posts from Facebook page to news';

    public function handle(FacebookService $facebookService)
    {
        $limit = (int) $this->option('limit');

        $this->info("Syncing up to {$limit} Facebook posts...");

        $count = $facebookService->syncPagePosts($limit);

        if ($count === 0) {
            $this->info('No new posts to sync.');
            return 0;
        }

        $this->info("✓ Successfully imported {$count} post(s)");
        return 0;
    }
}
