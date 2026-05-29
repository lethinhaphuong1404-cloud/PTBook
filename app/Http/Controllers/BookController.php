<?php

namespace App\Http\Controllers;
use App\Models\UserBook;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Reading;
class BookController extends Controller
{
    /**
     * Trang danh sách sách public
     */
   public function index(Request $request)
    {
        // 1. GIỮ NGUYÊN: Logic xử lý danh sách thể loại (Categories) của bạn
        $allCats = \App\Models\Book::pluck('category')->implode(',');
        $categories = collect(explode(',', $allCats))
            ->map(function ($item) {
                $item = trim(mb_strtolower($item));
                return mb_convert_case($item, MB_CASE_TITLE, "UTF-8");
            })
            ->filter()->unique()->sort()->values();

        // 2. THAY ĐỔI: Nâng cấp truy vấn để nhận thêm các bộ lọc mới
        $query = Book::query()->where('is_active', true);
        
        // Nạp thêm ratings để tính sao (Giữ nguyên)
        $query->with(['ratings']); 

        // Lọc theo thể loại (Giữ nguyên)
        if ($request->category) {
            $query->where('category', 'LIKE', '%' . $request->category . '%');
        }

        // --- BẮT ĐẦU PHẦN BỔ SUNG ---

        // Lọc theo trạng thái (Full/Ongoing)
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo số lượng chương
        if ($request->chapters) {
            $range = explode('-', $request->chapters);
            if (count($range) == 2) {
                // Sử dụng withCount để đếm số chapters liên kết và lọc
                $query->withCount('chapters')->havingBetween('chapters_count', [(int)$range[0], (int)$range[1]]);
            }
        }
        
        // --- KẾT THÚC PHẦN BỔ SUNG ---

        // Sắp xếp mới nhất và phân trang
        $books = $query->latest()->paginate(12)->withQueryString();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Trang chi tiết sách
     */
    // app/Http/Controllers/BookController.php

    public function show($slug) // Lưu ý: Bạn đang để (Book $book) nhưng bên dưới dùng $slug, hãy đổi thống nhất
    {
       $book = Book::where('slug', $slug)->firstOrFail();
    abort_unless($book->is_active, 404);

    if (Auth::check()) {
        UserBook::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['is_read' => true, 'last_read_at' => now()]
        );
    }
    
    $book->increment('views');
    $book->load(['chapters', 'comments.user', 'ratings']);


    $averageRating = $book->ratings->avg('stars') ?: 0;
    $totalRatings = $book->ratings->count();

    $relatedBooks = Book::where('id', '!=', $book->id)
        ->where('is_active', true)
        ->where('category', 'LIKE', '%' . $book->category . '%')
        ->latest()->take(4)->get();

    return view('books.show', compact('book', 'averageRating', 'totalRatings', 'relatedBooks'));
    }

    /**
     * Download sách
     */
  public function download($slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();
        abort_unless($book->is_active, 404);

        if (Auth::check()) {
            // Cập nhật trạng thái đã tải vào bảng user_books
            UserBook::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'book_id' => $book->id,
                ],
                [
                    'is_downloaded' => true,
                ]
            );
        }

        if (!$book->book_file || !Storage::disk('public')->exists($book->book_file)) {
            abort(404, 'Không tìm thấy file sách.');
        }

        $book->increment('downloads');

        $filePath = storage_path('app/public/' . $book->book_file);
        return response()->download($filePath, basename($book->book_file));
    }

    /**
     * Đọc online
     */
public function read(Book $book, $chapter = 1)
{
    // 1. Ép kiểu biến truyền vào chắc chắn là số
    $chapter = (int) $chapter;

    // 2. Lấy chương hiện tại
    $currentChapter = $book->chapters()
        ->where('order_number', $chapter)
        ->firstOrFail();

    // 3. Tìm chương TRƯỚC: Ép kiểu cột order_number về số nguyên để so sánh chính xác
    $prevChapter = $book->chapters()
        ->whereRaw('CAST(order_number AS UNSIGNED) < ?', [$currentChapter->order_number])
        ->orderByRaw('CAST(order_number AS UNSIGNED) DESC')
        ->first();

    // 4. Tìm chương SAU: Tương tự ép kiểu về số
    $nextChapter = $book->chapters()
        ->whereRaw('CAST(order_number AS UNSIGNED) > ?', [$currentChapter->order_number])
        ->orderByRaw('CAST(order_number AS UNSIGNED) ASC')
        ->first();

    // 5. Đọc nội dung
    $content = \Storage::disk('public')->get($currentChapter->file_path);

    return view('books.read', compact('book', 'currentChapter', 'prevChapter', 'nextChapter', 'content'));
    }
    
    public function storeComment(Request $request, $id)
    {
        // Bỏ dòng dd($request->all()) đi để code chạy tiếp
    
        $request->validate([
            'content' => 'required|string|min:1', // Giảm xuống 1 ký tự
            'stars'   => 'required|integer|min:1|max:5',
        ]);

        // Giữ nguyên phần lưu Rating và Comment bên dưới...
        \App\Models\Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $id],
            ['stars' => (int) $request->stars]
        );

        \App\Models\Comment::create([
            'user_id' => auth()->id(),
            'book_id' => $id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Gửi đánh giá thành công!');
    }
/**
 * Nghe audio online
 */
    public function listen(Book $book, $chapter = null)
    {
        abort_unless($book->is_active, 404);

        // Lấy danh sách chương có audio
        $audioChapters = $book->chapters()->whereNotNull('audio_path')->orderBy('order_number')->get();

        if ($audioChapters->isEmpty()) {
            return back()->with('error', 'Sách này chưa có nội dung audio.');
        }

        // Nếu không truyền chapter id, lấy chương audio đầu tiên
        $currentChapter = $chapter 
            ? $book->chapters()->findOrFail($chapter) 
            : $audioChapters->first();

        return view('books.listen', compact('book', 'currentChapter', 'audioChapters'));
    }

    public function update(Request $request, $slug)
{
    $book = Book::where('slug', $slug)->firstOrFail();
    
    // ... code update title, author, description ...

    if ($request->hasFile('audio_file')) {
        $audioPath = $request->file('audio_file')->store('audios/' . $book->id, 'public');

        // Tìm chương 1 hoặc tạo mới nếu chưa có
        $chapter1 = $book->chapters()->where('order_number', 1)->first();

        if ($chapter1) {
            // Xóa file cũ nếu muốn tiết kiệm bộ nhớ
            if ($chapter1->audio_path) {
                Storage::disk('public')->delete($chapter1->audio_path);
            }
            $chapter1->update(['audio_path' => $audioPath]);
        } else {
            // Nếu chưa có chương 1 thì tạo mới luôn
            $book->chapters()->create([
                'title' => 'Chương 1',
                'order_number' => 1,
                'file_path' => $book->file_path, // Lấy file mặc định của sách
                'audio_path' => $audioPath,
            ]);
        }
    }

    $book->save();
    return back()->with('success', 'Cập nhật thành công!');
}

// app/Http/Controllers/BookController.php
    public function audioIndex(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');

        $books = \App\Models\Book::where('is_active', true)
            ->whereHas('chapters', function($q) {
                $q->whereNotNull('audio_path')->where('audio_path', '!=', '');
            })
            ->when($query, function($q) use ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('author', 'LIKE', "%{$query}%");
                });
            })
            ->when($category, function($q) use ($category) {
                $q->where('category', 'LIKE', "%{$category}%");
            })
            ->latest()
            ->paginate(16)
            ->withQueryString();

        $categories = \App\Models\Book::whereHas('chapters', function($q) {
                $q->whereNotNull('audio_path');
            })
            ->pluck('category')
            ->flatMap(fn($item) => explode(',', $item))
            ->unique()
            ->filter()
            ->map(fn($s) => trim($s))
            ->sort();

        return view('books.audio', compact('books', 'categories'));
    }

    public function search(Request $request)
    {
        // 1. Lấy từ khóa và xóa khoảng trắng thừa
        $query = trim($request->input('query'));
        
        // Nếu không có từ khóa, quay về trang danh sách hoặc thông báo
        if (empty($query)) {
            return redirect()->route('books.index');
        }

        // 2. Thực hiện truy vấn tìm kiếm
        $books = \App\Models\Book::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%") // Tìm theo tên truyện
                ->orWhere('author', 'LIKE', "%{$query}%"); // Tìm theo tên tác giả
            })
            ->latest()
            ->paginate(15)
            ->withQueryString(); // Giữ lại từ khóa khi chuyển trang (Phân trang)

        // 3. Lấy danh sách thể loại cho Sidebar
        $categories = \App\Models\Book::where('is_active', true)
            ->pluck('category')
            ->flatMap(fn($item) => explode(',', $item))
            ->unique()
            ->filter()
            ->map(fn($s) => trim($s))
            ->sort();

        // 4. Trả về view với tiêu đề thông báo kết quả
        return view('books.index', [
            'books' => $books,
            'categories' => $categories,
            'title' => "Kết quả tìm kiếm cho: \"{$query}\""
        ]);
    }
    public function incrementAudioView($id)
    {
        $book = Book::findOrFail($id);
        $book->increment('audio_views');
        
        return response()->json([
            'success' => true, 
            'new_views' => number_format($book->audio_views)
        ]);
    }
}