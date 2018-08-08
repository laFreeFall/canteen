<?php

use Illuminate\Http\Request;

Route::get('reports/{week}', 'Api\ReportsController@index');

// Route::post('pupils', 'Api\PupilsController@store');

Route::post('daydishpupil/', 'Api\DayDishPupilController@store');
Route::delete('daydishpupil/{dayDishPupil}', 'Api\DayDishPupilController@destroy');

// Absents
Route::post('absences/', 'Api\AbsencesController@store');
Route::delete('absences/{absence}', 'Api\AbsencesController@destroy');

// Active days
Route::post('days/{day}', 'Api\ActiveDaysController@store');
Route::delete('days/{day}', 'Api\ActiveDaysController@destroy');

// Move data
Route::post('balances/{week}/move', 'Api\BalancesController@move');

// Receive payment
Route::post('payments/store', 'Api\PaymentsController@store');

// Change dish price
Route::post('daydishprice/', 'Api\DayDishPriceController@store');
Route::patch('daydishprice/{dayDishPrice}', 'Api\DayDishPriceController@update');

// Templates
Route::post('templates/', 'Api\TemplatesController@store');
Route::patch('templates/{template}', 'Api\TemplatesController@update');
Route::delete('templates/{template}', 'Api\TemplatesController@destroy');
Route::post('templates/{template}/apply', 'Api\TemplatesController@apply');
Route::post('templates/{template}/clone', 'Api\TemplatesController@clone');

// Templates dishes
Route::post('dishpupiltemplate/', 'Api\DishPupilTemplateController@store');
Route::delete('dishpupiltemplate/{dishPupilTemplate}', 'Api\DishPupilTemplateController@destroy');

// Pupils
Route::post('pupils/', 'Api\PupilsController@store');
Route::patch('pupils/{pupil}', 'Api\PupilsController@update');
Route::delete('pupils/{pupil}', 'Api\PupilsController@destroy');

// Dishes
Route::post('dishes/', 'Api\DishesController@store');
Route::patch('dishes/{dish}', 'Api\DishesController@update');
Route::delete('dishes/{dish}', 'Api\DishesController@destroy');
Route::post('dishes/swap', 'Api\DishesController@swap');