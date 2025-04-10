<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // Primary key (rating_id)

            // User who gave the rating
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Polymorphic relationship: Entity being rated
            $table->string('rateable_type'); // e.g., 'App\Models\Restaurant' or 'App\Models\Item'
            $table->unsignedBigInteger('rateable_id'); // ID of the entity being rated

            // Rating
            $table->decimal('rating', 3, 1)->unsigned();

            // Timestamps
            $table->timestamp('rating_date')->useCurrent(); // Date of the rating

            // Indexes
            $table->index(['rateable_type', 'rateable_id']); // Index for faster lookups

            // Ensure a user can rate a specific entity only once
            $table->unique(['user_id', 'rateable_type', 'rateable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
}
