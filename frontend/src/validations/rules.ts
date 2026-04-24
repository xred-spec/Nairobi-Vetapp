const rules = {
    required: (value:any)=>{ 
      if(value===undefined || value===null){
        return 'campo requerido'
      }else if(value.toString()||!!value){
        return true;
      }
      return 'campo requerido'
    },
    email:(value:string)=>/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)||'email no valido',
    password:(value:string)=>value.length>8||'minimo 8 caracteres',
    phoneLength:(value:string)=>value.length===10||'longitud del telefono no valido',
    phone:(value:string)=>/^[0-9]{10}$/.test(value)||'telefono no valido',
    min: (min: number) => (value: string) => value.length > min || `mínimo ${min}`,
    max: (max: number) => (value: string) => value.length < max || `máximo ${max}`,
    number: (value: string) => !isNaN(Number(value)) || 'Debe ser un número',
    mustBeTheSame:(value2?:string,message?:string)=>(value:string)=>value===value2||`${message} no son iguales`,
    canBePhoneOrEmail:(value:string)=>/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)||/^[0-9]{10}$/.test(value)||'telefono o email no valido',
    shouldbeOnlyWords:(value:string)=>/[A-za-z]/.test(value)||'no debe contener numeros',
    shouldNotBeCeroOrLess:(value:string)=>Number(value)>0||'Valor no acepatado',
    shouldbeAtLeast:(num:number,message:string)=>(value:string)=>Number(value)>=num || message
}



export const validate =<T>(value:T,rules:((v:T)=>string|boolean)[])=>{
    for (const rule of rules) {
    const result = rule(value)
    if (result !== true) return { isValid: false, error: result as string }
  }
  return { isValid: true, error: "" }
}
export default {rules,validate};