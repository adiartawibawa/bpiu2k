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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            // $table->integer('parent_id')->default(-1)->index();
            // $table->integer('order')->default(0);
            // $table->string('title');
            $table->treeColumns();
            $table->string('url');
            $table->string('target')->default('_self');
            $table->string('icon')->nullable();
            $table->string('type')->default('link'); // link, page, post, category, etc.
            $table->string('route')->nullable();
            $table->string('parameters')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
};
