<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Batch;
use App\Template;
use App\Day;
use App\DayDishPupil;
use App\Balance;
use App\DayDishPrice;
use App\Pupil;
use App\TemplateApply;
use App\DishPupilTemplate;

class TemplatesController extends Controller
{
    public function store(Request $request)
    {
        return Template::create(
            $request->only(['title'])
        );
    }

    public function update(Template $template, Request $request)
    {
        $template->update($request->only(['title']));
        return $template;
    }

    public function destroy(Template $template)
    {
        return strval($template->delete());
    }

    public function apply(Template $template, Request $request)
    {
        $templateItems = $template->items;
        $day = Day::find($request->day_id);
        $dayId = $day->id;
        $weekId = $day->week->id;

        $templateApply = TemplateApply::create([
            'template_id' => $template->id,
            'day_id' => $dayId
        ]);

        $dayDishPupilArrayToInsert = [];
        foreach($templateItems as $templateItem) {
            $dayDishPupilArrayToInsert[] = [
                'day_id' => $dayId,
                'dish_id' => $templateItem->dish_id,
                'pupil_id' => $templateItem->pupil_id,
                'template_apply_id' => $templateApply->id
            ];
        }
        DayDishPupil::insert($dayDishPupilArrayToInsert);

        $balances = Balance::where('week_id', $weekId)->get();
        $balancesArrayToUpdate = [];
        $prices = DayDishPrice::where('day_id', $dayId)->get();
        $pupils = Pupil::all();
        foreach($pupils as $pupil) {
            $pupilItems = $templateItems->where('pupil_id', $pupil->id);
            $totalPrice = 0;
            if($pupilItems->count()) {
                foreach($pupilItems as $pupilItem) {
                    $itemPrice = $prices->where('dish_id', $pupilItem->dish_id);
                    if($itemPrice->count()) {
                        $totalPrice += $itemPrice->first()->price;
                    }
                }
                $currentBalance = $balances->where('pupil_id', $pupil->id)->where('week_id', $weekId)->first();
                $balancesArrayToUpdate[] = [
                    'id' => $currentBalance->id,
                    'current_amount' => $currentBalance->current_amount - $totalPrice
                ];

                Batch::update('balances', $balancesArrayToUpdate, 'id');
            }
        }

        return DayDishPupil::where('template_apply_id', $templateApply->id)->get();
    }

    public function clone(Template $template, Request $request)
    {
        $createdTemplate = Template::create($request->only(['title']));

        $createdTemplateItems = $template->items()
            ->get(['dish_id', 'pupil_id', 'template_id'])
            ->map(function($templateItem) use($createdTemplate) {
                $templateItem->template_id = $createdTemplate->id;
                return $templateItem;
            })
            ->toArray();
        DishPupilTemplate::insert($createdTemplateItems);

        return [
            'template' => $createdTemplate,
            'templateItems' => $createdTemplate->fresh()->items
        ];
    }
}
