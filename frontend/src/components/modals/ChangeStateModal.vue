<script setup  lang="ts">
import {useCita} from '@/composables/useCita.ts';
import {ref} from 'vue';
import {useToast} from '@/composables/useToast.ts';
const props = defineProps<{
    state:string
    id:number
}>()
const emit = defineEmits<{
    (e:'cerrar'):void,
    (e:'aceptar'):void,
}>();

// const result = ref<any>(null);
const loading = ref<any>(null);
const code = ref<any>(null);
async function CambiarEstado(state:string){
    let data2:any = null;
    if(props.state == 'cancelada'){
        loading.value = true;
        const {data,statusCode }  = await useCita().cancelCita(props.id);
        loading.value = false;
        console.log(data.value);
         data2 = data.value 
        code.value = statusCode.value
    }else{
        loading.value = true;
        const {data,statusCode }= await useCita().changestatus(props.id,{estado:state.toLowerCase()});
        loading.value = false;
        console.log(data.value);
        data2 = data.value 
        
        code.value = statusCode.value
    }
    console.log(data2);
     newCode(code.value,data2.error??data2.message)
   
}
function newCode(code:number,message?:any){
    console.log(message)
    console.log(code);
    if(code==200){
         console.log('status code'+code)
        emit('aceptar')
    }else{
        loading.value = false;
        const {showToast} = useToast();
        showToast({status:'error',customMessage:message})
         emit('aceptar')
    }
}
/*
watch(code,(nuevovalor)=>{
    console.log('fetching')
    console.log(nuevovalor)
    if(nuevovalor.value==200){
         console.log('status code'+ nuevovalor.value)
        emit('aceptar')
    }else{
        loading.value = false;
        const {showToast} = useToast();
         emit('aceptar')
        showToast({status:'error',customMessage:result.value.value.error??result.value.value.message})
    }
},{deep:true})**/


</script>

<template>
    <Teleport to="body">
        <transition name="fade" appear>
            <div class="modal-fondo fixed flex justify-center items-center inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 px-4 z-[9999999]" @click.self="emit('cerrar')">
                
                <div v-if="state === 'cancelada'" class="modal-wrapper bg-[#fcf7f2] rounded-[20px] p-6 md:p-8 shadow-2xl flex justify-center items-center flex-col w-full max-w-sm min-h-[250px]">
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#b51f1f] mb-4 leading-tight">¿Está seguro de cancelar la cita?</h2>
                    
                    <div class="flex flex-col gap-3 w-full mt-2">
                        <button :disabled="loading" class="w-full py-3 md:py-3.5 rounded-lg bg-[#b51f1f] text-[#fcf7f2] font-black text-base transition-transform hover:scale-105 shadow-md disabled:opacity-50 disabled:cursor-wait"
                        @click="CambiarEstado(state)">
                            {{ loading ? 'Cargando...' : 'Sí, cancelar' }}
                        </button>
                        <button :disabled="loading" class="w-full py-3 md:py-3.5 rounded-lg bg-[#fcf7f2] text-[#523013] font-bold text-base transition-all hover:bg-[#523013]/10 disabled:opacity-50 disabled:cursor-wait"
                        @click="emit('cerrar')">
                            Regresar
                        </button>
                    </div>
                </div>

                <div v-else-if="state === 'en_proceso'" class="modal-wrapper bg-[#fcf7f2] rounded-[20px] p-6 md:p-8 shadow-2xl flex justify-center items-center flex-col w-full max-w-sm min-h-[250px]">
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#812c8f] mb-4 leading-tight">¿Está seguro de iniciar la consulta?</h2>
                    
                    <div class="flex flex-col gap-3 w-full mt-2">
                        <button :disabled="loading" class="w-full py-3 md:py-3.5 rounded-lg bg-[#812c8f] text-[#fcf7f2] font-black text-base transition-transform hover:scale-105 shadow-md disabled:opacity-50 disabled:cursor-wait"
                        @click="CambiarEstado(state)">
                        {{ loading ? 'Cargando...' : 'Sí, iniciar' }}
                        </button>
                        <button :disabled="loading" class="w-full py-3 md:py-3.5 rounded-lg bg-[#fcf7f2] text-[#b51f1f] font-bold text-base transition-all hover:bg-[#b51f1f]/10 disabled:opacity-50 disabled:cursor-wait"
                        @click="emit('cerrar')">
                            Regresar
                        </button>
                    </div>
                </div>

                <div v-else class="modal-wrapper bg-[#fcf7f2] rounded-[20px] p-6 md:p-8 shadow-2xl flex justify-center items-center flex-col w-full max-w-sm min-h-[250px]">
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#812c8f] mb-4 leading-tight">¿Cambiar estado de la cita a pendiente?</h2>
                    
                    <div class="flex flex-col gap-3 w-full mt-2">
                        <button :disabled="loading" class="w-full py-3 md:py-3.5 rounded-lg bg-[#812c8f] text-[#fcf7f2] font-black text-base transition-transform hover:scale-105 shadow-md disabled:opacity-50 disabled:cursor-wait"
                        @click="CambiarEstado(state)">
                            {{ loading ? 'Cargando...' : 'Sí, cambiar' }}
                        </button>
                        <button :disabled="loading" class="w-full py-3 md:py-3.5 rounded-lg bg-[#fcf7f2] text-[#b51f1f] font-bold text-base transition-all hover:bg-[#b51f1f]/10 disabled:opacity-50 disabled:cursor-wait"
                        @click="emit('cerrar')">
                            Regresar
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
