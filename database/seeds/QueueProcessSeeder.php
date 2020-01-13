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
        $users = User::where('id', '!=', 42)->get()->pluck('id')->all();
        $patients = Patient::where('id', '!=', 63)->limit(3)->get()->pluck('id')->all();
        $insurances = Insurance::get()->pluck('id')->all();
        $schedule = DoctorSchedule::first()->id;

        $faker = Factory::create();

        foreach ($patients as $patient){
            QueueProcess::create([
                'user_id' => $faker->randomElement($users),
                'patient_id' => $patient,
                'insurance_id' => $faker->randomElement($insurances),
                'doctor_schedule_id' => '08377387-8fa8-400c-93bc-ec22b88e453c',
                'is_valid' => QueueEnum::Valid,
                'submit_time' => Carbon::now(\App\Enums\TimeConfigEnum::zone),
                'process_status' => QueueEnum::waiting,
            ]);
        }
    }
}
