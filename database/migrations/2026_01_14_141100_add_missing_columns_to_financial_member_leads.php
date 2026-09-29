<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_member_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('financial_member_leads', 'interaction_count')) {
                $table->integer('interaction_count')->default(0)->after('notes');
            }
            if (!Schema::hasColumn('financial_member_leads', 'last_interaction_at')) {
                $table->timestamp('last_interaction_at')->nullable()->after('contacted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('financial_member_leads', function (Blueprint $table) {
            if (Schema::hasColumn('financial_member_leads', 'interaction_count')) {
                $table->dropColumn('interaction_count');
            }
            if (Schema::hasColumn('financial_member_leads', 'last_interaction_at')) {
                $table->dropColumn('last_interaction_at');
            }
        });
    }
};
