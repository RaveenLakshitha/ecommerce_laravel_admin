<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('LKR');
            $table->string('refund_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('reason')->nullable();
            $table->string('requested_by')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
