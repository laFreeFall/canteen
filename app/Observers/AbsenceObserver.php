<?php

namespace App\Observers;

use App\Absence;
use App\DayDishPupil;

class AbsenceObserver
{
    /**
     * Handle to the absence "created" event.
     *
     * @param  \App\Absence  $absence
     * @return void
     */
    public function created(Absence $absence)
    {
        DayDishPupil::where([
            'day_id' => $absence->day_id,
            'pupil_id' => $absence->pupil_id
        ])->delete();
    }

    /**
     * Handle the absence "updated" event.
     *
     * @param  \App\Absence  $absence
     * @return void
     */
    public function updated(Absence $absence)
    {
        //
    }

    /**
     * Handle the absence "deleted" event.
     *
     * @param  \App\Absence  $absence
     * @return void
     */
    public function deleted(Absence $absence)
    {
        //
    }
}
