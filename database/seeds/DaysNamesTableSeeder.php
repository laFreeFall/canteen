<?php

use Illuminate\Database\Seeder;

class DaysNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('days_names')->insert([
            [
                'title' => 'Понедельник',
                'abbr' => 'Пн',
                'action' => 'в понедельник'
            ],
            [
                'title' => 'Вторник',
                'abbr' => 'Вт',
                'action' => 'во вторник'
            ],
            [
                'title' => 'Среда',
                'abbr' => 'Ср',
                'action' => 'в среду'
            ],
            [
                'title' => 'Четверг',
                'abbr' => 'Чт',
                'action' => 'в четверг'
            ],
            [
                'title' => 'Пятница',
                'abbr' => 'Пт',
                'action' => 'в пятницу'
            ],
            [
                'title' => 'Суббота',
                'abbr' => 'Сб',
                'action' => 'в субботу'
            ],
            [
                'title' => 'Воскресенье',
                'abbr' => 'Вс',
                'action' => 'в воскресенье'
            ]
        ]);
    }
}
