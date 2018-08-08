<?php

namespace App\Observers;

use App\DayDishPupil;

class DayDishPupilObserver
{
    /**
     * Handle to the day dish pupil "created" event.
     *
     * @param  DayDishPupil  $dayDishPupil
     * @return void
     */
    public function created(DayDishPupil $dayDishPupil)
    {
        $balance = $dayDishPupil->pupil->balanceForWeek($dayDishPupil->day->week);
        $balance->decrement('current_amount', $dayDishPupil->dish->priceForDay($dayDishPupil->day));
    }

    /**
     * Handle the day dish pupil "deleted" event.
     *
     * @param  DayDishPupil  $dayDishPupil
     * @return void
     */
    public function deleted(DayDishPupil $dayDishPupil)
    {
        $balance = $dayDishPupil->pupil->balanceForWeek($dayDishPupil->day->week);
        $balance->increment('current_amount', $dayDishPupil->dish->priceForDay($dayDishPupil->day));
    }
}
