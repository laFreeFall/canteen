<?php

use Illuminate\Database\Seeder;

class DishesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dishes')->insert([
            [
                'title' => 'Порция',
                'abbr' => 'П',
                'order_id' => 1,
                'icon' => 'utensils',
                'color' => '#fff',
                'accusative' => 'порцию',
                'action' => 'съел',
                'gender' => false
            ],
            [
                'title' => 'Чай',
                'abbr' => 'Ч',
                'order_id' => 2,
                'icon' => 'coffee',
                'color' => '#fff',
                'accusative' => 'чай',
                'action' => 'выпил',
                'gender' => true
            ],
            [
                'title' => 'Булочка',
                'abbr' => 'Б',
                'order_id' => 3,
                'icon' => 'lemon',
                'color' => '#fff',
                'accusative' => 'булочку',
                'action' => 'съел',
                'gender' => false
            ],
            [
                'title' => 'Суп',
                'abbr' => 'С',
                'order_id' => 4,
                'icon' => 'utensil-spoon',
                'color' => '#fff',
                'accusative' => 'суп',
                'action' => 'съел',
                'gender' => true
            ]
        ]);
    }
}
