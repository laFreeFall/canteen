<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Day;
use App\Dish;
use App\Pupil;

class DayDishPupil extends Model
{
    protected $table = 'day_dish_pupil';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'day_id' => 'integer',
        'dish_id' => 'integer',
        'pupil_id' => 'integer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'template_apply_id',
    ];

    public function scopeForWeek($query, $week) {
        return $query->whereIn('day_id', $week->days()->pluck('id'));
    }

    public function day() {
        return $this->belongsTo(Day::class);
    }

    public function dish() {
        return $this->belongsTo(Dish::class);
    }

    public function pupil() {
        return $this->belongsTo(Pupil::class);
    }
}
