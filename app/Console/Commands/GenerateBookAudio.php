<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class GenerateBookAudio extends Command
{
    protected $signature = 'book:generate-audio {bookId}';
    protected $description = 'Tạo audio tự động từ file TXT';

    public function handle()
    {
        $book = Book::find($this->argument('bookId'));

        if (!$book) {
            $this->error('Không tìm thấy sách.');
            return;
        }

        if (!$book->book_file) {
            $this->error('Sách không có file.');
            return;
        }

        $path = storage_path('app/public/' . $book->book_file);

        if (!file_exists($path)) {
            $this->error('File không tồn tại.');
            return;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension !== 'txt') {
            $this->error('Hiện tại chỉ hỗ trợ TXT.');
            return;
        }

        $text = file_get_contents($path);

        if (!$text) {
            $this->error('Không đọc được nội dung.');
            return;
        }

        /**
         * Lưu text tạm
         */
        $tempText = storage_path('app/temp_book.txt');
        file_put_contents($tempText, $text);

        /**
         * File output
         */
        $audioName = 'audios/book_' . $book->id . '.mp3';
        $audioPath = storage_path('app/public/' . $audioName);

        /**
         * Python gTTS
         */
        $python = base_path('tts.py');

        exec("python $python \"$tempText\" \"$audioPath\"");

        if (file_exists($audioPath)) {
            $book->update([
                'audio_file' => $audioName
            ]);

            $this->info('Tạo audio thành công!');
        } else {
            $this->error('Tạo audio thất bại.');
        }
    }
}