<?php

namespace App\Listeners;

use App\Events\UserRegister;
use App\MailjetClass;
use App\MailTemplate;
use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegisterListener
{
    /**
     * Create the event listener.
     */

    public function __construct(public MailjetClass $mailjet)
    {

    }

    /**
     * Handle the event.
     */
    public function handle(UserRegister $event): void
    {
        $randomToken = bin2hex(random_bytes(40));
        Token::create([
            'user_id'=>$event->user->id,
            'token'=>hash('sha256',$randomToken),
            'type'=>'confirmar',
            'expired_at'=>Carbon::now()->addDay(2),
        ]);
        $baseURl = config('app.baseUrl').'/verification?token='.$randomToken;
        $this->mailjet->from()->to(['Email'=>$event->user->email,'Name'=>$event->user->nombre])->subject('Confirmar cuenta')->htmlContent(MailTemplate::EmailVerification($baseURl,$event->user))->send();
    }
}
