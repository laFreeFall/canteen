<?php

use Illuminate\Database\Seeder;

class DayDishPriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('day_dish_price')->insert([
            [
                'day_id' => 1,
                'dish_id' => 1,
                'price' => 620
            ],
            [
                'day_id' => 1,
                'dish_id' => 2,
                'price' => 410
            ],
            [
                'day_id' => 1,
                'dish_id' => 3,
                'price' => 160
            ],
            [
                'day_id' => 1,
                'dish_id' => 4,
                'price' => 57
            ],
            [
                'day_id' => 2,
                'dish_id' => 1,
                'price' => 740
            ],
            [
                'day_id' => 2,
                'dish_id' => 2,
                'price' => 403
            ],
            [
                'day_id' => 2,
                'dish_id' => 3,
                'price' => 155
            ],
            [
                'day_id' => 2,
                'dish_id' => 4,
                'price' => 64
            ],
            [
                'day_id' => 3,
                'dish_id' => 1,
                'price' => 635
            ],
            [
                'day_id' => 3,
                'dish_id' => 2,
                'price' => 440
            ],
            [
                'day_id' => 3,
                'dish_id' => 3,
                'price' => 180
            ],
            [
                'day_id' => 3,
                'dish_id' => 4,
                'price' => 75
            ]
        ]);
    }
}
