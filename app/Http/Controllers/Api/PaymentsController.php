<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;

class PaymentsController extends Controller
{
    public function store(Request $request) {
        return Payment::updateOrCreate(
            $request->only(['pupil_id', 'week_id']),
            $request->only(['amount'])
        );
    }
}
