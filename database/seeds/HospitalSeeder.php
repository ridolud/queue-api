<?php

use Illuminate\Database\Seeder;
use App\Hospital;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hospital::create([
            'full_name' => 'RSIA Putra Dalima',
            'phone_number' => '(021) 5380375',
            'address' => 'Jalan Rawa Buntu Utara, Sektor I.2 Blok UA No.26-27, Rawa Buntu, Serpong, Rw. Buntu, Kec. Serpong, Kota Tangerang Selatan, Banten 15311',
            'latitude' => '-6.301843',
            'longitude' => '106.679778',
            'province_id' => 36,
            'city_id' => 3674,
            'photo' => 'putradalima.jpg',
            'id' => '93f39f66-bf4e-4378-ba27-85aa61ab279f'
        ]);

    }
}
