<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $guarded = ['id'];
    
    public function tableSessions()
    {
        return $this->hasMany(TableSession::class);
    }
}
