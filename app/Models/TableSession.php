<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableSession extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }
}
