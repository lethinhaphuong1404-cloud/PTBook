<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * =========================
     * FORM QUÊN MẬT KHẨU
     * =========================
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * =========================
     * GỬI LINK RESET THẬT QUA EMAIL
     * =========================
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => '⚠ Vui lòng nhập Gmail.',
            'email.email'    => '⚠ Gmail không hợp lệ.'
        ]);

        // Không báo user tồn tại hay không
        $token = bin2hex(random_bytes(32));

        PasswordReset::updateOrCreate(
            ['email' => $request->email],
            [
                'token'      => $token,
                'expired_at' => now()->addMinutes(15),
            ]
        );

        // Link thật
        $resetLink = url('/reset-password?token=' . $token);

        // Gửi mail thật bằng SMTP Gmail
        Mail::raw(
            "PTApp - Khôi phục mật khẩu\n\nNhấn vào link dưới đây để đặt lại mật khẩu:\n$resetLink\n\nLink hết hạn sau 15 phút.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('PTApp - Reset Password');
            }
        );

        return back()->with(
            'success',
            '✅ Nếu Gmail tồn tại, link khôi phục đã được gửi.'
        );
    }

    /**
     * =========================
     * FORM RESET PASSWORD
     * =========================
     */
    public function showResetForm(Request $request)
    {
        $token = $request->token;

        $reset = PasswordReset::where('token', $token)
            ->where('expired_at', '>', now())
            ->first();

        if (!$reset) {
            return redirect('/forgot-password')
                ->withErrors([
                    'token' => '❌ Link không hợp lệ hoặc đã hết hạn.'
                ]);
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * =========================
     * CẬP NHẬT MẬT KHẨU MỚI
     * =========================
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'password'              => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'password.required'  => '⚠ Vui lòng nhập mật khẩu mới.',
            'password.min'       => '⚠ Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => '❌ Xác nhận mật khẩu không khớp.',
            'password.regex'     => '⚠ Mật khẩu cần chữ hoa, chữ thường và số.',
        ]);

        $reset = PasswordReset::where('token', $request->token)
            ->where('expired_at', '>', now())
            ->first();

        if (!$reset) {
            return redirect('/forgot-password')
                ->withErrors([
                    'token' => '❌ Token không hợp lệ hoặc hết hạn.'
                ]);
        }

        $user = User::where('email', $reset->email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Xóa token sau 1 lần dùng
        $reset->delete();

        return redirect('/login')
            ->with('success', '✅ Mật khẩu đã được cập nhật.');
    }
}