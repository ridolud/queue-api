<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListTodayQueue extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/api/v1/admin/queue/index/deaaa25d-dcd5-4d76-99d1-9b90247d6904/9ee1f382-8c3f-4c56-bf37-cd5441caefc1');

        $response->assertJsonStructure([
            "current_page",
            "data" => [
                [
                    "queue_id",
                    "user_id",
                    "patient_id",
                    "doctor_schedule_id",
                    "is_valid",
                    "submit_time",
                    "insurance_id",
                    "process_status",
                    "day",
                    "id",
                    "doctor_id",
                    "time_start",
                    "time_end",
                    "full_name",
                    "phone_number",
                    "address",
                    "latitude",
                    "longitude",
                    "photo",
                    "province_id",
                    "city_id",
                    "hospital_id",
                    "mother_name",
                    "identity_number",
                    "dob",
                    "gender",
                    "blood_type",
                    "auth_id",
                    "created_at",
                    "updated_at",
                    "identity_photo",
                    "patient_fullname",
                    "hospital_fullname",
                    "insurance_fullname",
                    "doctor_fullname",
                    "poli_fullname",
                    "queue_remaining"
                 ]
            ],
            "first_page_url",
            "from",
            "last_page",
            "last_page_url",
            "next_page_url",
            "path",
            "per_page",
            "prev_page_url",
            "to",
            "total"
        ]);

        $response->assertStatus(200);
    }
}
