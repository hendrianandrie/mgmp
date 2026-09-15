<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationAlbum extends Model
{
    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function photos()
    {
        return $this->hasMany(DocumentationPhoto::class, 'album_id');
    }
}
