<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test login.
     *
     * @return void
     */
    public function testLogin()
    {
        $login = true;

        $this->assertTrue($login);

    }
}
