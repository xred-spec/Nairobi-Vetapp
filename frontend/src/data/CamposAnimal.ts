import {CampoForm} from "../interfaces/CamposForm";

export const camposAnimal: CampoForm[] =[
    {
        label:'Nombre',
        modelKey:'nombre',
        type:'text',
        required:true,
        placeholder:'Ingresa el nombre del animal'
    },
    {
        label:'Visibilidad',
        modelKey:'visibilidad',
        type:'select',
        required:true,
        options: [
            {label: 'visible', value: 'visible'}, 
            {label: 'invisible', value: 'invisible'}
        ]
    }
];