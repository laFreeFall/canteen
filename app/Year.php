<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    public function months() {
        return $this->hasMany(Month::Class);
    }
}
