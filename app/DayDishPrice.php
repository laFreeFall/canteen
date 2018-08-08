<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Day;

class DayDishPrice extends Model
{
    protected $table = 'day_dish_price';

    protected $guarded = [];
    
    public $timestamps = false;

    protected $casts = [
        'day_id' => 'integer',
        'dish_id' => 'integer'
    ];

    public function scopeForWeek($query, $week) {
        return $query->whereIn('day_id', $week->days()->pluck('id'));
    }

    public function day() {
        return $this->belongsTo(Day::class);
    }
}
