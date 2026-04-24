import {CampoForm} from "../interfaces/CamposForm";

export const CamposServicios: CampoForm[] =[
    {
        label:'Nombre',
        modelKey:'nombre',
        type:'text',
        required:true,
        placeholder:'Ingresa el nombre del servicio'
    },
    {
        label: 'Descripcion',
        modelKey: 'descripcion',
        type: 'textarea',
        rows: 3,
        required: true,
        placeholder: 'Descripcion del servicio...'
    },
    {
        label: 'visibilidad',
        modelKey: 'visibilidad',
        type: 'select',
        required: true,
        options: [
            {label: 'visible', value: 'visible'}, 
            {label: 'invisible', value: 'invisible'}
        ]
    }
];