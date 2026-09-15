<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Member;
use App\Models\Position;
use App\Models\School;

class PublicController extends Controller
{
    public function index()
    {
        $totalSekolah = School::count();
        $totalAnggota = Member::count();
        $totalKegiatan = Activity::count();
        $kegiatanMendatang = Activity::where('tanggal_kegiatan', '>=', date('Y-m-d'))->orderBy('tanggal_kegiatan')->take(3)->get();
        $pengumuman = Announcement::where('is_public', true)->orderBy('created_at', 'desc')->take(4)->get();
        $pengurus = Position::with('member.school')->orderBy('urutan')->get();

        return view('welcome', compact('totalSekolah', 'totalAnggota', 'totalKegiatan', 'kegiatanMendatang', 'pengumuman', 'pengurus'));
    }
}
