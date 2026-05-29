<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ChapterController extends Controller
{
    // app/Http/Controllers/Admin/ChapterController.php
    public function store(Request $request, $bookId)
    {
        $request->validate([
            'chapter_title' => 'required',
            'chapter_file' => 'required|file',
            'audio_path' => 'nullable|mimes:mp3,wav,m4a|max:20480', // Max 20MB
        ]);

        $book = Book::findOrFail($bookId);
        
        // Lưu file nội dung chữ
        $filePath = $request->file('chapter_file')->store('chapters', 'public');

        // Lưu file Audio (nếu có)
        $audioPath = null;
        if ($request->hasFile('audio_path')) {
            $audioPath = $request->file('audio_path')->store('audios', 'public');
        }

        $book->chapters()->create([
            'title' => $request->chapter_title,
            'order_number' => $request->order_number,
            'file_path' => $filePath,
            'audio_path' => $audioPath, // Lưu vào cột mới
        ]);

        return back()->with('success', 'Thêm chương mới thành công!');
    }
    // app/Http/Controllers/Admin/ChapterController.php
    public function update(Request $request, $id)
    {
        // Tìm chương
        $chapter = Chapter::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Cập nhật và lưu
        $chapter->title = $request->title;
        $chapter->save();

        return back()->with('success', 'Cập nhật tên chương thành công!');
    }
    public function destroy(Chapter $chapter)
    {
        // 1. Xoá file vật lý trong storage
        if (Storage::disk('public')->exists($chapter->file_path)) {
            Storage::disk('public')->delete($chapter->file_path);
        }

        // 2. Xoá bản ghi trong database
        $chapter->delete();

        return back()->with('success', 'Đã xoá chương thành công!');
    }
    public function updateAudio(Request $request, $bookId)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'audio_file' => 'required|mimes:mp3,wav,m4a|max:51200', 
        ]);

        $chapter = Chapter::findOrFail($request->chapter_id);

        if ($request->hasFile('audio_file')) {
            // Xóa audio cũ
            if ($chapter->audio_path) {
                \Storage::disk('public')->delete($chapter->audio_path);
            }

            // Lưu file vào thư mục audios/{bookId}
            $path = $request->file('audio_file')->store('audios/' . $bookId, 'public');
            
            // Cập nhật Database
            $chapter->update(['audio_path' => $path]);
        }

        return back()->with('success', 'Đã cập nhật Audio cho ' . $chapter->title);
    }
}