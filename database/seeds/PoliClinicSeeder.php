<?php

use Illuminate\Database\Seeder;
use App\Models\PoliClinic;
use App\Models\Hospital;

class PoliClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $polies = [
            'Poli Gigi',
            'Poli THT',
            'Poli Penyakit Dalam',
            'Poli Penyakit Kulit',
            'Poli Anak',
            'Poli Umum',
        ];

        // TODO: remove conditional 'where'
        $hospitals = Hospital::where('id', '!=', 'deaaa25d-dcd5-4d76-99d1-9b90247d6904')
            ->get()
            ->pluck('id')
            ->all();

        foreach ($hospitals as $hospital) {
            foreach ($polies as $poly) {
                PoliClinic::create([
                    'full_name' => $poly,
                    'hospital_id' => $hospital,
                ]);
            }
        }
    }
}
