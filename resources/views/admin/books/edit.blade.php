<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sửa sách</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

<header class="bg-black border-b border-gray-800 px-8 py-4 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-red-500">PTBook Admin</h1>
    <a href="{{ route('admin.books.index') }}" class="text-gray-300 hover:text-white transition">← Quay lại danh sách</a>
</header>

<main class="max-w-5xl mx-auto px-6 py-10">

    @if(session('success'))
        <div class="mb-6 bg-green-500/20 border border-green-500 text-green-300 px-4 py-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-black/50 border border-gray-800 rounded-2xl p-8">
        <h2 class="text-4xl font-bold mb-2">Sửa sách</h2>
        <p class="text-gray-400 mb-8">Cập nhật thông tin, thay file sách, audio hoặc ảnh bìa</p>

        <form action="{{ route('admin.books.update', $book->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block mb-2 text-gray-300">Tiêu đề sách</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-yellow-500 focus:outline-none">
                </div>
                <div>
                    <label class="block mb-2 text-gray-300">Tác giả</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-yellow-500 focus:outline-none">
                </div>
                <div>
                    <label class="block mb-2 text-gray-300">Thể loại</label>
                    <input type="text" name="category" value="{{ old('category', $book->category) }}" class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-yellow-500 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-white font-bold mb-2">Trạng thái truyện</label>
                    <select name="status" class="w-full p-3 border rounded-lg bg-gray-900 text-white border-gray-700 focus:border-red-500 outline-none">
                        <option value="ongoing" {{ (isset($book) && $book->status == 'ongoing') ? 'selected' : '' }}>Đang ra (Ongoing)</option>
                        <option value="completed" {{ (isset($book) && ($book->status == 'completed' || $book->status == 'full')) ? 'selected' : '' }}>Hoàn thành (Full)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-gray-300">Mô tả</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-yellow-500 focus:outline-none">{{ old('description', $book->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 text-gray-300">Ảnh bìa mới</label>
                    <input type="file" name="cover_image" accept="image/*" class="text-sm text-gray-400">
                </div>
                <div>
                    <label class="block mb-2 text-gray-300">File sách mới</label>
                    <input type="file" name="book_file" accept=".pdf,.epub,.txt" class="text-sm text-gray-400">
                </div>
            </div>

            <div class="flex gap-8 border-t border-gray-800 pt-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_premium" value="1" {{ $book->is_premium ? 'checked' : '' }} class="w-5 h-5 accent-yellow-500">
                    <span>Sách Premium</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $book->is_active ? 'checked' : '' }} class="w-5 h-5 accent-green-500">
                    <span>Hiển thị công khai</span>
                </label>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black py-4 rounded-lg font-bold transition">
                    Cập nhật thông tin sách
                </button>
        </form> <form action="{{ route('admin.books.destroy', $book->slug) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xoá sách này?')" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600/20 hover:bg-red-600 border border-red-600 text-red-500 hover:text-white py-4 rounded-lg font-bold transition">
                        Xoá sách
                    </button>
                </form>
            </div>
    </div>

    <div class="mt-10 bg-black/50 border border-gray-800 rounded-2xl p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-2xl font-bold text-yellow-500">Quản lý nội dung chương</h3>
            <p class="text-gray-400 text-sm">Danh sách các chương chữ và file âm thanh đi kèm</p>
        </div>
        <button type="button" onclick="openAddChapterModal()" class="bg-blue-600 hover:bg-blue-700 px-6 py-2.5 rounded-lg font-bold transition shadow-lg shadow-blue-900/20">
            + Thêm chương mới
        </button>
    </div>

    <div class="space-y-4">
        @forelse($book->chapters->sortBy('order_number') as $chapter)
            <div class="group flex flex-col md:flex-row md:items-center justify-between bg-gray-900/40 p-5 rounded-xl border border-gray-800 hover:border-gray-600 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center font-bold text-gray-400">
                        {{ $chapter->order_number }}
                    </div>
                    <div>
                        <h4 class="font-bold text-white group-hover:text-yellow-500 transition">
                            {{ Str::contains($chapter->title, 'Chương') ? $chapter->title : 'Chương ' . $chapter->order_number . ': ' . $chapter->title }}
                        </h4>
                        <div class="flex gap-2">
                            <button onclick="openEditChapterModal('{{ $chapter->id }}', '{{ $chapter->title }}')" 
                                    class="text-blue-500 hover:text-blue-700 transition-all" title="Sửa tên chương">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            
                            {{-- Kiểm tra chính xác giá trị audio_path --}}
                            @if(!empty($chapter->audio_path))
                                <span class="text-xs text-green-500 flex items-center gap-1 font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M18 8a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h10a3 3 0 013 3v1z" />
                                    </svg>
                                    🔊 Audio: Đã tải lên
                                </span>
                            @else
                                <span class="text-xs text-red-400 flex items-center gap-1">
                                    🔇 Audio: Chưa có
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-4 md:mt-0">
                    <button type="button" 
                            onclick="openAudioModal({{ $chapter->id }}, '{{ $chapter->title }}')"
                            class="flex-1 md:flex-none px-4 py-2 rounded-lg text-sm font-bold bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 hover:bg-yellow-500 hover:text-black transition">
                        {{ $chapter->audio_path ? 'Đổi Audio' : 'Thêm Audio' }}
                    </button>

                    <form action="{{ route('admin.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Xoá chương này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 border-2 border-dashed border-gray-800 rounded-2xl">
                <p class="text-gray-500 text-lg">Chưa có chương nào được tải lên.</p>
            </div>
        @endforelse
    </div>
</div>

<div id="add-chapter-modal" class="hidden fixed inset-0 bg-black/95 flex items-center justify-center p-4 z-[60]">
    <div class="bg-gray-900 border border-gray-700 p-8 rounded-2xl w-full max-w-md shadow-2xl">
        <h4 class="text-2xl font-bold mb-6 text-blue-400">Thêm chương mới</h4>
        <form action="{{ route('admin.chapters.store', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tên chương</label>
                <input type="text" name="chapter_title" placeholder="VD: Cuộc phiêu lưu bắt đầu" class="w-full bg-black border border-gray-700 p-3 rounded-lg focus:border-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Số thứ tự</label>
                <input type="number" name="order_number" value="{{ $book->chapters->count() + 1 }}" class="w-full bg-black border border-gray-700 p-3 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">File nội dung (.txt, .pdf)</label>
                <input type="file" name="chapter_file" class="w-full text-sm text-gray-400" required>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-bold transition">Lưu chương</button>
                <button type="button" onclick="closeAllModals()" class="flex-1 bg-gray-800 hover:bg-gray-700 py-3 rounded-lg font-bold">Huỷ</button>
            </div>
        </form>
    </div>
</div>

<div id="audio-modal" class="hidden fixed inset-0 bg-black/95 flex items-center justify-center p-4 z-[60]">
    <div class="bg-gray-900 border border-gray-800 p-8 rounded-2xl w-full max-w-md shadow-2xl">
        <h4 class="text-2xl font-bold mb-2 text-yellow-500">Cập nhật âm thanh</h4>
        <p id="audio-chapter-name" class="text-gray-400 text-sm mb-6 font-medium"></p>
        
        <form action="{{ route('admin.chapters.updateAudio', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <input type="hidden" name="chapter_id" id="target-chapter-id">

            <div class="relative border-2 border-dashed border-gray-700 rounded-xl p-8 text-center hover:border-yellow-500 transition group">
                <input type="file" name="audio_file" accept="audio/mp3" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="updateFileName(this)">
                <div class="text-4xl mb-2 group-hover:scale-110 transition">🎵</div>
                <div id="file-name-display" class="text-gray-500 text-sm font-medium">Chọn file MP3 của chương</div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black py-3 rounded-lg font-bold shadow-lg shadow-yellow-900/20">Tải lên ngay</button>
                <button type="button" onclick="closeAllModals()" class="flex-1 bg-gray-800 py-3 rounded-lg font-bold">Đóng</button>
            </div>
        </form>
    </div>
</div>
<div id="editChapterModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/10 p-6 rounded-2xl w-full max-w-md shadow-2xl">
        <h3 class="text-xl font-bold mb-4">Sửa tên chương</h3>
        <form id="editChapterForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Tên chương mới</label>
                <input type="text" name="title" id="editChapterTitle" 
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-red-500 transition-all">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditChapterModal()" class="px-4 py-2 text-gray-400 hover:text-white">Hủy</button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-xl font-bold transition-all">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openAddChapterModal() {
        document.getElementById('add-chapter-modal').classList.remove('hidden');
    }

    function openAudioModal(chapterId, chapterTitle) {
        document.getElementById('target-chapter-id').value = chapterId;
        document.getElementById('audio-chapter-name').innerText = "Đang chọn: " + chapterTitle;
        document.getElementById('audio-modal').classList.remove('hidden');
    }

    function closeAllModals() {
        document.getElementById('add-chapter-modal').classList.add('hidden');
        document.getElementById('audio-modal').classList.add('hidden');
    }

    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files.length > 0) {
            display.innerText = "Đã chọn: " + input.files[0].name;
            display.classList.add('text-yellow-500');
        }
    }

    function openEditChapterModal(id, currentTitle) {
        const modal = document.getElementById('editChapterModal');
        const form = document.getElementById('editChapterForm');
        const input = document.getElementById('editChapterTitle');
        
        // Thêm dấu / ở đầu để URL luôn là http://127.0.0.1:8000/admin/chapters/id
        form.action = `/admin/chapters/${id}`; 
        
        input.value = currentTitle;
        modal.classList.remove('hidden');
    }

    // Sửa lại hàm đóng Modal vì bạn đang dùng sai cú pháp .add
    function closeEditChapterModal() {
        document.getElementById('editChapterModal').classList.add('hidden');
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