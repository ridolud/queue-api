<?php

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use Faker\Factory;
use App\Models\Province;
use App\Models\City;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $provinces = Province::limit(4)->get()->pluck('id')->all();
        $cities = City::limit(4)->get()->pluck('id')->all();

        $arr_hospital = [
            'RS Prima Husada',
            'RS Pertamina Jaya',
            'RS Eka Hospital',
            'Rumah Sakit Selaras',
            'RSIA Intana Medika',
            'RS Hermina Serpong',
            'RS Pandia',
            'RS Medika BSD',
            'RS Bunda Dalima',
            'RS Pondok Indah Bintaro',
            'Hospital Avisena',
            'Premier Bintaro Hospital',
            'RS Bersalin Permata Ibu',
            'OMNI Hospital Alam Sutera'
        ];

        $arr_image = [
            'https://lh3.googleusercontent.com/LpOmv8eDcq1oUDi-Hj33p8khvyVrhCgiaf6Z1jJwR3amBT9dBn9fnY7MFbs1Tc6sqF4nBgmI=w600-h0',
            'https://static.guesehat.com/static/directories_thumb/1224_Rumah_Sakit_Pertamina_Jaya_(RSPJ).jpg',
            'https://serpongku.com/wp-content/uploads/2018/08/Eka-Hospital-BSD-City.jpg',
            'https://rs-selaras.com//templates/images/ourhospital/selaras-cisauk.png',
            'https://i0.wp.com/ppk.stikku.ac.id/wp-content/uploads/2016/08/intanmedika-300x150.jpg?resize=364%2C182',
            'https://www.herminahospitalgroup.com/storage/infos/telah-dibuka-rs-hermina-purwokerto-11-Wz8kl.jpg',
            'https://static.konsula.com/images/practice/0001001000/0001000668/rumah-sakit-ibu-and-anak-budhi-jaya.800x600.jpg',
            'https://res.cloudinary.com/dk0z4ums3/image/upload/w_360,h_240,c_fill,dpr_3.0/v1537253802/hospital_image/26231073_1634519349949037_6166847709445102428_n.jpg.jpg',
            'https://lh3.googleusercontent.com/p/AF1QipMB3gFL-gCqq8LzPUVbu7HqwvIQZZkMBoQvid2W=s1600-w1000',
            'https://i.ytimg.com/vi/rT2JFQa5cbE/maxresdefault.jpg',
            'https://www.medisify.com/uploads/images/hospitals/avisena-specialist-hospital.jpg',
            'https://res.cloudinary.com/dk0z4ums3/image/upload/w_360,h_240,c_fill,dpr_3.0/v1499765924/hospital_image/RS-Bintaro-Hospital-%20resize.jpg.jpg',
            'https://static.konsula.com/images/practice/0001001000/0001000651/rumah-sakit-permata-ibu-tangerang.800x600.jpg',
            'https://static.guesehat.com/static/directories_thumb/69332_Omni_Hospital_Alam_Sutera.jpg'
        ];

        foreach ($arr_hospital as $key => $item) {
            Hospital::create([
                'full_name' => $item,
                'phone_number' => $faker->tollFreePhoneNumber,
                'address' => $faker->address,
                'latitude' => '-6.301843',
                'longitude' => '106.679778',
                'province_id' => $faker->randomElement($provinces),
                'city_id' => $faker->randomElement($cities),
                'photo' => $arr_image[$key]
            ]);
        }


    }
}
