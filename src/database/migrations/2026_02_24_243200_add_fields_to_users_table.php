<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer("reputation_score")->default(0);
            $table->boolean("is_global_admin")->default(false);
            $table->boolean("is_banned")->default(false);
            $table->foreignId("current_colocation_id")->nullable()->constrained("colocations")->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_colocation_id']);
            $table->dropColumn([
                'reputation_score',
                'is_global_admin',
                'is_banned',
                'current_colocation_id',
            ]);
        });
    }
};
