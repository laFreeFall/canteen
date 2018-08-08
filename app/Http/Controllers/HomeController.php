<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pupil;
use App\Grade;
use App\Dish;
use App\Month;
use App\Day;
use App\DayName;
use App\Week;
use App\Template;
use App\DishPupilTemplate;
use Illuminate\Support\Facades\DB;
use Batch;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // #TODO: move dayDishPupil & dayDishPrice to ReportsController,
        // because we don't need to load all the results, and, if we don't want to,
        // we don't know for which exact week should we load,
        // so it'll be nicer just to load it in ReportsController@index,
        // which already accepts week id
        $initialState = [
            'pupils' => Pupil::withTrashed()
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(),
            'grades' => Grade::all(),
            'dishes' => Dish::withTrashed()->orderBy('id')->get(),
            'calendar' => Month::with('weeks.days.month', 'year')->get(),
            'daysNames' => DayName::all(),
            'currentDate' => Day::getCurrent(),
            'weeksLimits' => [
                'begin' => 1,
                'end' => Week::count()
            ],
            'templates' => Template::all(),
            'dishPupilTemplate' => DishPupilTemplate::all()
        ];

        return view('layouts.app', compact('initialState'));
    }

    public function test()
    {
        $dish = Dish::create([
            'title' => 'test',
            'abbr' => 'T',
            'icon' => 'cube',
            'accusative' => 'test',
            'action' => 'test'
        ]);
        return $dish;
    }
}
