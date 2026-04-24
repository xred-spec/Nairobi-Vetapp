import {CampoForm} from "../interfaces/CamposForm";
export const camposReclamar:CampoForm[] = 
     [
        {
            label: 'Email',
            modelKey: 'email',
            type: 'email',
            required: true,
            placeholder: 'Ingresa tu email'
        },
        {
            label: 'telefono',
            modelKey: 'telefono',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu telefono'
        }
    ];