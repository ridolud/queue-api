<?php

use Illuminate\Database\Seeder;
use App\Models\Insurance;
use App\Models\Hospital;
use Faker\Factory;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $hospital = Hospital::get()->pluck('id')->all();
        $insurances = [
            'UnitedHealthcare Group',
            'Anthem',
            'Aetna',
            'Cigna',
            'Humana',
            'Blue Cross Blue Shield of Alabama',
            'Premera Blue Cross',
            'Centene Corp.',
            'Arkansas Blue Cross Blue Shield',
            'Blue Cross Blue Shield California',
            'Kaiser Foundation',
            'Emblem Health',
            'Highmark',
            'Carefirst Inc.',
            'Guidewell Mutual Health',
            'Centene Corp.',
            'Hawaii Medical Service',
            'Blue Cross of Idaho',
            'Health Care Service Corp.',
            'Caresource',
            'Wellmark Inc.',
            'Blue Cross Blue Shield of Kansas',
            'Anthem Inc.',
            'Louisiana Medical Serv',
            'Maine Comm Health Options',
            'Carefirst Inc.',
            'Tufts',
            'HealthPartners'
        ];

        $faker = Factory::create();


        foreach ($insurances as $insurance) {
            Insurance::create([
                'full_name' => $insurance,
                'hospital_id' => $faker->randomElement($hospital)
            ]);
        }
    }
}
