<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        schema::create('expenses', function(Blueprint $table){
            $table->id();
            $table->foreignId('colocation_id')->constrained('colocations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title');
            $table->float('amount', 10, 2);
            $table->date('date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        schema::dropIfExists('expenses');
    }
};
