<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DayDishPrice;

class DayDishPriceController extends Controller
{
    public function store(Request $request) {
        return DayDishPrice::create(
            $request->only(['day_id', 'dish_id', 'price'])
        );
    }

    public function update(DayDishPrice $dayDishPrice, Request $request) {
        $dayDishPrice->price = $request->price;
        $dayDishPrice->save();
        return $dayDishPrice;
    }
}
