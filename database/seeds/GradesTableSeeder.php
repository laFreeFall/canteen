<?php

use Illuminate\Database\Seeder;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->insert([
            ['title' => '5-А'],
            ['title' => '5-Б'],
            ['title' => '5-В'],
            ['title' => '6-А'],
            ['title' => '6-Б'],
            ['title' => '6-В'],
            ['title' => '7-А'],
            ['title' => '7-Б'],
            ['title' => '7-В'],
            ['title' => '8-А'],
            ['title' => '8-Б'],
            ['title' => '8-В'],
            ['title' => '9-А'],
            ['title' => '9-Б'],
            ['title' => '9-В'],
            ['title' => '10-А'],
            ['title' => '10-Б'],
            ['title' => '10-В'],
            ['title' => '11-А'],
            ['title' => '11-Б'],
            ['title' => '11-В'],

        ]);
    }
}
