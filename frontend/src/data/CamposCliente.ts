import { CampoForm } from "@/interfaces/CamposForm";

export const camposCliente: CampoForm[] = [
        {
            label: 'Nombre',
            modelKey: 'nombre',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu nombre'
        },
        {
        label: 'Teléfono',
            modelKey: 'telefono',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu telefono',
            min:10,
            pattern: '^[0-9]{10}$'
        },
        {
            label: 'Email',
            modelKey: 'email',
            type: 'email',
            required: true,
            placeholder: 'Ingresa tu email'
        },
        {
            label:'Municipio',
            modelKey: 'municipio',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu municipio',
        },
        {
            label: 'Colonia',
            modelKey: 'colonia',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu colonia'
        },
        {
            label:'Código postal',
            modelKey: 'codigo_postal',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu codigo postal',
            min:5,
            max:5
        },
        {
            label: 'Calle',
            modelKey: 'calle',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu calle'
        },{
            label: 'Número exterior',
            modelKey: 'numero_exterior',
            type: 'text',
            required: true,
            placeholder: 'Ingresa tu número exterior'
        },    
];