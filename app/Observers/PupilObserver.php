<?php

namespace App\Observers;

use App\Pupil;
use App\Balance;
use App\Week;

class PupilObserver
{
    /**
     * Handle to the pupil "created" event.
     *
     * @param  \App\Pupil  $pupil
     * @return void
     */
    public function created(Pupil $pupil)
    {
        $currentWeekId = Week::getCurrent('2018-09-19 10:23:00')->id;
        $arrayToInsert = [];
        while($currentWeekId > 0) {
            $arrayToInsert[] = [
                'pupil_id' => $pupil->id,
                'week_id' => $currentWeekId,
                'initial_amount' => 0,
                'current_amount' => 0
            ];
            $currentWeekId--;
        }
        Balance::insert($arrayToInsert);
    }

}
