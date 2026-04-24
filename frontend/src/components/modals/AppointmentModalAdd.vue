<script setup lang="ts">
    import {useUserStore} from "@/stores/user";
    import {ref,watch,computed,reactive} from "vue";
    import {useCita} from "@/composables/useCita";
    import CalendarComponent from "@/components/CalendarComponent.vue";
    import vSelect from "vue-select";
    import { createPopper } from '@popperjs/core'
    import 'vue-select/dist/vue-select.css'; 
    import {AppointMentValidator} from '@/validations/AuthValidation.ts';
    import {useToast} from '@/composables/useToast.ts';
/*
onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
*/
    const emit = defineEmits<{
        (e:'cerrar'):void,
        (e:'enviado',message:string):void
    }>()
    const pinia = useUserStore();
    const  clienteSeleccionado = ref<any>('');

    function withPopper(dropdownList:any, component:any, { width}:any) {
        dropdownList.style.width = width
        dropdownList.style.zIndex = "10000000";
        const popper = createPopper(component.$refs.toggle, dropdownList, {
            placement: 'top', 
            modifiers: [
            { name: 'offset', options: { offset: [0, -1] } }
            ]
        })
        return () => popper.destroy()
    }
    const errores = reactive<Record<string,string>>({});
    const fields = reactive<Record<string,string>>({});
    const {data:prefetching}= useCita().ObtenerPrefetchingCita();
    const clientes = ref<any>(null);
    const mascota = ref<any>(null);
    const trabajador = ref<any>(null);
    const trabajadorselected = ref<any>(null);
    const Hora = ref<any>(null);
    const mascotaselected = ref<any>(null);
    const ClienteSearch = ref<string>('');
    const loadingClientes = ref<boolean>(true);
    const loadingMascotas = ref<boolean>(false);
    const loadingTrabajadores = ref<boolean>(true);
    const loadingHoras = ref<boolean>(false);
    const shouldBounce = ref<boolean>(false);
    const permanente = ref<any>(null);
    const horaselected = ref<any>(null);
    const mascotaDisabled = ref<boolean>(pinia.getUser.rol != 'cliente');
    const horaDisabled = ref<boolean>(true);
    const tipocita = ref<string>('');
    const fechaCita = ref<string>('');
    const abortable = ref<(()=>void)|null>(null);
    let timmer2:any = null;
    const descripcion = ref<string>('');
    const fetching = ref<boolean>(false);
/*asignacion de los campos del field */

    const CitaInterface = computed<any>(()=>({
        fecha:fechaCita.value,
        tipo:tipocita.value,
        horario_trabajador_id:horaselected.value?.id,
        mascota_id:mascotaselected.value?.id,
        descripcion:descripcion.value
    }));

    if(pinia.getUser.rol=='cliente'){
        loadingMascotas.value = true;
    }


    watch(prefetching, (nuevoValor) => {
        if (nuevoValor.data.clientes) {
            clientes.value = nuevoValor.data.clientes;
            loadingClientes.value = false
            permanente.value = nuevoValor.data.clientes;
        }
        if (nuevoValor.data.mascotas) {
            mascota.value = nuevoValor
            loadingMascotas.value = false
        }
        if(nuevoValor.data.trabajadores){
            trabajador.value = nuevoValor.data.trabajadores
            loadingTrabajadores.value = false;
        }
        if(nuevoValor.data.trabajador){
            trabajadorselected.value = nuevoValor.data.trabajador;
        }
    })

    watch([fechaCita,trabajadorselected],([fecha,trabajador])=>{
        Hora.value =null;
        abortable.value?.();
        horaselected.value = null;
        horaDisabled.value = true;
        if(fecha && trabajador){
            loadingHoras.value = true
            const {data,abort} = useCita().getDisponibilidadHora(trabajador.id,fecha);
            abortable.value = abort
            watch(data,(nuevadata)=>{
                horaDisabled.value =false;
                loadingHoras.value = false;
                Hora.value = nuevadata.data.horas
           })
        }
    })



watch(clienteSeleccionado, (nuevoValor) => {
    mascotaselected.value = null;
    loadingMascotas.value=true;
    if(nuevoValor){
        mascotaDisabled.value=false;
        const {data } = useCita().getMascotasCitas(nuevoValor.id)
       // abort.value = abort;
        mascota.value = data
        watch(data,(result)=>{
            mascota.value = result;
            loadingMascotas.value=false;
        })
    }else{
        loadingMascotas.value=false;
        mascotaDisabled.value=true;
    }
})

function buscarClientes(value:string){
    shouldBounce.value = false
     clientes.value = permanente.value
    ClienteSearch.value = value

    clearTimeout(timmer2);
    timmer2 = setTimeout(async () => {
        if (!value.length) {
            clientes.value = permanente.value
            return
        }
        loadingClientes.value = true;
        const { data } = await useCita().getClientesCitas(value)
        loadingClientes.value = false;
        const newReference = data.value?.data?.clientes;
        clientes.value = newReference;
         console.log(clientes.value);
    }, 1000);
       
}
function filtros(option:any,label:string,search:string){
    const query = search.toLowerCase();
    return(
    label.toLowerCase().includes(query) || option.telefono?.toString().includes(query) 
    )
}
function assignField(){
    if(pinia.getUser.rol!='cliente'){
        fields['cliente'] = clienteSeleccionado.value?.id
    }
    if(pinia.getUser.rol!='trabajador'){
        fields['trabajador'] = trabajadorselected.value?.id
    }
    fields['fecha'] = CitaInterface.value?.fecha
    fields['tipo'] = CitaInterface.value?.tipo
    fields['horario'] = CitaInterface.value?.horario_trabajador_id
    fields['mascota'] = CitaInterface.value?.mascota_id
    fields['descripcion'] = CitaInterface.value?.descripcion?.trim();
}
async function enviarDatos(){
    assignField()
    Object.keys(errores).forEach(key => {
            delete errores[key];
        });
    const result = AppointMentValidator(fields);
    Object.assign(errores,result.errors)
    if(result.isValid){
        fetching.value = true;
        const {data,statusCode} = await useCita().citastore(CitaInterface.value);
        fetching.value = false;
        if(statusCode.value == 201){
            emit('enviado',data.value?.message)
        }else{
            const {showToast} = useToast();
            showToast({status:'error',customMessage:data.value.error??data.value.message})
        }

    }

}

</script>

<template>
    <Teleport to="body">
        <div class="modal-fondo fixed flex justify-center items-center inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 p-4 md:p-8" style="z-index: 9999999" @click.self="emit('cerrar')">
            
            <div class="rounded-[20px] p-4 sm:p-5 md:p-6 bg-[#e2d2c4] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col w-full max-w-[800px] h-auto max-h-full md:max-h-[90vh]">
                
                <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] shrink-0 mb-2">Crear cita</h2>
                
                <form @submit.prevent class="flex flex-col flex-1 min-h-0 h-full">
                    <div class="form flex flex-col flex-1 min-h-0 pt-2 border-t-2 border-[#a07a58]">
                        
                        <div class="overflow-y-auto pr-1 md:pr-2 pb-2 custom-scrollbar flex-1">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                
                                <div class="selects flex flex-col gap-3">
                                    <div v-if="pinia.getUser.rol != 'cliente'">
                                        <p class="block text-[#523013] font-bold text-sm md:text-base">Dueño</p>
                                        <span class="error" v-if="errores?.['cliente']">{{errores?.['cliente']}}</span>
                                        <v-select
                                            :options="clientes??[]"
                                            label="nombre"
                                            v-model="clienteSeleccionado"
                                            @search="buscarClientes"
                                            :filterable="true"
                                            :filterBy="filtros"
                                            placeholder="Seleccione un dueño..."
                                            :calculate-position=" withPopper"
                                            append-to-body
                                            :clearSearchOnBlur="()=>false"
                                            :loading="loadingClientes">
                                            <template #no-options>0 resultados</template>
                                            <template #option="option:any">
                                                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-right: 8px;">
                                                        {{ option?.nombre }}
                                                    </span>
                                                    <small style="color: gray; flex-shrink: 0;">
                                                        {{ option?.telefono }}
                                                    </small>
                                                </div>
                                            </template>
                                            <template #selected-option="option:any">
                                                {{ option?.nombre }}
                                            </template>
                                        </v-select>    
                                    </div>
                                    <div>
                                        <p class="block text-[#523013] font-bold text-sm md:text-base">Mascota</p>
                                        <span class="error" v-if="errores?.['mascota']">{{errores?.['mascota']}}</span>
                                        <v-select
                                            :options="mascota?.data?.mascotas??[]"
                                            label="nombre"
                                            v-model="mascotaselected"
                                            @search="buscarClientes"
                                            placeholder="Seleccione una mascota..."
                                            :searchable="false"
                                            :calculate-position=" withPopper"
                                            append-to-body
                                            :loading="loadingMascotas"
                                            :disabled="mascotaDisabled">
                                            <template #no-options>0 resultados</template>
                                        </v-select>
                                    </div>
                                    <div>
                                        <p class="block text-[#523013] font-bold text-sm md:text-base">Tipo de cita</p>
                                        <span class="error" v-if="errores?.['tipo']">{{errores?.['tipo']}}</span>
                                        <v-select
                                            :options="[`medico`,`estetico`,'mixto']"
                                            label="tipo"
                                            v-model="tipocita"
                                            :searchable="false"
                                            :calculate-position=" withPopper"
                                            append-to-body
                                            placeholder="Seleccione un tipo de cita."/>
                                    </div>
                                    <div v-if="pinia.getUser.rol != 'trabajador'">
                                        <p class="block text-[#523013] font-bold text-sm md:text-base">Trabajador</p>
                                        <span class="error" v-if="errores?.['trabajador']">{{errores?.['trabajador']}}</span>
                                        <v-select
                                            :options="trabajador??[]"
                                            :searchable="false"
                                            :loading="loadingTrabajadores"
                                            :getOptionLabel="(option:any)=>option.user?.nombre"
                                            v-model="trabajadorselected"
                                            placeholder="Seleccione un trabajador"
                                        />
                                    </div>
                                    <div >
                                        <p class="block text-[#523013] font-bold text-sm md:text-base">Horario</p>
                                        <span class="error" v-if="errores?.['horario']">{{errores?.['horario']}}</span>
                                        <v-select 
                                            :options="Hora??[]"
                                            :getOptionLabel="(option:any)=>option.horario?.inicio_hora"
                                            v-model="horaselected"
                                            :loading="loadingHoras"
                                            :searchable="false"
                                            :disabled="horaDisabled"
                                            :calculate-position=" withPopper"
                                            append-to-body
                                            placeholder="Seleccione una hora...">
                                            <template #no-options>
                                                0 resultados
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                                
                                <div class="calendar pb-2 md:pb-6 w-full flex flex-col">
                                    <p class="block text-[#523013] font-bold text-sm md:text-base">Calendario</p>
                                    <span class="error" v-if="errores?.['fecha']">{{errores?.['fecha']}}</span>
                                    <div class="flex-1 w-full min-h-[250px]">
                                        <CalendarComponent :allow-future="true" :isForAppointment="true" v-model:modelValue="fechaCita" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 md:mt-2">
                                <p class="block text-[#523013] font-bold text-sm md:text-base">Descripción</p>
                                <span class="error" v-if="errores?.['descripcion']">{{errores?.['descripcion']}}</span>
                                <textarea class="text-tarea modal-input w-full py-2.5 md:py-3 px-3 md:px-4 rounded-md bg-[#fcf7f2] box-border font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none min-h-[80px] outline-none text-sm md:text-base" v-model="descripcion"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-3 justify-center mt-3 md:mt-5 pt-3 md:pt-4 border-t-2 border-[#a07a58] shrink-0">
                            <button type="button"
                            class="w-full sm:flex-1 py-3 md:py-3.5 font-extrabold text-sm md:text-base rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-[1.02] hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#b51f1f]"
                            @click="emit('cerrar')" :disabled="fetching">Cerrar</button>
                            <button type="button"
                                    class="w-full sm:flex-1 flex justify-center items-center py-3 md:py-3.5 font-extrabold text-sm md:text-base rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#812c8f] hover:scale-[1.02] hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f]"
                                    @click="enviarDatos" :disabled="fetching">
                                    <div v-if="fetching" class="loader"></div>
                                    <span v-else>Aceptar</span>
                            </button>
                        </div> 
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

.loader {
  margin:auto;
  width: 25px;
  aspect-ratio: 1;
  border-radius: 50%;
  border: 4px solid #812c8f;
  animation:
    l20-1 0.8s infinite linear alternate,
    l20-2 1.6s infinite linear;
}

button:hover .loader {
    border-color: white;
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

.error{
    @apply text-[11px] md:text-sm text-[#b51f1f] font-bold block mb-0.5;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(160, 122, 88, 0.4);
  border-radius: 10px;
}
    
:deep(.vs__dropdown-toggle) {
    @apply w-full py-1.5 md:py-2 px-2 rounded-md bg-[#fcf7f2] cursor-pointer font-medium text-[#523013] border-b-2 border-b-[#812c8f] overflow-hidden text-sm md:text-base outline-none;
}

:deep(.vs__search) {
  color: #3B2A1A;
  margin-top: 0;
}

:deep(.vs__dropdown-menu) {
    width: 100%; 
    max-height: 200px;
    margin-top: 4px;
    background-color: #F5EBE0;
    border: 0.5px solid #D4B8A0;
    z-index: 99999999; 
}

:deep(.vs__dropdown-option) {
    color: #523013;
    padding: 6px 12px;
    font-weight: 500;
}

:deep(.vs_no-options){
    display: none;
}

:deep(.vs__dropdown-option--highlight) {
  background-color: #C0392B;
  color: white;
}

:deep(.vs__selected-options){
    flex-wrap: nowrap;
    overflow-y: hidden;
    scrollbar-width: none;
}

:deep(.vs__selected) {
  color: #3B2A1A;
  background-color: #EDD9C8;
  border: 0.5px solid #D4B8A0;
  white-space: nowrap;
}

/* Removidos los anchos fijos que rompían el móvil */
.wrapper-results{
    width: 100%;
    background-color: #A9F5F0;
    margin-top: 10px;
    max-height: 100px;
    overflow-y: auto;
}

.searcher-modal{
    position: absolute;
    z-index: 100;
}

.searcher{
    display: inline-block;
    width: 100%;
    cursor: pointer;
    color: gray;
    background-color: #A9F5F0;
}
</style>