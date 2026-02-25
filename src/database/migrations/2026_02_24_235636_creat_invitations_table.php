<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        schema::create('invitations', function (Blueprint $table){
            $table->id();
            $table->foreignId('colocation_id')->constrained('colocations')->cascadeOnDelete();
            $table->string('email');
            $table->string('token', 32)->unique();
            $table->enum('status', ['pending', 'accepted', 'refused'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        schema::dropIfExists('invitations');
    }
};
