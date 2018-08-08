<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Month extends Model
{
    public function year() {
        return $this->belongsTo(Year::class);
    }

    public function weeks() {
        return $this->hasMany(Week::class);
    }

    public function days() {
        return $this->hasMany(Day::class);
    }
}
