<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function position()
    {
        return $this->hasOne(Position::class);
    }

    public function getNamaDenganGelarAttribute(): string
    {
        $depan = $this->gelar_depan ? trim($this->gelar_depan).' ' : '';
        $belakang = $this->gelar_belakang ? ', '.trim($this->gelar_belakang) : '';

        return $depan.$this->nama_lengkap.$belakang;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
