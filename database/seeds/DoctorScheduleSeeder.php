<?php

use Illuminate\Database\Seeder;

use App\Models\DoctorSchedule;
use App\Models\Doctor;
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

        $morning_start = [
            "07:30:00",
            "09:30:00",
            "08:45:00",
            "10:00:00"
        ];

        $morning_end = [
            "10:30:00",
            "11:20:00",
            "12:40:00",
            "13:50:00"
        ];

        $afternoon_start = [
            "14:00:00",
            "14:30:00",
            "15:10:00",
            "15:45:00"
        ];

        $afternoon_end = [
            "19:00:00",
            "20:00:00",
            "19:30:00",
            "18:45:00"
        ];

        foreach ($doctors as $doctor) {
            DoctorSchedule::create([
                'day' => $faker->randomElement($days),
                'time' => $faker->date('h:m') . ' - ' . $faker->date('h:m'),
                'doctor_id' => $doctor,
                'time_start' => $faker->randomElement($afternoon_start),
                'time_end' => $faker->randomElement($afternoon_end),
            ]);
        }
    }
}
