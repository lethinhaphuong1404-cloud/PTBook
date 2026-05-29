<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBook - Đặt lại mật khẩu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white min-h-screen relative overflow-y-auto overflow-x-hidden font-sans">

    <!-- Background -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=1600');">
        </div>
        <div class="absolute inset-0 bg-black/85"></div>
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

        <div class="w-full max-w-md bg-black/75 backdrop-blur-md rounded-2xl shadow-2xl p-8 md:p-10">

            <h1 class="text-4xl font-bold mb-2">Đặt lại mật khẩu</h1>
            <p class="text-gray-400 mb-8">Nhập mật khẩu mới cho tài khoản của bạn</p>

            <!-- Error -->
            @if(session('error'))
                <div class="mb-4 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-4 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <!-- Hidden Token -->
                <input type="hidden" name="token" value="{{ request()->token }}">

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Gmail</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ request()->email }}"
                        required
                        class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                    >
                </div>

                <!-- New Password -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Mật khẩu mới</label>
                    <div class="relative">
                        <input
                            id="newPassword"
                            type="password"
                            name="password"
                            required
                            class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                        >
                        <button
                            type="button"
                            id="newPasswordEye"
                            onclick="togglePassword('newPassword', 'newPasswordEye')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            👁
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-2 text-sm text-gray-300">Xác nhận mật khẩu mới</label>
                    <div class="relative">
                        <input
                            id="confirmPassword"
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-4 rounded bg-gray-800/80 border border-gray-700 focus:border-red-500 focus:outline-none"
                        >
                        <button
                            type="button"
                            id="confirmPasswordEye"
                            onclick="togglePassword('confirmPassword', 'confirmPasswordEye')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            👁
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 transition py-4 rounded font-bold text-lg shadow-lg">
                    Cập nhật mật khẩu
                </button>
            </form>

            <!-- Back -->
            <p class="mt-8 text-gray-400 text-center">
                Quay lại
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

    <!-- PASSWORD TOGGLE -->
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