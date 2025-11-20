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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade');

            $table->string('book_title');
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->foreignId('publisher_id')->constrained('publishers')->onDelete('cascade');

            $table->integer('year_published')->nullable();
            $table->integer('quantity')->default(1);

            $table->enum('status', ['pending', 'accepted', 'rejected'])
                ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
