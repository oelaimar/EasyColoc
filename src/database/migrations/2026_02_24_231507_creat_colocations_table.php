<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        schema::create('colocations', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('token')->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['active', 'cancelled']);
            $table->timestamp('create_at');
            $table->timestamp('cancelled_at');
        });
    }
    public function down(): void
    {
        schema::dropIfExists('colocations');
    }
};
