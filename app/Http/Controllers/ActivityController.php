<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::withCount('attendances')->orderBy('tanggal_kegiatan', 'desc')->paginate(12);

        return view('activities.index', compact('activities'));
    }

    public function store(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
            'narasumber' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:BERJALAN,DRAFT,SELESAI',
        ]);

        $data = $request->all();
        if (! $request->id) {
            $data['kode_kegiatan'] = 'ACT-'.date('Ym').'-'.rand(100, 999);
            $data['qr_code_token'] = Str::random(32);
        }

        Activity::updateOrCreate(['id' => $request->id], $data);

        return redirect()->route('activities.index')->with('success', 'Agenda kegiatan berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $activity = Activity::findOrFail($id);

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
            'narasumber' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:BERJALAN,DRAFT,SELESAI',
        ]);

        $activity->update($request->all());

        return redirect()->route('activities.index')->with('success', 'Agenda kegiatan berhasil diperbarui!');
    }

    public function show($id)
    {
        $activity = Activity::with(['attendances.member.school'])->findOrFail($id);

        return view('activities.show', compact('activity'));
    }

    public function destroy($id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $activity = Activity::findOrFail($id);
        $activity->attendances()->delete();
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Agenda kegiatan berhasil dihapus!');
    }
}
