<script setup lang ="ts">

import {useUserStore} from '@/stores/user.ts';
//import CalendarComponent from '@/components/CalendarComponent.vue';
import {onMounted,onUnmounted,ref} from 'vue';
const useUSer = useUserStore();
const rol = useUSer.getUser.rol;
const modal = ref<HTMLElement|null>(null);
const props = defineProps<{
    cita:any,
}>()
const emit = defineEmits<{
    (e:'cerrar'):void
}>()
const handdler =(event:MouseEvent)=>{
    if(modal.value && !modal.value.contains(event.target as Node)){
        emit('cerrar');
    }
}
onMounted(()=>{
    document.addEventListener('click',handdler);
});
onUnmounted(()=>document.addEventListener('click',handdler));

if(props.cita.consulta){
    props.cita.consulta.consulta_servicios.forEach((element:any) => {
    element['visible'] = false
    });
}

const formatFecha = (fechaInput: any) => {
    console.log(fechaInput);
    if (!fechaInput) return 'Sin fecha';
    const dateStr = typeof fechaInput === 'object' ? fechaInput.date : fechaInput;
    const soloFecha = dateStr.split(' ')[0].split('T')[0];
    const [year, month, day] = soloFecha.split('-');
    
    return `${day}/${month}/${year}`;
};
</script>

<template>
    <Teleport to="body">
        <div class="modal-fondo fixed flex justify-center items-center inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 px-3 sm:px-4" style="z-index: 9999999" @click.self="emit('cerrar')">
            
            <div class="rounded-[20px] p-4 sm:p-5 md:p-6 bg-[#e2d2c4] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col h-auto max-h-[95vh] md:max-h-[90vh] w-full max-w-[1300px]">
                
                <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] mb-1 shrink-0 truncate">Cita: {{cita?.mascota?.nombre}}</h2>
                
                <div class="flex flex-col lg:flex-row flex-1 items-stretch mt-1 pt-2 md:pt-4 border-t-2 border-[#a07a58] min-h-0 overflow-y-auto lg:overflow-hidden gap-6 lg:gap-0 custom-scrollbar pr-1 lg:pr-0">
                    
                    <div class="big-data-left w-full lg:w-1/3 py-1 pr-0 lg:pr-5 border-b border-[#a07a58]/30 lg:border-b-0 lg:border-r-2 lg:border-[#a07a58] overflow-visible lg:overflow-y-auto overflow-x-hidden pb-4 lg:pb-0 shrink-0">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                            <div class="left-wrap flex flex-col gap-3">
                                <div v-if="rol!='cliente'">
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Cliente</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{cita?.mascota?.cliente?.user?.nombre}}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Tipo de cita</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{cita?.tipo}}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Mascota</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{cita?.mascota?.nombre}}
                                    </div>
                                </div>

                                <div v-if="rol!='cliente'" class="flex flex-col gap-3">
                                    <div>
                                        <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Animal</label>
                                        <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                            {{cita?.mascota?.raza?.animal?.nombre}}
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Raza</label>
                                        <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                            {{cita?.mascota?.raza?.nombre}}
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Sexo</label>
                                        <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                            {{cita?.mascota?.sexo}}
                                        </div>
                                    </div>
                                </div>
                                
                                <div v-if="rol=='cliente'" class="flex flex-col gap-3">
                                    <div>
                                        <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Trabajador</label>
                                        <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                            {{cita?.horario_trabajador?.trabajador?.user?.nombre}}
                                        </div>
                                    </div>
                            
                                    <div>
                                        <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Hora</label>
                                        <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                            {{cita?.horario_trabajador?.horario?.inicio_hora}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="right-wrap flex flex-col gap-3">
                                <div v-if="rol=='administrador'">
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Trabajador</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{cita?.horario_trabajador?.trabajador?.user?.nombre}}
                                    </div>
                                </div>
                            
                                <div>
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Fecha</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{formatFecha(cita?.fecha)}}
                                    </div>
                                </div>

                                <div v-if="rol!='cliente'">
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Hora</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{cita?.horario_trabajador?.horario?.inicio_hora}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 md:mt-4">
                            <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Descripción</label>
                            <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed whitespace-pre-wrap break-words text-sm md:text-base min-h-[60px]">
                                {{cita?.descripcion ?? 'Sin descripción'}}
                            </div>
                        </div>
                    </div>

                    <div class="big-data-middle w-full lg:w-1/3 py-1 px-0 lg:px-5 border-b border-[#a07a58]/30 lg:border-b-0 lg:border-r-2 lg:border-[#a07a58] overflow-visible lg:overflow-y-auto overflow-x-hidden pb-4 lg:pb-0 shrink-0">
                        <template v-if="cita?.consulta">
                            <div v-if="cita?.consulta?.pre_diagnostico && cita?.estado != 'realizada'" class="mb-3 md:mb-4">
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Prediagnóstico</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed whitespace-pre-wrap break-words text-sm md:text-base min-h-[60px]">
                                    {{cita.consulta.pre_diagnostico ?? 'Sin prediagnóstico'}}
                                </div>
                            </div>
                            <div v-if="cita?.estado == 'realizada'" class="mb-3 md:mb-4">
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Diagnóstico</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed whitespace-pre-wrap break-words text-sm md:text-base min-h-[60px]">
                                    {{cita.consulta.pre_diagnostico ?? 'Sin prediagnóstico'}}
                                </div>
                            </div>
                            <div class="mb-3 md:mb-4">
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Descripción de la consulta</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed whitespace-pre-wrap break-words text-sm md:text-base min-h-[60px]">
                                    {{cita.consulta.descripcion_consulta ?? 'Sin descripción'}}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Indicaciones</label>
                                <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed whitespace-pre-wrap break-words text-sm md:text-base min-h-[60px]">
                                    {{cita.consulta.indicaciones ?? 'Sin indicaciones'}}
                                </div>
                            </div>
                        </template>
                        <div v-else class="h-full min-h-[100px] flex items-center justify-center opacity-50 text-[#523013] font-bold text-center">
                            No hay consulta registrada
                        </div>
                    </div>

                    <div class="big-data-right w-full lg:w-1/3 py-1 pl-0 lg:pl-5 overflow-visible lg:overflow-y-auto overflow-x-hidden shrink-0">
                        <template v-if="cita?.consulta?.consulta_servicios?.length">
                            <label class="block text-[#523013] mb-2 md:mb-3 font-bold text-xs md:text-sm opacity-80">Servicios y Productos</label>
                            <div v-for="servicio in cita.consulta.consulta_servicios" :key="servicio.id" 
                            class="service-wrapper bg-[#fcf7f2] rounded-[16px] px-3 md:px-4 py-2.5 mb-3 border border-[#a07a58]/20 shadow-sm">
                                <div class="subheader-servicios-name flex items-baseline justify-between gap-2">
                                    <div class="subheader-servicios-buttons">
                                        <p class="header-text-service font-extrabold text-[#b51f1f] text-sm md:text-base">{{servicio?.servicio?.nombre}}</p>
                                    </div>
                                    <div class="shrink-0">
                                        <p class="btn-ocultar font-bold text-xs md:text-sm text-[#812c8f] cursor-pointer hover:scale-95 transition-transform" 
                                        @click="servicio.visible = !servicio.visible">
                                            {{servicio.visible ? 'Ocultar' : 'Mostrar'}}
                                        </p>
                                    </div>
                                </div>

                                <div v-if="servicio.visible" class="mt-2">
                                    <p class="text-[#523013] text-xs md:text-sm font-bold mt-1 opacity-70">Detalles del servicio</p> 
                                    <p class="text-sm md:text-base font-medium text-[#523013]">{{servicio?.detalles_servicio}}</p>
                                    
                                    <template v-if="servicio?.consulta_servicio_productos?.length">
                                        <div class="grid-productos grid grid-cols-4 gap-1 w-full items-center font-bold text-[10px] md:text-xs mt-3 text-[#523013] text-center border-b border-[#a07a58]/40 pb-1">
                                            <p class="truncate">Producto</p>
                                            <p class="truncate">Tipo</p>
                                            <p class="truncate">Cant.</p>
                                            <p class="truncate">Subtot.</p>
                                        </div>

                                        <div class="mt-1">
                                            <div v-for="producto in servicio.consulta_servicio_productos" :key="producto.id" 
                                            class="grid-productos-content grid grid-cols-4 gap-1 w-full items-center font-medium text-[10px] md:text-xs mt-1.5 text-[#523013] text-center">
                                                <p class="font-bold truncate text-[#812c8f]">{{producto?.producto?.nombre}}</p>
                                                <p class="truncate opacity-80">{{producto?.tipo_venta}}</p>
                                                <p class="opacity-80">{{producto?.cantidad_usada}}</p>
                                                <p class="font-bold text-[#b51f1f]">${{producto?.subtotal}}</p>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="cita-final-wrapper flex flex-wrap sm:grid sm:grid-cols-3 justify-between gap-2 w-full items-center font-normal text-xs md:text-sm pt-2 mt-3 text-[#523013] text-center border-t border-[#a07a58]/40">
                                        <div class="flex flex-col flex-1  rounded-md py-1"> 
                                            <p class="font-bold text-[#812c8f] text-[10px]">Servicio</p>
                                            <p class="font-extrabold text-[#812c8f]">${{servicio?.precio_servicio}}</p>
                                        </div>
                                        <div class="flex flex-col flex-1  rounded-md py-1">
                                            <p class="font-bold text-[#812c8f] text-[10px]">Prods.</p>
                                            <p class="font-extrabold text-[#812c8f]">${{servicio?.precio_producto}}</p>
                                        </div>
                                        <div class="flex flex-col flex-1  rounded-md py-1">
                                            <p class="font-bold text-[#b51f1f] text-[10px]">Total</p>
                                            <p class="font-extrabold text-[#b51f1f]">${{servicio?.total}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div v-else class="h-full min-h-[100px] flex items-center justify-center opacity-50 text-[#523013] font-bold text-center px-4">
                            Aún no hay servicios ni productos facturados
                        </div>
                    </div>
                </div> <div v-if="cita?.consulta" class="cita-final-total grid grid-cols-2 lg:flex items-center justify-between mt-3 md:mt-4 pt-3 md:pt-4 border-t-2 border-[#a07a58] gap-2 md:gap-4 shrink-0">
                    <div class="price-note flex-col md:flex-row flex-1 text-center bg-[#fcf7f2] py-2 md:py-3 px-2 md:px-5 rounded-xl border border-[#a07a58]/20 shadow-sm">
                        <p class="text-[#812c8f] font-bold text-base md:text-xl">Total Servicios: </p>
                        <p class="text-[#812c8f] font-black text-base md:text-xl">${{cita?.consulta?.total_servicios}}</p>
                    </div>
                    <div class="price-note flex-col md:flex-row flex-1 text-center bg-[#fcf7f2] py-2 md:py-3 px-2 md:px-5 rounded-xl border border-[#a07a58]/20 shadow-sm">
                        <p class="text-[#812c8f] font-bold text-base md:text-xl">Total Producto: </p>
                        <p class="text-[#812c8f] font-black text-base md:text-xl">${{cita?.consulta?.total_producto}}</p>
                    </div>
                    <div class="price-note flex-col md:flex-row col-span-2 lg:flex-1 text-center bg-[#b51f1f]/10 py-3 md:py-3 px-2 md:px-5 rounded-xl border border-[#b51f1f]/20 shadow-sm"> 
                        <p class="text-[#b51f1f] font-black text-base md:text-xl">Total a Pagar: </p>
                        <p class="text-[#b51f1f] font-black text-base md:text-xl">${{cita?.consulta?.total}}</p>
                    </div>
                </div>

                <div class="flex justify-center pt-3 md:pt-4 mt-2 md:mt-3 border-t-2 border-[#a07a58] shrink-0">
                    <button type="button"
                    class="w-full md:w-auto md:px-12 py-3.5 md:py-3 font-black text-base rounded-lg cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-105 hover:shadow-xl hover:text-[#fcf7f2] hover:bg-[#b51f1f] border border-[#b51f1f]/20 hover:border-transparent"
                    @click="emit('cerrar')">
                    Cerrar Modal
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

.price-note {
    @apply flex gap-1 justify-center items-center w-full bg-[#fcf7f2] px-5 py-3 rounded-[20px];
}

/* Scrollbar sutil para el modal */
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
</style>