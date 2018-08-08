<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pupil;
use App\Week;

class Payment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'pupil_id' => 'integer',
        'week_id' => 'integer',
        'amount' => 'integer'
    ];

    public function pupil() {
        return $this->belongsTo(Pupil::class);
    }

    public function week() {
        return $this->belongsTo(Week::class);
    }

    public function safeAmount() {
        if($this->count() > 0) {
            return $this->value;
        } else {
            return 0;
        }
    }
}
