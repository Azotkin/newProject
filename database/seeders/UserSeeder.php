<?php

namespace Database\Seeders;

use App\Models\StateUser;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');
        $educations = [
            'Основное',
            'Высшее',
            'Среднее профессиональное',
            'Среднее общее',
        ];
        $genders = [
            'Мужской'=>'male',
            'Женский'=>'female',
        ];

        for ($i = 1; $i <= 110; $i++) {
            $gender = array_rand($genders, 1);
            $name = explode(' ', $faker->name($genders[$gender]));
            DB::table('state_users')->insert([
                'firstname' => $name[0],
                'surname' => $name[1],
                'lastname' => $name[2],
                'gender' => $gender,
                'age' => rand(15, 65),
                'education' => $educations[array_rand($educations, 1)],
                'city' => $faker->city(),
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ]);
        }

    }
}
