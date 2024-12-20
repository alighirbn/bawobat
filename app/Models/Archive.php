<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = ['image_path'];

    public function archivable()
    {
        return $this->morphTo();
    }
}
