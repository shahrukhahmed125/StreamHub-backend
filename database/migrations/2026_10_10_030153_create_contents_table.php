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
        Schema::create('contents', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type', 20)->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('release_date')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('maturity_rating', 20)->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
