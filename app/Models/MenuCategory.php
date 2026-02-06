<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $guarded = ['id'];
    
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
