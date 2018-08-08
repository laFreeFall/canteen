<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pupil;

class PupilsController extends Controller
{
    public function store(Request $request)
    {
        return Pupil::create(
            $request->only(['first_name', 'last_name', 'grade_id', 'gender'])
        );
    }

    public function update(Pupil $pupil, Request $request)
    {
        $pupil->update(
            $request->only(['first_name', 'last_name', 'grade_id', 'gender'])
        );
        return $pupil;
    }

    public function destroy(Pupil $pupil)
    {
        if ($pupil->trashed()) {
            $pupil->restore();
        } else {
            $pupil->delete();
        }
        return $pupil;
    }
}
