<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sách nói: {{ $book->title }} - PTBook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Tùy chỉnh thanh cuộn danh sách */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #dc2626; border-radius: 10px; }

        /* Quan trọng: Fix thanh Audio Player trải dài 100% */
        audio {
            width: 100%;
            height: 45px;
            filter: invert(100%) hue-rotate(180deg) brightness(1.5); /* Tạo giao diện dark mode cho trình phát mặc định */
        }
        
        /* Hiệu ứng kính mờ (Glassmorphism) */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-[#050505] text-white min-h-screen font-sans selection:bg-red-500">

<header class="bg-black/80 backdrop-blur-md border-b border-white/10 px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-50">
    <div class="flex items-center gap-4 md:gap-8">
        <a href="{{ route('home') }}" class="text-2xl md:text-3xl font-black text-red-600 tracking-tighter">PTBook</a>
        <nav class="hidden lg:flex items-center gap-10 text-sm text-gray-300 font-bold uppercase tracking-[0.2em]">
            <a href="/" class="hover:text-red-500 transition">Trang chủ</a>
            <a href="{{ route('books.index') }}" class="hover:text-red-500 transition">Kho sách</a>
            <a href="{{ route('books.audio') }}" class="hover:text-red-500 transition">Sách nói</a>
        </nav>
        <a href="{{ route('books.audio') }}" class="hidden md:flex items-center gap-2 text-gray-400 hover:text-yellow-500 transition-all text-[11px] font-bold uppercase tracking-widest border-l border-white/10 pl-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
            Tất cả sách nói
        </a>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('books.show', $book->slug) }}" class="text-gray-400 hover:text-white transition-all flex items-center gap-2 bg-white/5 px-4 py-2 rounded-full border border-white/10 text-[11px] font-bold uppercase tracking-tighter">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span class="hidden sm:inline">Thông tin sách</span>
            <span class="sm:hidden">Thông tin</span>
        </a>

        <a href="{{ route('books.audio') }}" class="md:hidden bg-yellow-500/10 border border-yellow-500/20 p-2 rounded-full text-yellow-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </a>
    </div>
</header>

<main class="max-w-[1400px] mx-auto px-6 py-8 md:py-12">
    <div class="grid lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div class="glass-panel rounded-[2.5rem] p-8 md:p-10 flex flex-col items-center shadow-2xl relative overflow-hidden">
                <div class="absolute -top-20 -left-20 w-80 h-80 bg-red-600/10 blur-[120px] rounded-full"></div>

                <div class="w-full max-w-[400px] aspect-square bg-gray-900 rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5 flex items-center justify-center p-2 mb-8">
                    <img id="book-cover" src="{{ asset('storage/' . $book->cover_image) }}" 
                         class="w-full h-full object-contain" 
                         alt="{{ $book->title }}">
                </div>

                <div class="text-center w-full mb-10">
                    <div class="flex items-center justify-center gap-2 mb-2 h-6">
                        <span id="status-dot" class="w-2 h-2 bg-gray-500 rounded-full transition-all duration-300"></span>
                        <span id="status-text" class="text-gray-500 text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                            Chưa phát audio
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black mb-1 tracking-tight">{{ $book->title }}</h2>
                    <p class="text-lg text-gray-500 font-medium mb-6 italic">{{ $book->author }}</p>
                    
                    <div class="inline-block px-6 py-3 bg-white/5 border border-white/10 rounded-2xl">
                        <span class="text-xs text-gray-500 block uppercase font-bold mb-1">Tên chương</span>
                        <h3 id="current-title" class="text-xl font-bold text-white">{{ $currentChapter->title }}</h3>
                    </div>
                </div>
                    <div class="flex flex-wrap justify-center gap-4 mt-6 py-4 border-y border-white/5">
                        <div class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full">
                            <span class="text-[10px] text-gray-500 font-bold uppercase">Tốc độ:</span>
                            <select id="playback-speed" onchange="changeSpeed(this.value)" class="bg-transparent text-xs font-bold text-red-500 outline-none cursor-pointer">
                                <option value="1" class="bg-black">x1.0</option>
                                <option value="1.25" class="bg-black">x1.25</option>
                                <option value="1.5" class="bg-black">x1.5</option>
                                <option value="2" class="bg-black">x2.0</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full group cursor-pointer" onclick="toggleAutoNext()">
                            <span class="text-[10px] text-gray-500 font-bold uppercase">Tự động chuyển:</span>
                            <div id="auto-next-toggle" class="w-8 h-4 bg-gray-700 rounded-full relative transition-colors duration-300">
                                <div id="auto-next-dot" class="absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform duration-300 transform translate-x-4 bg-red-500"></div>
                            </div>
                            <span id="auto-next-status" class="text-[10px] font-black text-red-500 uppercase">Bật</span>
                        </div>

                        <div class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full">
                            <span class="text-[10px] text-gray-500 font-bold uppercase">Hẹn giờ:</span>
                            <select id="sleep-timer" onchange="setSleepTimer(this.value)" class="bg-transparent text-xs font-bold text-red-500 outline-none cursor-pointer">
                                <option value="0" class="bg-black">Tắt</option>
                                <option value="15" class="bg-black">15 Phút</option>
                                <option value="30" class="bg-black">30 Phút</option>
                                <option value="60" class="bg-black">60 Phút</option>
                            </select>
                        </div>
                    </div>
                <div class="w-full bg-black/60 p-6 rounded-3xl border border-white/10 mt-8 shadow-2xl">
                    <audio id="audio-player">
                        <source id="audio-source" src="{{ asset('storage/' . $currentChapter->audio_path) }}" type="audio/mpeg">
                    </audio>

                    <div class="flex flex-col gap-5">
                        <div id="progress-container" class="w-full h-4 bg-gray-800/50 rounded-full cursor-pointer relative flex items-center group overflow-hidden">
                            <div id="progress-bar" class="h-full bg-red-600 rounded-full w-0 relative shadow-[0_0_15px_rgba(220,38,38,0.6)] pointer-events-none transition-all duration-75">
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 bg-white rounded-full shadow-lg scale-0 group-hover:scale-100 transition-transform"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-[10px] font-bold text-gray-500 uppercase tracking-widest px-1">
                            <span id="current-time">0:00</span>
                            
                            <div class="flex items-center gap-6 md:gap-8">
                                <button onclick="prevChapter()" class="text-gray-500 hover:text-red-500 transition-all transform active:scale-90" title="Chương trước">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.445 14.832A1 1 0 0010 14V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4zM16.445 14.832A1 1 0 0018 14V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z"/></svg>
                                </button>

                                <button onclick="rewind10s()" class="text-gray-400 hover:text-white transition-all transform active:scale-90" title="Lùi 10 giây">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.334 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z" />
                                    </svg>
                                    <span class="text-[8px] block -mt-1 font-bold">10S</span>
                                </button>
                                <button id="play-pause-btn" onclick="togglePlay()" class="bg-red-600 hover:bg-red-700 p-4 rounded-full shadow-xl transform active:scale-95 transition-all">
                                    <svg id="play-icon" class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                    <svg id="pause-icon" class="h-8 w-8 text-white hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5 0a1 1 0 012 0v4a1 1 0 11-2 0V8z" clip-rule="evenodd"/></svg>
                                </button>

                                <button onclick="forward10s()" class="text-gray-400 hover:text-white transition-all transform active:scale-90" title="Tiến 10 giây">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.934 12.8a1 1 0 000-1.6l-5.334-4A1 1 0 005 8v8a1 1 0 001.6.8l5.334-4zM19.934 12.8a1 1 0 000-1.6l-5.334-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.334-4z" />
                                    </svg>
                                    <span class="text-[8px] block -mt-1 font-bold">10S</span>
                                </button>

                                <button onclick="nextChapter()" class="text-gray-500 hover:text-red-500 transition-all transform active:scale-90" title="Chương sau">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4zM11.555 5.168A1 1 0 0010 6v8a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4z"/></svg>
                                </button>
                            </div>

                            <span id="duration">0:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 h-full">
            <div class="glass-panel rounded-[2.5rem] flex flex-col h-[750px] shadow-xl overflow-hidden">
                <div class="p-6 border-b border-white/10 bg-white/5 flex items-center justify-between">
                    <h3 class="text-xl font-black flex items-center gap-3">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                        Danh sách audio
                    </h3>
                    
                    <div class="flex items-center gap-3">
                        <button id="repeat-btn" onclick="toggleRepeat()" class="group flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-xl hover:border-yellow-500/50 transition-all active:scale-95 relative" title="Chế độ phát lại">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 group-hover:text-yellow-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span id="repeat-label" class="text-[10px] font-bold text-gray-500 group-hover:text-yellow-500 uppercase tracking-tighter">Tắt lặp</span>
                            <span id="repeat-badge" class="absolute -top-1 -right-1 bg-yellow-500 text-black text-[8px] font-black px-1 rounded-full hidden">1</span>
                        </button>

                        <span class="text-[10px] font-bold bg-white/10 text-white px-3 py-1.5 rounded-xl uppercase tracking-tighter border border-white/5">
                            {{ $book->chapters->whereNotNull('audio_path')->count() }} tập
                        </span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 custom-scrollbar space-y-3">
                    @foreach($book->chapters->whereNotNull('audio_path')->sortBy('order_number') as $chapter)
                        <button 
                            onclick="changeChapter('{{ asset('storage/' . $chapter->audio_path) }}', '{{ $chapter->title }}', this)"
                            class="playlist-item w-full flex items-center gap-4 p-4 rounded-2xl border transition-all duration-500 group
                            {{ $chapter->id == $currentChapter->id ? 'bg-red-600/10 border-red-500/50' : 'bg-transparent border-transparent hover:bg-white/5' }}">
                            
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-sm transition-all
                                {{ $chapter->id == $currentChapter->id ? 'bg-red-600 text-white scale-110 shadow-lg shadow-red-600/20' : 'bg-white/5 text-gray-500 group-hover:text-white' }}">
                                {{ $chapter->order_number }}
                            </div>
                            
                            <div class="flex-1 text-left">
                                <h4 class="font-bold text-sm leading-tight {{ $chapter->id == $currentChapter->id ? 'text-white' : 'text-gray-400' }} group-hover:text-white">{{ $chapter->title }}</h4>
                                <p class="text-[10px] text-gray-600 mt-1 uppercase font-bold tracking-widest group-hover:text-red-500 transition-colors">Sẵn sàng phát</p>
                            </div>

                            <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                </svg>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    let currentBlobUrl = null;
    let sleepTimerId = null;
    let repeatMode = 0;
    let isAutoNext = true; // Mặc định là bật
    let viewCounted = false;
    // Khai báo các phần tử
    const player = document.getElementById('audio-player');
    const progressBar = document.getElementById('progress-bar');
    const progressContainer = document.getElementById('progress-container');
    const currentTimeEl = document.getElementById('current-time');
    const durationEl = document.getElementById('duration');
    const playIcon = document.getElementById('play-icon');
    const pauseIcon = document.getElementById('pause-icon');
    const statusText = document.getElementById('status-text');
    const statusDot = document.getElementById('status-dot');

    // --- XỬ LÝ TRẠNG THÁI ---
    player.onplay = () => {
        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');
        statusText.innerText = "Đang phát audio";
        statusText.style.color = "#ef4444";
        statusDot.style.backgroundColor = "#ef4444";
        statusDot.classList.add('animate-pulse');

        // GỌI API CẬP NHẬT LƯỢT NGHE
        if (!viewCounted) {
            incrementView();
        }
    };

    player.onpause = () => {
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        if (player.currentTime < player.duration) {
            statusText.innerText = "Tạm dừng audio";
            statusText.style.color = "#eab308";
            statusDot.style.backgroundColor = "#eab308";
            statusDot.classList.remove('animate-pulse');
        }
    };

    player.onended = () => {
        statusText.innerText = "Chưa phát audio";
        statusText.style.color = "#6b7280";
        statusDot.style.backgroundColor = "#6b7280";
        statusDot.classList.remove('animate-pulse');
        nextChapter();
    };

    // --- ĐIỀU KHIỂN ---
    function togglePlay() {
        if (player.paused) player.play();
        else player.pause();
    }

    // --- TÍnh năng tự động chuyển chương ---
    function toggleAutoNext() {
        isAutoNext = !isAutoNext;
        const toggleBg = document.getElementById('auto-next-toggle');
        const toggleDot = document.getElementById('auto-next-dot');
        const statusText = document.getElementById('auto-next-status');

        if (isAutoNext) {
            toggleDot.classList.add('translate-x-4');
            toggleDot.classList.add('bg-red-500');
            toggleBg.classList.replace('bg-gray-700', 'bg-red-600/20');
            statusText.innerText = "Bật";
            statusText.classList.replace('text-gray-500', 'text-red-500');
        } else {
            toggleDot.classList.remove('translate-x-4');
            toggleDot.classList.remove('bg-red-500');
            toggleBg.classList.replace('bg-red-600/20', 'bg-gray-700');
            statusText.innerText = "Tắt";
            statusText.classList.replace('text-red-500', 'text-gray-500');
        }
    }

    // FIX TUA 10S: Đảm bảo thực hiện thay đổi currentTime một cách trực tiếp
    function rewind10s() {
        if (player && !isNaN(player.duration)) {
            player.currentTime = Math.max(0, player.currentTime - 10);
        }
    }

    function forward10s() {
        if (player && !isNaN(player.duration)) {
            player.currentTime = Math.min(player.duration, player.currentTime + 10);
        }
    }

    // Cập nhật thanh tiến trình
    player.addEventListener('timeupdate', () => {
        if (player.duration && !isNaN(player.duration)) {
            const percent = (player.currentTime / player.duration) * 100;
            progressBar.style.width = percent + "%";
            currentTimeEl.innerText = formatTime(player.currentTime);
        }
    });

    player.addEventListener('loadedmetadata', () => {
        durationEl.innerText = formatTime(player.duration);
    });

    // Tua bằng cách click thanh progress
    progressContainer.addEventListener('mousedown', function(e) {
        const rect = this.getBoundingClientRect();
        const offsetX = e.clientX - rect.left;
        const totalWidth = rect.width;
        if (player.duration && !isNaN(player.duration)) {
            player.currentTime = (offsetX / totalWidth) * player.duration;
        }
    });

    // --- CHƯƠNG ---
    async function changeChapter(url, title, element) {
        // 1. Xóa trạng thái "đang phát" của tất cả các chương khác
        document.querySelectorAll('.playlist-item').forEach(item => {
            // Trả về trạng thái mặc định (trong suốt)
            item.classList.remove('bg-red-600/20', 'border-red-500/50', 'ring-1', 'ring-red-500/30');
            item.classList.add('bg-transparent', 'border-transparent');
            
            // Reset số thứ tự chương về màu xám
            const numBox = item.querySelector('div:first-child');
            if (numBox) {
                numBox.classList.remove('bg-red-600', 'text-white', 'scale-110', 'shadow-[0_0_15px_rgba(220,38,38,0.4)]');
                numBox.classList.add('bg-white/5', 'text-gray-500');
            }
            
            // Reset tiêu đề chương về màu xám
            const h4 = item.querySelector('h4');
            if(h4) h4.classList.replace('text-white', 'text-gray-400');
        });

        // 2. Tô đỏ chương hiện tại (Active State)
        element.classList.remove('bg-transparent', 'border-transparent');
        element.classList.add('bg-red-600/20', 'border-red-500/50', 'ring-1', 'ring-red-500/30');
        
        const activeNum = element.querySelector('div:first-child');
        if (activeNum) {
            activeNum.classList.remove('bg-white/5', 'text-gray-500');
            activeNum.classList.add('bg-red-600', 'text-white', 'scale-110', 'shadow-[0_0_15px_rgba(220,38,38,0.4)]');
        }
        
        const activeH4 = element.querySelector('h4');
        if(activeH4) activeH4.classList.replace('text-gray-400', 'text-white');

        // 3. Xử lý Audio Source
        try {
            statusText.innerText = "Đang tải dữ liệu...";
            
            // Giải phóng Blob cũ nếu có để tránh tràn bộ nhớ RAM
            if (currentBlobUrl) {
                URL.revokeObjectURL(currentBlobUrl);
            }

            const response = await fetch(url);
            const blob = await response.blob();
            currentBlobUrl = URL.createObjectURL(blob);

            player.src = currentBlobUrl;
            document.getElementById('current-title').innerText = title;
            
            player.load();
            player.play().catch(e => console.log("Chờ tương tác người dùng để phát"));
            
        } catch (e) {
            console.error("Lỗi tải audio:", e);
            // Fallback: Nếu lỗi Blob thì dùng URL trực tiếp
            player.src = url;
            player.load();
            player.play();
        }
    }

    function nextChapter() {
        const items = Array.from(document.querySelectorAll('.playlist-item'));
        const currentIndex = items.findIndex(item => item.classList.contains('bg-red-600/10'));
        if (currentIndex !== -1 && currentIndex < items.length - 1) {
            items[currentIndex + 1].click();
        }
    }

    function prevChapter() {
        const items = Array.from(document.querySelectorAll('.playlist-item'));
        const currentIndex = items.findIndex(item => item.classList.contains('bg-red-600/10'));
        if (currentIndex > 0) {
            items[currentIndex - 1].click();
        }
    }

    function formatTime(time) {
        if (isNaN(time)) return "0:00";
        const min = Math.floor(time / 60);
        const sec = Math.floor(time % 60);
        return min + ":" + (sec < 10 ? '0' + sec : sec);
    }

    // --- TÍNH NĂNG 1: TỐC ĐỘ PHÁT ---
    function changeSpeed(speed) {
        player.playbackRate = parseFloat(speed);
    }

    // --- TÍNH NĂNG 2: HẸN GIỜ TẮT ---
    function setSleepTimer(minutes) {
        if (sleepTimerId) clearTimeout(sleepTimerId);
        
        const mins = parseInt(minutes);
        if (mins > 0) {
            alert(`Audio sẽ tự động tắt sau ${mins} phút nữa.`);
            sleepTimerId = setTimeout(() => {
                player.pause();
                alert("Đã hết thời gian hẹn giờ. Audio đã dừng.");
                document.getElementById('sleep-timer').value = "0";
            }, mins * 60 * 1000);
        }
    }

    // --- TÍNH NĂNG 3: LƯU VỊ TRÍ (LocalStorage) ---

    // Lưu vị trí mỗi 5 giây khi đang phát
    player.ontimeupdate = () => {
        // Logic cập nhật thanh progress cũ của bạn giữ nguyên
        if (player.duration) {
            const percent = (player.currentTime / player.duration) * 100;
            progressBar.style.width = percent + "%";
            currentTimeEl.innerText = formatTime(player.currentTime);
            
            // Lưu vào LocalStorage dựa trên ID chương (VD: audio_pos_3)
            const chapterId = "{{ $currentChapter->id }}";
            localStorage.setItem(`audio_pos_${chapterId}`, player.currentTime);
        }
    };

    // Khôi phục vị trí khi audio sẵn sàng
    player.onloadedmetadata = () => {
        durationEl.innerText = formatTime(player.duration);
        
        const chapterId = "{{ $currentChapter->id }}";
        const savedPos = localStorage.getItem(`audio_pos_${chapterId}`);
        
        if (savedPos) {
            player.currentTime = parseFloat(savedPos);
            // Tùy chọn: alert(`Đang nghe tiếp từ ${formatTime(savedPos)}`);
        }
    };
    // --- TÍNH NĂNG 4: CHẾ ĐỘ LẶP ---
    function toggleRepeat() {
        const btn = document.getElementById('repeat-btn');
        const label = document.getElementById('repeat-label');
        const badge = document.getElementById('repeat-badge');
        const svg = btn.querySelector('svg');
        
        repeatMode = (repeatMode + 1) % 3; 

        if (repeatMode === 0) {
            label.innerText = "Tắt lặp";
            label.className = "text-[10px] font-bold text-gray-500 uppercase tracking-tighter";
            svg.className = "h-4 w-4 text-gray-500 transition-colors";
            btn.classList.remove('border-yellow-500/50', 'bg-yellow-500/5');
            badge.classList.add('hidden');
            player.loop = false;
        } else if (repeatMode === 1) {
            label.innerText = "Lặp chương";
            label.className = "text-[10px] font-bold text-yellow-500 uppercase tracking-tighter";
            svg.className = "h-4 w-4 text-yellow-500 transition-colors";
            btn.classList.add('border-yellow-500/50', 'bg-yellow-500/5');
            badge.innerText = "1";
            badge.classList.remove('hidden');
            player.loop = false; 
        } else if (repeatMode === 2) {
            label.innerText = "Lặp vô tận";
            badge.innerText = "∞";
            player.loop = true; 
        }
    }

    // Cập nhật lại sự kiện kết thúc của trình phát
    player.onended = () => {
        if (repeatMode === 1) {
            repeatMode = 0; // Reset trạng thái sau khi lặp xong 1 lần
            toggleRepeat(); // Gọi lại hàm để cập nhật giao diện về "Tắt lặp"
            player.currentTime = 0;
            player.play();
        } else if (repeatMode === 2) {
            // Trình duyệt tự xử lý vì loop = true
        } else {
            // Logic mặc định của bạn: Chuyển chương
            nextChapter();
        }
    };
    async function incrementView() {
        try {
            const response = await fetch("{{ route('books.audio.increment', $book->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                viewCounted = true; // Đã ghi nhận, không gửi lại lần nữa cho đến khi load trang mới
                console.log("Đã cập nhật lượt nghe lên hệ thống.");
            }
        } catch (error) {
            console.error("Lỗi khi cập nhật lượt nghe:", error);
        }
    }
</script>

</body>
</html>