<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DayDishPupil;
use App\Pupil;
use App\DayDishPrice;

class PupilsDishesController extends Controller
{
    public function store(Request $request)
    {
        // return response('false', 422);
        return DayDishPupil::create(
            $request->only(['day_id', 'dish_id', 'pupil_id'])
        );
    }

    public function destroy(DayDishPupil $dayDishPupil)
    {
        // return response('false', 422);
        return strval($dayDishPupil->delete());
    }

    public function price(Request $request) {
        return DayDishPrice::updateOrCreate($request->only(['day_id', 'dish_id']), $request->only(['price']));
    }
}
