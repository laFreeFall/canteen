<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Week;
use App\DayDishPupil;
use App\DayDishPrice;
use App\Absence;
use App\Balance;
use App\Pupil;
use App\Payment;
use App\Dish;

class ReportsController extends Controller
{
    public function index(Week $week) {
        $weekDaysIds = $week->days()->pluck('id');
        return [
            'dayDishPupil' => DayDishPupil::whereIn('day_id', $weekDaysIds)->get(),
            'dayDishPrice' => DayDishPrice::whereIn('day_id', $weekDaysIds)->get(),
            'absences' => Absence::whereIn('day_id', $weekDaysIds)->get(),
            'balances' => $week->balances,
            'payments' => $week->payments,
            'week' => $week->load('days.name', 'days.month')
        ];
    }

    public function download(Week $week) {
        $document = new \PhpOffice\PhpWord\PhpWord();

        // Setting landscape orientation
        $wrapperSection = $document->addSection([
            'orientation' => 'landscape',
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(40),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(40),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(60),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(60)
        ]);

        // Setting default font settings
        $document->setDefaultFontName('Times New Roman');
        $document->setDefaultFontSize(12);
        $document->getSettings()->setDecimalSymbol(',');

        // Settings document properties
        $properties = $document->getDocInfo();
        $properties->setCreator('Людмила Лобода');
        $properties->setCompany('ОШ №2');
        $properties->setTitle('Отчёт по питанию');
        $properties->setLastModifiedBy('Людмила Лобода');
        $properties->setKeywords('отчёт, отчет, питание');

        $spaceBeforeAndAfter = \PhpOffice\PhpWord\Shared\Converter::pointToTwip(3);

        $heading = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'paragraph' => [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(10),
                'spaceAfter' => 0
            ]
        ];


        $innerTableData = [
            'centered' => [
                'font' => [],
                'paragraph' => [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceBefore' => $spaceBeforeAndAfter,
                    'spaceAfter' => $spaceBeforeAndAfter
                ]
            ],

            'bold' => [
                'font' => [
                    'bold' => true
                ],
                'paragraph' => [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'spaceBefore' => $spaceBeforeAndAfter,
                    'spaceAfter' => $spaceBeforeAndAfter,
                    'indent' => 0.138888889
                ]
            ],

            'boldCentered' => [
                'font' => [
                    'bold' => true
                ],
                'paragraph' => [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceBefore' => $spaceBeforeAndAfter,
                    'spaceAfter' => $spaceBeforeAndAfter
                ]
            ],

            'default' => [
                'font' => [],
                'paragraph' => [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'spaceBefore' => $spaceBeforeAndAfter,
                    'spaceAfter' => $spaceBeforeAndAfter,
                    'indent' => 0.138888889
                ]
            ],

            'totalDishAbbr' => [
                'font' => [
                    'size' => 12
                ],
            ],
            'totalDishAmount' => [
                'font' => [
                    'size' => 10
                ],
            ]
        ];

        // Styles for tables
        $table = [
            'table' => [
                'borderSize' => 1,
                'borderColor' => '000',
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
                // 'cellMarginLeft' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(5)
            ],
            'firstRow' => [
                'bgColor' => 'd3d3d3'
            ],
            'cell' => [
                'valign' => \PhpOffice\PhpWord\Style\Cell::VALIGN_CENTER
            ]
        ];

        // Styles for horizontal line
        $line = [
            'weight' => 1,
            'width' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(600),
            'height' => 0,
            'color' => 'd3d3d3'
        ];

        $tableStyles = 'Default Table';
        $document->addTableStyle($tableStyles, $table['table'], $table['firstRow']);

        $titleStyles = 'Default style';
        $document->addTitleStyle($titleStyles, $heading['font'], $heading['paragraph']);

        // widths of cols of both tables
        $widths = [
            'main' => [
                'pupil' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(100),
                'payment' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(60),
                'day' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(100),
                'wasted' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(60),
                'debt' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(60),
                'left' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(60)
            ],
            'secondary' => [
                'dish' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(80),
                'day' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(80)
            ]
        ];

        // Outputting main table header text
        $mainHeaderText = $week->id . ' неделя ' . $week->daysLimits();
        $wrapperSection->addTitle($mainHeaderText, $titleStyles);

        // Fetching data for main table
        $weekDays = $week->days()
            ->with('name')
            ->where('active', true)
            ->get();
        $pupils = Pupil::orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        $balances = Balance::where('week_id', $week->id)->get();
        $payments = Payment::where('week_id', $week->id)->get();
        $dayDishPupil = DayDishPupil::with('dish:id,abbr,order_id')->get();
        $dayDishPrice = DayDishPrice::whereIn('day_id', $weekDays->pluck('id')
            ->toArray())
            ->get();
        
        // Outputting main table
        $mainTable = $wrapperSection->addTable($tableStyles);
 
        // Outputing main table first header row
        $mainTable->addRow();
        $mainTable->addCell($widths['main']['pupil'], $table['cell'])
            ->addText('Ученик', $innerTableData['default']['font'], $innerTableData['default']['paragraph']);
        $mainTable->addCell($widths['main']['payment'], $table['cell'])
            ->addText('Заплатил', $innerTableData['centered']['font'], $innerTableData['centered']['paragraph']);
        foreach($weekDays as $day) {
            $mainTable->addCell($widths['main']['day'], $table['cell'])
                ->addText($day->formatOutput(true), $innerTableData['centered']['font'], $innerTableData['centered']['paragraph']);
        }
        $mainTable->addCell($widths['main']['wasted'], $table['cell'])
            ->addText('Потратил', $innerTableData['centered']['font'], $innerTableData['centered']['paragraph']);
        $mainTable->addCell($widths['main']['debt'], $table['cell'])
            ->addText('Долг', $innerTableData['centered']['font'], $innerTableData['centered']['paragraph']);
        $mainTable->addCell($widths['main']['left'], $table['cell'])
            ->addText('Остаток', $innerTableData['centered']['font'], $innerTableData['centered']['paragraph']);

        // Outputting main table content
        foreach($pupils as $pupil) {
            if(!$dayDishPupil->where('pupil_id', $pupil->id)->count() && $pupil->trashed()) {
                continue;
            }
            $mainTable->addRow();

            $mainTable->addCell($widths['main']['pupil'], $table['cell'])
                ->addText($pupil->short_name, $innerTableData['default']['font'], $innerTableData['default']['paragraph']);

            $payment = ($payments->where('pupil_id', $pupil->id)->count() > 0)
                ? $payments->where('pupil_id', $pupil->id)->first()->amount
                : 0;
            $mainTable->addCell($widths['main']['payment'], $table['cell'])
                ->addText($this->formatMoney($payment), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

                foreach($weekDays as $day) {
                $dishes = $dayDishPupil->where('pupil_id', $pupil->id)
                    ->where('day_id', $day->id)
                    ->sortBy('dish.order_id')
                    ->pluck('dish.abbr')
                    ->toArray();
                $glue = count($dishes) > 4 ? ' ' : '  ';
                $mainTable->addCell($widths['main']['day'], $table['cell'])->addText(implode($glue, $dishes), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);
            }

            $wasted = $balances->where('pupil_id', $pupil->id)
                ->first()
                ->wasted;
            $mainTable->addCell($widths['main']['wasted'], $table['cell'])
                ->addText($this->formatMoney($wasted), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

            $debt = $balances->where('pupil_id', $pupil->id)
                ->first()
                ->debt;
            $mainTable->addCell($widths['main']['debt'], $table['cell'])
                ->addText($this->formatMoney($debt), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

            $left = $balances->where('pupil_id', $pupil->id)
                ->first()
                ->left;
            $mainTable->addCell($widths['main']['left'], $table['cell'])
                ->addText($this->formatMoney($left), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);
        }

        // Outputing main table last footer row
        $mainTable->addRow();

        $mainTable->addCell($widths['main']['pupil'], $table['cell'])
            ->addText('Итого: ', $innerTableData['bold']['font'], $innerTableData['bold']['paragraph']);

        $mainTable->addCell($widths['main']['payment'], $table['cell'])
            ->addText($this->formatMoney($payments->sum('amount')), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

        foreach($weekDays as $day) {
            $textRun = $mainTable->addCell($widths['main']['day'], $table['cell'])->addTextRun($innerTableData['boldCentered']['paragraph']);
            $dishes = $dayDishPupil->where('day_id', $day->id)->sortBy('dish.order_id');
            $dishesAbbrs = array_count_values($dishes->pluck('dish.abbr')->toArray());
            $string = '';
            foreach($dishesAbbrs as $dish => $amount) {
                $textRun->addText($amount, $innerTableData['totalDishAmount']['font']);
                $textRun->addText($dish, $innerTableData['totalDishAbbr']['font']);
                $textRun->addText(' ');
                $string .= $dish . $amount . '  ';
            }
            $textRun->addTextBreak();
            $dishesPrice = 0;
            foreach($dishes as $dish) {
                $price = ($dayDishPrice->where('day_id', $day->id)->where('dish_id', $dish->dish_id)->count() > 0)
                    ? $dayDishPrice->where('day_id', $day->id)->where('dish_id', $dish->dish_id)->first()->price
                    : 0;
                $dishesPrice += $price;
            }
            $dishesPrice = $this->formatMoney($dishesPrice);
            $textRun->addText($dishesPrice, $innerTableData['boldCentered']['font']);
            $string .= $dishesPrice;
            // $mainTable->addCell($widths['main']['day'], $table['cell'])
                // ->addText($string, $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);
        }
        $mainTable->addCell($widths['main']['wasted'], $table['cell'])
            ->addText($this->formatMoney($balances->sum('wasted')), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

        $mainTable->addCell($widths['main']['debt'], $table['cell'])
            ->addText($this->formatMoney($balances->sum('debt')), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

        $mainTable->addCell($widths['main']['left'], $table['cell'])
            ->addText($this->formatMoney($balances->sum('left')), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);

        // Outputting horizontal line
        // $wrapperSection->addLine($line);
        
        // Outputting secondary table header text
        $mainHeaderText = 'Цены на еду на ' . $week->id . '-й неделе ' . $week->daysLimits();
        $wrapperSection->addTitle($mainHeaderText, $titleStyles);

        // Fetching data for secondary table
        $dishes = Dish::all();
        
        // Outputting secondary table
        $secondaryTable = $wrapperSection->addTable($tableStyles);
    
        // Outputing secondary table first header row
        $secondaryTable->addRow();

        $secondaryTable->addCell($widths['secondary']['dish'], $table['cell'])
            ->addText('Блюдо', $innerTableData['default']['font'], $innerTableData['default']['paragraph']);

        foreach($weekDays as $weekDay) {
            $secondaryTable->addCell($widths['secondary']['day'], $table['cell'])
                ->addText($weekDay->name->title, $innerTableData['centered']['font'], $innerTableData['centered']['paragraph']);
        }

        // Outputing secondary table content
        foreach($dishes as $dish) {
            if(! $dayDishPrice->where('dish_id', $dish->id)->count()) {
                continue;
            }

            $secondaryTable->addRow();

            $secondaryTable->addCell($widths['secondary']['dish'], $table['cell'])
                ->addText($dish->title, $innerTableData['default']['font'], $innerTableData['default']['paragraph']);
            foreach($weekDays as $day) {
                $price = ($dayDishPrice->where('day_id', $day->id)->where('dish_id', $dish->id)->count() > 0)
                    ? $dayDishPrice->where('day_id', $day->id)->where('dish_id', $dish->id)->first()->price
                    : 0;
                $secondaryTable->addCell($widths['secondary']['day'], $table['cell'])
                    ->addText($this->formatMoney($price), $innerTableData['boldCentered']['font'], $innerTableData['boldCentered']['paragraph']);
            }
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($document, 'Word2007');
        $objWriter->save('report.docx');

        $documentName = $week->id . '(' . $weekDays->first()->formatOutput() . '-' . $weekDays->last()->formatOutput() . ')';
        return response()->download('report.docx', "{$documentName}.docx")->deleteFileAfterSend(true);
    }

    private function formatMoney($money) {
        return number_format($money / 100, 2) . '₴';
    }
}
