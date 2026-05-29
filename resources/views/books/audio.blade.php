<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Thư viện Sách nói' }} - PTBook</title>
    
    <style>
        /* Khung chứa thể loại có giới hạn chiều cao */
        .category-scroll-container {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* Tùy chỉnh thanh cuộn Sidebar */
        .category-scroll-container::-webkit-scrollbar { width: 4px; }
        .category-scroll-container::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .category-scroll-container::-webkit-scrollbar-thumb { background: #ef4444; border-radius: 10px; }
        
        /* Hiệu ứng ảnh bìa đồng bộ trang chủ */
        .book-card-container { 
            position: relative; 
            overflow: hidden; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background-color: #000; 
            width: 100%; 
        }
        .book-bg-blur { 
            position: absolute; 
            inset: 0; 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            filter: blur(15px) brightness(0.3); 
            transform: scale(1.2); 
            z-index: 0; 
        }
        .book-main-img { 
            position: relative; 
            z-index: 10; 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#141414] text-white min-h-screen font-sans">

<header class="bg-black/80 backdrop-blur-md border-b border-white/10 px-8 py-5 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="hover:opacity-80 transition">
                <h1 class="text-4xl font-extrabold text-red-600 tracking-tighter italic">PTBook</h1>
            </a>
            <nav class="hidden lg:flex items-center gap-10 text-sm text-gray-300 font-bold uppercase tracking-[0.2em]">
            <a href="/" class="hover:text-red-500 transition-colors duration-300 relative group">
                Trang chủ
                <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-red-500 transition-all group-hover:w-full"></span>
            </a>
            <a href="{{ route('books.index') }}" class="hover:text-red-500 transition-colors duration-300 relative group">
                Kho sách
                <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-red-500 transition-all group-hover:w-full"></span>
            </a>
            <a href="{{ route('books.audio') }}" class="hover:text-red-500 transition-colors duration-300 relative group">
                Sách nói
                <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-red-500 transition-all group-hover:w-full"></span>
            </a>
            </nav>
        </div>

        <form action="{{ route('books.audio') }}" method="GET" class="relative group">
            <input 
                type="text" 
                name="query" 
                value="{{ request('query') }}"
                placeholder="Tìm sách nói hoặc tác giả..." 
                class="bg-white/5 border border-white/10 rounded-full px-6 py-2 w-full md:w-80 focus:outline-none focus:border-red-500 transition-all text-sm text-white"
            >
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-gray-900/50 p-6 rounded-3xl border border-white/5 sticky top-28 backdrop-blur-md">
                <h3 class="text-xl font-bold mb-5 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-red-600 rounded-full"></span> Thể loại
                </h3>
                <div class="category-scroll-container">
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('books.audio') }}" 
                           class="px-4 py-2.5 rounded-xl text-sm transition-all {{ !request('category') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-gray-400 hover:bg-white/5' }}">
                            Tất cả thể loại
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('books.audio', ['category' => $category, 'query' => request('query')]) }}" 
                               class="px-4 py-2.5 rounded-xl text-sm transition-all {{ request('category') == $category ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-gray-400 hover:bg-white/5' }}">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black tracking-tight">{{ $title ?? 'Thư viện Sách nói' }}</h2>
                    @if(request('category') || request('query'))
                        <p class="text-red-500 text-sm mt-1 font-medium">Đang lọc kết quả theo yêu cầu của bạn</p>
                    @endif
                </div>
                <p class="text-gray-500 text-sm font-bold bg-white/5 px-4 py-1 rounded-full border border-white/5">
                    {{ $books->total() }} Sách nói
                </p>
            </div>

            @if($books->count())
                <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-8">
                    @foreach($books as $book)
                        <a href="{{ route('books.listen', $book->slug) }}" class="group block flex-shrink-0">
                            <div class="relative aspect-[3/4] bg-black rounded-3xl overflow-hidden border border-white/5 group-hover:border-red-500 transition-all duration-500 shadow-2xl book-card-container">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-bg-blur">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="book-main-img transition-transform duration-700 group-hover:scale-110">
                                
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                    <div class="bg-red-600 p-4 rounded-full shadow-[0_0_30px_rgba(220,38,38,0.5)] transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 space-y-1 px-1">
                                <h4 class="font-bold text-lg line-clamp-1 group-hover:text-red-500 transition">{{ $book->title }}</h4>
                                <p class="text-gray-500 text-sm font-medium">{{ $book->author ?: 'Khuyết danh' }}</p>
                                <div class="flex items-center justify-between pt-2">
                                    <div class="flex items-center gap-1">
                                        <span class="text-yellow-500 text-xs">★</span>
                                        <span class="text-xs font-bold text-gray-400">{{ number_format($book->averageRating(), 1) }}</span>
                                    </div>
                                    @if($book->is_premium)
                                        <span class="text-[9px] font-black px-2 py-0.5 rounded bg-yellow-500 text-black uppercase">Premium</span>
                                    @else
                                        <span class="text-[9px] font-black px-2 py-0.5 rounded bg-white/10 text-gray-400 uppercase">Miễn phí</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <div class="mt-16">
                    {{ $books->links() }}
                </div>
            @else
                <div class="text-center py-32 bg-white/5 rounded-[3rem] border border-dashed border-white/10">
                    <div class="text-6xl mb-4">🎧</div>
                    <h3 class="text-2xl font-bold text-gray-400">Chưa có sách nói nào</h3>
                    <p class="text-gray-600 mt-2">Hãy thử chọn một thể loại khác hoặc quay lại sau nhé!</p>
                    <a href="{{ route('books.audio') }}" class="inline-block mt-6 px-8 py-3 bg-red-600 rounded-full font-bold hover:bg-red-700 transition">Xem tất cả sách nói</a>
                </div>
            @endif
        </div>
    </div>
</main>

<footer class="px-8 py-20 text-gray-600 text-sm border-t border-white/5 bg-black/40 mt-20">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-red-600 text-2xl font-black italic opacity-50 mb-6">PTBook</h2>
        <p class="font-medium">© 2026 PTBook — Nghe truyện mọi lúc, mọi nơi.</p>
        <div class="flex justify-center gap-8 mt-6 text-[10px] uppercase tracking-widest font-bold">
            <a href="#" class="hover:text-white transition">Chính sách</a>
            <a href="#" class="hover:text-white transition">Hợp tác</a>
            <a href="#" class="hover:text-white transition">Liên hệ</a>
        </div>
    </div>
</footer>

</body>
</html>