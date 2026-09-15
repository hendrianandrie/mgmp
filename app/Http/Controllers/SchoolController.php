<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::withCount('members');

        if ($request->filled('komisariat')) {
            $query->where('komisariat', $request->komisariat);
        }
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_sekolah', 'like', '%'.$request->search.'%')
                    ->orWhere('npsn', 'like', '%'.$request->search.'%');
            });
        }

        $schools = $query->orderBy('komisariat')->orderBy('nama_sekolah')->paginate(15);
        $kecamatans = School::select('kecamatan')->distinct()->pluck('kecamatan');
        $komisariats = School::select('komisariat')->whereNotNull('komisariat')->distinct()->orderBy('komisariat')->pluck('komisariat');

        return view('schools.index', compact('schools', 'kecamatans', 'komisariats'));
    }

    public function store(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Hanya Pengurus/Admin yang dapat mengelola data sekolah.');
        }
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'komisariat' => 'nullable|string|max:20',
            'npsn' => 'nullable|string|unique:schools,npsn,'.$request->id,
            'status' => 'required|in:NEGERI,SWASTA',
            'kecamatan' => 'required|string|max:100',
        ]);

        School::updateOrCreate(
            ['id' => $request->id],
            $request->only(['nama_sekolah', 'npsn', 'status', 'kecamatan', 'komisariat', 'nama_kepsek', 'alamat', 'no_telp_sekolah'])
        );

        return redirect()->route('schools.index')->with('success', 'Data sekolah berhasil disimpan!');
    }

    public function destroy($id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }
        School::findOrFail($id)->delete();

        return redirect()->route('schools.index')->with('success', 'Data sekolah berhasil dihapus.');
    }
}
