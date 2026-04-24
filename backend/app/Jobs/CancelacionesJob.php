<?php

namespace App\Jobs;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CancelacionesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Cita::where('estado','agendada')->where('fecha',Carbon::now()->format('Y-m-d'))->update(['estado'=>'cancelada']);
    }
}
