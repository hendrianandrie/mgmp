<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin'])) {
            abort(403, 'Akses khusus administrator.');
        }

        $query = User::with('member.school');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $members = Member::with('school')->orderBy('nama_lengkap', 'asc')->get();

        return view('users.index', compact('users', 'members'));
    }

    public function store(Request $request)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin'])) {
            abort(403, 'Akses khusus administrator.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:superadmin,pengurus,guru,tamu',
            'member_id' => 'nullable|exists:members,id',
            'is_active' => 'nullable|boolean',
        ]);

        $username = $request->username;
        if (empty($username)) {
            $username = Str::slug($request->name, '.').rand(10, 99);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        if ($request->filled('member_id')) {
            Member::where('id', $request->member_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'Akun pengguna baru berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin'])) {
            abort(403, 'Akses khusus administrator.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,'.$id,
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:superadmin,pengurus,guru,tamu',
            'member_id' => 'nullable|exists:members,id',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Handle Member link
        Member::where('user_id', $user->id)->update(['user_id' => null]);
        if ($request->filled('member_id')) {
            Member::where('id', $request->member_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'Data akun pengguna berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin'])) {
            abort(403, 'Akses khusus administrator.');
        }

        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat me-nonaktifkan akun Anda sendiri yang sedang aktif.');
        }

        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('users.index')->with('success', "Status akun {$user->name} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        if (! in_array(auth()->user()->role ?? '', ['superadmin', 'admin'])) {
            abort(403, 'Akses khusus administrator.');
        }

        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user = User::findOrFail($id);
        Member::where('user_id', $user->id)->update(['user_id' => null]);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
