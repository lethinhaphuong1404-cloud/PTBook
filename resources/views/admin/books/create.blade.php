<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Thêm sách</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <!-- Header -->
    <header class="bg-black border-b border-gray-800 px-8 py-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-red-500">PTBook Admin</h1>

        <a href="{{ route('admin.books.index') }}"
           class="text-gray-300 hover:text-white">
            ← Quay lại
        </a>
    </header>

    <!-- Main -->
    <main class="max-w-4xl mx-auto px-6 py-10">

        <div class="bg-black/50 border border-gray-800 rounded-2xl p-8">
            <h2 class="text-4xl font-bold mb-2">Thêm sách mới</h2>
            <p class="text-gray-400 mb-8">
                Upload sách PDF / EPUB / TXT và ảnh bìa
            </p>

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-500/20 border border-red-500 text-red-300 px-4 py-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.books.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block mb-2 text-gray-300">Tiêu đề sách</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-red-500 focus:outline-none">
                </div>

                <!-- Author -->
                <div>
                    <label class="block mb-2 text-gray-300">Tác giả</label>
                    <input type="text"
                           name="author"
                           value="{{ old('author') }}"
                           required
                           class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-red-500 focus:outline-none">
                </div>

                <!-- Category -->
                <div>
                    <label class="block mb-2 text-gray-300">Thể loại</label>
                    <input type="text"
                           name="category"
                           value="{{ old('category') }}"
                           placeholder="Ví dụ: Tiểu thuyết, Kỹ năng sống..."
                           required
                           class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-red-500 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-white font-bold mb-2">Trạng thái truyện</label>
                    <select name="status" class="w-full p-3 border rounded-lg bg-gray-900 text-white border-gray-700 focus:border-red-500 outline-none">
                        <option value="ongoing" {{ (isset($book) && $book->status == 'ongoing') ? 'selected' : '' }}>Đang ra (Ongoing)</option>
                        <option value="completed" {{ (isset($book) && ($book->status == 'completed' || $book->status == 'full')) ? 'selected' : '' }}>Hoàn thành (Full)</option>
                    </select>
                </div>
                <!-- Description -->
                <div>
                    <label class="block mb-2 text-gray-300">Mô tả</label>
                    <textarea name="description"
                              rows="5"
                              class="w-full px-4 py-3 rounded bg-gray-900 border border-gray-700 focus:border-red-500 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                <!-- Cover -->
                <div>
                    <label class="block mb-2 text-gray-300">Ảnh bìa</label>
                    <input type="file"
                           name="cover_image"
                           accept="image/*"
                           class="w-full text-gray-300">
                </div>

                <!-- Book File -->
                <div>
                    <label class="block mb-2 text-gray-300">File sách</label>
                    <input type="file"
                           name="book_file"
                           accept=".pdf,.epub,.txt"
                           required
                           class="w-full text-gray-300">
                </div>


                <!-- Published -->
                <label class="flex items-center gap-3">
                    <input type="checkbox"
                           name="is_published"
                           value="1"
                           class="accent-red-600"
                           checked>
                    <span>Xuất bản ngay</span>
                </label>

                <!-- Submit -->
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 transition py-4 rounded-lg font-bold text-lg">
                    Thêm sách
                </button>

            </form>
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