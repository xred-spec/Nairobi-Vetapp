<script setup lang="ts">
import { ref, watch, computed, nextTick, onMounted, onBeforeUnmount } from 'vue';
import StatsGrid from '@/components/StatsGrid.vue';
import CalendarComponent from '@/components/CalendarComponent.vue';
import CitaCard from '@/components/CitaCard.vue';
import Loader from '@/components/Loader.vue';
import { useDashboard } from '@/composables/useDashboard';
import { useUserStore } from '@/stores/user';
import { useReportes } from '@/composables/useReportes';
import {
    createSocket,
    userConnection,
    adminConnection
} from '@/services/broadcastingService.ts';

const { citas, citasFiltradas, fechaFiltro, obtenerCitas, cantidadCitas } = useDashboard();
const { datosDistribucion, loadingTipos, cargarDistribucion } = useReportes();
const userStore = useUserStore();
const loaderState = ref<string | null>('loading');

const scrollContainer = ref<HTMLElement | null>(null);
const hasScrollbar = ref(false);
let resizeObserver: ResizeObserver | null = null;

const totalDia = computed(() => {
    return datosDistribucion.value.reduce((acc, item) => acc + item.cantidad, 0);
});

const checkScrollbar = () => {
    if (scrollContainer.value) {
        hasScrollbar.value = scrollContainer.value.scrollHeight > scrollContainer.value.clientHeight;
    }
};


const handleCitaEvent = async (e: any) => {
    console.log(`[WS MainView] Evento detectado: ${e.action}`);
    const reloadEvents = ['creation', 'modificacion', 'estado'];

    if (reloadEvents.includes(e.action)) {
        console.log(`[WS MainView] Sincronizando lista por ${e.action}...`);
        await Promise.all([
            obtenerCitas(),
            cargarDistribucion(fechaFiltro.value || new Date().toISOString().split('T')[0])
        ]);
        await nextTick();
        checkScrollbar();
    }
};

const connectWebSocket = () => {
    const user = userStore.getUser;
    if (!user?.id || !user?.rol) return;

    createSocket();

    if (user.rol === 'administrador') {
        adminConnection(handleCitaEvent);
    } else {
        userConnection(handleCitaEvent);
    }
};

onMounted(async () => {

    const hoy = new Date().toISOString().split('T')[0];


    const [successCitas] = await Promise.all([
        obtenerCitas(),
        cargarDistribucion(hoy)
    ]);
    console.log(datosDistribucion.value);

    loaderState.value = successCitas ? 'success' : 'error';
    setTimeout(() => loaderState.value = null, 800);
    connectWebSocket();
});

watch(loaderState, async (newVal) => {
    if (newVal === null) {
        await nextTick();
        if (scrollContainer.value) {
            resizeObserver = new ResizeObserver(checkScrollbar);
            resizeObserver.observe(scrollContainer.value);
            checkScrollbar();
        }
    }
});

watch(() => userStore.getUser?.id, (newId) => {
    if (newId) connectWebSocket();
});

watch(fechaFiltro, (nuevaFecha) => {
    if (nuevaFecha) {
        cargarDistribucion(nuevaFecha);
    }
    console.log(nuevaFecha);
    console.log(datosDistribucion.value);
});

onBeforeUnmount(() => {
    if (resizeObserver) resizeObserver.disconnect();
});
</script>

<template>
    <div class="flex flex-col lg:flex-row w-full h-full lg:overflow-hidden overflow-y-auto min-h-0 custom-scrollbar lg:flex-nowrap">

        <div class="flex flex-col w-full lg:w-4/6 lg:h-full min-h-0 gap-1.5 px-2 lg:px-0 shrink-0 lg:shrink">
            <StatsGrid :stats="{ pendientes: cantidadCitas }" :proxima-cita="citas[0]" :loading="loaderState === 'loading'" />

            <section class="flex flex-col shrink-0 lg:shrink lg:flex-1 min-h-0 gap-1.5 mt-1">
                <div class="flex justify-between items-center px-2 md:px-5">
                    <h2 class="text-[#523013] text-lg md:text-xl font-black tracking-tight">Citas agendadas</h2>
                    <button class="text-[#812c8f] flex items-center font-bold text-xs md:text-base hover:scale-105 transition-transform cursor-pointer shrink-0" @click="$router.push({ name: 'perfil-citas' })">
                        <span class="px-1">Ver historial</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8L22 12L18 16" /><path d="M2 12H22" /></svg>
                    </button>
                </div>

                <div class="content bg-[#52301338] rounded-[20px] flex flex-col overflow-hidden h-[400px] shrink-0 lg:h-auto lg:shrink lg:flex-1 lg:min-h-0" :class="[loaderState ? '' : 'py-1.5', hasScrollbar ? 'pl-3 md:pl-5 pr-1 md:pr-2' : 'px-3 md:px-5']">
                    <Transition name="fade-tabla" mode="out-in">
                        <div v-if="loaderState" class="w-full h-full flex items-center justify-center">
                            <Loader :state="loaderState" />
                        </div>
                        <div v-else-if="citas.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="mb-2 opacity-50"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" /></svg>
                            <p class="text-sm md:text-base font-extrabold py-1">No se encontraron Citas.</p>
                        </div>
                        <div v-else ref="scrollContainer" class="w-full h-full flex flex-col gap-2 min-h-0 py-1.5 overflow-y-auto custom-scrollbar">
                            <CitaCard v-for="cita in citasFiltradas" :key="cita.id" :cita="cita" :main="true" @eliminado="obtenerCitas" @creado="obtenerCitas" @creadoConsulta="obtenerCitas" />
                        </div>
                    </Transition>
                </div>
            </section>
        </div>

        <div class="w-full lg:w-2/6 lg:h-full shrink-0 flex flex-col items-center justify-start mt-3 lg:mt-0 px-2 lg:px-4 lg:overflow-hidden min-w-0">
            <div class="py-2 w-full flex justify-center shrink-0">
                <CalendarComponent v-model="fechaFiltro" :citas="citas" :allow-future="true" class="w-full" />
            </div>

            <div class="hidden lg:flex w-full flex-col items-center mt-2 shrink-0 h-fit">
                <div v-if="userStore.getUser?.rol === 'administrador'"
                    class="w-full bg-[#fcf7f2] rounded-[20px] p-4 shadow-md border border-[#52301305] max-w-[320px] lg:max-w-[95%] xl:max-w-none flex flex-col h-fit">

                    <div class="flex flex-col gap-0.5 mb-4">
                        <span class="text-[#523013] text-[9px] xl:text-[11px] font-bold uppercase opacity-60 tracking-[0.2em]">Reporte del día</span>
                        <h4 class="text-[#523013] text-base xl:text-lg font-black leading-tight">Distribución</h4>
                    </div>

                    <div v-if="datosDistribucion.length === 0 && !loadingTipos"
                        class="flex-1 flex items-center justify-center text-[9px] xl:text-[11px] font-bold text-[#523013]/30 uppercase italic py-4">
                        Sin registros
                    </div>

                    <div v-else class="space-y-4 w-full" :class="{ 'opacity-60 pointer-events-none': loadingTipos }">
                        <div v-for="(item, index) in (loadingTipos && datosDistribucion.length === 0 ? [1, 2, 3] : datosDistribucion)" :key="typeof item === 'object' ? item.tipo : index">
                            <div class="flex justify-between items-end mb-1 px-1">
                                <span class="text-[#523013] font-black text-[9px] xl:text-[12px] uppercase tracking-wider opacity-70">
                                    {{ typeof item === 'object' ? item.tipo : 'Cargando...' }}
                                </span>
                                <span class="text-[#523013] font-black text-[10px] xl:text-sm">
                                    {{ typeof item === 'object' ? item.cantidad : '--' }}
                                </span>
                            </div>

                            <div class="w-full bg-[#52301315] h-1.5 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-1000 ease-out" :class="{ 'animate-loading-pulse': loadingTipos }" 
                                    :style="{
                                        width: typeof item === 'object' ? (totalDia > 0 ? (item.cantidad / totalDia * 100) + '%' : '0%') : '100%',
                                        backgroundColor: typeof item === 'object' ? (item.tipo === 'medico' ? '#812c8f' : item.tipo === 'estetico' ? '#dc7474' : '#523013') : '#52301320'
                                    }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="userStore.getUser?.rol === 'cliente'"
                    class="w-full max-w-[320px] lg:max-w-[95%] xl:max-w-none bg-[#fcf7f2] rounded-[20px] p-5 shadow-md border border-[#52301305] text-center h-fit">
                    <span class="text-[#523013] text-[9px] xl:text-[11px] font-bold uppercase opacity-60">Tu Agenda</span>
                    <h4 class="text-[#523013] text-lg xl:text-xl font-black italic mt-0.5">{{ fechaFiltro || 'Resumen' }}</h4>
                    <div class="w-8 h-0.5 bg-[#812c8f20] mx-auto my-3 rounded-full"></div>
                    <p class="text-[#523013]/50 text-[9px] xl:text-[11px] italic">Toca una fecha para filtrar</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-tabla-enter-active,
.fade-tabla-leave-active {
    transition: opacity 0.2s ease;
}

.fade-tabla-enter-from,
.fade-tabla-leave-to {
    opacity: 0;
}

@keyframes loading-pulse {

    0%,
    100% {
        opacity: 1;
        transform: scaleX(1);
    }

    50% {
        opacity: 0.5;
        transform: scaleX(0.99);
    }
}

.animate-loading-pulse {
    animation: loading-pulse 1.8s ease-in-out infinite;
    transform-origin: left;
}


.transition-all {
    transition-property: width, background-color, opacity;
    transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
}



.lg\:w-2\/6 {
    min-width: 0;
}

@media (min-width: 1024px) {
    .columna-derecha {
        min-width: 320px !important; 
        max-width: 450px !important; 
    }
}

@media (min-width: 1440px) {
    .columna-derecha {
        min-width: 380px !important;
    }
}

.h-fit {
    height: fit-content !important;
}
</style>