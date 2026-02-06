<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $guarded = ['id'];

    //create a relationship with MenuCategory
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }
}
