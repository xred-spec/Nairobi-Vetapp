export interface CampoForm {
    label: string;
    modelKey: string;
    type: 'text' | 'number' | 'select' | 'email' | 'password' | 'date' | 'textarea'| 'tel';
    options?: {
        label: string;
        value: number | string;
    }[];
    placeholder?: string;
    required?: boolean; 
    disabled?: boolean;
    min?: number;
    relation?: string; // para saber que endpoint usar
    max?: number;
    pattern?: string;
    rows?: number;
    dependsOn?: string;
    filterFn?: (value: any, formData: any) => any[];
}