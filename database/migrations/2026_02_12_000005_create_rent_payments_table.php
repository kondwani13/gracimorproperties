<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('month', 7); // YYYY-MM
            $table->string('payment_method')->default('cash');
            $table->date('payment_date');
            $table->string('receipt_number')->nullable();
            $table->string('status')->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'month']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
