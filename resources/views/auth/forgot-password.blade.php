<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBook - Quên mật khẩu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white min-h-screen relative overflow-y-auto overflow-x-hidden font-sans">

    <!-- Background Hero -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=1600');">
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/85"></div>

        <!-- Gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/75 to-black/40"></div>
    </div>

    <!-- Header -->
    <header class="relative z-20 flex justify-between items-center px-6 md:px-12 py-6">
        <a href="/" class="text-red-600 text-4xl md:text-5xl font-extrabold tracking-wider">
            PTBook
        </a>
    </header>

    <!-- Main -->
    <main class="relative z-20 flex items-center justify-center min-h-screen px-4 py-16">

        <!-- Forgot Password Card -->
        <div class="w-full max-w-md bg-black/75 backdrop-blur-md rounded-2xl shadow-2xl p-8 md:p-10">

            <!-- Title -->
            <h1 class="text-4xl font-bold mb-2">Quên mật khẩu?</h1>
            <p class="text-gray-400 mb-8 leading-relaxed">
                Đừng lo. Nhập Gmail bạn đã đăng ký với PTApp, chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn.
            </p>

            <!-- Status Message Example -->
            {{-- <div class="mb-6 bg-green-600/20 border border-green-500 text-green-400 p-4 rounded">
                Link đặt lại mật khẩu đã được gửi tới Gmail của bạn.
            </div> --}}

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Gmail</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="your@gmail.com"
                        required
                        class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                    >
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 transition py-4 rounded font-bold text-lg shadow-lg">
                    Gửi link đặt lại mật khẩu
                </button>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 text-sm text-gray-500 leading-relaxed">
                • Link chỉ có hiệu lực trong 10–15 phút<br>
                • Mỗi token chỉ dùng 1 lần<br>
                • Vui lòng kiểm tra cả Spam/Junk nếu chưa thấy email
            </div>

            <!-- Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-1 h-px bg-gray-700"></div>
                <span class="px-4 text-gray-500 text-sm">hoặc</span>
                <div class="flex-1 h-px bg-gray-700"></div>
            </div>

            <!-- Back to Login -->
            <a href="/login"
               class="block w-full text-center border border-gray-600 hover:border-white py-4 rounded font-medium transition">
                Quay lại đăng nhập
            </a>

            <!-- Register -->
            <p class="mt-8 text-gray-400 text-center">
                Chưa có tài khoản?
                <a href="/register" class="text-white font-semibold hover:underline">
                    Đăng ký ngay
                </a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-20 text-center text-gray-500 text-sm pb-6">
        © 2026 PTApp — Đọc. Nghe. Đắm chìm.
    </footer>

</body>
</html>