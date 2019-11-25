<?php

use Illuminate\Database\Seeder;

use App\Doctor;
use App\Hospital;
use App\PoliClinic;
use Faker\Factory;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = Hospital::get()->pluck('id')->all();
        $policlinics = PoliClinic::get()->pluck('id')->all();

        $faker = Factory::create();

        for ($i=0; $i<50; $i++) {
            Doctor::create([
                'full_name' => "Dr. " . $faker->firstName,
                'hospital_id' => $faker->randomElement($hospitals),
                'poli_id' => $faker->randomElement($policlinics)
            ]);
        }

    }
}
