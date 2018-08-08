<?php

use Illuminate\Database\Seeder;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'title' => 'Шаблон 1 (Б)'
            ],
            [
                'title' => 'Шаблон 2 (П+Б)'
            ],
        ];

        DB::table('templates')->insert($templates);

        $dishPupilTemplate = [
            [
                'dish_id' => 3,
                'pupil_id' => 1,
                'template_id' => 1
            ],
            [
                'dish_id' => 3,
                'pupil_id' => 2,
                'template_id' => 1
            ],
            [
                'dish_id' => 3,
                'pupil_id' => 3,
                'template_id' => 1
            ],
            [
                'dish_id' => 3,
                'pupil_id' => 3,
                'template_id' => 1
            ],
            [
                'dish_id' => 3,
                'pupil_id' => 4,
                'template_id' => 1
            ],
            [
                'dish_id' => 1,
                'pupil_id' => 1,
                'template_id' => 2
            ],
            [
                'dish_id' => 3,
                'pupil_id' => 3,
                'template_id' => 2
            ],
            [
                'dish_id' => 1,
                'pupil_id' => 5,
                'template_id' => 2
            ],
            [
                'dish_id' => 3,
                'pupil_id' => 5,
                'template_id' => 2
            ]
        ];

        DB::table('dish_pupil_template')->insert($dishPupilTemplate);

        
    }
}
