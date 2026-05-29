<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    // Phải có user_id và book_id để updateOrCreate hoạt động
    protected $fillable = ['user_id', 'book_id', 'stars'];
}
