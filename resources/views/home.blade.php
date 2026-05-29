<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBook - Đọc. Nghe. Đắm chìm.</title>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <style>
        /* Áp dụng Noto Sans cho toàn bộ trang hoặc ưu tiên các phần nội dung */
        body {
            font-family: 'Noto Sans', sans-serif !important;
        }

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

        .status-label {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 30;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            pointer-events: none;
        }
        .status-full { background: linear-gradient(to right, #10b981, #059669); color: white; }
        .status-ongoing { background: linear-gradient(to right, #3b82f6, #2563eb); color: white; }
        .description-container {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Giới hạn 3 dòng */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.3s ease;
        }
        
        .view-more-link {
            display: inline-block;
            margin-top: 8px;
            color: #ef4444; /* màu red-500 */
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
        }

        .view-more-link:hover {
            text-decoration: underline;
            color: #f87171;
        }
        .status-new {
        background: linear-gradient(to right, #f59e0b, #ea580c); /* Màu cam rực rỡ */
        color: white;
        }
        /* Chuyển động bồng bềnh của Poster */
       @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); } /* Giảm biên độ để bớt "dư thừa" */
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
            will-change: transform; /* Tối ưu hiệu năng */
        }

        /* Đảm bảo ảnh bìa chính hiển thị sắc nét */
        .hero-poster-img {
            width: 380px; 
            height: 550px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
        }

        /* Hiệu ứng mờ ảo khi chuyển slide */
        .animate-fade-in {
            animation: slideReveal 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideReveal {
            from { opacity: 0; transform: translateY(20px); filter: blur(5px); }
            to { opacity: 1; transform: translateY(0); filter: blur(0); }
        }
        .audio-stats {
            font-family: 'Roboto', sans-serif;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.5px;
        }
        /* Hiệu ứng dropdown mượt mà */
        .dropdown-content {
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        /* Hiển thị khi hover hoặc khi có class active (để click) */
        .group:hover .dropdown-content,
        .dropdown-active .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Tùy chỉnh thanh cuộn cho dropdown nếu danh mục quá dài */
        .dropdown-content::-webkit-scrollbar {
            width: 4px;
        }
        .dropdown-content::-webkit-scrollbar-thumb {
            background: #ef4444;
            border-radius: 10px;
        }
        /* Cho phép hiển thị tối đa 6 dòng thay vì 3 */
        .line-clamp-extended {
            display: -webkit-box;
            -webkit-line-clamp: 6; 
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Hiệu ứng chữ mờ dần ở cuối dòng cuối cùng để tạo cảm giác chiều sâu */
        .description-fade {
            position: relative;
        }
        
        /* Làm cho phần mô tả trông sang trọng hơn với font chữ nhẹ nhàng */
        .hero-description {
            font-weight: 300;
            line-height: 1.8;
            letter-spacing: 0.02em;
            color: rgba(255, 255, 255, 0.8);
        }
        /* Tùy chỉnh thanh chỉ báo chuyển động */
        .indicator-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px; /* Khoảng cách với phần Xu hướng */
        }

        .indicator-bar {
            width: 60px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: #dc2626; /* Màu đỏ chủ đạo */
            transition: width 0.1s linear;
        }

        .indicator-bar.active {
            background: rgba(255, 255, 255, 0.2);
            width: 80px;
        }

        /* Fix nội dung Hero để đầy đặn hơn */
        .hero-content-wrapper {
            max-width: 1400px; /* Tăng độ rộng tổng thể */
            margin: 0 auto;
            padding: 0 4rem;
        }
        /* Tăng khoảng cách dòng và độ thoáng cho text Hero */
        .hero-content-wrapper h2 {
            margin-bottom: 1.5rem;
        }

        /* 1. Thu nhỏ chiều cao của Hero Section */
        .hero-section-custom {
            min-h-[70vh] !important; /* Giảm từ 95vh xuống 70vh hoặc tùy ý */
            height: auto;
            padding-bottom: 20px; /* Tạo khoảng nghỉ nhỏ trước thanh indicator */
        }

        /* 2. Loại bỏ Margin âm quá lớn để thanh indicator sát lại */
        .indicator-wrapper-fixed {
            position: relative;
            z-index: 30;
            margin-top: -40px; /* Kéo thanh indicator lên sát Hero content */
        }

        /* 3. Tinh chỉnh lại container chứa thanh bar */
        .indicator-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px; /* Khoảng cách nhỏ với phần Xu hướng */
        }

        /* 4. Đảm bảo phần Main không bị đẩy quá xa */
        main {
            margin-top: 10px !important; /* Đưa các mục truyện sát lên trên */
            background: transparent; /* Loại bỏ gradient nếu muốn nhìn liền mạch */
        }
        /* Mặc định là Dark Mode (theo thiết kế hiện tại của bạn) */
        :root {
            --bg-main: #141414;
            --text-main: #ffffff;
            --header-bg: linear-gradient(to bottom, #000000, rgba(0,0,0,0.9), transparent);
        }

        /* Cấu hình Light Mode Toàn diện */
        html.light {
            --bg-main: #ffffff;
            --text-main: #1a1a1a;
            --header-bg: #ffffff;
            --card-bg: #f3f4f6;
            --border-color: rgba(0, 0, 0, 0.1);
        }

        /* --- Tinh chỉnh Body & Header --- */
        html.light body {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
        }

        html.light header {
            background: var(--header-bg) !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        html.light nav a, 
        html.light #category-dropdown div,
        html.light header input {
            color: #374151 !important; /* gray-700 */
        }

        html.light header input {
            background: #f3f4f6 !important;
            border: 1px solid #e5e7eb !important;
        }

        /* --- FIX PHẦN NỔI BẬT (HERO SECTION) --- */
        html.light section.relative.min-h-\[75vh\] {
            background-color: #f9fafb !important; /* Nền xám cực nhẹ cho phần Hero */
        }

        /* Đổi Gradient từ Đen sang Trắng để chữ đen nổi bật trên nền ảnh blur */
        html.light .absolute.inset-0.bg-gradient-to-r {
            background: linear-gradient(to right, #ffffff 30%, rgba(255,255,255,0.7) 60%, transparent 100%) !important;
        }

        /* Chuyển toàn bộ text trong Hero sang màu tối */
        html.light .hero-content-wrapper h2 { color: #000000 !important; }
        html.light .hero-content-wrapper .text-gray-300 { color: #4b5563 !important; }
        html.light .hero-content-wrapper .text-gray-400 { color: #374151 !important; }

        /* Nút Đọc Ngay (Chuyển sang dạng viền hoặc nền tối nhẹ) */
        html.light .group\/btn {
            background: #1a1a1a !important;
            color: #ffffff !important;
        }

        /* Nút Nghe Ngay */
        html.light .group\/audio {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #1a1a1a !important;
        }

        /* --- FIX CÁC THÀNH PHẦN KHÁC --- */
        /* Dropdown Thể loại */
        html.light .dropdown-content {
            background: #ffffff !important;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        html.light .dropdown-content a { color: #4b5563 !important; }
        html.light .dropdown-content a:hover { color: #dc2626 !important; }

        /* Indicator (Thanh chạy slide) */
        html.light .indicator-bar { background: rgba(0, 0, 0, 0.1) !important; }
        html.light .indicator-bar.active { background: rgba(0, 0, 0, 0.2) !important; }

        /* Card truyện bên dưới */
        html.light .bg-white\/5 { background-color: #f3f4f6 !important; }
        html.light .border-gray-800 { border-color: #e5e7eb !important; }
        html.light h3, html.light h4 { color: #111827 !important; }

        /* Footer */
        html.light footer {
            background-color: #f9fafb !important;
            border-top: 1px solid #e5e7eb;
        }
        /* Ẩn thanh cuộn cho toàn bộ các trình duyệt nhưng vẫn giữ tính năng kéo/cuộn */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
            scroll-behavior: smooth; /* Tạo hiệu ứng cuộn mượt khi click */
        }

        /* Định vị nút mũi tên */
        .carousel-btn {
            opacity: 0;
            transition: all 0.3s ease;
        }
        /* Khi rê chuột vào khu vực danh sách truyện thì hiện mũi tên lên */
        .group-carousel:hover .carousel-btn {
            opacity: 1;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#141414] text-white min-h-screen font-sans">

<header class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-b from-black via-black/90 to-transparent px-6 md:px-12 py-5 flex items-center justify-between border-b border-white/5">
    <div class="flex items-center gap-12 flex-1">
        <h1 class="text-red-600 text-4xl font-extrabold tracking-wider italic">PTBook</h1>
        
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

            <div class="relative group cursor-pointer py-2" id="category-dropdown">
                <div class="flex items-center gap-1 hover:text-red-500 transition-colors duration-300 font-bold uppercase tracking-[0.2em]">
                    Thể loại
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-500 transition-all group-hover:w-full"></span>

                <div class="dropdown-content absolute top-full left-1/2 -translate-x-1/2 mt-3 w-[600px] bg-[#1a1a1a]/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.9)] p-8 z-[100]">
                    <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-[#1a1a1a] border-t border-l border-white/10 rotate-45"></div>

                    <div class="grid grid-cols-4 gap-y-4 gap-x-6 overflow-y-auto max-h-[400px]">
                        @foreach($globalCategories as $cat)
                            <a href="{{ route('books.index', ['category' => $cat]) }}" 
                            class="group/item flex items-center gap-3 text-[13px] text-gray-400 hover:text-white transition-all duration-300 py-1">
                                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full group-hover/item:bg-red-500 group-hover/item:scale-125 transition-all"></span> 
                                <span class="font-medium tracking-wide">{{ $cat }}</span>
                            </a>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-white/5 flex justify-between items-center">
                        <span class="text-[10px] text-gray-500 italic uppercase tracking-widest">Khám phá {{ count($globalCategories) }} thể loại</span>
                        <a href="{{ route('books.index') }}" class="text-[10px] text-red-500 font-bold hover:underline uppercase tracking-tighter">Xem tất cả truyện →</a>
                    </div>
                </div>
            </div>
            <button id="theme-toggle" class="ml-6 p-2 rounded-full hover:bg-gray-500/20 transition-all duration-300 border border-transparent hover:border-red-500/30">
    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
</button>
        </nav>
    </div>
 <div class="flex items-center gap-4">
        @auth
            <a href="{{ route('profile.index') }}" class="text-white font-medium hover:text-red-400 transition hidden md:block">
                Xin chào, {{ Auth::user()->name }}
            </a>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.books.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded font-bold transition text-sm">Quản lý</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition text-sm">Đăng xuất</button>
            </form>
            <div class="flex items-center ml-4 mr-2">
    <button id="theme-toggle" class="p-2 rounded-full hover:bg-white/10 transition-all duration-300">
        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
    </button>
</div>
        @else
            <a href="/login" class="border border-gray-400 px-4 py-2 rounded hover:bg-gray-800 transition text-sm">Đăng nhập</a>
            <a href="/register" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition text-sm">Đăng ký</a>
        @endauth
    <div class="flex items-center">
        <form action="{{ route('books.search') }}" method="GET" class="relative group">
            <input type="text" name="query" placeholder="Tìm kiếm truyện..." 
                class="bg-white/5 border border-white/10 rounded-full px-6 py-2.5 w-64 md:w-80 focus:outline-none focus:border-red-500 focus:bg-white/10 transition-all text-sm text-white placeholder:text-gray-500">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 group-hover:text-red-500 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
</header>

<section class="relative min-h-[75vh] pt-24 overflow-hidden bg-[#050505] font-['Noto_Sans'] group">
    <div id="hero-slider" class="flex h-full" style="transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);">
        @php 
            $top5 = $trendingBooks->take(5);
            // Thêm bản sao của slide 1 vào cuối để tạo hiệu ứng vòng lặp vô tận
            $slides = $top5->concat([$top5->first()]);
        @endphp

        @foreach($slides as $index => $book)
    <div class="min-w-full h-full relative flex items-center px-6 md:px-16 flex-shrink-0">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/' . $book->cover_image) }}" 
                 class="w-full h-full object-cover opacity-20 blur-[80px] scale-110" alt="bg">
            <div class="absolute inset-0 bg-gradient-to-r from-[#141414] via-[#141414]/60 to-transparent"></div>
        </div>
        
        <div class="hero-content-wrapper w-full flex flex-col md:flex-row items-center relative z-10 gap-16 pb-32">
            <div class="space-y-6 flex-1"> 
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-red-600 text-white text-[11px] font-black uppercase tracking-widest shadow-lg">
                        NỔI BẬT #{{ ($index % 5) + 1 }}
                    </span>
                </div>
                
                <h2 class="text-5xl md:text-7xl font-black leading-tight text-white uppercase tracking-tighter">
                    {{ $book->title }}
                </h2>

                <div class="flex items-center gap-4 text-sm font-bold text-gray-300">
                    <span class="text-yellow-500">★ {{ number_format($book->averageRating(), 1) }}</span>
                    <span class="w-1 h-1 bg-gray-500 rounded-full"></span>
                    <span class="uppercase">{{ explode(',', $book->category)[0] }}</span>
                </div>

                <p class="text-lg text-gray-400 italic border-l-4 border-red-600 pl-6 max-w-2xl line-clamp-3">
                    "{{ $book->description }}"
                </p>

                <div class="flex items-center gap-4 pt-6">
                    <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => 1]) }}" 
                    class="group/btn relative inline-flex items-center gap-3 bg-white text-black font-black px-10 py-4 rounded-full overflow-hidden transition-all duration-300 hover:bg-red-600 hover:text-white uppercase text-xs tracking-[0.2em] shadow-[0_10px_30px_rgba(255,255,255,0.1)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Đọc ngay</span>
                    </a>

                    @if($book->chapters->whereNotNull('audio_path')->count() > 0)
                        <a href="{{ route('books.listen', $book->slug) }}" 
                        class="group/audio relative inline-flex items-center gap-3 bg-white/10 backdrop-blur-md text-white font-black px-10 py-4 rounded-full border border-white/20 transition-all duration-300 hover:bg-yellow-500 hover:text-black hover:border-yellow-500 hover:scale-105 uppercase text-xs tracking-[0.2em] shadow-lg">
                            
                            <div class="flex items-center gap-[3px] h-4 w-5">
                                <span class="w-[3px] bg-current rounded-full animate-wave-1"></span>
                                <span class="w-[3px] bg-current rounded-full animate-wave-2"></span>
                                <span class="w-[3px] bg-current rounded-full animate-wave-3"></span>
                            </div>

                            <span>Nghe ngay</span>
                        </a>
                        @endif
                </div>
            </div>
            
            <div class="hidden lg:block flex-shrink-0">
                <div class="animate-float">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                         class="hero-poster-img" 
                         alt="{{ $book->title }}">
                </div>
            </div>
        </div>
    </div>
@endforeach
    </div>
</section>
<div class="relative z-30 -mt-12"> 
    <div class="indicator-container" id="custom-indicators">
        @for($i = 0; $i < 5; $i++)
            <div class="indicator-bar" onclick="goToSlide({{ $i }})" style="height: 3px; width: 40px;"> <div class="indicator-progress" id="progress-{{ $i }}"></div>
            </div>
        @endfor
    </div>
</div>

<main class="relative z-20 mt-4 px-6 md:px-12 space-y-20 pb-36">

    <section class="group-carousel relative">
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-3xl font-bold flex items-center gap-2"><span class="w-1.5 h-8 bg-red-600 rounded-full"></span> Xu hướng</h3>
            <a href="{{ route('books.index') }}" class="text-sm text-gray-400 hover:text-red-500 transition font-bold uppercase tracking-wider">Xem tất cả truyện →</a>
        </div>
        
        <button onclick="scrollCarousel('carousel-trending', 'left')" class="carousel-btn absolute left-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-red-600 border border-white/10 text-white p-3 rounded-full ml-2 shadow-2xl focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button onclick="scrollCarousel('carousel-trending', 'right')" class="carousel-btn absolute right-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-red-600 border border-white/10 text-white p-3 rounded-full mr-2 shadow-2xl focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>

        <div id="carousel-trending" class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide">
            @foreach($trendingBooks as $book)
                <a href="{{ route('books.show', $book->slug) }}" class="w-[220px] flex-shrink-0 group">
                    <div class="relative aspect-[3/4] book-card-container rounded-2xl border border-gray-800 group-hover:border-red-500 transition-all shadow-2xl">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-bg-blur">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-main-img">
                        <div class="absolute bottom-2 left-2 z-20 px-3 py-1 bg-black/60 backdrop-blur-md rounded-lg border border-white/10">
                            <span class="text-2xl font-black italic text-red-500">#{{ $loop->iteration }}</span>
                        </div>
                        @if($book->status === 'completed' || $book->status === 'full')
                            <span class="status-label status-full">Full</span>
                        @else
                            <span class="status-label status-ongoing">Đang ra</span>
                        @endif
                    </div>
                    <div class="mt-4 px-1">
                        <h4 class="font-bold text-lg truncate group-hover:text-red-500 transition">{{ $book->title }}</h4>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-sm text-gray-500">{{ explode(',', $book->category)[0] }}</span>
                            <span class="text-yellow-500 font-bold text-sm">★ {{ number_format($book->averageRating(), 1) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>


    <section class="group-carousel relative">
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-3xl font-bold flex items-center gap-2">
                <span class="w-1.5 h-8 bg-blue-600 rounded-full"></span> Mới cập nhật
            </h3>
            <a href="{{ route('books.index') }}" class="text-sm text-gray-400 hover:text-blue-500 transition font-bold uppercase tracking-wider">Xem tất cả truyện mới →</a>
        </div>
        
        <button onclick="scrollCarousel('carousel-latest', 'left')" class="carousel-btn absolute left-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-blue-600 border border-white/10 text-white p-3 rounded-full ml-2 shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button onclick="scrollCarousel('carousel-latest', 'right')" class="carousel-btn absolute right-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-blue-600 border border-white/10 text-white p-3 rounded-full mr-2 shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>

        <div id="carousel-latest" class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide">
            @foreach($latestBooks as $book)
                <a href="{{ route('books.show', $book->slug) }}" class="w-[180px] flex-shrink-0 group">
                    <div class="relative aspect-[3/4] book-card-container rounded-2xl border border-gray-800 group-hover:border-blue-500 transition-all shadow-2xl">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-bg-blur">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-main-img">
                        <span class="status-label status-new flex items-center gap-1">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                            </span>
                            NEW
                        </span>
                    </div>
                    <div class="mt-3">
                        <h4 class="font-bold truncate group-hover:text-blue-400 transition">{{ $book->title }}</h4>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 italic truncate w-24">{{ $book->author }}</p>
                            <span class="text-yellow-500 font-bold text-sm">★ {{ number_format($book->averageRating(), 1) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>


    <section class="group-carousel relative">
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-3xl font-bold flex items-center gap-2"><span class="w-1.5 h-8 bg-green-500 rounded-full"></span> Truyện đã hoàn thành</h3>
            <a href="{{ route('books.index', ['status' => 'completed']) }}" class="text-sm text-gray-400 hover:text-green-500 transition font-bold uppercase tracking-wider">Xem tất cả truyện Full →</a>
        </div>

        <button onclick="scrollCarousel('carousel-completed', 'left')" class="carousel-btn absolute left-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-green-600 border border-white/10 text-white p-3 rounded-full ml-2 shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button onclick="scrollCarousel('carousel-completed', 'right')" class="carousel-btn absolute right-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-green-600 border border-white/10 text-white p-3 rounded-full mr-2 shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>

        <div id="carousel-completed" class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide">
            @foreach($completedBooks as $book)
                <a href="{{ route('books.show', $book->slug) }}" class="w-[180px] flex-shrink-0 group">
                    <div class="relative aspect-[3/4] book-card-container rounded-2xl border border-gray-800 group-hover:border-green-500 transition-all shadow-2xl">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-bg-blur">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-main-img">
                        <span class="status-label status-full">Full</span>
                    </div>
                    <div class="mt-4 px-1">
                        <h4 class="font-bold text-lg truncate group-hover:text-green-500 transition">{{ $book->title }}</h4>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-sm text-gray-500 italic truncate w-24">{{ $book->author }}</p>
                            <span class="text-yellow-500 font-bold text-sm">★ {{ number_format($book->averageRating(), 1) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section>
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-3xl font-bold flex items-center gap-2"><span class="w-1.5 h-8 bg-yellow-500 rounded-full"></span> Sách nói nổi bật</h3>
            <a href="{{ route('books.audio') }}" class="text-sm text-gray-400 hover:text-yellow-500 transition font-bold uppercase tracking-wider">Xem tất cả Sách nói →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($audioBooks as $book)
                <a href="{{ route('books.listen', $book->slug) }}" class="flex items-center gap-5 bg-white/5 p-5 rounded-2xl border border-gray-800 hover:bg-white/10 hover:border-yellow-500 transition-all group relative overflow-hidden">
                    <div class="absolute -top-1 -right-1 bg-yellow-500 text-black text-[10px] font-black px-3 py-1 rounded-bl-xl shadow-lg z-10">TOP {{ $loop->iteration }}</div>
                    <div class="w-24 h-32 flex-shrink-0 bg-black rounded-lg overflow-hidden flex items-center justify-center shadow-2xl">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-contain group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h4 class="font-bold text-xl truncate group-hover:text-yellow-500 transition">{{ $book->title }}</h4>
                        <p class="text-gray-500 text-sm mt-1 truncate">{{ $book->author }}</p>
                        <div class="mt-3 flex items-center gap-4">
                            <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                {{ number_format($book->audio_views ?? 0) }} lượt nghe
                            </div>
                        </div>
                        <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-500/10 text-yellow-500 rounded-full text-xs font-bold uppercase tracking-tighter group-hover:bg-yellow-500 group-hover:text-black transition-colors">🎧 Nghe ngay</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="group-carousel relative">
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-3xl font-bold flex items-center gap-2"><span class="w-1.5 h-8 bg-yellow-600 rounded-full"></span> Nội dung Premium</h3>
        </div>

        <button onclick="scrollCarousel('carousel-premium', 'left')" class="carousel-btn absolute left-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-yellow-600 border border-white/10 text-black p-3 rounded-full ml-2 shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button onclick="scrollCarousel('carousel-premium', 'right')" class="carousel-btn absolute right-0 top-[50%] -translate-y-1/2 z-30 bg-black/70 hover:bg-yellow-600 border border-white/10 text-black p-3 rounded-full mr-2 shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>

        <div id="carousel-premium" class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide">
           @foreach($premiumBooks as $book)
                <a href="{{ route('books.show', $book->slug) }}" class="w-[200px] flex-shrink-0 group">
                    <div class="relative aspect-[3/4] book-card-container rounded-2xl border border-gray-800 group-hover:border-yellow-500 transition-all shadow-2xl">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-bg-blur">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-main-img opacity-90">
                        <div class="absolute top-0 right-0 z-20 p-2">
                            <div class="bg-gradient-to-tr from-yellow-600 to-yellow-400 text-black text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase">PREMIUM</div>
                        </div>
                        @if($book->status === 'completed' || $book->status === 'full')
                            <span class="status-label status-full">Full</span>
                        @else
                            <span class="status-label status-ongoing">Đang ra</span>
                        @endif
                    </div>
                    <div class="mt-3 px-1 text-center">
                        <h4 class="font-bold truncate group-hover:text-yellow-500 transition">{{ $book->title }}</h4>
                        <span class="text-yellow-500 font-bold text-sm">★ {{ number_format($book->averageRating(), 1) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</main>
<script>
    let currentSlide = 0;
    const totalSlides = 5;
    const slider = document.getElementById('hero-slider');
    const duration = 5000; // 5 giây mỗi slide
    let startTime = Date.now();
    let slideTimer;

    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Chỉ cập nhật logic cho thanh indicator đỏ
        const bars = document.querySelectorAll('.indicator-bar');
        bars.forEach((bar, index) => {
            // Kiểm tra xem bar có tồn tại không để tránh lỗi
            if (bar) {
                bar.classList.toggle('active', index === currentSlide % totalSlides);
                const progressFill = document.getElementById(`progress-${index}`);
                if (index !== currentSlide % totalSlides && progressFill) {
                    progressFill.style.width = '0%';
                }
            }
        });
        
        startTime = Date.now();
    }

    function animateProgress() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min((elapsed / duration) * 100, 100);
        
        const currentProgressElement = document.getElementById(`progress-${currentSlide % totalSlides}`);
        if (currentProgressElement) {
            currentProgressElement.style.width = `${progress}%`;
        }

        if (elapsed >= duration) {
            nextSlide();
        }
        requestAnimationFrame(animateProgress);
    }

    function nextSlide() {
        currentSlide++;
        if (currentSlide >= totalSlides) {
            // Hiệu ứng lặp vô tận
            slider.style.transition = 'none';
            currentSlide = 0;
            slider.style.transform = `translateX(0)`;
            setTimeout(() => {
                slider.style.transition = 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1)';
            }, 50);
        }
        updateSlider();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlider();
    }

    // Khởi chạy
    updateSlider();
    requestAnimationFrame(animateProgress);

    // Tạm dừng khi hover
    const heroSection = document.querySelector('section.group');
    heroSection.addEventListener('mouseenter', () => {
        // Bạn có thể thêm logic dừng thời gian ở đây nếu muốn
    });
   const themeToggleBtn = document.getElementById('theme-toggle');
const darkIcon = document.getElementById('theme-toggle-dark-icon');
const lightIcon = document.getElementById('theme-toggle-light-icon');

// Kiểm tra trạng thái lưu trữ khi load trang
if (localStorage.getItem('theme') === 'light') {
    document.documentElement.classList.add('light');
    darkIcon.classList.remove('hidden');
} else {
    document.documentElement.classList.remove('light');
    lightIcon.classList.remove('hidden');
}

themeToggleBtn.addEventListener('click', function() {
    // Đảo ngược class light
    document.documentElement.classList.toggle('light');
    
    // Đổi icon tương ứng
    darkIcon.classList.toggle('hidden');
    lightIcon.classList.toggle('hidden');
    
    // Lưu trạng thái vào localStorage
    if (document.documentElement.classList.contains('light')) {
        localStorage.setItem('theme', 'light');
    } else {
        localStorage.setItem('theme', 'dark');
    }
});
function scrollCarousel(elementId, direction) {
    const container = document.getElementById(elementId);
    if (!container) return;
    
    // Tự động tính toán chiều rộng để trượt dựa trên kích cỡ màn hình hiện tại của user
    const scrollAmount = container.clientWidth * 0.75; 
    
    if (direction === 'left') {
        container.scrollLeft -= scrollAmount;
    } else {
        container.scrollLeft += scrollAmount;
    }
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