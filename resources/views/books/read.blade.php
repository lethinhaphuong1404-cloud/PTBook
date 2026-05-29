<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chương {{ $currentChapter->order_number }}: {{ $currentChapter->title }} - PTBook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .reading-content {
            font-family: 'Lora', serif; /* Font có chân giúp mắt ít mỏi hơn */
            line-height: 2;
            letter-spacing: 0.01em;
        }
        .glass-header {
            background: rgba(13, 17, 23, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-[#0f141a] text-gray-200 min-h-screen selection:bg-red-500/30">

<header class="sticky top-0 z-50 glass-header">
    <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('books.show', $book->slug) }}" class="text-gray-400 hover:text-white transition p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 m0 0l7-7 m-7 7h18" />
                </svg>
            </a>
            <div class="hidden md:block">
                <h1 class="text-sm font-bold text-gray-100 line-clamp-1 uppercase tracking-wider">{{ $book->title }}</h1>
                <p class="text-xs text-red-500 font-medium">Chương {{ $currentChapter->order_number }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
             <button onclick="toggleSettings()" class="p-2 text-gray-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </button>
            <a href="{{ route('books.download', $book->slug) }}" class="hidden sm:flex items-center gap-2 bg-white/5 hover:bg-white/10 px-4 py-2 rounded-full text-xs font-bold transition border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Tải về
            </a>
        </div>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 py-12 md:py-20">
    <div class="text-center mb-16">
        <h2 class="text-gray-500 text-sm font-bold uppercase tracking-[0.2em] mb-4">Chương {{ $currentChapter->order_number }}</h2>
        <h3 class="text-3xl md:text-5xl font-extrabold text-white leading-tight mb-6">{{ $currentChapter->title }}</h3>
        <div class="flex items-center justify-center gap-4 text-gray-400 text-sm">
            <span class="flex items-center gap-1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> {{ $book->author }}</span>
            <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
            <span class="flex items-center gap-1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ $book->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <div class="flex justify-between items-center mb-12 border-y border-white/5 py-4">
        @if($prevChapter)
            <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => $prevChapter->order_number]) }}" class="text-sm font-bold text-gray-400 hover:text-red-500 transition flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M15 19l-7-7 7-7" /></svg> Chương trước
            </a>
        @else
            <span class="text-sm font-bold text-gray-700 cursor-not-allowed">Đầu truyện</span>
        @endif

        <select onchange="window.location.href=this.value" class="bg-transparent text-sm font-bold border-none focus:ring-0 text-gray-300 hover:text-white cursor-pointer transition">
            @foreach($book->chapters->sortBy('order_number') as $chap)
                <option value="{{ route('books.read', ['book' => $book->slug, 'chapter' => $chap->order_number]) }}" 
                    {{ $currentChapter->order_number == $chap->order_number ? 'selected' : '' }} class="bg-[#141920]">
                    Chương {{ $chap->order_number }}
                </option>
            @endforeach
        </select>

        @if($nextChapter)
            <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => $nextChapter->order_number]) }}" class="text-sm font-bold text-red-500 hover:text-red-400 transition flex items-center gap-2">
                Chương sau <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        @else
            <span class="text-sm font-bold text-gray-700">Hết rồi</span>
        @endif
    </div>

    <article class="reading-content text-[1.25rem] md:text-[1.4rem] text-gray-300 leading-[2.2] mb-20 antialiased">
        {!! nl2br(e($content)) !!}
    </article>

    <div class="grid grid-cols-2 gap-4 mt-20 border-t border-white/10 pt-10">
        @if($prevChapter)
            <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => $prevChapter->order_number]) }}" class="group p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-red-500/50 transition">
                <p class="text-xs font-bold text-gray-500 uppercase mb-2 group-hover:text-red-500">Chương trước</p>
                <p class="text-sm font-bold text-white line-clamp-1">Chương {{ $prevChapter->order_number }}</p>
            </a>
        @endif

        @if($nextChapter)
            <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => $nextChapter->order_number]) }}" class="group p-6 bg-red-600 rounded-2xl border border-red-500 hover:bg-red-700 transition {{ !$prevChapter ? 'col-span-2' : '' }}">
                <p class="text-xs font-bold text-red-200 uppercase mb-2">Chương tiếp theo</p>
                <p class="text-sm font-bold text-white line-clamp-1">Chương {{ $nextChapter->order_number }}</p>
            </a>
        @else
             <a href="{{ route('books.show', $book->slug) }}" class="col-span-2 group p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-yellow-500/50 text-center transition">
                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Bạn đã đọc hết</p>
                <p class="text-sm font-bold text-white">Quay lại trang chi tiết truyện</p>
            </a>
        @endif
    </div>
</main>

<footer class="py-12 text-center text-gray-600 text-xs border-t border-white/5">
    <p>© {{ date('Y') }} PTBook - Đọc truyện văn minh</p>
</footer>

<script>
    // Hàm phụ trợ nếu bạn muốn thêm tính năng tùy chỉnh sau này
    function toggleSettings() {
        alert('Tính năng đổi font/cỡ chữ đang được phát triển!');
    }
</script>
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