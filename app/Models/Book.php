<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $fillable = [
        'title',
        'status', // Thêm dòng này
        'author',
        'slug',
        'category',
        'description',
        'cover_image',
        'book_file',
        'audio_file',
        'is_premium',
        'is_active',
        'views',
        'downloads',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active'  => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug)) {
                $slug = Str::slug($book->title);
                $count = static::where('slug', 'LIKE', "{$slug}%")->count();

                $book->slug = $count ? "{$slug}-" . ($count + 1) : $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-book-cover.jpg');
    }

    public function getBookUrlAttribute()
    {
        return asset('storage/' . $this->book_file);
    }
    // app/Models/Book.php
    public function chapters() {
        return $this->hasMany(Chapter::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class)->latest();
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    // Hàm tính trung bình sao trực tiếp
    public function averageRating() {
        return $this->ratings()->avg('stars') ?: 0;
    }
    public function userBooks()
{
    return $this->hasMany(UserBook::class);
}

}