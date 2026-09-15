<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')->orderBy('created_at', 'desc')->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_pengumuman' => 'required',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->judul).'-'.rand(100, 999);
        $data['author_id'] = auth()->id();

        Announcement::create($data);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_pengumuman' => 'required',
        ]);

        $announcement->update([
            'judul' => $request->judul,
            'isi_pengumuman' => $request->isi_pengumuman,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
