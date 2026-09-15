<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with(['category', 'uploader', 'penyusun.school']);

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('link_external', 'like', "%{$search}%");
            });
        }

        $materials = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Category::where('tipe', 'MATERI')->get();
        $members = Member::with('school')->orderBy('nama_lengkap', 'asc')->get();

        return view('materials.index', compact('materials', 'categories', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|in:7,8,9,Semua',
            'link_external' => 'required|url',
            'member_id' => 'nullable|exists:members,id',
            'category_id' => 'nullable|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pdf')) {
            $filePath = $request->file('file_pdf')->store('materials', 'public');
        }

        Material::create([
            'judul' => $request->judul,
            'kelas' => $request->kelas,
            'link_external' => $request->link_external,
            'member_id' => $request->member_id,
            'category_id' => $request->category_id,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'uploader_id' => auth()->id(),
        ]);

        return redirect()->route('materials.index')->with('success', 'Bahan Ajar Digital berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Bahan Ajar Digital berhasil dihapus!');
    }
}
