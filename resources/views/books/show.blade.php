<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - PTBook</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">   @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

<header class="bg-black/80 backdrop-blur-md border-b border-gray-800 px-8 py-5 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="hover:opacity-80 transition">
            <h1 class="text-3xl font-bold text-red-500 tracking-tighter">PTBook</h1>
        </a>
        <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Quay lại thư viện
        </a>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid md:grid-cols-3 gap-12">
        <div class="relative group">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full rounded-2xl shadow-2xl border border-gray-800 transition duration-300 group-hover:border-red-500">
            @else
                <div class="aspect-[3/4] bg-gray-900 rounded-2xl flex items-center justify-center text-gray-600 border border-dashed border-gray-700">Không có ảnh bìa</div>
            @endif
        </div>

        <div class="md:col-span-2 space-y-8">
    <div>
        <h2 class="text-5xl font-[800] leading-tight mb-4 text-white tracking-tight uppercase font-roboto">
    {{ $book->title }}
</h2> <p class="text-2xl text-gray-400 italic">Tác giả: {{ $book->author ?: 'Đang cập nhật' }}</p>
        
        <div class="flex items-center gap-4 mt-4">
            <div class="flex text-yellow-500">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-600' }}" 
                        viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-white">{{ number_format($averageRating, 1) }}</span>
                <span class="text-gray-500 text-sm">/ 5</span>
                <span class="text-gray-400 text-xs ml-2">({{ $totalRatings }} lượt đánh giá)</span>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        @if($book->category)
            @foreach(explode(',', $book->category) as $tag)
                <span class="px-5 py-2 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 font-medium text-sm">
                    {{ trim($tag) }}
                </span>
            @endforeach
        @endif

        @if($book->status == 'completed' || $book->status == 'full')
            <span class="px-5 py-2 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-medium text-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                Hoàn thành
            </span>
        @else
            <span class="px-5 py-2 rounded-full bg-green-500/10 text-green-400 border border-green-500/20 font-medium text-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-bounce"></span>
                Đang ra
            </span>
        @endif

        <span class="px-5 py-2 rounded-full {{ $book->is_premium ? 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20' : 'bg-green-500/10 text-green-500 border border-green-500/20' }} font-medium text-sm">
            {{ $book->is_premium ? 'Premium' : 'Miễn phí' }}
        </span>
    </div>

            <div class="flex gap-10 py-4 border-y border-gray-800/50">
                <div class="text-center md:text-left">
                    <p class="text-gray-500 text-sm uppercase tracking-widest">Lượt xem</p>
                    <p class="text-xl font-bold">👁 {{ number_format($book->views) }}</p>
                </div>
                <div class="text-center md:text-left">
                    <p class="text-gray-500 text-sm uppercase tracking-widest">Lượt tải</p>
                    <p class="text-xl font-bold">⬇ {{ number_format($book->downloads) }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-gray-100 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Giới thiệu nội dung
                </h3>
                <p class="text-gray-400 leading-relaxed text-lg whitespace-pre-line bg-gray-900/30 p-4 rounded-xl border border-gray-800/50 italic">
                    "{{ $book->description ?: 'Chưa có mô tả cho tác phẩm này.' }}"
                </p>
            </div>

            <div class="space-y-4">
                @if($book->is_premium && (!auth()->check() || !auth()->user()->is_vip))
                    <div class="bg-gradient-to-r from-yellow-500/20 to-transparent border-l-4 border-yellow-500 p-6 rounded-r-2xl mb-4">
                        <div class="flex items-center gap-4">
                            <span class="text-4xl">👑</span>
                            <div>
                                <h4 class="text-yellow-500 font-black text-lg uppercase">Nội dung Premium</h4>
                                <p class="text-gray-400 text-sm">Bạn cần tài khoản VIP để mở khóa toàn bộ tác phẩm này.</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" 
                        class="mt-4 inline-block bg-yellow-500 hover:bg-yellow-400 text-black font-black px-6 py-2 rounded-lg transition transform hover:scale-105 shadow-lg shadow-yellow-500/20">
                            NÂNG CẤP VIP NGAY
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 opacity-50">
                        <button disabled class="flex-1 bg-gray-800 text-gray-500 py-4 rounded-xl font-black text-xl flex items-center justify-center gap-2 cursor-not-allowed">
                            🔒 BẮT ĐẦU ĐỌC
                        </button>
                        <button disabled class="flex-1 bg-gray-800 text-gray-500 py-4 rounded-xl font-bold text-xl border border-gray-700 cursor-not-allowed">
                            🔒 TẢI XUỐNG
                        </button>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => 1]) }}"
                        class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-black py-4 rounded-xl font-black text-xl transition transform hover:-translate-y-1 shadow-lg shadow-yellow-500/20">
                            📖 BẮT ĐẦU ĐỌC
                        </a>
                        <a href="{{ route('books.download', $book->slug) }}"
                        class="flex-1 text-center bg-gray-800 hover:bg-gray-700 text-white py-4 rounded-xl font-bold text-xl transition border border-gray-700">
                            ⬇ TẢI XUỐNG
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-10 bg-black/40 border border-gray-800 rounded-2xl p-6">
    <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <span class="text-red-500">☰</span> Danh sách chương
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($book->chapters->sortBy('order_number') as $chapter)
            <div class="flex items-center justify-between bg-gray-900/50 p-4 rounded-xl border border-gray-800 {{ $book->is_premium && (!auth()->check() || !auth()->user()->is_vip) ? 'opacity-75' : 'hover:border-red-500' }} transition group">
                
                @if($book->is_premium && (!auth()->check() || !auth()->user()->is_vip))
                    <button onclick="showVipModal()" class="flex-1 flex text-left italic">
                        <span class="text-gray-500 mr-2">Chương {{ $chapter->order_number }}</span>
                        <span class="text-gray-500">🔒 Nội dung Premium</span>
                    </button>
                @else
                    <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => $chapter->order_number]) }}" class="flex-1">
                        <span class="text-gray-400 mr-2">Chương {{ $chapter->order_number }}</span>
                        <span class="font-medium group-hover:text-red-500">{{ $chapter->title }}</span>
                    </a>
                @endif

                {{-- Nút nghe Audio --}}
                @if($chapter->audio_path)
                    @if($book->is_premium && (!auth()->check() || !auth()->user()->is_vip))
                        <button onclick="showVipModal()" class="ml-4 bg-gray-800 text-gray-600 p-2 rounded-full cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217z" />
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('books.listen', ['book' => $book->slug, 'chapter' => $chapter->id]) }}" 
                        class="ml-4 bg-yellow-500/10 text-yellow-500 p-2 rounded-full hover:bg-yellow-500 hover:text-black transition shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217z" />
                            </svg>
                        </a>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
        <!-- @if($book->chapters->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($book->chapters->sortBy('order_number') as $chapter)
                    <a href="{{ route('books.read', ['book' => $book->slug, 'chapter' => $chapter->order_number]) }}" 
                       class="group bg-gray-900/40 border border-gray-800 hover:border-red-500 p-4 rounded-xl transition-all duration-300 flex items-center justify-between">
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-xs font-bold text-red-500 uppercase tracking-tighter mb-1">Chương {{ $chapter->order_number }}</span>
                            <span class="text-gray-300 group-hover:text-white transition font-medium truncate">{{ $chapter->title }}</span>
                        </div>
                        <div class="bg-gray-800 group-hover:bg-red-500 p-2 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div> -->
        @else
            <div class="text-center py-20 bg-gray-900/20 rounded-3xl border border-dashed border-gray-800">
                <p class="text-gray-500 text-lg italic">Nội dung đang được cập nhật, vui lòng quay lại sau...</p>
            </div>
        @endif
    </div>
<section class="mt-16 bg-gray-900/40 rounded-3xl border border-gray-800 p-8">
    <h3 class="text-[17px] font-bold text-red-500 mb-3 flex items-center gap-2 tracking-[0.2em] uppercase">
        <span class="w-2 h-6 bg-red-500 rounded-full"></span>
        Đánh giá từ độc giả
    </h3>

    @auth
    <form action="{{ route('books.comment', $book->id) }}" method="POST" class="bg-gray-800/20 p-6 rounded-2xl border border-gray-700 mb-10">
        @csrf
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Chạm để đánh giá sao:</label>
            <div class="flex flex-row-reverse justify-end gap-2 text-3xl">
                @for($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{$i}}" name="stars" value="{{$i}}" class="hidden peer" required>
                    <label for="star{{$i}}" class="cursor-pointer text-gray-700 peer-hover:text-yellow-400 peer-checked:text-yellow-500 transition-colors">★</label>
                @endfor
            </div>
        </div>

        <textarea name="content" required rows="3" placeholder="Viết bình luận của bạn..." 
                  class="w-full bg-gray-950 border border-gray-800 rounded-xl p-4 text-gray-200 focus:border-red-500 outline-none transition resize-none mb-4 shadow-inner"></textarea>
        
        <button type="submit" class="bg-red-600 hover:bg-red-700 px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-red-600/20 transform hover:scale-105"> 
            GỬI ĐÁNH GIÁ 
        </button>
    </form>
    @else
    <div class="bg-gray-800/10 border border-dashed border-gray-700 rounded-2xl p-6 text-center mb-10">
        <p class="text-gray-400">Vui lòng <a href="{{ route('login') }}" class="text-red-500 font-bold hover:underline">đăng nhập</a> để để lại đánh giá.</p>
    </div>
    @endauth

    <div class="space-y-6">
        @forelse($book->comments as $comment)
        <div class="flex gap-4 p-5 rounded-2xl bg-gray-800/10 border border-gray-800/50 hover:border-gray-700 transition group">
            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-gray-700 to-gray-600 flex items-center justify-center font-bold text-white uppercase flex-shrink-0 shadow-lg">
                {{ substr($comment->user->name, 0, 1) }}
            </div>
            
            <div class="flex-1">
                <div class="flex justify-between items-start mb-1">
                    <div>
                        <h4 class="font-bold text-red-400 inline-block mr-2">{{ $comment->user->name }}</h4>
                        @php $userRating = $book->ratings->where('user_id', $comment->user_id)->first(); @endphp
                        @if($userRating)
                            <div class="inline-flex text-yellow-500 text-xs">
                                @for($i=1; $i<=5; $i++)
                                    <span>{{ $i <= $userRating->stars ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        @endif
                    </div>
                    <span class="text-[10px] text-gray-500 font-medium uppercase">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-gray-300 text-sm leading-relaxed">"{{ $comment->content }}"</p>
            </div>
        </div>
        @empty
        <div class="text-center py-10 opacity-20 italic">Chưa có bình luận nào...</div>
        @endforelse
    </div>
</section>
</section>
    @if($relatedBooks->count())
        <section class="mt-24 pt-16 border-t border-gray-900">
            <h3 class="text-3xl font-black mb-10">Có thể bạn sẽ thích</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($relatedBooks as $related)
                    <a href="{{ route('books.show', $related->slug) }}" class="group space-y-4">
                        <div class="aspect-[3/4] bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 group-hover:border-red-500 transition-all">
                            @if($related->cover_image)
                                <img src="{{ asset('storage/' . $related->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-lg line-clamp-1 group-hover:text-red-500 transition">{{ $related->title }}</h4>
                            <p class="text-sm text-gray-500">{{ $related->author }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</main>

<footer class="px-8 py-16 text-gray-600 text-sm border-t border-gray-900 bg-black/20">
    </footer>

<script>
    window.showVipModal = function() {
        const modal = document.getElementById('vip-modal');
        if (modal) {
            modal.style.display = 'flex'; // Ép kiểu display thay vì dùng class nếu class bị đè
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeVipModal = function() {
        const modal = document.getElementById('vip-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    };
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