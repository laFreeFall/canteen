<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $appends = [
        'debt',
        'left',
        'wasted'
    ];

    protected $casts = [
        'week_id' => 'integer',
        'pupil_id' => 'integer',
        'initial_amount' => 'integer',
        'current_amount' => 'integer'
    ];

    public function getDebtAttribute() {
        return $this->current_amount < 0 ? abs($this->current_amount) : 0;
    }

    public function getLeftAttribute() {
        return $this->current_amount > 0 ? $this->current_amount : 0;
    }

    public function getWastedAttribute() {
        return abs($this->current_amount - $this->initial_amount);
    }
}
