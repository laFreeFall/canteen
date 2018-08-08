<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'day_id' => 'integer',
        'pupil_id' => 'integer'
    ];

    public function scopeForWeek($query, $week) {
        return $query->whereIn('day_id', $week->days()->pluck('id'));
    }
}
