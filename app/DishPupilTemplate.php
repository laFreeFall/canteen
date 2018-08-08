<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishPupilTemplate extends Model
{
    protected $table = 'dish_pupil_template';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'dish_id' => 'integer',
        'pupil_id' => 'integer',
        'template_id' => 'integer'
    ];
}
