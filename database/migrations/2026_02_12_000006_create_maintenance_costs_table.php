<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description');
            $table->string('category')->default('general');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('status')->default('pending');
            $table->string('vendor')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['apartment_id', 'category']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_costs');
    }
};
