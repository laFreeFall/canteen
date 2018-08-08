<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\DayDishPrice;
use App\Day;

class Dish extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;

    public function prices() {
        return $this->hasMany(DayDishPrice::class);
    }
    
    public function priceForDay(Day $day) {
        return ($this->prices->where('day_id', $day->id)->count()) > 0
            ? $this->prices->firstWhere('day_id', $day->id)->price
            : 0;
    }
}
