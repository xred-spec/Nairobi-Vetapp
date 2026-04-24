import {CampoForm} from "../interfaces/CamposForm";

export const camposRaza: CampoForm[] =[
    {
        label: 'Nombre',
        modelKey: 'nombre',
        type: 'text',
        required: true,
        placeholder: 'Nombre de la raza'
    },
    {
        label: 'Animal',
        modelKey: 'animal_id',
        type: 'select',
        placeholder: 'Seleccione un animal',
        relation: 'animal',
        required: true,
        options: []
    },
    {
        label: 'Visibilidad',
        modelKey: 'visibilidad',
        type: 'select',
        required: true,
        options: [
            {label: 'Visible', value: 'visible'}, 
            {label: 'Invisible', value: 'invisible'}
        ]
    }
];