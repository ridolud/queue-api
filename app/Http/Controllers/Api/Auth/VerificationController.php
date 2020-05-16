<?php


namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use \App\Http\Controllers\TraitController\VerifiesEmails;
use App\Models\User;
use App\Enums\ResponseCodeEnum;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function reSendVeriesEmails(Request $request) {
        $current_user = User::where('email', $request->email)->first();

        if (!$current_user) {
            return response()->json([
                'error' => 'User not found',
            ], ResponseCodeEnum::NotFound);
        }

        $current_user->sendEmailVerificationNotification();
        return response()->json('email verification has been send', ResponseCodeEnum::Success);
    }

}
