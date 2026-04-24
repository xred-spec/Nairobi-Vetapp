<script setup lang="ts">
import apexchart from 'vue3-apexcharts';
import type { ApexOptions } from 'apexcharts';
import { useReportes } from '@/composables/useReportes';
import { onMounted, computed, watch } from 'vue';
import StatsGrid from '@/components/StatsGrid.vue';
import { useToast } from '@/composables/useToast';
import Loader from '@/components/Loader.vue';

const {
    inicio,
    fin,
    infoGeneral,
    trabajadores,
    ingresosPromedios,
    finanzasTipoCita,
    finanzasPorVeterinario,
    loading,
    obtenerReporteGeneral,
    obtenerReporteGeneral2
} = useReportes();

const { showToast, ToastStatus } = useToast();

const chartOptionsCitas = computed((): ApexOptions => ({
    labels: ['Agendadas', 'En Proceso', 'Realizadas', 'Canceladas'],
    colors: ['#dc7474', '#523013', '#812c8f', '#b51f1f'],
    chart: {
        type: 'donut',
        fontFamily: 'inherit',
        sparkline: { enabled: false },
        offsetY: -10
    },
    plotOptions: {
        pie: {
            customScale: 1.1,
            donut: {
                size: '75%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total Citas',
                        formatter: () => String(infoGeneral.value?.total_citas || 0),
                        color: '#523013',
                        fontSize: '16px',
                        fontWeight: '900'
                    }
                }
            }
        }
    },
    legend: {
        position: 'bottom',
        offsetY: 0,
        fontSize: '12px',
        fontWeight: 'bold',
        labels: { colors: '#4a2c10' },
        markers: {
            fillColors: ['#dc7474', '#523013', '#812c8f', '#b51f1f'],
            shape: 'circle'
        }
    },
    dataLabels: { enabled: false },
    stroke: { show: false }
}));

const chartSeriesCitas = computed(() => {
    if (!infoGeneral.value) return [0, 0, 0, 0];
    return [
        Number(infoGeneral.value.citas_agendadas),
        Number(infoGeneral.value.citas_proceso),
        Number(infoGeneral.value.citas_realizadas),
        Number(infoGeneral.value.citas_canceladas)
    ];
});

onMounted(() => {
    obtenerReporteGeneral();
    obtenerReporteGeneral2();
});

const statsMapeadas = computed(() => {
    if (!infoGeneral.value) return undefined;
    return {
        usuarios: infoGeneral.value.nuevos_clientes,
        mascotas: infoGeneral.value.nuevas_mascotas
    };
});

// validacion lógica de fechas
watch([inicio, fin], ([nuevoInicio, nuevoFin]) => {
    if (nuevoInicio && nuevoFin) {
        const fechaI = new Date(nuevoInicio);
        const fechaF = new Date(nuevoFin);

        if (fechaI > fechaF) {
            fin.value = nuevoInicio;
            showToast({ 
                status: ToastStatus.WARNING, 
                customMessage: 'La fecha de inicio no puede ser mayor a la de fin' 
            });
        }
    }
});
</script>

<template>
    <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
        
        <div class="main-header w-full flex flex-col md:flex-row items-center md:justify-between py-3 px-4 md:px-5 mt-2 rounded-xl bg-[#523013] text-[#fcf7f2] gap-3 md:gap-0 shrink-0 shadow-sm">
            <h1 class="z-10 flex justify-center md:justify-start text-center md:text-start text-xl md:text-2xl font-black w-full md:w-auto">
                Reporte de Actividad
            </h1>
            
            <div class="flex flex-col sm:flex-row items-center justify-center w-full md:w-auto bg-[#fcf7f2] px-4 py-2 md:py-1.5 rounded-md shadow-sm shrink-0 gap-2 md:gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-xs md:text-sm font-black text-[#dc7474]">Desde</label>
                    <input type="date" v-model="inicio" :max="fin"
                        class="bg-transparent text-sm md:text-base font-bold text-[#523013] outline-none cursor-pointer" />
                </div>
                <span class="text-[#a07a58] hidden sm:block opacity-50">|</span>
                <div class="flex items-center gap-2">
                    <label class="text-xs md:text-sm font-black text-[#dc7474]">Hasta</label>
                    <input type="date" v-model="fin" :min="inicio"
                        class="bg-transparent text-sm md:text-base font-bold text-[#523013] outline-none cursor-pointer" />
                </div>
            </div>
        </div>

        <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
             :class="!loading ? 'pt-2 px-3 md:px-5' : ''">
            
            <Transition name="fade-tabla" mode="out-in">
                
                <div v-if="loading" class="w-full h-full flex justify-center items-center">
                    <Loader :state="'loading'" />
                </div>
                
                <div v-else class="w-full h-full flex flex-col items-center min-h-0">
                    
                    <div class="w-full max-w-7xl flex flex-col h-full min-h-0 overflow-y-auto custom-scrollbar pr-1 md:pr-2 pb-4 gap-4">
                        
                        <StatsGrid class="shrink-0" :stats="statsMapeadas" :proxima-cita="null" :loading="loading" />

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 shrink-0">
                            <div class="bg-[#fcf7f2] p-5 rounded-[20px] shadow-sm flex flex-col items-center justify-center min-h-[420px] transition-all hover:shadow-md">
                                <h3 class="font-black text-[#523013] text-lg mb-6 text-center">
                                    Distribución de Citas
                                </h3>
                                <div class="w-full max-w-[280px] flex items-center justify-center">
                                    <apexchart v-if="chartSeriesCitas.some(val => val > 0)" :key="infoGeneral?.total_citas"
                                    type="donut" width="100%" :options="chartOptionsCitas" :series="chartSeriesCitas" />
                                    <div v-else class="flex flex-col items-center text-[#523013] py-10 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-column-icon lucide-chart-column size-5"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                                        <p class="font-bold">Sin datos registrados</p>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                                <div class="bg-[#fcf7f2] p-6 md:p-8 rounded-[20px] shadow-sm flex flex-col justify-center relative overflow-hidden group">
                                    <p class="text-[#812c8f] text-sm font-bold mb-2">Citas Realizadas</p>
                                    <div class="flex items-center gap-3">
                                        <span class="text-4xl md:text-5xl font-black text-[#812c8f]">{{ infoGeneral?.citas_realizadas }}</span>
                                    </div>
                                    <div class="absolute -right-2 -top-2 text-6xl md:text-7xl font-black text-[#812c8f] group-hover:scale-110 transition-transform opacity-50">✓</div>
                                </div>

                                <div class="bg-[rgb(252,247,242)] p-6 md:p-8 rounded-[20px] shadow-sm flex flex-col justify-center relative overflow-hidden group">
                                    <p class="text-[#523013] text-sm font-bold mb-2">En Proceso / Pendientes</p>
                                    <span class="text-4xl md:text-5xl font-black text-[#523013]">{{ infoGeneral?.citas_proceso }}</span>
                                </div>

                                <div class="bg-[#fcf7f2] p-6 md:p-8 rounded-[20px] shadow-sm flex flex-col justify-center relative overflow-hidden group">
                                    <p class="text-[#b51f1f] text-sm font-bold mb-2">Canceladas</p>
                                    <span class="text-4xl md:text-5xl font-black text-[#b51f1f]">{{ infoGeneral?.citas_canceladas }}</span>
                                </div>

                                <div class="bg-[#dc7474] p-6 md:p-8 rounded-[20px] shadow-sm flex flex-col justify-center relative overflow-hidden group">
                                    <div class="relative z-10">
                                        <p class="text-[#fcf7f2] text-sm font-bold mb-2">Total Consultas Atendidas</p>
                                        <span class="text-4xl md:text-5xl font-black text-[#fcf7f2]">{{ infoGeneral?.total_consultas }}</span>
                                    </div>
                                    <div class="absolute -right-4 -bottom-6 text-[#fcf7f2] text-8xl md:text-9xl font-black select-none rotate-12 opacity-50">#</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#fcf7f2] rounded-[20px] shadow-sm flex flex-col overflow-hidden max-h-[500px] shrink-0">
                            <div class="p-6 md:p-8 border-b border-[#a07a58] flex flex-col md:flex-row justify-between md:items-center bg-[#fcf7f2] z-10 gap-3 md:gap-0">
                                <div>
                                    <h3 class="font-black text-[#523013] text-xl leading-none">Productividad Médica</h3>
                                    <p class="text-xs text-[#523013]/50 font-bold mt-2">Seguimiento por especialista asignado</p>
                                </div>
                                <div class="bg-[#812c8f] text-[#fcf7f2] text-sm font-black px-6 py-2 rounded-md w-fit">
                                    {{ infoGeneral?.trabajadores_activos }} Activos
                                </div>
                            </div>
                            <div class="overflow-x-auto overflow-y-auto custom-scrollbar flex-1 bg-[#fcf7f2]">
                                <table class="w-full text-left border-collapse min-w-[500px]">
                                    <thead class="sticky top-0 z-20 bg-[#faf7f2]">
                                        <tr>
                                            <th class="px-6 md:px-8 py-4 text-sm md:text-base font-bold text-[#523013] border-b border-[#a07a58] text-left">Especialista</th>
                                            <th class="px-6 md:px-8 py-4 text-sm md:text-base font-bold text-[#523013] border-b border-[#a07a58] text-center">Asignadas</th>
                                            <th class="px-6 md:px-8 py-4 text-sm md:text-base font-bold text-[#523013] border-b border-[#a07a58] text-right">Completadas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#a07a58]/50">
                                        <tr v-for="t in trabajadores" :key="t.nombre_trabajador" class="hover:bg-[#faf7f2]/70 transition-colors group">
                                            <td class="px-6 md:px-8 py-2 font-bold text-[#523013] flex items-center gap-3 md:gap-4">
                                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl md:rounded-2xl bg-[#523013] text-[#faf7f2] flex items-center justify-center font-black text-sm group-hover:bg-[#dc7474] group-hover:text-white transition-all transform group-hover:scale-105 shrink-0">
                                                    {{ t.nombre_trabajador.charAt(0) }}
                                                </div>
                                                <span class="group-hover:translate-x-1 transition-transform truncate">{{ t.nombre_trabajador }}</span>
                                            </td>
                                            <td class="px-6 md:px-8 py-2 text-center text-[#b51f1f] font-black">{{ t.cantidad_citas }}</td>
                                            <td class="px-6 md:px-8 py-2 text-right">
                                                <span class="bg-[#812c8f] px-6 py-2 rounded-md font-black text-[#faf7f2] border border-transparent group-hover:border-[#BE570D]/20 transition-colors inline-block">
                                                    {{ t.completadas }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <section class="bg-[#fcf7f2] rounded-[20px] shadow-sm flex flex-col overflow-hidden max-h-[500px] shrink-0">
                            <div class="p-6 md:p-8 border-b border-[#a07a58] flex justify-between items-center bg-[#fcf7f2] z-10 rounded-t-[20px]">
                                <div>
                                    <h3 class="font-black text-[#523013] text-xl leading-none">Rendimiento Financiero</h3>
                                    <p class="text-xs text-[#523013]/50 font-bold mt-2">Desglose de ingresos por especialista</p>
                                </div>
                            </div>

                            <div class="overflow-y-auto custom-scrollbar flex flex-col px-4 md:px-8">
                                <div v-for="vet in finanzasPorVeterinario" :key="vet.nombre" 
                                    class="py-5 transition-all group relative border-b last:border-0 border-[#a07a58]/20">
                                    
                                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 lg:gap-6 relative z-10">    
                                        <div class="flex items-center gap-4 lg:w-1/4 w-full">
                                            <div class="w-12 h-12 md:w-14 md:h-14 shrink-0 rounded-2xl bg-[#523013] text-[#fcf7f2] flex items-center justify-center font-black text-xl shadow-lg shadow-[#4a2c10]/20">
                                                {{ vet.nombre.charAt(0) }}
                                            </div>
                                            <div class="flex flex-col justify-center min-w-0">
                                                <h4 class="font-black text-[#523013] text-base md:text-lg leading-tight truncate">{{ vet.nombre }}</h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-[10px] md:text-xs bg-[#812c8f] text-[#faf7f2] px-2 py-1 rounded-md font-bold shrink-0">
                                                        {{ vet.total_citas }} citas
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 w-full items-center">
                                            <div class="flex flex-col justify-center">
                                                <span class="text-[10px] md:text-xs font-black text-[#523013]">Venta Productos</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-xl md:text-2xl font-bold text-[#dc7474]">$</span>
                                                    <span class="text-xl md:text-2xl font-black text-[#dc7474]">{{ vet.ganancia_productos.toFixed(2) }}</span>
                                                </div>
                                            </div>
                                                
                                            <div class="flex flex-col justify-center">
                                                <span class="text-[10px] md:text-xs font-black text-[#523013]">Honorarios Servicios</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-xl md:text-2xl font-bold text-[#812c8f]">$</span>
                                                    <span class="text-xl md:text-2xl font-black text-[#812c8f]">{{ vet.ganancia_servicios }}</span>
                                                </div>
                                            </div>

                                            <div class="flex flex-col justify-center md:items-end col-span-2 md:col-span-1 border-t md:border-t-0 border-[#a07a58]/20 pt-2 md:pt-0">
                                                <span class="text-[10px] md:text-xs font-black text-[#523013]">Generación Total</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-xl md:text-2xl font-bold text-[#b51f1f]">$</span>
                                                    <span class="text-xl md:text-2xl font-bold text-[#b51f1f]">{{ vet.total }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="finanzasPorVeterinario.length == 0" class="flex flex-col items-center justify-center py-10 px-4 gap-3 rounded-2xl border border-dashed border-stone-300 bg-stone-50 my-4">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="4" y="8" width="32" height="24" rx="4" stroke="#a8a29e" stroke-width="1.5"/>
                                        <path d="M13 20h14M13 25h8" stroke="#a8a29e" stroke-width="1.5" stroke-linecap="round"/>
                                        <circle cx="30" cy="10" r="6" fill="white" stroke="#a8a29e" stroke-width="1.5"/>
                                        <path d="M30 8v4M28 10h4" stroke="#a8a29e" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-[#523013] text-center m-0">Sin citas completadas aún</p>
                                    <p class="text-xs text-[#523013]/50 text-center max-w-xs m-0">Cuando un especialista finalice una consulta, aquí aparecerá el desglose de ingresos.</p>
                                </div>
                            </div>
                        </section>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 shrink-0">
                            <div class="bg-[#fcf7f2] p-6 md:p-8 rounded-[20px] shadow-sm">
                                <h4 class="font-black text-[#523013] text-base mb-2">Ticket Promedio por Tipo</h4>
                                <div class="py-4">
                                    <div v-for="promedio in ingresosPromedios" :key="promedio.tipo" 
                                         class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-y border-y-[#e2d2c4] font-bold gap-2 sm:gap-0">
                                        <span class="font-black text-[#523013]">{{ promedio.tipo }}</span>
                                        <div class="flex flex-wrap gap-2 md:gap-4 text-xs md:text-sm font-bold">
                                            <span class="text-[#523013]">Total citas: {{promedio.total_citas}}</span>
                                            <span class="text-[#dc7474]">S: ${{ promedio.promedio_servicios.toFixed(2) }}</span>
                                            <span class="text-[#812c8f]">P: ${{ promedio.promedio_producto.toFixed(2) }}</span>
                                        </div>
                                    </div>
                                    <div v-if="ingresosPromedios.length == 0" class="flex flex-col items-center justify-center py-10 gap-3 rounded-[2rem] border border-dashed border-[#e5d5c5] bg-[#faf7f2]">
                                        <div class="w-12 h-12 rounded-2xl bg-[#f2e9e0] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4a2c10" stroke-width="2" stroke-opacity="0.3">
                                                <path d="M2 20h20M6 20V10M12 20V4M18 20v-6"/>
                                            </svg>
                                        </div>
                                        <p class="font-black text-[#523013] text-sm text-center leading-none">Sin datos de ticket promedio</p>
                                        <p class="text-xs text-[#523013]/50 font-bold text-center">No hay citas completadas aún</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#fcf7f2] p-6 md:p-8 rounded-[20px] shadow-sm">
                                <h4 class="font-black text-[#523013] text-base mb-2">Resumen de Margen por Cita</h4>
                                <div class="py-4">
                                    <div v-for="finanzas in finanzasTipoCita" :key="finanzas.tipo" 
                                         class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-y border-y-[#e2d2c4] font-bold gap-2 sm:gap-0"> 
                                        <span class="font-black text-[#523013]">{{ finanzas.tipo }}</span>
                                        <div class="flex flex-wrap gap-2 md:gap-4 text-xs md:text-sm font-bold">
                                            <span class="text-[#523013]">Total citas: {{finanzas.total_citas}}</span>
                                            <span class="text-[#dc7474]">S: ${{ finanzas.sutotal.toFixed(2) }}</span>
                                            <span class="text-[#812c8f]">P: ${{ finanzas.diferencia.toFixed(2) }}</span>
                                            <span class="text-[#b51f1f]">${{ finanzas.total.toFixed(2) }}</span>
                                        </div>
                                    </div>
                                    <div v-if="finanzasTipoCita.length == 0"  class="flex flex-col items-center justify-center py-10 gap-3 rounded-[2rem] border border-dashed border-[#e5d5c5] bg-[#faf7f2]">
                                        <div class="w-12 h-12 rounded-2xl bg-[#f2e9e0] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4a2c10" stroke-width="2" stroke-opacity="0.3">
                                                <circle cx="12" cy="12" r="10"/>
                                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/>
                                            </svg>
                                        </div>
                                        <p class="font-black text-[#523013] text-sm text-center leading-none">Sin márgenes registrados</p>
                                        <p class="text-xs text-[#523013]/50 font-bold text-center">No hay citas completadas aún</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.fade-tabla-enter-active, .fade-tabla-leave-active { transition: opacity 0.2s ease; }
.fade-tabla-enter-from, .fade-tabla-leave-to { opacity: 0; }

/* Scrollbar sutil estandarizada para toda la vista */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(160, 122, 88, 0.3);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #BE570D;
}
</style>