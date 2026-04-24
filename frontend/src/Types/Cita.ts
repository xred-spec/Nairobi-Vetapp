export interface Cita{
    id: number;
    fecha: string;   
    estado: 'agendada' | 'en_proceso' | 'realizada' | 'cancelada';
    descripcion?: string;
    mascota:{
        nombre: string;
    };
    horario_trabajador: {
        horario:{
            inicio_hora: string;
        }
    }
}