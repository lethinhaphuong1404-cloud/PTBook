<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý sách</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <!-- Header -->
    <header class="bg-black border-b border-gray-800 px-8 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-3xl font-black text-red-600 tracking-tighter hover:opacity-80 transition-all">
            PTBook Admin
        </a>

        <a href="{{ route('admin.books.create') }}"
           class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded-lg font-semibold">
            + Thêm sách
        </a>
    </header>

    <!-- Main -->
    <main class="max-w-7xl mx-auto px-6 py-10">

        <!-- Success -->
        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-black/50 border border-gray-800 rounded-2xl overflow-hidden">

            <!-- Title -->
            <div class="px-6 py-4 border-b border-gray-800">
                <h2 class="text-2xl font-bold">Danh sách sách</h2>
                <p class="text-gray-400 text-sm mt-1">
                    Tổng: {{ $books->total() }} sách
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-900 text-gray-300">
                        <tr>
                            <th class="px-4 py-4 w-20">Bìa</th>
                            <th class="px-4 py-4 min-w-[200px]">Tiêu đề</th>
                            <th class="px-4 py-4">Tác giả</th>
                            <th class="px-4 py-4">Thể loại</th>
                            <th class="px-4 py-4">Loại</th>
                            <th class="px-4 py-4 whitespace-nowrap min-w-[120px]">Trạng thái</th>
                            <th class="px-4 py-4">Views</th>
                            <th class="px-4 py-4 whitespace-nowrap min-w-[140px]">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-800">

                        @forelse($books as $book)
                            <tr class="hover:bg-gray-900/40">

                                <!-- Cover -->
                               <td class="px-4 py-4">
    @if($book->cover_image)
        <img src="{{ asset('storage/' . $book->cover_image) }}"
             alt="{{ $book->title }}"
             class="w-16 h-24 object-cover rounded-lg border border-gray-700 shadow">
    @else
        <div class="w-16 h-24 bg-gray-800 rounded flex items-center justify-center text-xs text-gray-500">
            No Cover
        </div>
    @endif
</td>

                                <!-- Title -->
                                <td class="px-4 py-4 font-semibold">
                                    {{ $book->title }}
                                </td>

                                <!-- Author -->
                                <td class="px-4 py-4 text-gray-300">
                                    {{ $book->author ?? 'Không rõ' }}
                                </td>

                                <!-- Category -->
                                <td class="px-4 py-4 text-gray-300">
                                    {{ $book->category ?? '-' }}
                                </td>

                                <!-- Premium -->
                                <td class="px-4 py-4">
                                    @if($book->is_premium)
                                        <span class="bg-yellow-500/20 text-yellow-300 px-3 py-1 rounded-full text-sm">
                                            Premium
                                        </span>
                                    @else
                                        <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm">
                                            Free
                                        </span>
                                    @endif
                                </td>

                                <!-- Active -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($book->is_active)
                                        <span class="bg-blue-500/20 text-blue-300 px-3 py-1 rounded-full text-sm inline-block">
                                            Hiển thị
                                        </span>
                                    @else
                                        <span class="bg-red-500/20 text-red-300 px-3 py-1 rounded-full text-sm inline-block">
                                            Ẩn
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    {{ number_format($book->views) }}
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="inline-flex gap-4"> <a href="{{ route('admin.books.edit', $book) }}"
                                        class="text-blue-400 hover:text-blue-300 font-medium">
                                            Sửa
                                        </a>

                                        <form action="{{ route('admin.books.destroy', $book) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Bạn chắc chắn muốn xoá sách này?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-400 hover:text-red-300 font-medium">
                                                Xoá
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-500">
                                    Chưa có sách nào.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-800">
                {{ $books->links() }}
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