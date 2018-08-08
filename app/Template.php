<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DishPupilTemplate;

class Template extends Model
{
    protected $guarded = [];

    public $timestamps = false;
    
    public function items() {
        return $this->hasMany(DishPupilTemplate::class);
    }
}
