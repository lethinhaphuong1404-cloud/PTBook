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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // Thông tin cơ bản
            $table->string('title'); // Tên sách
            $table->string('author'); // Tác giả
            $table->string('slug')->unique(); // URL đẹp

            // Nội dung
            $table->longText('description')->nullable(); // Mô tả
            $table->string('category')->nullable(); // Thể loại

            // File
            $table->string('cover_image')->nullable(); // Ảnh bìa
            $table->string('book_file')->nullable(); // PDF / EPUB

            // Premium
            $table->boolean('is_premium')->default(false);

            // Tracking
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};