<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\DocumentationAlbum;
use App\Models\DocumentationPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentationAlbum::with(['activity', 'uploader', 'photos'])->withCount('photos');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_album', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        $albums = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(12);
        $activities = Activity::orderBy('tanggal_kegiatan', 'desc')->get();

        return view('documentations.index', compact('albums', 'activities'));
    }

    public function show($id)
    {
        $album = DocumentationAlbum::with(['activity', 'uploader', 'photos'])->findOrFail($id);

        return view('documentations.show', compact('album'));
    }

    public function store(Request $request)
    {
        if (! auth()->check()) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'nama_album' => 'required|string|max:255',
            'activity_id' => 'nullable|exists:activities,id',
            'tanggal' => 'required|date',
            'komisariat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'cover_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_foto')) {
            $coverPath = $request->file('cover_foto')->store('documentation/covers', 'public');
        }

        $album = DocumentationAlbum::create([
            'nama_album' => $request->nama_album,
            'activity_id' => $request->activity_id,
            'tanggal' => $request->tanggal,
            'komisariat' => $request->komisariat,
            'deskripsi' => $request->deskripsi,
            'cover_foto' => $coverPath,
            'uploaded_by' => auth()->id(),
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photoFile) {
                $photoPath = $photoFile->store('documentation/photos', 'public');
                $photo = DocumentationPhoto::create([
                    'album_id' => $album->id,
                    'foto_path' => $photoPath,
                ]);

                if (! $coverPath && $index === 0) {
                    $album->update(['cover_foto' => $photoPath]);
                }
            }
        }

        return redirect()->route('documentations.index')->with('success', 'Album dokumentasi kegiatan berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        if (! auth()->check()) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $album = DocumentationAlbum::findOrFail($id);

        $request->validate([
            'nama_album' => 'required|string|max:255',
            'activity_id' => 'nullable|exists:activities,id',
            'tanggal' => 'required|date',
            'komisariat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'cover_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'nama_album' => $request->nama_album,
            'activity_id' => $request->activity_id,
            'tanggal' => $request->tanggal,
            'komisariat' => $request->komisariat,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('cover_foto')) {
            if ($album->cover_foto) {
                Storage::disk('public')->delete($album->cover_foto);
            }
            $data['cover_foto'] = $request->file('cover_foto')->store('documentation/covers', 'public');
        }

        $album->update($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photoFile) {
                $photoPath = $photoFile->store('documentation/photos', 'public');
                DocumentationPhoto::create([
                    'album_id' => $album->id,
                    'foto_path' => $photoPath,
                ]);
            }
        }

        return redirect()->route('documentations.show', $album->id)->with('success', 'Album dokumentasi berhasil diperbarui!');
    }

    public function storePhoto(Request $request, $id)
    {
        if (! auth()->check()) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $album = DocumentationAlbum::findOrFail($id);

        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'judul_foto' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
        ]);

        foreach ($request->file('photos') as $photoFile) {
            $photoPath = $photoFile->store('documentation/photos', 'public');
            DocumentationPhoto::create([
                'album_id' => $album->id,
                'judul_foto' => $request->judul_foto,
                'caption' => $request->caption,
                'foto_path' => $photoPath,
            ]);
        }

        return back()->with('success', 'Foto kegiatan berhasil ditambahkan ke album!');
    }

    public function destroyPhoto($id)
    {
        if (! auth()->check()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $photo = DocumentationPhoto::findOrFail($id);
        if ($photo->foto_path) {
            Storage::disk('public')->delete($photo->foto_path);
        }
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }

    public function destroy($id)
    {
        if (! auth()->check()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $album = DocumentationAlbum::with('photos')->findOrFail($id);

        foreach ($album->photos as $photo) {
            if ($photo->foto_path) {
                Storage::disk('public')->delete($photo->foto_path);
            }
        }

        if ($album->cover_foto) {
            Storage::disk('public')->delete($album->cover_foto);
        }

        $album->delete();

        return redirect()->route('documentations.index')->with('success', 'Album dokumentasi berhasil dihapus!');
    }
}
