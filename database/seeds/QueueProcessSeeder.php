<?php

use App\Enums\QueueEnum;
use App\Models\QueueProcess;
use App\Models\User;
use App\Models\Insurance;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class QueueProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get()->pluck('id')->all();
        $patients = Patient::get()->pluck('id')->all();
        $insurances = Insurance::get()->pluck('id')->all();
        $schedule = DoctorSchedule::first()->id;

        $faker = Factory::create();

        foreach ($patients as $patient){
            QueueProcess::create([
                'user_id' => $faker->randomElement($users),
                'patient_id' => $patient,
                'insurance_id' => $faker->randomElement($insurances),
                'doctor_schedule_id' => $schedule,
                'is_valid' => QueueEnum::Valid,
                'submit_time' => Carbon::now(),
                'process_status' => QueueEnum::waiting,
            ]);
        }
    }
}
