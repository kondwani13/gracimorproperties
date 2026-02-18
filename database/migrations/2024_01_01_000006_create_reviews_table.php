<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->integer('cleanliness_rating')->nullable();
            $table->integer('accuracy_rating')->nullable();
            $table->integer('communication_rating')->nullable();
            $table->integer('location_rating')->nullable();
            $table->integer('value_rating')->nullable();
            $table->text('comment');
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            $table->unique(['booking_id']); // One review per booking
            $table->index(['apartment_id', 'is_approved']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
