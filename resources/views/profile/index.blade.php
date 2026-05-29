<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - PTBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; letter-spacing: -0.01em; }
        .divider-gradient {
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.1), transparent);
        }
    </style>
</head>
<body class="bg-[#0a0a0a] text-gray-200 min-h-screen">

<header class="bg-black/80 backdrop-blur-md border-b border-white/5 px-8 py-4 flex justify-between items-center sticky top-0 z-50">
    <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity">
        <h1 class="text-2xl font-black text-red-600 italic tracking-tighter">PTBook</h1>
    </a>
    <div class="flex items-center gap-6">
        <nav class="hidden md:flex gap-8 text-[11px] font-bold uppercase tracking-[0.2em] text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Trang chủ</a>
            <a href="{{ route('books.index') }}" class="hover:text-white transition">Kho sách</a>
            <a href="{{ route('books.audio') }}" class="hover:text-white transition">Sách nói</a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-white text-black hover:bg-red-600 hover:text-white px-5 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all">
                Đăng xuất
            </button>
        </form>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-12">

    <section class="bg-[#111] border border-white/5 rounded-[2rem] p-8 mb-12 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/5 blur-[100px] -z-10"></div>
        
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center text-4xl font-black text-white shadow-xl shadow-red-900/20 transform -rotate-2">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <div class="flex flex-col md:flex-row items-center gap-4 mb-2">
                        <h2 class="text-3xl font-extrabold text-white tracking-tight">{{ $user->name }}</h2>
                        @if($user->is_vip)
                            <span class="px-3 py-1 bg-yellow-500 text-black text-[10px] font-black rounded-full uppercase tracking-widest">👑 VIP MEMBER</span>
                        @else
                            <span class="px-3 py-1 bg-white/5 text-gray-500 text-[10px] font-black rounded-full uppercase tracking-widest border border-white/10">FREE MEMBER</span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    @if($user->is_vip && $user->vip_expires_at)
                        <p class="text-yellow-500/80 text-[10px] mt-2 font-bold uppercase tracking-wider">
                            Hạn dùng: {{ \Carbon\Carbon::parse($user->vip_expires_at)->format('d/m/Y') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex gap-4 w-full md:w-auto">
                <div class="flex-1 md:w-28 bg-white/[0.03] rounded-2xl p-4 border border-white/5 text-center">
                    <p class="text-[9px] text-gray-500 font-bold uppercase mb-1 tracking-tighter">Đã đọc</p>
                    <h3 class="text-2xl font-black text-green-500">{{ $totalRead }}</h3>
                </div>
                <div class="flex-1 md:w-28 bg-white/[0.03] rounded-2xl p-4 border border-white/5 text-center">
                    <p class="text-[9px] text-gray-500 font-bold uppercase mb-1 tracking-tighter">Đã tải</p>
                    <h3 class="text-2xl font-black text-blue-500">{{ $totalDownloaded }}</h3>
                </div>
            </div>
        </div>

        @if(!$user->is_vip)
            <div class="mt-12 pt-10 border-t border-white/5">
                <h3 class="text-sm font-bold text-white mb-6 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-1 h-4 bg-red-600 rounded-full"></span> Nâng cấp Premium
                </h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-6 bg-white/[0.02] rounded-3xl border border-white/5 hover:border-pink-500/30 transition-all group">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-1">Gói 30 ngày</h4>
                        <p class="text-2xl font-black text-white mb-5">50.000đ</p>
                        <a href="{{ route('payment.momo', 'monthly') }}" class="flex items-center justify-center gap-3 w-full bg-[#ae2070] py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest hover:brightness-110 transition-all shadow-lg active:scale-95">
                            <img src="https://developers.momo.vn/v3/assets/images/logo-custom-48403487019685655548.png" class="w-6 h-6 object-contain" alt="Momo">
                            Thanh toán MoMo
                        </a>
                    </div>

                    <div class="p-6 bg-yellow-500/[0.02] rounded-3xl border border-yellow-500/10 hover:border-yellow-500/40 transition-all relative overflow-hidden group">
                        <div class="absolute top-0 right-0 bg-yellow-500 text-black text-[9px] px-3 py-1 font-black rounded-bl-xl">TIẾT KIỆM 20%</div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-1">Gói 12 tháng</h4>
                        <p class="text-2xl font-black text-white mb-5">500.000đ</p>
                        <a href="{{ route('payment.momo', 'yearly') }}" class="flex items-center justify-center gap-3 w-full bg-[#ae2070] py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest hover:brightness-110 transition-all shadow-lg active:scale-95">
                            <img src="https://developers.momo.vn/v3/assets/images/logo-custom-48403487019685655548.png" class="w-6 h-6 object-contain" alt="Momo">
                            Thanh toán MoMo
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <div class="relative grid lg:grid-cols-2 gap-12 lg:gap-0">
        <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px divider-gradient -translate-x-1/2"></div>

        <section class="lg:pr-12">
            <h2 class="text-xl font-black mb-8 flex items-center gap-3 text-white">
                <span class="text-green-500 text-2xl">📖</span> SÁCH ĐÃ ĐỌC
            </h2>
            @if($readingBooks->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-5">
                    @foreach($readingBooks as $item)
                        @if($item->book)
                            <a href="{{ route('books.show', $item->book->slug) }}" class="group">
                                <div class="bg-white/5 rounded-2xl overflow-hidden border border-white/5 group-hover:border-green-500/50 transition-all duration-500">
                                    <div class="aspect-[3/4] overflow-hidden bg-gray-900">
                                        <img src="{{ $item->book->cover_image ? asset('storage/' . $item->book->cover_image) : 'https://via.placeholder.com/300x400?text=No+Cover' }}" 
                                             onerror="this.src='https://via.placeholder.com/300x400?text=Error'"
                                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                    </div>
                                    <div class="p-3">
                                        <h3 class="font-bold text-[13px] line-clamp-1 group-hover:text-green-400 transition">{{ $item->book->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="mt-8">{{ $readingBooks->links() }}</div>
            @else
                <div class="py-16 text-center bg-white/[0.02] rounded-3xl border border-dashed border-white/10 text-gray-600 text-xs font-medium uppercase tracking-widest">Chưa có lịch sử đọc</div>
            @endif
        </section>

        <section class="lg:pl-12">
            <h2 class="text-xl font-black mb-8 flex items-center gap-3 text-white">
                <span class="text-blue-500 text-2xl">⬇️</span> SÁCH ĐÃ TẢI
            </h2>
            @if($downloadedBooks->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-5">
                    @foreach($downloadedBooks as $item)
                        @if($item->book)
                            <a href="{{ route('books.show', $item->book->slug) }}" class="group">
                                <div class="bg-white/5 rounded-2xl overflow-hidden border border-white/5 group-hover:border-blue-500/50 transition-all duration-500">
                                    <div class="aspect-[3/4] overflow-hidden bg-gray-900">
                                        <img src="{{ $item->book->cover_image ? asset('storage/' . $item->book->cover_image) : 'https://via.placeholder.com/300x400?text=No+Cover' }}" 
                                             onerror="this.src='https://via.placeholder.com/300x400?text=Error'"
                                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                    </div>
                                    <div class="p-3">
                                        <h3 class="font-bold text-[13px] line-clamp-1 group-hover:text-blue-400 transition">{{ $item->book->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="mt-8">{{ $downloadedBooks->links() }}</div>
            @else
                <div class="py-16 text-center bg-white/[0.02] rounded-3xl border border-dashed border-white/10 text-gray-600 text-xs font-medium uppercase tracking-widest">Chưa có sách đã tải</div>
            @endif
        </section>
    </div>
</main>

<footer class="px-8 py-12 text-gray-600 text-[11px] border-t border-white/5 bg-black/40 mt-20">
    <div class="max-w-7xl mx-auto text-center flex flex-col items-center justify-center space-y-4">
        <h2 class="text-red-600 text-xl font-black tracking-tighter opacity-40 italic">PTBook</h2>
        <p class="font-bold uppercase tracking-[0.2em]">© 2026 PTBook — Explore. Read. Evolve.</p>
    </div>
</footer>

</body>
</html>