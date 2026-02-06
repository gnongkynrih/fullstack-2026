<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = ['id'];

     /**
     * Get the parent imageable model (property, location, etc.)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
