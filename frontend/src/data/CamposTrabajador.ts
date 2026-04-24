import { CampoForm } from "@/interfaces/CamposForm";

export const camposTrabajador: CampoForm[] = [
    {
        label: 'Nombre',
        modelKey: 'nombre',
        type: 'text',
        required: true,
        placeholder: 'Ingresa el nombre del trabajador'
    },
    {
        label: 'Email',
        modelKey: 'email',
        type: 'email',       
        required: true,
        placeholder: 'Ingresa el correo del trabajador'
    },
    {
        label: 'Teléfono',
        modelKey: 'telefono',
        type: 'text',
        required: true,
        placeholder: 'Ingresa el teléfono del trabajador'
    },
    /*{
    label: 'Horarios',
    modelKey: 'horarios',  
    type: 'multiselect',   
    required: true,
    options: []            
    }*/

];