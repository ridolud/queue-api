<?php

use Illuminate\Database\Seeder;
use App\Models\Insurance;
use App\Models\Hospital;
use Faker\Factory;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = Hospital::where('id', '!=', 'deaaa25d-dcd5-4d76-99d1-9b90247d6904')
            ->get()
            ->pluck('id')
            ->all();

        $insurances = [
            ['UnitedHealthcare Group', 'https://thumbor.forbes.com/thumbor/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fi.forbesimg.com%2Fmedia%2Flists%2Fcompanies%2Funitedhealth-group_416x416.jpg'],
            ['Anthem', 'https://s11284.pcdn.co/wp-content/uploads/2015/10/Anthem-Blue-Cross.png'],
            ['Aetna', 'https://media.licdn.com/dms/image/C4E0BAQFhA8h46hvabA/company-logo_200_200/0?e=2159024400&v=beta&t=bD7FQoJwHmnvOtS0WE1l5jDcsiBGoWAu68A0YFn25HQ'],
            ['Cigna', 'https://cdn2.tstatic.net/jatim/foto/bank/images/asuransi-kesehatan-cigna_20170518_203332.jpg'],
            ['Humana', 'https://www.rootfin.com/wp-content/uploads/2019/10/Humana.jpg'],
            ['Blue Cross Blue Shield of Alabama', 'https://alchetron.com/cdn/blue-cross-and-blue-shield-of-alabama-82ddebd8-41e1-4698-b421-f4f73d63a8e-resize-750.jpeg'],
            ['Premera Blue Cross', 'https://130e178e8f8ba617604b-8aedd782b7d22cfe0d1146da69a52436.ssl.cf1.rackcdn.com/judge-gives-go-ahead-for-settlement-premera-breach-case-showcase_image-9-a-12865.jpg'],
            ['Centene Corp.', 'https://www.saversmarketing.com/wp-content/uploads/2018/02/Centene.png'],
            ['Arkansas Blue Cross Blue Shield', 'https://media.licdn.com/dms/image/C4E0BAQGFjcB0O2fkzA/company-logo_200_200/0?e=2159024400&v=beta&t=3AGjo1ex-4ddtDCSZy3IIs-MMJl9z0Ap4hp9VSQd4M0'],
            ['Blue Cross Blue Shield California', 'https://policyalerts.com/wp-content/uploads/2017/09/BlueShield_California.png'],
            ['Kaiser Foundation', 'https://pbs.twimg.com/profile_images/971039409540878337/2n-HSVxP_400x400.jpg'],
            ['Emblem Health', 'https://www.expertinsurancereviews.com/wp-content/uploads/2017/07/Emblem-Health-300.png'],
            ['Highmark', 'https://www.expertinsurancereviews.com/wp-content/uploads/2017/07/Highmark-300-1.png'],
            /*'Carefirst Inc.',
            'Guidewell Mutual Health',
            'Centene Corp.',
            'Hawaii Medical Service',
            'Blue Cross of Idaho',
            'Health Care Service Corp.',
            'Caresource',
            'Wellmark Inc.',
            'Blue Cross Blue Shield of Kansas',
            'Anthem Inc.',
            'Louisiana Medical Serv',
            'Maine Comm Health Options',
            'Carefirst Inc.',
            'Tufts',
            'HealthPartners'*/
        ];

        foreach ($hospitals as $key => $hospital) {
            foreach ($insurances as $insurance) {
                Insurance::create([
                    'full_name' => $insurance[0],
                    'photo' => $insurance[1],
                    'hospital_id' => $hospital
                ]);
            }
        }
    }
}
