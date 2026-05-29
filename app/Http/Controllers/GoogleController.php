<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect user to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Google callback xử lý đăng nhập / đăng ký
     */
    public function handleGoogleCallback()
    {
        try {
            // Bỏ stateless() nếu IDE báo lỗi hoặc package chưa hỗ trợ đúng
            $googleUser = Socialite::driver('google')->user();

            // Kiểm tra email
            if (!$googleUser->email) {
                return redirect('/login')
                    ->with('error', 'Không lấy được email từ Google.');
            }

            // Tìm user theo email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update nếu đã có
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'name'      => $googleUser->name ?: $user->name,
                ]);
            } else {
                // Tạo mới
                $user = User::create([
                    'name'      => $googleUser->name ?: 'Google User',
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'password'  => bcrypt(Str::random(24)),
                ]);
            }

            // Login
            Auth::login($user, true);

            // Regenerate session
            request()->session()->regenerate();

            return redirect('/')
                ->with('success', 'Đăng nhập Google thành công!');

        } catch (\Exception $e) {
            return redirect('/login')
                ->with('error', 'Google login thất bại: ' . $e->getMessage());
        }
    }
}