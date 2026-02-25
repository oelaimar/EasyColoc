<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        schema::create('memberships',function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('colocation_id')->constrained('colocations')->cascadeOnDelete();
            $table->enum('role',['owner', 'member']);
            $table->timestamp('join_at');
            $table->timestamp('left_at')->nullable();
        });
    }
    public function down(): void
    {
        schema::dropIfExists("memberships");
    }
};
