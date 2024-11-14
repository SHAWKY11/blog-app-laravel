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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('image'); 
            $table->text('post_content'); 
            $table->string('author'); 
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
            $table->boolean('active')->default(true); 
            $table->integer('comments_count')->default(0); 
            $table->integer('views')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
