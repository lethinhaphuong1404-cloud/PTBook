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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Tên chương: Chương 1, Tiết tử...
            $table->integer('order_number')->default(1); // Thứ tự chương
            $table->string('file_path'); // Đường dẫn file của chương đó
            $$table->string('audio_path')->nullable()->after('file_path'); // Đường dẫn file audio (nếu có)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
