<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBook - Đăng ký</title>
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
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-black/40"></div>
    </div>

    <!-- Header -->
    <header class="relative z-20 flex justify-between items-center px-6 md:px-12 py-6">
        <a href="/" class="text-red-600 text-4xl md:text-5xl font-extrabold tracking-wider">
            PTBook
        </a>
    </header>

    <!-- Main -->
    <main class="relative z-20 flex items-center justify-center min-h-screen px-4 py-16">

        <!-- Register Card -->
        <div class="w-full max-w-md bg-black/75 backdrop-blur-md rounded-2xl shadow-2xl p-8 md:p-10">

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-600/20 border border-green-500 text-green-400 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 bg-red-600/20 border border-red-500 text-red-400 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Title -->
            <h1 class="text-4xl font-bold mb-2">Đăng ký</h1>
            <p class="text-gray-400 mb-8">Tạo tài khoản để bắt đầu đọc và nghe không giới hạn</p>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Full Name -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Họ và tên</label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Nguyễn Văn A"
                        required
                        class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                    >

                    @error('name')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Gmail</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="your@gmail.com"
                        required
                        class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                    >

                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Mật khẩu</label>

                    <div class="relative">
                        <input
                            id="registerPassword"
                            type="password"
                            name="password"
                            placeholder="••••••••••••"
                            required
                            class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                        >

                        <button
                            type="button"
                            id="registerEye"
                            onclick="togglePassword('registerPassword', 'registerEye')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            👁
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Xác nhận mật khẩu</label>

                    <div class="relative">
                        <input
                            id="registerConfirmPassword"
                            type="password"
                            name="password_confirmation"
                            placeholder="••••••••••••"
                            required
                            class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                        >

                        <button
                            type="button"
                            id="registerConfirmEye"
                            onclick="togglePassword('registerConfirmPassword', 'registerConfirmEye')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            👁
                        </button>
                    </div>
                </div>

                <!-- Terms -->
                <label class="flex items-start gap-3 text-sm text-gray-400 cursor-pointer">
                    <input type="checkbox" required class="accent-red-600 mt-1">
                    <span>
                        Tôi đồng ý với
                        <a href="#" class="text-white hover:underline">Điều khoản dịch vụ</a>
                        và
                        <a href="#" class="text-white hover:underline">Chính sách bảo mật</a>
                    </span>
                </label>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 transition py-4 rounded font-bold text-lg shadow-lg">
                    Tạo tài khoản
                </button>
            </form>

            <!-- Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-1 h-px bg-gray-700"></div>
                <span class="px-4 text-gray-500 text-sm">hoặc</span>
                <div class="flex-1 h-px bg-gray-700"></div>
            </div>

            <!-- Google Register -->
            <a
                href="{{ route('google.login') }}"
                class="block w-full text-center border border-gray-600 hover:border-white py-4 rounded font-medium transition">
                Tiếp tục với Google
            </a>

            <!-- Login -->
            <p class="mt-8 text-gray-400 text-center">
                Đã có tài khoản?
                <a href="/login" class="text-white font-semibold hover:underline">
                    Đăng nhập
                </a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-20 text-center text-gray-500 text-sm pb-6">
        © 2026 PTApp — Đọc. Nghe. Đắm chìm.
    </footer>

    <!-- PASSWORD TOGGLE SCRIPT -->
    <script>
        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);

            if (input.type === "password") {
                input.type = "text";
                eye.innerText = "🙈";
            } else {
                input.type = "password";
                eye.innerText = "👁";
            }
        }
    </script>

</body>
</html>