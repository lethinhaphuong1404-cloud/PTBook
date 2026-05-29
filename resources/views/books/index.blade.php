<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện sách - PTBook</title>
    <style>
        .category-scroll-container {
            max-height: 280px;
            overflow-y: auto;
            padding-right: 8px;
        }
        .category-scroll-container::-webkit-scrollbar { width: 4px; }
        .category-scroll-container::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .category-scroll-container::-webkit-scrollbar-thumb { background: #ef4444; border-radius: 10px; }
        
        .book-card-container { position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #000; width: 100%; }
        .book-bg-blur { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: blur(15px) brightness(0.3); transform: scale(1.2); z-index: 0; }
        .book-main-img { position: relative; z-index: 10; width: 100%; height: 100%; object-fit: contain; }

        /* Styles nhãn trạng thái mới thêm */
        .status-label { position: absolute; top: 10px; left: 10px; z-index: 30; padding: 3px 10px; border-radius: 8px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); pointer-events: none; }
        .status-full { background: linear-gradient(to right, #10b981, #059669); color: white; }
        .status-ongoing { background: linear-gradient(to right, #3b82f6, #2563eb); color: white; }
        /* Class khi thể loại được chọn */
        .category-active {
            background: linear-gradient(to right, rgba(239, 68, 68, 0.15), transparent) !important;
            border-left: 4px solid #ef4444 !important;
            color: #ef4444 !important;
            font-weight: 900 !important;
            padding-left: 18px !important; /* Đẩy chữ sang phải để tạo chiều sâu */
        }

        /* Hiệu ứng đặc biệt cho văn bản bên trong khi active */
        .category-active span {
            color: #ef4444 !important;
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
        }

        /* Đảm bảo danh sách không bị xuống dòng */
        .flex-col a {
            white-space: nowrap;
            overflow: hidden;
        }
    </style>
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

<header class="bg-black border-b border-gray-800 px-8 py-5">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <a href="{{ route('home') }}" class="hover:opacity-80 transition">
            <h1 class="text-4xl font-extrabold text-red-500 tracking-wide">PTBook</h1>
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
        <form action="{{ route('books.search') }}" method="GET" class="relative group">
            <input type="text" name="query" value="{{ request('query') }}" placeholder="Tìm tên truyện hoặc tác giả..." class="bg-white/5 border border-white/10 rounded-full px-6 py-2 w-64 focus:outline-none focus:border-red-500 transition-all text-sm text-white">
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
            <div class="bg-gray-900/50 p-6 rounded-3xl border border-white/5 sticky top-28 backdrop-blur-md space-y-8">
                
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-red-500">
                        <span class="w-1 h-4 bg-red-600 rounded-full"></span> Thể loại
                    </h3>
                    <div class="category-scroll-container pr-2">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('books.index', array_merge(request()->query(), ['category' => null])) }}" 
                            class="px-3 py-2 rounded-lg text-sm transition-all {{ !request('category') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-gray-400 hover:bg-white/5' }}">
                                Tất cả truyện
                            </a>

                           @foreach($globalCategories as $cat)
                                @php 
                                    // So sánh chính xác sau khi đã trim khoảng trắng
                                    $isActive = trim(request('category')) === trim($cat); 
                                @endphp
                                
                                <a href="{{ route('books.index', ['category' => $cat]) }}" 
                                    class="px-3 py-2 rounded-lg text-sm flex justify-between items-center group transition-all duration-300
                                    {{ $isActive ? 'category-active' : 'text-gray-400 border-l-4 border-transparent hover:bg-white/5 hover:text-white' }}">
                                    
                                    <div class="flex items-center gap-2">
                                        @if($isActive)
                                            <span class="w-2 h-2 bg-red-500 rounded-full shadow-[0_0_10px_#ef4444] animate-pulse"></span>
                                        @endif
                                        <span class="truncate">{{ $cat }}</span>
                                    </div>
                                    
                                    @if($isActive)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a> @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-green-500">
                        <span class="w-1 h-4 bg-green-600 rounded-full"></span> Trạng thái
                    </h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('books.index', array_merge(request()->query(), ['status' => 'completed'])) }}" 
                        class="px-3 py-2 rounded-lg text-sm {{ request('status') == 'completed' ? 'bg-green-600 text-white' : 'text-gray-400 hover:bg-white/5' }}">
                            Truyện Full
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->query(), ['status' => 'ongoing'])) }}" 
                        class="px-3 py-2 rounded-lg text-sm {{ request('status') == 'ongoing' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-white/5' }}">
                            Đang tiến hành
                        </a>
                    </div>
                </div>

                <!-- <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-yellow-500">
                        <span class="w-1 h-4 bg-yellow-500 rounded-full"></span> Số chương
                    </h3>
                    <div class="flex flex-col gap-1">
                        @foreach(['0-10' => 'Dưới 10 chương', '10-20' => '10 - 20 chương', '30-50' => '30 - 50 chương', '51-9999' => 'Trên 50 chương'] as $key => $label)
                            <a href="{{ route('books.index', array_merge(request()->query(), ['chapters' => $key])) }}" 
                            class="px-3 py-2 rounded-lg text-sm {{ request('chapters') == $key ? 'bg-yellow-600 text-black font-bold' : 'text-gray-400 hover:bg-white/5' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div> -->

                @if(request()->anyFilled(['category', 'status', 'chapters', 'query']))
                    <a href="{{ route('books.index') }}" class="block text-center py-2 text-xs text-red-400 hover:underline">× Xóa tất cả bộ lọc</a>
                @endif
            </div>
        </aside>

        <div class="flex-1">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    @if(request('category'))
                        <span class="px-3 py-1 bg-red-600 text-xs rounded-md uppercase tracking-widest">Thể loại</span>
                        <span class="text-white">{{ request('category') }}</span>
                    @elseif(request('status'))
                        <span class="px-3 py-1 bg-green-600 text-xs rounded-md uppercase tracking-widest">Trạng thái</span>
                        <span class="text-white">{{ request('status') == 'completed' ? 'Truyện Full' : 'Đang tiến hành' }}</span>
                    @else
                        Danh sách Truyện và Sách
                    @endif
                </h2>
                <p class="text-gray-400 text-sm font-medium">{{ $books->total() }} tác phẩm</p>
            </div>

            @if($books->count())
                <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    @foreach($books as $book)
                        <a href="{{ route('books.show', $book->slug) }}" class="group block bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 hover:border-red-500 transition hover:-translate-y-2">
                            <div class="aspect-[3/4] book-card-container">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-bg-blur">
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="book-main-img transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500">Không có ảnh</div>
                                @endif

                                @if($book->status === 'completed' || $book->status === 'full')
                                    <span class="status-label status-full">Full</span>
                                @else
                                    <span class="status-label status-ongoing">Đang ra</span>
                                @endif
                            </div>

                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-lg line-clamp-2 group-hover:text-red-400 transition">{{ $book->title }}</h3>
                                <p class="text-gray-400 text-sm italic">{{ $book->author ?: 'Khuyết danh' }}</p>

                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-yellow-500">★</span>
                                    <span class="text-sm font-bold text-gray-400">{{ number_format($book->averageRating(), 1) }}</span>
                                    
                                    @if($book->is_premium)
                                        <span class="ml-auto px-2 py-0.5 text-[10px] font-black rounded bg-yellow-500 text-black">PREMIUM</span>
                                    @else
                                        <span class="ml-auto px-2 py-0.5 text-[10px] font-black rounded bg-green-500 text-white">FREE</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-12">{{ $books->links() }}</div>
            @else
                <div class="text-center py-20"><h3 class="text-2xl font-bold text-gray-500">Không tìm thấy sách phù hợp</h3></div>
            @endif
        </div>
    </div>
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