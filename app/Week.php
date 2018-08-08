<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Day;
use App\Month;
use App\Balance;
use App\Payment;

class Week extends Model
{
    // protected $with = ['days.name', 'days.month'];

    public function days() {
        return $this->hasMany(Day::class);
    }

    public function activeDays() {
        return $this->days->where('active', true);
    }

    public function month() {
        return $this->belongsTo(Month::class);
    }

    public function balances() {
        return $this->hasMany(Balance::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    static public function getCurrent($time = null) {
        $givenDay = $time ? Carbon::parse($time) : Carbon::now();

        if($month = Month::whereNumber($givenDay->month)->exists()) {
            $month = $month->first();
        } else {
            return Week::first()->load('days.name', 'days.month');
        }

        if($day = $month->days()->where('number', $givenDay->day)->exists()) {
            $day = $day->first();
        } else {
            return Week::first()->load('days.name', 'days.month');
        }

        return $day->week->load('days.name', 'days.month');
    }

    public function daysLimits($onlyActiveDays = true) {
        $weekDays = $this->days()->with('month')->where('active', true)->get();

        return '(' . $weekDays->first()->formatOutput() . ' - ' . $weekDays->last()->formatOutput() . ')';
    }
}
