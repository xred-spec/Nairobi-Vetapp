import rules, { validate } from '@/validations/rules';







export function registerValidator(formData:Record<string,string>){
    const errors: Record<string,string>={};
    
    Object.entries(formData).forEach(([key,value]) => {
       const result = validate(value,getRules(key,formData));
       if(!result.isValid){
        errors[key]=result.error;
       }
    });
    return {
        isValid: Object.keys(errors).length === 0,
        errors:errors
    }
    
}

export function ConsultaValidator(input: Record<string,string>){

    const errors:Record<string,string> = { };
    Object.entries(input).forEach(([key,value])=>{
        const result = validate(value,getConsultaRules(key));
        if(!result.isValid){
            errors[key]=result.error
        }
    });
    return {
        isValid: Object.keys(errors).length===0,
        errors:errors
    }
}

export function PreconsutaValidator(input: Record<string,string>){
    const errors:Record<string,string> = { };
    Object.entries(input).forEach(([key,value])=>{
        const result = validate(value,getPreconsultaRules(key));
        
        if(!result.isValid){
            errors[key]=result.error
        }
    });
    return {
        isValid: Object.keys(errors).length===0,
        errors:errors
    }
}

function getPreconsultaRules(input:string){
    switch(input){
        case 'descripcion':
            return [rules.rules.required]
        case 'indicaciones':
            return [rules.rules.required]    
        case 'prediagnostico':
            return [rules.rules.required]
    }
    return [];
}

export function AppointMentValidator(input: Record<string,string>){
    const errors: Record<string,string> = {};
    Object.entries(input).forEach(([key,value])=>{
        const result = validate(value,getAppointMentRules(key));
        
        if(!result.isValid){
            errors[key]=result.error;
        }
    });

    return{
        isValid:Object.keys(errors).length===0,
        errors:errors
    }
}

function getAppointMentRules(input:string){
    switch(input){
        case 'cliente':
            return[rules.rules.required]
        case 'descripcion':
            return[rules.rules.required]
        case 'fecha':
            return[rules.rules.required]
        case 'horario':
            return[rules.rules.required,rules.rules.number]
        case 'mascota':
            return[rules.rules.required,rules.rules.number]
        case 'tipo':
            return[rules.rules.required]
        case 'trabajador':
            return[rules.rules.required,rules.rules.number]
    }
    return [];
}

function getConsultaRules(input: string){
    
    if(input.includes('detalles_servicio')){
        return [rules.rules.required]
    }
    if(input.includes('cantidad_usada ')){
        return [rules.rules.required,rules.rules.number,rules.rules.shouldNotBeCeroOrLess]
    }
    if(input.includes('tipo_venta')){
        return [rules.rules.required]
    }
    if(input.includes('precio_Servicio')){
        return [rules.rules.required,rules.rules.number]
    }
    switch(input){
        case 'descripcion':
            return [rules.rules.required]
        case 'indicaciones':
            return [rules.rules.required]    
        case 'prediagnostico':
            return [rules.rules.required]
        case 'servicios':
            return[rules.rules.shouldbeAtLeast(1,'debe de tener por lo menos un servicio asociado')]    
    }
    return []
}

function getRules(field: string,extraValues?:Record<string,any>){
    switch(field){
        case 'nombre':
            return [rules.rules.required,rules.rules.shouldbeOnlyWords]
        case 'email':
            return [rules.rules.required,rules.rules.email]
        case 'password':
            return [rules.rules.required,rules.rules.password]
        case 'passwordConfirmation':
            return [rules.rules.required,rules.rules.password,rules.rules.mustBeTheSame(extraValues?.password,'las contraseñas')] //revisar esto pq creo q va a fallar
        case 'telefono':
            return [rules.rules.required,rules.rules.phoneLength,rules.rules.phone]
        case 'colonia':
            return [rules.rules.required,rules.rules.shouldbeOnlyWords]
        case 'calle':
            return [rules.rules.required,rules.rules.shouldbeOnlyWords]
        case 'numero_exterior':
            return [rules.rules.required]
        case 'codigo_postal':
            return [rules.rules.required,rules.rules.number,rules.rules.min(4),rules.rules.max(6)]
        case 'municipio':
            return [rules.rules.required,rules.rules.shouldbeOnlyWords]
        case 'estado':
            return [rules.rules.required,rules.rules.shouldbeOnlyWords]
        default:
            return []
    }
    
}