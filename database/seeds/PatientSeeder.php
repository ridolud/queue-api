<?php

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use Faker\Factory;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $blood_type = [
          'A',
          'B',
          'AB',
          'O'
        ];

        $auth_id = User::get()->pluck('id')->all();

        for ($i=0; $i<10; $i++) {
            Patient::create([
                'full_name'     => $faker->name,
                'mother_name'   => $faker->name,
                'identity_number' => $faker->randomNumber(6),
                'dob'           => $faker->date('Y-m-d'),
                'gender'        => $faker->boolean,
                'blood_type'    => $faker->randomElement($blood_type),
                'address'       => $faker->address,
                'auth_id'       => $faker->randomElement($auth_id)
            ]);
        }

    }
}
