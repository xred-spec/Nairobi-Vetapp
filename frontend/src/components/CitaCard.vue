<script setup lang="ts">
import ButtonAccept from '@/components/ButtonAccept.vue';
import ChangeStateModal from '@/components/modals/ChangeStateModal.vue'
//import Dots from '@/components/Dots.vue';
import {useUserStore} from '@/stores/user.ts';
import ConsultaModal from '@/components/modals/ConsultaModal.vue';
import {onMounted,onUnmounted,ref} from 'vue';
import FichaModal from '@/components/modals/FichaModal.vue';
import CitaModal from '@/components/modals/CitaModal.vue';
//import ModalConfirm from './ModalConfirm.vue';
const Nav = ref<HTMLElement | null>(null)
const isOpen = ref(false);
// const confirmAction = ref<string | null>('');
onMounted(() =>document.addEventListener('click',enventHandlernav, true));
onUnmounted(()=>document.addEventListener('click',enventHandlernav, true));


const pinia = useUserStore();
const props = defineProps<{
    main?: boolean
    cita: any,
}>();

const emit = defineEmits<{
    (e:'editModal',citaselected:any):void,
    (e:'eliminado',cita:any):void,
    (e:'creado',cita:any):void,
    (e:'onFail',message?:string):void,
    (e:'creadoConsulta',cita:any,payload:any):void
}>();

const citaModal = ref<boolean>(false);
const Day:Date = new Date();
const canBeShown = ref<boolean>(false)
const MyDay  = props.cita?.fecha.slice(0,10);
const myNewDate = new Date(MyDay)
const state = ref<any>(props.cita.estado);
function enventHandlernav(event :MouseEvent){
    if(Nav.value && !Nav.value.contains(event.target as Node)){
        isOpen.value = false
    }
}

 
    const utc1 = new Date(Day.getFullYear(), Day.getMonth(), Day.getDate());
    const utc2 = new Date(myNewDate.getFullYear(), myNewDate.getMonth(), myNewDate.getDate());
    const diference = utc2.getTime()-utc1.getTime();
    const diffInDays = diference /(1000 * 60 * 60 * 24);
    if((diffInDays>=1 && pinia.getUser.rol=="cliente" && props.cita.estado=="agendada") || (pinia.getUser.rol!='cliente')){
         canBeShown.value=true;
    }

const visibilityModal = ref<boolean>(false);
//const state = ref<string>('')
const citaState = ref<string>(props.cita.estado);
function changeStatus(estado:string){
    console.log('cambio de estado')
    state.value = estado;
  //  emit('eliminado',props.cita)
    visibilityModal.value = true;
}
const showConsultaModal = ref<boolean>(false);
const showFichaModal = ref<boolean>(false);
function modalestado(estado:string){
    switch (estado) {
        case 'en_proceso':
            showConsultaModal.value = true;
            break;
        case 'Consulta':
            showFichaModal.value = true;
            break;
        case 'agendada':
            state.value = 'en_proceso'
            visibilityModal.value = true;
            break;
        default:
            break;
    }
}
function quitCard(){
    console.log('si llego xd');
    console.log(props.cita)
    emit('eliminado',props.cita)
    visibilityModal.value = false;
}

function createdficha(){
    showFichaModal.value = false;
    emit('creado',props.cita);
}
function showModal(){
    if(props.cita.consulta && props.cita.estado!='realizada'){
        citaState.value = 'consulta'
    }
    citaModal.value=true
}
/*
const abrirModalConfirm = () => {
    confirmAction.value = 'cancel';
};
*/


</script>

<template>
<CitaModal v-if="citaModal" :cita="cita" :type="citaState" @cerrar="citaModal=false"/>
<ConsultaModal v-if="showConsultaModal" :cita="cita" @cerrar="showConsultaModal=false" @creadoConsulta="(payload)=>{showConsultaModal=false;emit('creadoConsulta',props.cita,payload)}" @onFail="(message)=>emit('onFail',message)"/>
<FichaModal v-if="showFichaModal" :cita="cita" @cerrar="showFichaModal=false" @creado="createdficha()"/>
<ChangeStateModal v-if="visibilityModal" :state="state" :id="cita.id" @cerrar="visibilityModal=false" @aceptar="quitCard()"/>
    
    <div class="card-wrapper rounded-[20px] bg-[#fcf7f2] cursor-pointer shadow-sm px-4 md:px-5 py-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 shrink-0 border border-[#a07a58]/10" @click.stop="showModal()">
        
        <div class="flex flex-col"> 
            
            <div class="flex text-[#523013] font-extrabold text-base md:text-lg border-b-2 border-[#523013]/20 pb-1 mb-1 items-center justify-between">
                
                <div class="flex items-center flex-1 min-w-0 pr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                    class="size-4 md:size-5 mr-1.5 shrink-0"
                    fill="#523013" viewBox="0 0 24 24" stroke-width="" stroke="currentColor">
                        <circle cx="11" cy="4" r="2" />
                        <circle cx="18" cy="8" r="2" />
                        <circle cx="20" cy="16" r="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
                    </svg>
                    <p class="truncate">Mascota: {{cita.mascota.nombre}}</p>
                </div>

                <div class="dots flex flex-col relative shrink-0" v-if="(cita.estado!=='realizada' && cita.estado!=='cancelada')&&(!cita.consulta) && (canBeShown) " ref="Nav" @click.stop="isOpen=!isOpen" >
                    <button class="p-1 hover:bg-[#523013]/10 rounded-full transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" 
                        class="lucide lucide-ellipsis-icon lucide-ellipsis size-5 md:size-6 text-[#523013]"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </button>
                    
                    <div 
                    class="state-subnav flex flex-col absolute px-4 py-2 bg-[#eddcce] rounded-[16px] rounded-tr-none shadow-xl border border-[#a07a58] top-[80%] right-full mr-2 z-20 w-max" 
                    v-if="isOpen">
                        <div v-if="cita.estado!='en_proceso' " @click="emit('editModal',cita)"
                        class="option-subnav py-1.5 text-sm md:text-base font-bold text-[#523013] hover:text-[#812c8f] border-b border-[#a07a58]/20 last:border-0">
                            Editar
                        </div>
                        <div v-if="cita.estado!='agendada' " @click="changeStatus('agendada')" 
                        class="option-subnav py-1.5 text-sm md:text-base font-bold text-[#523013] hover:text-[#812c8f] border-b border-[#a07a58]/20 last:border-0">
                            Cambiar estado
                        </div>
                        <div v-if="cita.estado!='en_proceso'" @click="changeStatus('cancelada')" 
                        class="option-subnav py-1.5 text-sm md:text-base font-bold text-[#523013] hover:text-[#b51f1f] border-b border-[#a07a58]/20 last:border-0">
                            Cancelar
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="flex flex-col md:flex-row md:items-end justify-between flex-wrap gap-2 md:gap-4 mt-1">
                
                <div class="flex flex-col gap-1 text-sm md:text-base py-1 min-w-0">
                    <div class="flex text-[#812c8f] font-bold items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="size-4 md:size-5 mr-1.5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        <span>Fecha: {{cita.fecha.slice(0,10).split('-').reverse().join('/')}}</span>
                    </div>
                    <div class="flex text-[#b51f1f] font-bold items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" 
                        class="lucide lucide-clock-icon lucide-clock size-4 md:size-5 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        <span>Hora: {{cita.horario_trabajador.horario.inicio_hora.slice(0,5)}} hrs</span>
                    </div>
                    <div class="flex text-[#523013] font-bold items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" 
                        class="lucide lucide-scroll-text-icon lucide-scroll-text size-4 md:size-5 mr-1.5 shrink-0 mt-0.5"><path d="M15 12h-5"/><path d="M15 8h-5"/><path d="M19 17V5a2 2 0 0 0-2-2H4"/><path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"/></svg>
                        <span class="line-clamp-2">Motivo: {{cita.descripcion ?? 'No se especificó un motivo'}}</span>
                    </div>
                </div>
                
                <div class="w-full md:w-auto md:mt-0 flex justify-end shrink-0">
                    <ButtonAccept v-if="cita.estado==='en_proceso' &&  pinia.getUser.rol!='cliente' && !cita.consulta" :message="'Iniciar consulta'" :state="'en_proceso'" :id="cita.id" @modal='(state:any)=>modalestado(state)' class="w-full md:w-auto"/>
                    <ButtonAccept v-else-if="cita.estado==='agendada' &&  pinia.getUser.rol!='cliente'" :message="'Iniciar Consulta'" :state="'agendada'" :id="cita.id" @modal='(state:any)=>modalestado(state)' class="w-full md:w-auto"/>
                    <ButtonAccept v-else-if="cita.estado==='realizada'" :message="'Ver Consulta'" :state="'Completada'" :id="cita.id" @modal="citaModal=true" class="w-full md:w-auto"/>
                    <ButtonAccept v-else-if="cita.estado==='en_proceso' && cita.consulta &&  pinia.getUser.rol!='cliente'"  :message="'Generar Ficha Médica'" :state="'Consulta'" :id="cita.id" @modal='(state:any)=>modalestado(state)' class="w-full md:w-auto"/>
                    <p v-else-if="cita.estado==='cancelada'" class="text-[#b51f1f] px-3 py-1 rounded-md font-bold text-sm md:text-base text-center w-full md:w-auto shadow-sm">Cancelada</p>
                </div>

            </div>
        </div>
    </div>
</template>
   

<style scoped>
    .option-subnav{
        transition: all 0.2s ease-in-out;
    }
</style>