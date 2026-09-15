<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function scan($token)
    {
        $activity = Activity::where('qr_code_token', $token)->firstOrFail();
        $user = auth()->user();

        if (! $user || ! $user->member) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai anggota guru terlebih dahulu untuk melakukan presensi.');
        }

        Attendance::firstOrCreate(
            ['activity_id' => $activity->id, 'member_id' => $user->member->id],
            ['metode' => 'QR_CODE', 'keterangan' => 'HADIR']
        );

        return redirect()->route('activities.show', $activity->id)->with('success', 'Presensi berhasil dicatat! Terima kasih telah hadir.');
    }
}
