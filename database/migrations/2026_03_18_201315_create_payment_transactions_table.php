<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('gateway');
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('LKR');
            $table->string('status')->default('pending');
            $table->string('payment_type')->nullable();
            $table->boolean('is_manual')->default(false);
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
