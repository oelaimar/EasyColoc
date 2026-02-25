<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        schema::create('payments', function(Blueprint $table){
            $table->id();
            $table->foreignId('colocation_id')->constrained('colocations')->cascadeOnDelete();
            $table->foreignId('debtor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('creditor_id')->constrained('users')->cascadeOnDelete();
            $table->float('amount',10, 2);
            $table->enum('status', ['pending','paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        schema::dropIfExists('payments');
    }
};
