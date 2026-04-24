<script setup lang="ts">
//import CalendarComponente from '@/components/CalendarComponent.vue';
import {ref,computed,reactive} from 'vue';
//import vSelect from "vue-select";
//import { createPopper } from '@popperjs/core'
import {useCita} from '@/composables/useCita.ts';
import 'vue-select/dist/vue-select.css'; 
import {PreconsutaValidator} from '@/validations/AuthValidation.ts';
//import type {PrediagnosticoInterface} from '@/interfaces/CitaForm.ts'

const props = defineProps<{
    cita:any
}>();

const emit = defineEmits<{
    (e:'cerrar'):void,
    (e:'onFail',message:string):void,
    (e:'creadoConsulta',payload:any):void
}>();

const isLoading = ref<boolean>(false);
const dueño = ref<string>(props.cita.mascota.cliente.user.nombre);
const mascota = ref<string>(props.cita.mascota.nombre);
//const animal = ref<string>(props.cita.mascota.raza.animal.perro)
//const raza = ref<string>(props.cita.mascota.raza.nombre)
const descripcion = ref<string>(props.cita.descripcion??'esta cita no tiene descripcion')
const trabajador = ref<string>(props.cita.horario_trabajador.trabajador.user.nombre)
//const descripcion_consulta = ref<string>('');
const tipocita = ref<string>(props.cita.tipo)
const prediagnostico = ref<string>('');
const errores = reactive<Record<string,string>>({});
const fields = reactive<Record<string,string>>({});
fields['prediagnostico'] = prediagnostico.value;
const payload = computed<any>(()=>{
    
    return {
            pre_diagnostico: prediagnostico.value,
        }
})

const enviarDatos = async ()=>{
    Object.keys(errores).forEach(key => {
            delete errores[key];
        });
        fields['prediagnostico'] = prediagnostico.value?.trim();
    const result = PreconsutaValidator(fields);
    Object.assign(errores,result.errors);
    if(!result.isValid){
        return;   
    }

    const data = {
        ...payload.value,
        cita_id:props.cita.id
    }
    isLoading.value = true;

    const citaStore= await useCita().consultaStore(data);
    isLoading.value = false;
        if(citaStore.statusCode.value == 201){
            emit('creadoConsulta', citaStore.data.value.data);
        }else{
            emit('onFail',citaStore?.data?.value?.message);
        }
};


</script>

<template>
    <Teleport to="body">
        <div class="modal-fondo fixed flex justify-center items-center inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 px-3 sm:px-4 p-4 md:p-8" style="z-index: 9999999" @click.self="emit('cerrar')">
            
            <div class="rounded-[20px] p-4 sm:p-5 bg-[#e2d2c4] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col w-full max-w-[700px] h-auto max-h-full md:max-h-[90vh]">
                
                <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] mb-1 shrink-0 truncate">Consulta: {{mascota}}</h2>
                
                <form @submit.prevent class="flex flex-col flex-1 min-h-0 w-full mt-2 md:mt-3 pt-3 md:pt-4 border-t-2 border-[#a07a58]">
                    
                    <div class="overflow-y-auto pr-2 custom-scrollbar flex-1">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <div>
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Dueño</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                    {{dueño}}
                                </div>
                            </div> 
                            <div>
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Mascota</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                    {{mascota}}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Tipo de cita</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                    {{tipocita}}
                                </div>    
                            </div>
                            <div>
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Trabajador</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                    {{trabajador}}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 md:mt-4">
                            <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Descripción de la cita</label>
                            <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed whitespace-pre-wrap break-words text-sm md:text-base min-h-[60px]">
                                {{descripcion}}
                            </div>
                        </div>

                        <div class="mt-3 md:mt-4 pt-3 md:pt-4 border-t-2 border-[#a07a58]">
                            <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Prediagnóstico</label>
                            <span class="text-xs md:text-sm text-[#b51f1f] font-bold block mb-1" v-if="errores['prediagnostico']">
                                {{errores['prediagnostico']}}
                            </span>
                            <textarea rows="4" v-model="prediagnostico" 
                                class="w-full py-2.5 md:py-3 px-3 md:px-4 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none outline-none focus:bg-[#F9F5F0] transition-colors placeholder-[#52301380] text-sm md:text-base" 
                                placeholder="Ingrese el diagnóstico inicial..."></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 pt-3 md:pt-4 mt-2 md:mt-3 border-t-2 border-[#a07a58] shrink-0">
                        <button type="button"
                            class="w-full sm:flex-1 py-3 md:py-3.5 font-black text-sm md:text-base rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-105 hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#b51f1f] border border-[#b51f1f]/20 hover:border-transparent"
                            @click="emit('cerrar')" :disabled="isLoading">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="w-full sm:flex-1 flex justify-center items-center py-3 md:py-3.5 font-black text-sm md:text-base rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#812c8f] hover:scale-105 hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f] border border-[#812c8f]/20 hover:border-transparent"
                            @click="enviarDatos" :disabled="isLoading">
                            <div v-if="isLoading" class="loader"></div>
                            <span v-else>Iniciar Consulta</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

/* El loader se mantiene igual porque son animaciones personalizadas */
.loader {
    display:block;
    margin:auto;
    width: 25px;
    aspect-ratio: 1;
    border-radius: 50%;
    border: 4px solid #812c8f; /* Hice el borde un poco más delgado para que se vea más elegante en el botón */
    animation:
        l20-1 0.8s infinite linear alternate,
        l20-2 1.6s infinite linear;
}

/* Cuando se hace hover al botón, el loader se vuelve blanco */
button:hover .loader {
    border: 4px solid #ffffff;
}

@keyframes l20-1{
   0%    {clip-path: polygon(50% 50%,0       0,  50%   0%,  50%    0%, 50%    0%, 50%    0%, 50%    0% )}
   12.5% {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100%   0%, 100%   0%, 100%   0% )}
   25%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 100% 100%, 100% 100% )}
   50%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   62.5% {clip-path: polygon(50% 50%,100%    0, 100%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   75%   {clip-path: polygon(50% 50%,100% 100%, 100% 100%,  100% 100%, 100% 100%, 50%  100%, 0%   100% )}
   100%  {clip-path: polygon(50% 50%,50%  100%,  50% 100%,   50% 100%,  50% 100%, 50%  100%, 0%   100% )}
}
@keyframes l20-2{ 
  0%    {transform:scaleY(1)  rotate(0deg)}
  49.99%{transform:scaleY(1)  rotate(135deg)}
  50%   {transform:scaleY(-1) rotate(0deg)}
  100%  {transform:scaleY(-1) rotate(-135deg)}
}
</style>