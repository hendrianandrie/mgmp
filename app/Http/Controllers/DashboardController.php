<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Material;
use App\Models\Member;
use App\Models\Question;
use App\Models\School;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalSekolah = School::count();
        $totalAnggota = Member::count();
        $totalKegiatan = Activity::count();
        $totalMateri = Material::count();
        $totalSoal = Question::count();

        $kegiatanTerbaru = Activity::orderBy('tanggal_kegiatan', 'desc')->take(5)->get();
        $pengumumanTerbaru = Announcement::where('is_public', true)->orderBy('created_at', 'desc')->take(5)->get();
        $materiTerbaru = Material::with('category')->orderBy('created_at', 'desc')->take(5)->get();

        $kegiatanHariIni = Activity::whereDate('tanggal_kegiatan', date('Y-m-d'))->first();
        $isHadirHariIni = false;

        if ($kegiatanHariIni && $user && $user->member) {
            $isHadirHariIni = Attendance::where('activity_id', $kegiatanHariIni->id)
                ->where('member_id', $user->member->id)
                ->exists();
        }

        return view('dashboard', compact(
            'totalSekolah', 'totalAnggota', 'totalKegiatan', 'totalMateri', 'totalSoal',
            'kegiatanTerbaru', 'pengumumanTerbaru', 'materiTerbaru', 'kegiatanHariIni', 'isHadirHariIni'
        ));
    }
}
