<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateApply extends Model
{
    protected $table = 'templates_applies';

    protected $guarded = [];

    protected $casts = [
        'template_id' => 'integer',
        'day_id' => 'integer'
    ];
    
}
