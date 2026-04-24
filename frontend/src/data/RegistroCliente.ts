import {CampoForm} from "../interfaces/CamposForm";
export function User(PasswordRequired:boolean):CampoForm[]{
    return [
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
            placeholder: 'Ingresa tu telefono'
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
        {
            label: 'Contraseña',
            modelKey: 'password',
            type: 'password',
            required: PasswordRequired,
            placeholder: 'Ingresa tu contraseña'
        },{
            label:'Confirmar contraseña',
            modelKey: 'passwordConfirmation',
            type: 'password',
            required: PasswordRequired,
            placeholder: 'Confirma tu contraseña'
        },
    ]
}