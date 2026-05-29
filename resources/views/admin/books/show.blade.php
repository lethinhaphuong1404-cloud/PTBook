<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - PTApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

<!-- Header -->
<header class="bg-black border-b border-gray-800 px-6 py-4 flex justify-between items-center">
    <a href="/" class="text-3xl font-bold text-red-500">PTBook</a>

    <div class="flex gap-4">
        <a href="{{ route('books.index') }}" class="text-gray-300 hover:text-white">
            Thư viện
        </a>

        @auth
            <span class="text-gray-300">{{ Auth::user()->name }}</span>
        @endauth
    </div>
</header>

<!-- Main -->
<main class="max-w-7xl mx-auto px-6 py-12">

    <!-- Top Section -->
    <div class="grid lg:grid-cols-3 gap-10">

        <!-- Cover -->
        <div>
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}"
                     alt="{{ $book->title }}"
                     class="w-full max-w-sm rounded-2xl shadow-2xl border border-gray-800">
            @else
                <div class="w-full h-[500px] bg-gray-800 rounded-2xl flex items-center justify-center">
                    Không có ảnh
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="lg:col-span-2">

            <div class="flex flex-wrap gap-3 mb-4">
            <span class="bg-gray-800 px-4 py-2 rounded-full text-sm">
                {{ $book->category ?? 'Khác' }}
            </span>

            @if($book->status == 'completed' || $book->status == 'full')
                <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                    Full
                </span>
            @else
                <span class="bg-green-500/10 text-green-400 border border-green-500/20 px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-bounce"></span>
                    Đang ra
                </span>
            @endif

            @if($book->is_premium)
                <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-bold shadow-lg shadow-yellow-500/20">
                    PREMIUM
                </span>
            @endif
        </div>

            <h1 class="text-5xl font-bold mb-4 leading-tight">
                {{ $book->title }}
            </h1>

            <p class="text-2xl text-gray-400 mb-6">
                {{ $book->author ?? 'Đang cập nhật' }}
            </p>

            <!-- Stats -->
            <div class="flex flex-wrap gap-6 text-gray-300 mb-8">
                <span>👁 {{ number_format($book->views) }} lượt xem</span>
                <span>⬇ {{ number_format($book->downloads) }} lượt tải</span>
            </div>

            <!-- Description -->
            <div class="bg-black/40 border border-gray-800 rounded-2xl p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4">Giới thiệu</h2>

                <p class="text-gray-300 leading-relaxed whitespace-pre-line">
                    {{ $book->description ?: 'Chưa có mô tả cho sách này.' }}
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">

                <!-- Read -->
                <a href="{{ route('reader.show', $book->slug) }}"
                   class="bg-red-600 hover:bg-red-700 px-8 py-4 rounded-xl font-bold text-lg text-center">
                    Đọc ngay
                </a>

                <!-- Download -->
                <a href="{{ route('books.download', $book->slug) }}"
                   class="bg-gray-800 hover:bg-gray-700 px-8 py-4 rounded-xl font-bold text-lg text-center">
                    Tải xuống
                </a>

            </div>

        </div>

    </div>

    <!-- Related -->
    @if($relatedBooks->count())
        <section class="mt-20">
            <h2 class="text-4xl font-bold mb-8">Sách liên quan</h2>

            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($relatedBooks as $related)
                    <a href="{{ route('books.show', $related->slug) }}"
                       class="bg-black/40 border border-gray-800 rounded-2xl overflow-hidden hover:scale-105 transition">

                        @if($related->cover_image)
                            <img src="{{ asset('storage/' . $related->cover_image) }}"
                                 class="w-full h-72 object-cover">
                        @endif

                        <div class="p-4">
                            <h3 class="font-bold line-clamp-2">
                                {{ $related->title }}
                            </h3>

                            <p class="text-gray-400 mt-2">
                                {{ $related->author }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</main>
<footer class="px-8 py-16 text-gray-600 text-sm border-t border-gray-900 bg-black/20">
    <div class="max-w-7xl mx-auto text-center flex flex-col items-center justify-center space-y-4">
        <h2 class="text-red-600 text-2xl font-black tracking-tighter opacity-50 italic">PTBook</h2>
        <p class="font-medium">© 2026 PTBook — Đọc. Nghe. Đắm chìm.</p>
        <div class="flex gap-6 text-xs uppercase tracking-widest">
            <a href="#" class="hover:text-white transition">Điều khoản</a>
            <a href="#" class="hover:text-white transition">Bảo mật</a>
            <a href="#" class="hover:text-white transition">Liên hệ</a>
        </div>
    </div>
</footer>
</body>
</html>