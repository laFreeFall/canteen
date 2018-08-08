<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dish;

class DishesController extends Controller
{
    public function store(Request $request)
    {
        return Dish::create(
            $request->only(['title', 'abbr', 'icon', 'accusative', 'action'])
        );
    }

    public function update(Dish $dish, Request $request)
    {
        $dish->update(
            $request->only(['title', 'abbr', 'icon', 'accusative', 'action'])
        );
        return $dish;
    }

    public function destroy(Dish $dish)
    {
        if ($dish->trashed()) {
            $dish->restore();
        } else {
            $dish->delete();
        }
        return $dish;
    }

    public function swap(Request $request) {
        $firstElement = Dish::where('order_id', $request->from)->first();
        $secondElement = Dish::where('order_id', $request->to)->first();

        $firstElement->order_id = $request->to;
        $firstElement->save();

        $secondElement->order_id = $request->from;
        $secondElement->save();

        return [
            'dishes' => [
                $firstElement,
                $secondElement
            ]
        ];
    }
}
