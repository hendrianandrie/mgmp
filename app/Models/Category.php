<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
