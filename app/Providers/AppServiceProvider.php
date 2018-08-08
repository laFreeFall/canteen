<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Absence;
use App\Observers\AbsenceObserver;
use App\DayDishPupil;
use App\Observers\DayDishPupilObserver;
use App\Payment;
use App\Observers\PaymentObserver;
use App\DayDishPrice;
use App\Observers\DayDishPriceObserver;
use App\Pupil;
use App\Observers\PupilObserver;
use App\Dish;
use App\Observers\DishObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Absence::observe(AbsenceObserver::class);
        DayDishPupil::observe(DayDishPupilObserver::class);
        Payment::observe(PaymentObserver::class);
        DayDishPrice::observe(DayDishPriceObserver::class);
        Pupil::observe(PupilObserver::class);
        Dish::observe(DishObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
