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
        Schema::create('video_assets', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('content_id')
                ->constrained('contents')
                ->cascadeOnDelete();

            $table->string('kind', 30);
            $table->string('disk', 50)->default('local');
            $table->string('path', 1024);
            $table->string('status', 20)->default('pending');

            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size_bytes')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['content_id', 'kind']);
            $table->index(['status', 'kind']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_assets');
    }
};
