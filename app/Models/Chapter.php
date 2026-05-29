<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    // Đảm bảo có dòng này
    protected $fillable = [
        'book_id', 
        'title', 
        'order_number', 
        'file_path', 
        'audio_path' // Thêm dòng này vào
    ];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}