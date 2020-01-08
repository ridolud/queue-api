<?php

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Faker\Factory;

class DoctorAvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $avatars = [
            'https://pixabay.com/get/57e1d14a4b56a514f1dc8460825668204022dfe054597540772e7bd7/doctor-1149149_640.jpg',
            'https://pixabay.com/get/54e1d1424252a414f1dc8460825668204022dfe054597540772e79d0/nurse-2141808_640.jpg',
            'https://pixabay.com/get/52e6d2444e52ad14f1dc8460825668204022dfe054597540772e7ed3/anesthesia-4677401_640.jpg',
            'https://pixabay.com/get/54e1d044435baf14f1dc8460825668204022dfe054597540772e73dc/dr-2157993_640.jpg',
            'https://pixabay.com/get/54e1dc4a485ab10ff3d89938b977692b083edbe25b55784b772d7a/man-219928_640.jpg',
            'https://pixabay.com/get/57e1dc424c55ad14f1dc8460825668204022dfe054597540772d7ed1/dentist-1191671_640.jpg',
            'https://pixabay.com/get/54e1d34a425ab10ff3d89938b977692b083edbe25b55784b77277d/woman-216988_640.jpg'
        ];

        $doctors = Doctor::all();

        $faker = Factory::create();

        foreach ($doctors as $doctor) {
            Doctor::where('id', $doctor->id)
                ->update([
                    'avatar' => $faker->randomElement($avatars)
                ]);
        }
    }
}
