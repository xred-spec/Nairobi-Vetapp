<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { useToast } from '@/composables/useToast';
import { useServicio } from '@/composables/useServicio';
import { CamposServicios } from '@/data/CamposServicios';
import GenericModal from '@/components/modals/GenericModal.vue';
import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import { ServicioColumns } from '@/data/ColumnasServicio';
import CatalogosCard from '@/components/CatalogosCard.vue';
import { useItemLoading } from '@/composables/loadingItem';
import Pagination from '@/components/Pagination.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import Loader from '@/components/Loader.vue';

const { showToast, ToastStatus } = useToast();
const { isLoading, setLoading, clearLoading } = useItemLoading();
const {
    servicios,
    paginacion,
    obtenerServicios,
    eliminarServicio,
    cambiarEstadoServicio,
    storeServicio,
    editarServicio,
    filtros 
} = useServicio();

const showModal = ref<boolean>(false);
const servicioSeleccionado = ref<Record<string, any> | null>(null);
const loaderState = ref<string | null>('loading');
const calculatedPage = ref(1);

const cargarDatos = async (page: number = 1) => {
    loaderState.value = 'loading';
    const success = await obtenerServicios(page);
    loaderState.value = success ? 'success' : 'error';

    setTimeout(() => {
        loaderState.value = null;
    }, 500); 
};

onMounted(() => cargarDatos());

let debounceTimer: number;

watch(() => filtros.value.nombre, () => {
    clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(() => cargarDatos(1), 300);
});

watch(() => filtros.value.visibilidad, () => {
    clearTimeout(debounceTimer);
    cargarDatos(1);
});

watch(servicios, () => {
    calculatedPage.value = paginacion.value.current_page;
});

const abrirModal = (servicio?: Record<string, any>) => {
    servicioSeleccionado.value = servicio ? { ...servicio } : { visibilidad: 'visible' };
    showModal.value = true;
};

const cerrarModal = () => {
    showModal.value = false;
    servicioSeleccionado.value = null;
};

const handleToggle = async (id: number) => {
    setLoading(id, true);
    try {
        await cambiarEstadoServicio(id, paginacion.value.current_page);
    } finally {
        clearLoading(id);
    }
};

const handleSave = async (data: Record<string, any>) => {
    const id = servicioSeleccionado.value?.id;
    if (id) setLoading(id, true);
    
    try {
        if (id) {
            await editarServicio(id, data, paginacion.value.current_page);
        } else {
            loaderState.value = 'loading';
            const success = await storeServicio(data, 1);
            loaderState.value = success ? 'success' : 'error';
        }
    } finally {
        if (id) clearLoading(id);
        cerrarModal();
        setTimeout(() => { loaderState.value = null; }, 500);
    }
};

const handleEliminar = async (id: number) => {
    loaderState.value = 'loading';
    setLoading(id, true);
    try {
        const success = await eliminarServicio(id, paginacion.value.current_page);
        loaderState.value = success ? 'success' : 'error';
    } finally {
        clearLoading(id);
        setTimeout(() => { loaderState.value = null; }, 500);
    }
}

const cambiarPagina = (page: number) => cargarDatos(page);
</script>

<template>
    <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
        
        <CatalogosOptionbar 
            titulo="Servicios" 
            texto="Servicio" 
            v-model="filtros"
            :advice="false" 
            @abrirModal="abrirModal" 
            :loading="loaderState !== null"
            :campos="CamposServicios"
        />

        <GenericModal
            :header="servicioSeleccionado?.id ? 'Editar servicio' : 'Agregar servicio'"
            :campos="CamposServicios"
            :show="showModal"
            :model-value="servicioSeleccionado"
            @accept="handleSave"
            @invalid="showToast({ status: ToastStatus.WARNING, customMessage: $event })"
            @close="cerrarModal"
        />

        <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
             :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
            
            <Transition name="fade-tabla" mode="out-in">
                <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
                    <Loader :state="loaderState" />
                </div>

                <div v-else-if="servicios.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
                    <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">No se encontraron servicios.</p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shower-head size-24 md:size-32 opacity-80 drop-shadow-md">
                        <path d="m4 4 2.5 2.5"/><path d="M13.5 6.5a4.95 4.95 0 0 0-7 7"/><path d="M15 5 5 15"/><path d="M14 17v.01"/><path d="M10 16v.01"/><path d="M13 13v.01"/><path d="M16 10v.01"/><path d="M11 20v.01"/><path d="M17 14v.01"/><path d="M20 11v.01"/>
                    </svg>
                </div>

                <div v-else class="w-full h-full flex flex-col items-center min-h-0">
                    <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                        <CatalogosContainer :header="true">
                            
                            <template #headers>
                                <div v-for="col in ServicioColumns" :key="'header-' + col.key" class="flex-1 text-center w-full truncate px-1">
                                    {{ col.label }}
                                </div>
                            </template>
                                
                            <template #content-cards>
                                <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                                    <CatalogosCard
                                        v-for="(servicio, index) in servicios"
                                        :key="servicio.id"
                                        :item="servicio"
                                        :numero="((calculatedPage - 1) * paginacion.per_page) + Number(index) + 1"
                                        :columnas="ServicioColumns"
                                        :isVisible="servicio.visibilidad === 'visible'"
                                        :is-fetching="isLoading(servicio.id)"
                                        class="w-full"
                                        @cambiarEstado="handleToggle"
                                        @eliminar="handleEliminar"
                                        @editar="abrirModal(servicio)"
                                    />
                                </div>
                            </template>

                            <template #pagination>
                                <Pagination
                                    :pages="paginacion.last_page"
                                    v-model="paginacion.current_page"
                                    @update:modelValue="cambiarPagina" 
                                />
                            </template>
                            
                        </CatalogosContainer>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<style scoped>
.fade-tabla-enter-active, .fade-tabla-leave-active { transition: opacity 0.2s ease; }
.fade-tabla-enter-from, .fade-tabla-leave-to { opacity: 0; }
</style>