<?php

use Illuminate\Database\Seeder;

class DayDishPupilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('day_dish_pupil')->insert([
            [
                'day_id' => 1,
                'dish_id' => 1,
                'pupil_id' => 1
            ],
            [
                'day_id' => 1,
                'dish_id' => 1,
                'pupil_id' => 1
            ],
            [
                'day_id' => 1,
                'dish_id' => 2,
                'pupil_id' => 1
            ],
            [
                'day_id' => 2,
                'dish_id' => 1,
                'pupil_id' => 1
            ],
            [
                'day_id' => 2,
                'dish_id' => 3,
                'pupil_id' => 1
            ],
            [
                'day_id' => 1,
                'dish_id' => 1,
                'pupil_id' => 2
            ],
            [
                'day_id' => 1,
                'dish_id' => 2,
                'pupil_id' => 2
            ],[
                'day_id' => 1,
                'dish_id' => 3,
                'pupil_id' => 2
            ],
            [
                'day_id' => 3,
                'dish_id' => 3,
                'pupil_id' => 3
            ],
        ]);
    }
}
