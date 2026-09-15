<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['user', 'school', 'position']);
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        if ($request->filled('status_kepegawaian')) {
            $query->where('status_kepegawaian', $request->status_kepegawaian);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                    ->orWhere('nip', 'like', '%'.$request->search.'%')
                    ->orWhere('nuptk', 'like', '%'.$request->search.'%');
            });
        }
        $members = $query->orderBy('nama_lengkap')->paginate(15);
        $schools = School::orderBy('nama_sekolah')->get();

        return view('members.index', compact('members', 'schools'));
    }

    public function store(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Hanya Admin/Pengurus yang dapat mengelola data anggota.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'status_kepegawaian' => 'required|in:PNS,PPPK,GTT,Honorer',
            'school_id' => 'nullable|exists:schools,id',
            'nip' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'username' => 'nullable|string|max:50|unique:users,username,'.($request->user_id ?? 0),
            'email' => 'nullable|email|max:255|unique:users,email,'.($request->user_id ?? 0),
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $userId = $request->user_id;

        if ($request->filled('username') || $request->filled('email')) {
            $userData = [
                'name' => $request->nama_lengkap,
                'username' => $request->username ?? Str::slug($request->nama_lengkap),
                'email' => $request->email ?? (Str::slug($request->nama_lengkap).'@mgmp.id'),
                'role' => 'guru',
                'is_active' => true,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            } elseif (! $userId) {
                $userData['password'] = Hash::make('password');
            }

            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->update($userData);
                }
            } else {
                $user = User::create($userData);
                $userId = $user->id;
            }
        }

        $memberData = [
            'user_id' => $userId,
            'school_id' => $request->school_id,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'gelar_depan' => $request->gelar_depan,
            'nama_lengkap' => $request->nama_lengkap,
            'gelar_belakang' => $request->gelar_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_kepegawaian' => $request->status_kepegawaian,
            'mapel_diampu' => $request->mapel_diampu ?? 'Informatika',
            'alamat_rumah' => $request->alamat_rumah,
        ];

        if ($request->hasFile('foto')) {
            if ($request->id) {
                $existing = Member::find($request->id);
                if ($existing && $existing->foto && Storage::disk('public')->exists($existing->foto)) {
                    Storage::disk('public')->delete($existing->foto);
                }
            }
            $memberData['foto'] = $request->file('foto')->store('members', 'public');
        }

        Member::updateOrCreate(['id' => $request->id], $memberData);

        return redirect()->route('members.index')->with('success', 'Data anggota guru berhasil disimpan!');
    }

    public function destroy($id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        $member = Member::findOrFail($id);
        if ($member->foto && Storage::disk('public')->exists($member->foto)) {
            Storage::disk('public')->delete($member->foto);
        }
        if ($member->user_id) {
            User::where('id', $member->user_id)->delete();
        }
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}
