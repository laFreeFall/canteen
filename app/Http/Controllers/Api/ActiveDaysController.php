<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Day;
use App\Month;

class ActiveDaysController extends Controller
{
    public function store(Day $day)
    {
        $day->active = true;
        $day->save();

        return [
            'calendar' => Month::with('weeks.days.month', 'year')->get()
        ];
    }

    public function destroy(Day $day)
    {
        $day->active = false;
        $day->save();

        return [
            'calendar' => Month::with('weeks.days.month', 'year')->get()
        ];
    }
}
