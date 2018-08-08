<?php

namespace App\Observers;

use App\Dish;

class DishObserver
{
    /**
     * Handle to the dish "created" event.
     *
     * @param  \App\Dish  $dish
     * @return void
     */
    public function created(Dish $dish)
    {
        $dish->order_id = Dish::count();
        $dish->save();
    }

    /**
     * Handle the dish "updated" event.
     *
     * @param  \App\Dish  $dish
     * @return void
     */
    public function updated(Dish $dish)
    {
        //
    }

    /**
     * Handle the dish "deleted" event.
     *
     * @param  \App\Dish  $dish
     * @return void
     */
    public function deleted(Dish $dish)
    {
        $dish->order_id = Dish::withTrashed()->count();
        $dish->save();
    }

        /**
     * Handle the dish "restored" event.
     *
     * @param  \App\Dish  $dish
     * @return void
     */
    public function restored(Dish $dish)
    {
        $dish->order_id = Dish::count();
        $dish->save();

        // Dish::onlyTrashed()->each->
    }
}
