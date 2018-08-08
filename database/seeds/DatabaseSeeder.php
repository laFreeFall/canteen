<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(YearsTableSeeder::class);
        $this->call(MonthsTableSeeder::class);
        $this->call(WeeksTableSeeder::class);
        $this->call(DaysNamesTableSeeder::class);
        $this->call(DaysTableSeeder::class);
        $this->call(DishesTableSeeder::class);
        $this->call(GradesTableSeeder::class);
        $this->call(PupilsTableSeeder::class);
        $this->call(DayDishPupilTableSeeder::class);
        $this->call(DayDishPriceTableSeeder::class);
        $this->call(TemplatesTableSeeder::class);
    }
}
