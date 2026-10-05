<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The add_publish_status_to_content migration added `publish_status` with a
 * default of 'draft' and only backfilled rows that already existed at that
 * time. Any news row created afterwards (e.g. by NewsSeeder, which set
 * `is_published = true` but never `publish_status`) stayed on the 'draft'
 * default and was therefore hidden by the News::published() scope.
 *
 * This migration restores the invariant "is_published = true => published"
 * for legacy/seed data so those articles become visible again.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('news', 'publish_status')) {
            return;
        }

        DB::table('news')
            ->where('is_published', true)
            ->where(function ($query) {
                $query->where('publish_status', '!=', 'published')
                    ->orWhereNull('publish_status');
            })
            ->update(['publish_status' => 'published']);
    }

    public function down(): void
    {
        // Non-destructive: we intentionally leave publish_status as-is.
    }
};
