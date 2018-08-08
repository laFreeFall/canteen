<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DayDishPupil;

class DayDishPupilController extends Controller
{
    public function store(Request $request)
    {
        return DayDishPupil::create(
            $request->only(['day_id', 'dish_id', 'pupil_id'])
        );
    }

    public function destroy(DayDishPupil $dayDishPupil)
    {
        return strval($dayDishPupil->delete());
    }
}
