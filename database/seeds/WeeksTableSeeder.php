<?php

use Illuminate\Database\Seeder;

class WeeksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('weeks')->insert([
            ['month_id' => 1],
            ['month_id' => 1],
            ['month_id' => 1],
            ['month_id' => 1],
            ['month_id' => 2],
            ['month_id' => 2],
            ['month_id' => 2],
            ['month_id' => 2],
            ['month_id' => 2],
            ['month_id' => 3],
            ['month_id' => 3],
            ['month_id' => 3],
            ['month_id' => 3],
            ['month_id' => 4],
            ['month_id' => 4],
            ['month_id' => 4],
            ['month_id' => 4],
            ['month_id' => 5],
            ['month_id' => 5],
            ['month_id' => 5],
            ['month_id' => 5],
            ['month_id' => 5],
            ['month_id' => 6],
            ['month_id' => 6],
            ['month_id' => 6],
            ['month_id' => 6],
            ['month_id' => 7],
            ['month_id' => 7],
            ['month_id' => 7],
            ['month_id' => 7],
            ['month_id' => 8],
            ['month_id' => 8],
            ['month_id' => 8],
            ['month_id' => 8],
            ['month_id' => 9],
            ['month_id' => 9],
            ['month_id' => 9],
            ['month_id' => 9],
            ['month_id' => 9]
        ]);
    }
}
