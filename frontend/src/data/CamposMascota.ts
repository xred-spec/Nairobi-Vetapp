import { CampoForm } from "@/interfaces/CamposForm";

export const camposMascota: CampoForm[] = [
    {
        label:'Nombre',
        modelKey:'nombre',
        type:'text',
        required:true,
        placeholder:'Ingresa el nombre del animal'
    },
    {
        label:'Sexo',
        modelKey:'sexo',
        type:'select',
        required:true,
        options: [
            { label: 'macho', value: 'macho' },
            { label: 'hembra', value: 'hembra' },
        ]
    },
    {
        label: 'Peso(Kg)',
        modelKey: 'peso',
        type: 'number',
        required: false,
        min: 0,
        pattern: '^\\d+$'
    },
    {
        label: 'Fecha de nacimiento',
        modelKey: 'fecha_nacimiento',
        type: 'date',
        required: true,
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
        label: 'Raza',
        modelKey: 'raza_id',
        type: 'select',
        placeholder: 'Seleccione una raza',
        relation: 'raza',
        dependsOn: 'animal_id',
        required: true,
        options: []
    },
    {
        label: 'Dueño',
        modelKey: 'cliente_id',
        type: 'select',
        placeholder: 'Seleccione un dueño',
        relation: 'dueño',
        required: true,
        options: []
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
    },
    {
        label: 'Descripción',
        modelKey: 'descripcion',
        type: 'textarea',
        rows: 5,
        required: true,
        placeholder: 'Descripcion de la mascota...'
    }
]