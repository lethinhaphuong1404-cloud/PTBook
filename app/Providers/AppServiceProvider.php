<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Book;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
       \Illuminate\Support\Facades\View::composer('*', function ($view) {
        // Lấy danh sách thể loại duy nhất từ bảng books
        $categories = \App\Models\Book::pluck('category')
            ->flatMap(fn($item) => explode(',', $item))
            ->map(fn($item) => trim($item))
            ->unique()
            ->filter()
            ->values();
            
        $view->with('globalCategories', $categories);
    });
    }
}
