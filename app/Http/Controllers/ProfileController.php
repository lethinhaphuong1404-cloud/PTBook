<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserBook;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

    /**
     * Lịch sử đọc (Lấy những dòng có is_read = 1)
     */
        $readingBooks = UserBook::with('book')
            ->where('user_id', $user->id)
            ->where('is_read', 1) // Sửa từ true thành 1
            ->latest('last_read_at')
            ->paginate(12, ['*'], 'reading');

        /**
         * Lịch sử tải (Lấy những dòng có is_downloaded = 1)
         */
        $downloadedBooks = UserBook::with('book')
            ->where('user_id', $user->id)
            ->where('is_downloaded', 1) // Sửa từ true thành 1
            ->latest()
            ->paginate(12, ['*'], 'downloads');

        /**
         * Stats
         */
        $totalRead = UserBook::where('user_id', $user->id)
            ->where('is_read', 1)
            ->count();

        $totalDownloaded = UserBook::where('user_id', $user->id)
            ->where('is_downloaded', 1)
        ->count();

        return view('profile.index', compact(
            'user',
            'readingBooks',
            'downloadedBooks',
            'totalRead',
            'totalDownloaded'
        ));
    }
}