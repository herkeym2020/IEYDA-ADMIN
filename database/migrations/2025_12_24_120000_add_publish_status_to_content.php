<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // News (guard against already applied columns)
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'publish_status')) {
                $table->enum('publish_status', ['draft', 'scheduled', 'published'])->default('draft')->after('is_published');
            }
            if (!Schema::hasColumn('news', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('publish_status');
            }
        });

        DB::table('news')->update([
            'published_at' => DB::raw('COALESCE(published_at, NOW())'),
        ]);
        DB::table('news')->whereNull('publish_status')->update([
            'publish_status' => DB::raw("CASE WHEN is_published = 1 THEN 'published' ELSE 'draft' END"),
        ]);

        // Events
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'publish_status')) {
                $table->enum('publish_status', ['draft', 'scheduled', 'published'])->default('draft')->after('status');
            }
            if (!Schema::hasColumn('events', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('publish_status');
            }
            if (!Schema::hasColumn('events', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('scheduled_at');
            }
        });

        DB::table('events')->whereNull('publish_status')->update([
            'publish_status' => 'published',
        ]);
        DB::table('events')->update([
            'published_at' => DB::raw('COALESCE(published_at, NOW())'),
        ]);

        // Programs
        Schema::table('programs', function (Blueprint $table) {
            if (!Schema::hasColumn('programs', 'publish_status')) {
                $table->enum('publish_status', ['draft', 'scheduled', 'published'])->default('draft')->after('status');
            }
            if (!Schema::hasColumn('programs', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('publish_status');
            }
            if (!Schema::hasColumn('programs', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('scheduled_at');
            }
        });

        DB::table('programs')->whereNull('publish_status')->update([
            'publish_status' => 'published',
        ]);
        DB::table('programs')->update([
            'published_at' => DB::raw('COALESCE(published_at, NOW())'),
        ]);
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['publish_status', 'scheduled_at']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['publish_status', 'scheduled_at', 'published_at']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['publish_status', 'scheduled_at', 'published_at']);
        });
    }
};
