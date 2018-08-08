<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Absence;

class AbsencesController extends Controller
{
    public function store(Request $request)
    {
        return Absence::create($request->only(['day_id', 'pupil_id']));
    }

    public function destroy(Absence $absence)
    {
        return strval($absence->delete());
    }
}
