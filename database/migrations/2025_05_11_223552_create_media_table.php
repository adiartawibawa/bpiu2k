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
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('path');
            $table->string('disk')->default('public');
            $table->unsignedBigInteger('size');
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuidMorphs('model');
            $table->string('collection_name')->default('default');
            $table->json('custom_properties')->nullable();
            $table->unsignedInteger('order_column')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
