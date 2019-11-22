<?php

use Illuminate\Database\Seeder;
use App\PoliClinic;

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

        foreach ($polies as $poly) {
            PoliClinic::create([
                'full_name' => $poly,
                'hospital_id' => '71536672-f5bd-40ac-a659-d34d4f428aec'
            ]);
        }
    }
}
