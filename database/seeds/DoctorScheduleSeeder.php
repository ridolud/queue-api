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
        $doctors = Doctor::get()
            ->pluck('id')
            ->all();

        $days = [
          'Senin',
          'Selasa',
          'Rabu',
          'Kamis',
          'Jum\'at',
        ];

        $faker = Factory::create();

        $morning_start = [
            "07:30",
            "09:30",
            "08:45",
            "10:00"
        ];

        $morning_end = [
            "10:30",
            "11:20",
            "12:40",
            "13:50"
        ];

        $afternoon_start = [
            "14:00",
            "14:30",
            "15:10",
            "15:45"
        ];

        $afternoon_end = [
            "19:00",
            "20:00",
            "19:30",
            "18:45"
        ];

        foreach ($doctors as $doctor) {
            DoctorSchedule::create([
                'day' => $faker->randomElement($days),
                'doctor_id' => $doctor,
                'time_start' => $faker->randomElement($morning_start),
                'time_end' => $faker->randomElement($morning_end),
            ]);
        }
    }
}
