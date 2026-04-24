<?php

namespace App\Mail;

use App\Services\ReporteGeneralService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from:'alejandropuenmacha123@gmail.com',
            subject: 'Reports Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'ReportMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $reportes = new ReporteGeneralService();
        $request = (object) ['fecha_inicio'=>Carbon::now(),'fecha_fin'=>Carbon::now()];
        $data1 = $reportes->getReporte($request);
        $data2 = $reportes->getReporte2($request);
         
        $data3 = 

        $pdf = Pdf::loadView('ReportsPDF',['data1'=>$data1,'data2'=>$data2]);

        return [
        Attachment::fromData(fn() => $pdf->output(), 'report.pdf')
            ->withMime('application/pdf'),
    ];
    }
}
