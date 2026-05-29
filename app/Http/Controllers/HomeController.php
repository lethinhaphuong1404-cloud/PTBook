<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        /**
         * Hero nổi bật
         */
       $featuredBook = Book::where('is_active', true)
    ->orderByDesc('views')
    ->first();

        /**
         * Xu hướng
         */
        $trendingBooks = Book::where('is_active', true)
            ->orderByDesc('views')
            ->take(10)
            ->get();

        /**
         * Mới cập nhật
         */
        $latestBooks = Book::where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        /**
         * Premium
         */
        $premiumBooks = Book::where('is_active', true)
            ->where('is_premium', true)
            ->latest()
            ->take(10)
            ->get();

        /**
         * Audio
         */
    // Lấy sách có audio và sắp xếp theo lượt nghe giảm dần
        $audioBooks = Book::whereHas('chapters', function ($query) {
                $query->whereNotNull('audio_path');
            })
            ->orderBy('audio_views', 'desc') // Giả sử bạn có cột audio_views trong bảng books
            ->take(10) 
            ->get();


        $completedBooks = \App\Models\Book::where('is_active', true)
            ->where('status', 'completed') // Đảm bảo chữ 'completed' viết thường giống trong DB
            ->latest()
            ->take(10)
            ->get();

        return view('home', compact(
            'featuredBook', 
            'trendingBooks', 
            'latestBooks', 
            'completedBooks', // Chắc chắn đã truyền biến này sang View
            'audioBooks', 
            'premiumBooks',
        ));
    }
}