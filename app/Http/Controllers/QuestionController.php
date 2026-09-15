<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['creator', 'penyusun.school']);

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('jenis_soal')) {
            $query->where('jenis_soal', $request->jenis_soal);
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('soal_text', 'like', "%{$search}%")
                    ->orWhere('materi_pokok', 'like', "%{$search}%");
            });
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(12);
        $members = Member::with('school')->orderBy('nama_lengkap', 'asc')->get();

        return view('questions.index', compact('questions', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|string',
            'jenis_soal' => 'required|string|max:50',
            'member_id' => 'nullable|exists:members,id',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $filePath = $file->store('bank_soal', 'public');

        $sizeInBytes = $file->getSize();
        if ($sizeInBytes >= 1048576) {
            $ukuranFile = number_format($sizeInBytes / 1048576, 2).' MB';
        } else {
            $ukuranFile = number_format($sizeInBytes / 1024, 1).' KB';
        }

        Question::create([
            'judul' => $request->judul,
            'kelas' => $request->kelas,
            'jenis_soal' => $request->jenis_soal,
            'member_id' => $request->member_id,
            'file_path' => $filePath,
            'ukuran_file' => $ukuranFile,
            'deskripsi' => $request->deskripsi,
            'jumlah_download' => 0,
            'creator_id' => auth()->id(),
        ]);

        return redirect()->route('questions.index')->with('success', 'File Bank Soal PDF berhasil diunggah!');
    }

    public function download($id)
    {
        $question = Question::findOrFail($id);

        if ($question->file_path && Storage::disk('public')->exists($question->file_path)) {
            $question->increment('jumlah_download');
            $filename = Str::slug($question->judul ?? 'Bank_Soal_Informatika').'.pdf';

            return Storage::disk('public')->download($question->file_path, $filename);
        }

        return redirect()->back()->with('error', 'File PDF tidak ditemukan atau belum diunggah.');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        if ($question->file_path && Storage::disk('public')->exists($question->file_path)) {
            Storage::disk('public')->delete($question->file_path);
        }

        $question->delete();

        return redirect()->route('questions.index')->with('success', 'File Bank Soal berhasil dihapus!');
    }
}
