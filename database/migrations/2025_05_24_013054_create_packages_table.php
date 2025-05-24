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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photographer_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 100)->nullable();
            $table->string('category', 50)->nullable();
            $table->decimal('base_price', 12, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('includes')->nullable();
            $table->text('excludes')->nullable();
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
