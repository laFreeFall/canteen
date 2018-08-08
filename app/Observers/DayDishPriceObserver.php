<?php

namespace App\Observers;

use App\DayDishPrice;
use App\DayDishPupil;
use Illuminate\Support\Facades\DB;

class DayDishPriceObserver
{
    /**
     * Handle to the day dish price "created" event.
     *
     * @param  \App\DayDishPrice  $dayDishPrice
     * @return void
     */
    public function created(DayDishPrice $dayDishPrice)
    {
        
        $pupils = DayDishPupil::where([
            'day_id' => $dayDishPrice->day_id,
            'dish_id' => $dayDishPrice->dish_id
        ])->pluck('pupil_id');
        $weekId = $dayDishPrice->day->week->id;
        $pupils = array_count_values($pupils->toArray());
        foreach($pupils as $pupilId => $count) {
            $sum = $dayDishPrice->price * $count;
            DB::table('balances')->where(['week_id' => $weekId, 'pupil_id' => $pupilId])->decrement('current_amount', $sum);
        }

    }

    /**
     * Handle the day dish price "updated" event.
     *
     * @param  \App\DayDishPrice  $dayDishPrice
     * @return void
     */
    public function updated(DayDishPrice $dayDishPrice)
    {
        $pupils = DayDishPupil::where([
            'day_id' => $dayDishPrice->day_id,
            'dish_id' => $dayDishPrice->dish_id
        ])->pluck('pupil_id');
        $weekId = $dayDishPrice->day->week->id;
        $pupils = array_count_values($pupils->toArray());
        foreach($pupils as $pupilId => $count) {
            $sum = ($dayDishPrice->getOriginal('price') - $dayDishPrice->price) * $count;
            DB::table('balances')->where(['week_id' => $weekId, 'pupil_id' => $pupilId])->decrement('current_amount', $sum);
        }
    }

}
