<?php

namespace App\Listeners;

use App\Events\ReclamarCuenta;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\MailjetClass;
use App\MailTemplate;
use App\Models\Token;
use Carbon\Carbon;class ReclamarCuentaList
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
    public function handle(ReclamarCuenta $event): void
    {
        $randomToken = bin2hex(random_bytes(40));
        Token::create([
            'user_id'=>$event->user->id,
            'token'=>hash('sha256',$randomToken),
            'type'=>'reclamar',
            'expired_at'=>Carbon::now()->addDay(2),
        ]);
        $baseURl = config('app.baseUrl').'/reclamacion?token='.$randomToken;
        $this->mailjet->from()->to(['Email'=>$event->user->email,'Name'=>$event->user->nombre])->subject('Reclamar cuenta')->htmlContent(MailTemplate::ReclamarCuenta($baseURl,$event->user))->send();
    }
}
