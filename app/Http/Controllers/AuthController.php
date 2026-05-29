<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * =========================
     * HIỂN THỊ LOGIN
     * =========================
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * =========================
     * XỬ LÝ LOGIN + RÀNG BUỘC
     * =========================
     */
    public function login(Request $request)
    {
        $messages = [
            'email.required'    => '⚠ Vui lòng nhập Gmail.',
            'email.email'       => '⚠ Gmail không đúng định dạng.',
            'password.required' => '⚠ Vui lòng nhập mật khẩu.',
        ];

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], $messages);

        // Không tồn tại email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => '❌ Gmail chưa được đăng ký.'
                ])
                ->withInput();
        }

        // Sai mật khẩu
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'password' => '❌ Mật khẩu không chính xác.'
                ])
                ->withInput();
        }

        // Đăng nhập
        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();

        return redirect('/')
            ->with('success', '✅ Đăng nhập thành công!');
    }

  

    /**
     * =========================
     * HIỂN THỊ REGISTER
     * =========================
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * =========================
     * XỬ LÝ REGISTER + RÀNG BUỘC
     * =========================
     */
    public function register(Request $request)
    {
        $messages = [
            'name.required'                  => '⚠ Vui lòng nhập họ và tên.',
            'name.min'                       => '⚠ Họ tên phải từ 3 ký tự trở lên.',
            'email.required'                 => '⚠ Vui lòng nhập Gmail.',
            'email.email'                    => '⚠ Gmail không đúng định dạng.',
            'email.unique'                   => '❌ Gmail này đã được đăng ký.',
            'password.required'              => '⚠ Vui lòng nhập mật khẩu.',
            'password.min'                   => '⚠ Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex'                 => '⚠ Mật khẩu phải có chữ hoa, chữ thường và số.',
            'password.confirmed'             => '❌ Mật khẩu xác nhận không khớp.',
        ];

        $request->validate([
            'name' => 'required|string|min:3|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], $messages);

        // Tạo user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Auto login
        Auth::login($user);

        return redirect('/')
            ->with('success', '✅ Tạo tài khoản thành công!');
    }

    /**
     * =========================
     * LOGOUT
     * =========================
     */
         // Đăng xuất
        public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')
        ->with('success', 'Bạn đã đăng xuất.');
}
}