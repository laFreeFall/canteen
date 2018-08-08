<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DishPupilTemplate;

class DishPupilTemplateController extends Controller
{
    public function store(Request $request)
    {
        return DishPupilTemplate::create(
            $request->only(['dish_id', 'pupil_id', 'template_id'])
        );
    }

    public function destroy(DishPupilTemplate $dishPupilTemplate)
    {
        return strval($dishPupilTemplate->delete());
    }
}
