<?php

use Illuminate\Database\Seeder;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\PoliClinic;
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
        $hospitals = Hospital::where('id', '!=', 'deaaa25d-dcd5-4d76-99d1-9b90247d6904')
            ->get()
            ->pluck('id')
            ->all();

        $policlinics = PoliClinic::where('id', '!=', 'deaaa25d-dcd5-4d76-99d1-9b90247d6904')
            ->get()
            ->pluck('id')
            ->all();

        $faker = Factory::create();

        foreach ($hospitals as $hospital)
        {
            for ($i=0; $i<20; $i++) {
                Doctor::create([
                    'full_name' => "Dr. " . $faker->firstName . ' ' . $faker->lastName,
                    'hospital_id' => $hospital,
                    'poli_id' => $faker->randomElement($policlinics)
                ]);
            }
        }
    }
}
