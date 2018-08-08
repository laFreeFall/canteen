<?php

use Illuminate\Database\Seeder;

class YearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('years')->insert([
            [
                'number' => 2018,
                'title' => '2018'
            ],
            [
                'number' => 2019,
                'title' => '2019'
            ]
        ]);
    }
}
