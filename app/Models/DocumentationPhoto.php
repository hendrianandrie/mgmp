<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationPhoto extends Model
{
    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo(DocumentationAlbum::class, 'album_id');
    }
}
