<?php

namespace App\Listeners;

use App\Events\PasswordSetter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Token;
use Carbon\Carbon;
use App\MailTemplate;
use App\MailjetClass;
class PasswordSetterList
{
    /**
     * Create the event listener.
     */
    public function __construct(public MailjetClass $mailjet)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordSetter $event): void
    {
        $randomToken = bin2hex(random_bytes(40));
        Token::create([
            'user_id'=>$event->user->id,
            'token'=>hash('sha256',$randomToken),
            'type'=>'recuperar',
            'expired_at'=>Carbon::now()->addDay(2),
        ]);
        $baseURl = config('app.baseUrl').'/reclamacion?token='.$randomToken;
        $this->mailjet->from()->to(['Email'=>$event->user->email,'Name'=>$event->user->nombre])->subject('reestablecer contraseña')->htmlContent(MailTemplate::PasswordReset($baseURl,$event->user))->send();
    }
}
