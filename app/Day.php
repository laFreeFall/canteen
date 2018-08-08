<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Day extends Model
{

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'name_id' => 'integer',
        'week_id' => 'integer',
        'month_id' => 'integer',
        'number' => 'integer',
        'active' => 'boolean'
    ];


    public function week() {
        return $this->belongsTo(Week::class);
    }

    public function month() {
        return $this->belongsTo(Month::class);
    }

    public function name() {
        return $this->belongsTo(DayName::class, 'name_id');
    }

    static public function getCurrent($time = null) {
        $givenDay = $time ? Carbon::parse($time) : Carbon::now();

        if($month = Month::whereNumber($givenDay->month)->exists()) {
            $month = $month->first();
        } else {
            return [
                'day' => Day::first(),
                'week' => Week::first(),
                'month' => Month::first()
            ];
        }

        if($day = $month->days()->where('number', $givenDay->day)->exists()) {
            $day = $day->first();
        } else {
            return [
                'day' => Day::first(),
                'week' => Week::first(),
                'month' => Month::first()
            ];
        }

        return [
            'day' => $day,
            'week' => $day->week,
            'month' => $month
        ];
    }

    public function formatOutput($withDayAbbr = false) {
        $output = sprintf('%02d', $this->number) . '.' . sprintf('%02d', $this->month->number);
        return $withDayAbbr ? $this->name->abbr . ' (' . $output . ')' : $output;
    }
}
