<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Balance;
use App\Week;

class Pupil extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;
    
    public function balances() {
        return $this->hasMany(Balance::class);
    }

    public function balanceForWeek(Week $week) {
        return $this->balances->firstWhere('week_id', $week->id);
    }

    public function getShortNameAttribute() {
        return $this->last_name . ' ' . mb_substr($this->first_name, 0, 1) . '.';
    }

    public function getFullNameAttribute() {
        return $this->last_name . ' ' . $this->first_name;
    }
}
