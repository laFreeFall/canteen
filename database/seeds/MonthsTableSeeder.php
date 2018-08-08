<?php

use Illuminate\Database\Seeder;

class MonthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('months')->insert([
            [
                'number' => '9',
                'year_id' => '1',
                'title' => 'Сентябрь',
                'genitive' => 'сентября',
            ],
            [
                'number' => '10',
                'year_id' => '1',
                'title' => 'Октябрь',
                'genitive' => 'октября'
            ],
            [
                'number' => '11',
                'year_id' => '1',
                'title' => 'Ноябрь',
                'genitive' => 'ноября'
            ],
            [
                'number' => '12',
                'year_id' => '1',
                'title' => 'Декабрь',
                'genitive' => 'декабря'
            ],
            [
                'number' => '1',
                'year_id' => '2',
                'title' => 'Январь',
                'genitive' => 'января'
            ],
            [
                'number' => '2',
                'year_id' => '2',
                'title' => 'Февраль',
                'genitive' => 'февраля'
            ],
            [
                'number' => '3',
                'year_id' => '2',
                'title' => 'Март',
                'genitive' => 'марта'
            ],
            [
                'number' => '4',
                'year_id' => '2',
                'title' => 'Апрель',
                'genitive' => 'апреля'
            ],
            [
                'number' => '5',
                'year_id' => '2',
                'title' => 'Май',
                'genitive' => 'мая'
            ],
        ]);
    }
}
