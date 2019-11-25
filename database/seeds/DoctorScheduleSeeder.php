<?php

use Illuminate\Database\Seeder;

use App\DoctorSchedule;
use App\Doctor;
use Faker\Factory;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $doctors = Doctor::get()->pluck('id')->all();
        $days = [
          'Senin',
          'Selasa',
          'Rabu',
          'Kamis',
          'Jum\'at',
        ];
        $faker = Factory::create();

        foreach ($doctors as $doctor) {
            DoctorSchedule::create([
                'day' => $faker->randomElement($days),
                'time' => $faker->date('h:m') . ' - ' . $faker->date('h:m'),
                'doctor_id' => $doctor
            ]);
        }
    }
}
