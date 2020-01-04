<?php


namespace App\Http\Controllers\TraitController;


use App\Enums\ResponseCodeEnum;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendNotification;
use App\Enums\NotificationTypeEnum;
use App\Enums\NotificationCategoryEnum;
use App\Libs\Helper;

trait VerifiesEmails
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::find($request->id);

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {

            self::sendNotifIfVerivied($user->device_token);
            
            return view('verified-email');
        }

        if ($user->markEmailAsVerified()) {

            self::sendNotifIfVerivied($user->device_token);

            event(new Verified($request->user()));
        }

        return view('verified-email');
    }

    private function sendNotifIfVerivied($device_token) {

        $type = NotificationTypeEnum::normal;
        $title = "Email anda berhasil diverifikasi";
        $category = NotificationCategoryEnum::user_email_verified;

        SendNotification::dispatch($device_token, Helper::setMessageNotification($type, $title, $category))
           ->delay(now()->addSeconds(15))
           ->onQueue('send-notification');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Email verification have been sent',
            'data' =>  [
                'resend' => true
            ],
        ], ResponseCodeEnum::Success);
    }
}
