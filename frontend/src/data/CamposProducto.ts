import { CampoForm } from "../interfaces/CamposForm";

export const camposProducto: CampoForm[] = [
    {
        label: 'Nombre',
        modelKey: 'nombre',
        type: 'text',
        required: true,
        placeholder: 'Nombre del producto'
    },
    {
        label: 'Marca',
        modelKey: 'marca',
        type: 'text',
        required: true,
        placeholder: 'Marca del producto'
    },
    {
        label: 'Medida',
        modelKey: 'medida',
        type: 'select',
        required: true,
        options: [
            { label: 'gramos', value: 'gramos' },
            { label: 'kilos', value: 'kilos' },
            { label: 'mililitros', value: 'mililitros' },
            { label: 'litros', value: 'litros' },
            { label: 'unidad', value: 'unidad' }
        ]
    },
    {
        label: 'Cantidad',
        modelKey: 'cantidad',
        type: 'number',
        required: true,
        min: 0,
        pattern: '^\\d+$'
    },
    {
        label: 'Stock',
        modelKey: 'stock',
        type: 'number',
        required: false,
        min: 0,
        pattern: '^\\d+$'
    },
    {
        label: 'Precio de compra($)',
        modelKey: 'precio_compra',
        type: 'number',
        required: true,
        placeholder: '0.00',
        min: 0,
        pattern: '^\\d+(\\.\\d+)?$'
    },
    {
        label: 'Precio de venta($)',
        modelKey: 'precio_venta',
        type: 'number',
        required: true,
        placeholder: '0.00',
        min: 0,
        pattern: '^\\d+(\\.\\d+)?$'
    },
    {
        label: 'Visibilidad',
        modelKey: 'visibilidad',
        type: 'select',
        required: true,
        options: [
            { label: 'visible', value: 'visible' },
            { label: 'invisible', value: 'invisible' }
        ]
    }


];
