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
        $count = 300;
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

        for ($i = 1; $i <= $count; $i++) {
            $gender = array_rand($genders, 1);
            $fullName = explode(' ', $faker->name($genders[$gender]));

            DB::table('state_users')->insert([
                'firstname' => $faker->firstName($genders[$gender]),
                'surname' => $fullName[2],
                'lastname' => $faker->lastName($genders[$gender]),
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
