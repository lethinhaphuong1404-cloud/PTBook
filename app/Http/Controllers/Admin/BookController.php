<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->paginate(12);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'status'      => 'required|in:ongoing,completed,full', // Thêm validate status
            'author'      => 'nullable|string|max:255',
            'category'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'book_file'   => 'required|mimes:pdf,epub,txt|max:51200',
            'audio_file'  => 'nullable|mimes:mp3,wav,m4a|max:102400',
        ], [
            'title.required'      => 'Vui lòng nhập tên sách.',
            'book_file.required'  => 'Vui lòng tải file sách.',
            'book_file.mimes'     => 'File sách phải là PDF, EPUB hoặc TXT.',
        ]);

        $coverPath = $request->hasFile('cover_image') 
            ? $request->file('cover_image')->store('covers', 'public') 
            : null;

        $bookPath = $request->file('book_file')->store('books', 'public');

        $audioPath = $request->hasFile('audio_file') 
            ? $request->file('audio_file')->store('audios', 'public') 
            : null;

        $slug = Str::slug($request->title);
        if (Book::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $book = Book::create([
            'title'       => $request->title,
            'status'      => $request->status, // Lưu status khi tạo mới
            'author'      => $request->author,
            'slug'        => $slug,
            'category'    => $request->category,
            'description' => $request->description,
            'cover_image' => $coverPath,
            'book_file'   => $bookPath,
            'is_premium'  => $request->boolean('is_premium'),
            'is_active'   => $request->boolean('is_active'),
            'views'       => 0,
            'downloads'   => 0,
        ]);

        $book->chapters()->create([
            'title'        => 'Khởi đầu', 
            'file_path'    => $bookPath,
            'order_number' => 1,
        ]);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Thêm sách và khởi tạo Chương 1 thành công!');
    }

    public function edit(Book $book)
    {
        $book->load('chapters');
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        // 1. Chỉnh sửa phần Validate (Đưa status vào đây)
        $request->validate([
            'title'       => 'required|string|max:255',
            'status'      => 'required|in:ongoing,completed,full', // Validate nằm ở đây
            'author'      => 'nullable|string|max:255',
            'category'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'book_file'   => 'nullable|mimes:pdf,epub,txt|max:51200',
            'audio_file'  => 'nullable|mimes:mp3,wav,m4a|max:102400',
        ]);

        // 2. Gán dữ liệu vào mảng data để update
        $data = [
            'title'       => $request->title,
            'status'      => $request->status, // Gán giá trị từ request vào database
            'author'      => $request->author,
            'category'    => $request->category,
            'description' => $request->description,
            'is_premium'  => $request->boolean('is_premium'),
            'is_active'   => $request->boolean('is_active'),
        ];

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('book_file')) {
            if ($book->book_file) Storage::disk('public')->delete($book->book_file);
            $data['book_file'] = $request->file('book_file')->store('books', 'public');
        }

        if ($request->hasFile('audio_file')) {
            if ($book->audio_file) Storage::disk('public')->delete($book->audio_file);
            $data['audio_file'] = $request->file('audio_file')->store('audios', 'public');
        }

        $book->update($data);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Cập nhật sách thành công!');
    }

    public function destroy(Book $book)
    {
        Storage::disk('public')->delete(array_filter([$book->cover_image, $book->book_file, $book->audio_file]));
        $book->delete(); 
        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Đã xoá sách thành công!');
    }
}