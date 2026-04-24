<script setup lang="ts">
import ToggleType from '@/components/ToggleType.vue';
import ButtonAdd from '@/components/ButtonAdd.vue';
import FilterButtonCita from '@/components/FilterButtonCita.vue';
import CitaCard from '@/components/CitaCard.vue';
import { ref, watch, computed, onMounted } from 'vue';
import { useCita } from '@/composables/useCita';
import { useUserStore } from '@/stores/user';
import { useToast } from '@/composables/useToast';
import { CitaInterface } from '@/interfaces/CitaForm';
import AppointMentModalAdd from '@/components/modals/AppointmentModalAdd.vue';
import EditModalAppointment from '@/components/modals/EditModalAppointment.vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';

const { showToast } = useToast();
const showModalEdit = ref<boolean>(false);
const state = ref<string>('Agendadas');
const estado = ref<string>('agendada');
const response = ref<any>(null);
const showmodal = ref<boolean>(false);
const user = useUserStore();
const filtros = ref<CitaInterface>({});

const payload = computed<CitaInterface>(()=>({
    ...filtros.value,
  estado:estado.value.toLowerCase().replace(' ', '_')
}));
const currentpage = ref<number>(1)

const loaderState = ref<string | null>('loading');

onMounted(()=>{
    response.value = fetching(payload,currentpage.value)
});
const search = (payload2:CitaInterface)=>{
    filtros.value = payload2;
};
const data = ref<any>([]);
watch(state,()=>{
    switch (state.value) {
        case 'Agendadas':
          currentpage.value = 1;
            estado.value = 'agendada';
            break;
        case 'En proceso':
            currentpage.value = 1;
            estado.value = 'en_proceso';
            break;
        case 'Completadas':
            currentpage.value = 1;
            estado.value = 'realizada';
            break;
        case 'Canceladas':
            currentpage.value = 1;
            estado.value = 'cancelada';
            break;
        default:
            break;
    }
})

watch(payload, async ()=>{
    response.value?.abort();
     currentpage.value = 1;
    data.value = [];
    response.value = fetching(payload,currentpage.value)

});
const pagination = ref<any>(null);
watch(()=>response.value?.data?.data?.data,()=>{
    if(response.value?.data?.data?.data){
        loaderState.value ='success';
        setTimeout(() => {
            loaderState.value =null;
        }, 800);
        pagination.value = response.value?.data?.data;
        data.value = response.value.data?.data?.data; 
    }
});

watch(()=>response?.value?.statusCode,()=>{
        if(response.value?.statusCode!=200 && response.value?.statusCode ){
        console.log('entro al error');
        loaderState.value ='error';
        setTimeout(() => {
            loaderState.value =null;
        }, 800);
    }
});
function editModal(cita:any){
    selectedCita.value = cita;
    showModalEdit.value =true;
}
function enviado(message:string){
    showmodal.value = false;
    if(state.value == 'Agendadas'){
        data.value = [];
        currentpage.value = 1;
       response.value = fetching(payload,currentpage.value)
    }
    showToast({status:'created',customMessage:message})
}

 function fetching(payload:any,currentpage:number){
    loaderState.value = 'loading';
    return useCita().ObtenerCita(payload,currentpage);
}

function updated(message:string){
    showModalEdit.value = false;
    data.value = [];
    currentpage.value = 1;
   response.value = fetching(payload,currentpage.value)
    showToast({status:'updated',customMessage:message})
}
function quitCard(cita:any){
    showToast({status:'status_changed',customMessage:'El estado de la cita ha sido cambiado'})
    data.value = data.value.filter((item:any)=>item.id !== cita.id)
}
function createdFicha(cita:any){
    showToast({status:'created',customMessage:'La ficha medica ha sido creada'})
    data.value = data.value.filter((item:any)=>item.id !== cita.id)
}
function createdConsulta(cita:any,payload:any){
    console.log(payload);
    showToast({status:'created',customMessage:'La consulta ha sido creada'})
    const indexCita = data.value.findIndex((item:any)=>item.id === cita.id);
    data.value[indexCita].consulta = payload;
    const CitaFecha = cita.fecha.split('T')[0];
    const time = new Date(`${CitaFecha}T${cita.horario_trabajador.horario.inicio_hora}`).getTime();
        const myNewCita = data.value[indexCita] 
    data.value.splice(indexCita,1);
    const newIndexCita = data.value.findIndex((item:any,index:number,array:any[])=>{
        const itemFecha = item.fecha.split('T')[0];
        const itemTime = new Date(`${itemFecha}T${item.horario_trabajador.horario.inicio_hora}`).getTime();
        
        if(item.consulta==null){
            return true
        }
        if(time<itemTime){
            return true
        }
        const index1 = index+1;
        if(!array[index1]){
            return true;
        }
        return false;
    })
    if(newIndexCita==-1){
        data.value.splice(0,0,myNewCita )
    }else{
        data.value.splice(newIndexCita,0,myNewCita )
    }
}
function cambiarpagina(numero:number){
    data.value =[];
    currentpage.value = numero;
    response.value = fetching(payload,currentpage.value)
}
const selectedCita = ref<any>(null);
</script>

<template>
    <EditModalAppointment v-if="showModalEdit" :cita="selectedCita" @cerrar="showModalEdit=false" @enviado="(message)=>updated(message)"/>

    <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
        
        <div class="main-header w-full flex flex-col md:flex-row items-center md:justify-between py-3 px-4 md:px-5 mt-2 rounded-xl bg-[#523013] text-[#fcf7f2] gap-2 md:gap-0 shrink-0 shadow-sm">
            <h1 class="z-10 flex justify-center md:justify-start text-center md:text-start text-xl md:text-2xl font-black w-full md:w-auto">
                Citas
            </h1>
            <div class="z-10 flex items-center justify-center md:justify-end gap-2 w-full md:w-auto opacity-90">
                <label class="text-xs md:text-sm font-medium text-center md:text-right">
                    Haz click en un registro para ver todos sus datos
                </label>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info shrink-0 hidden md:block size-5">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                </svg>
            </div>
        </div>
        
        <div class="option-bar flex flex-row items-center justify-between py-3 w-full gap-2 md:gap-3 shrink-0">
            <div class="flex flex-1 sm:flex-none min-w-0">
                <FilterButtonCita class="w-full" :loading="!!loaderState" :user="user" @search="search"/>
            </div>
            <div class="flex flex-1 sm:flex-none justify-end min-w-0">
                <ButtonAdd class="w-full sm:w-auto" :loading="!!loaderState" @abrirModal="showmodal = true"/>
            </div>
        </div>

        <div class="wrapper-toggle flex flex-col md:flex-row md:flex-wrap justify-start gap-2 md:gap-4 shrink-0 pb-2 w-full">
            <ToggleType :type="'Agendadas'" :selected="state" @selected="(val) => state=val" />
            <ToggleType :type="'En proceso'" :selected="state" @selected="(val) => state=val" />
            <ToggleType :type="'Completadas'" :selected="state" @selected="(val) => state=val"/>
            <ToggleType :type="'Canceladas'" :selected="state" @selected="(val) => state=val"/>
        </div>

        <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-24 lg:pb-2"
             :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">

            <Transition name="fade-tabla" mode="out-in">
                <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
                    <Loader :state="loaderState" />
                </div>

                <div v-else-if="data.length === 0 && loaderState!='loading'" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
                    <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">No se encontraron citas</p>
                    <img class="w-32 h-32 md:w-48 md:h-48 object-contain drop-shadow-md" src="https://cdni.iconscout.com/illustration/premium/thumb/gato-de-tablero-en-la-pagina-web-illustration-svg-download-png-10163670.png" alt="No hay citas">
                </div>

                <div v-else class="w-full h-full flex flex-col min-h-0">
                    <div class="w-full flex flex-col h-full min-h-0">
                        <CatalogosContainer :header="false">
                            <template #content-cards>
                                <div class="flex flex-col w-full gap-3">
                                    <CitaCard v-for="cita in data" :key="cita.id" :cita="cita" :main="true" class="w-full"
                                        @editModal="editModal($event)" 
                                        @eliminado="(cita:any)=>quitCard(cita)" 
                                        @creado="(cita :any)=>createdFicha(cita)" 
                                        @onFail="(message :any)=>showToast({status:'error',customMessage:message??'Error al crear la consulta'})"
                                        @creadoConsulta="(cita :any,payload:any)=>createdConsulta(cita,payload)"
                                    />    
                                </div>
                            </template>

                            <template #pagination>
                                <Pagination v-show="data?.length>0" :pages="response?.data?.data?.last_page??1" :modelValue="currentpage??1" @update:modelValue="(value)=>cambiarpagina(value)"/>
                            </template>
                        </CatalogosContainer>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
    
    <AppointMentModalAdd v-if="showmodal" @cerrar="showmodal=false" @enviado="(message)=>enviado(message)"/>
</template>

<style scoped>
@reference "tailwindcss";

.fade-tabla-enter-active,
.fade-tabla-leave-active {
  transition: opacity 0.2s ease;
}

.fade-tabla-enter-from,
.fade-tabla-leave-to {
  opacity: 0;
}
</style>