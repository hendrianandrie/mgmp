<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('member.school')->orderBy('urutan')->get();
        $members = Member::orderBy('nama_lengkap')->get();

        return view('positions.index', compact('positions', 'members'));
    }

    public function store(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'member_id' => 'nullable|exists:members,id',
            'urutan' => 'nullable|integer',
        ]);

        Position::updateOrCreate(
            ['id' => $request->id],
            [
                'nama_jabatan' => $request->nama_jabatan,
                'periode' => $request->periode,
                'member_id' => $request->member_id ?: null,
                'urutan' => $request->urutan ?? 1,
            ]
        );

        return redirect()->route('positions.index')->with('success', 'Struktur pengurus berhasil diperbarui!');
    }
}
