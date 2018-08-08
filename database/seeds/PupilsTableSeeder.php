<?php

use Illuminate\Database\Seeder;

class PupilsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestampNow = date("Y-m-d H:i:s");
        $pupils = [
            ['last_name' => 'Иванов', 'first_name' => 'Никита', 'gender' => true, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Петров', 'first_name' => 'Дмитрий', 'gender' => true, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Сидорова', 'first_name' => 'Карина', 'gender' => false, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Смирнова', 'first_name' => 'Татьяна', 'gender' => false, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Попов', 'first_name' => 'Александр', 'gender' => true, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Кузнецова', 'first_name' => 'Валерия', 'gender' => false, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Соколов', 'first_name' => 'Михаил', 'gender' => true, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Васильев', 'first_name' => 'Владислав', 'gender' => true, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Михайлов', 'first_name' => 'Павел', 'gender' => true, 'grade_id' => '13', 'created_at' => $timestampNow],
            ['last_name' => 'Федорова', 'first_name' => 'Елена', 'gender' => false, 'grade_id' => '13', 'created_at' => $timestampNow],
        ];
        DB::table('pupils')->insert($pupils);

        $balances = [];
        for($i = 1; $i <= count($pupils); $i++) {
            $balances[] = [
                'pupil_id' => $i,
                'week_id' => 1,
                'initial_amount' => 0,
                'current_amount' => 0
            ];
        }
        $balances[0]['current_amount'] = -2545;
        $balances[1]['current_amount'] = -1190;
        $balances[2]['current_amount'] = -180;
        DB::table('balances')->insert($balances);
    }
}
