<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
