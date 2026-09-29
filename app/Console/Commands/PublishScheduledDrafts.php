<?php

namespace App\Console\Commands;

use App\Models\{News, Event, Program};
use Illuminate\Console\Command;

class PublishScheduledDrafts extends Command
{
    protected $signature = 'content:publish-scheduled';

    protected $description = 'Publish scheduled content (news, events, programs) when scheduled_at has passed';

    public function handle(): int
    {
        $count = 0;
        $now = now();

        $models = [
            News::class,
            Event::class,
            Program::class,
        ];

        foreach ($models as $model) {
            $ready = $model::where('publish_status', 'scheduled')
                ->whereNotNull('scheduled_at')
                ->where('scheduled_at', '<=', $now)
                ->get();

            foreach ($ready as $item) {
                try {
                    $item->publish_status = 'published';
                    $item->published_at = $item->scheduled_at ?? $now;
                    if ($item instanceof News) {
                        $item->is_published = true;
                    }
                    $item->scheduled_at = null;
                    $item->save();
                    $count++;
                } catch (\Throwable $e) {
                    $this->error('Failed to publish scheduled item ID '.$item->id.' for '.class_basename($model).': '.$e->getMessage());
                }
            }
        }

        $this->info("Published {$count} scheduled drafts.");

        return Command::SUCCESS;
    }
}
