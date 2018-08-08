<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Week;
use App\Balance;

class BalancesController extends Controller
{
    public function move(Week $week) {
        if($week->id > 1) {
            // $balances = Week::find($week->id - 1)->balances;
            $balances = Balance::where('week_id', $week->id - 1)->get();
            $balances->transform(function($item, $key) {
                unset($item['id']);
                unset($item['debt']);
                unset($item['wasted']);
                unset($item['left']);
                $item['week_id'] = $item['week_id'] + 1;
                $item['initial_amount'] = $item['current_amount'];
                return $item;
            });
            return $balances;
            Balance::insert($balances->toArray());
            
            return $week->balances;
        }
    }
}
