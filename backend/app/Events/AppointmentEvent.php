<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\User;
use App\Models\Cita;
use Illuminate\Support\Facades\Log;
class AppointmentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     */
    public ?int $trabajadorid;
    public ?int $clientid;
    public function __construct(
        public array $data,
        public Cita $cita,
        public User $user,
        public string $action
    )
    {
    $cita->load([
        'consulta.consultaServicios.ConsultaServicioProductos.producto'=>function($q){$q->withTrashed();},
        'consulta.consultaServicios.servicio'=>function($q){$q->withTrashed();},
        'horarioTrabajador.trabajador.user',
        'horarioTrabajador.horario',
        'mascota.cliente.user',
        'mascota.raza.animal'
    ]);

    $this->trabajadorid = $cita->horarioTrabajador?->trabajador?->user?->id;
    $this->clientid = $cita->mascota?->cliente?->user?->id;

    }



    public function broadcastAs(): string
    {
        return 'AppointmentEvent';
    }
    public function broadcastOn(): array
    {

            $channels = [];
            $userid = $this->user->id;
            if($this->trabajadorid && $this->trabajadorid != $userid){
                $channels[] = new PrivateChannel('App.Appointment.User.'.$this->trabajadorid);
            }

            if($this->clientid && $this->clientid != $userid){
                $channels[] = new PrivateChannel('App.Appointment.User.'.$this->clientid);
            }

            $admins = User::whereHas('rol', fn($q) => $q->where('nombre', 'administrador'))->get();

            foreach($admins as $admin){
                if( $admin->id != $userid){
                    $channels[] = new PrivateChannel('App.Appointment.Admin.'.$admin->id); // ← $admin->id no $this->user->id
                }
            }

            return $channels;
    }

    public function broadcastWith(){
       return [
            'data'=>$this->data,
            'cita'=>$this->cita,
            'sendBy'=>$this->user,
            'action'=>$this->action
            ];
    }
}
