<?php

use Illuminate\Database\Seeder;
use App\PoliClinic;
use App\Hospital;

class PoliClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $polies = ['Poli Gigi', 'Poli THT', 'Poli Ginjal', 'Poli Penyakit Jiwa', 'Poli Gami', 'Poli Andri', 'Poli Ponik', 'Poli Gon', 'Poli Tron'];

        $hospital = Hospital::first();

        foreach ($polies as $poly) {
            PoliClinic::create([
                'full_name' => $poly,
                'hospital_id' => $hospital->id,
            ]);
        }
    }
}
