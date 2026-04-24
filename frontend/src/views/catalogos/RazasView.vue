<script setup lang="ts">
import { ref, watch, onMounted, computed } from 'vue';
import { useToast } from '../../composables/useToast';
import { camposRaza } from '@/data/CamposRaza';
import CatalogosCard from '@/components/CatalogosCard.vue';
import GenericModal from '@/components/modals/GenericModal.vue';
import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import { RazaColumns } from '@/data/ColumnasRaza';
import { useRaza } from '@/composables/useRaza';
import { useItemLoading } from '@/composables/loadingItem';
import Pagination from '@/components/Pagination.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import Loader from '@/components/Loader.vue';

const { showToast, ToastStatus } = useToast();
const { isLoading, setLoading, clearLoading } = useItemLoading();

const camposFormulario = ref([...camposRaza]);
const showModal = ref(false);
const razaSeleccionada = ref<Record<string, any> | null>(null);

const {
    razas, animalesConRazas, animalList, paginacion,
    obtenerRazas, guardarRaza, editarRaza, getFullList, getFullListRazas,
    alternarVisibilidad, eliminarRaza, filtros
} = useRaza();

const loaderState = ref<string | null>('loading');

const cargarDatos = async (page: number = 1) => {
    loaderState.value = 'loading';
    const success = await obtenerRazas(page, filtros.value);
    loaderState.value = success ? 'success' : 'error';
    window.setTimeout(() => { loaderState.value = null; }, 800);
}

onMounted(async () => { 
    loaderState.value = 'loading';
    filtros.value = {};
    await Promise.all([getFullList(), getFullListRazas()]);
    await cargarDatos(); 
});


let debounceTimer: number;
watch(filtros, (newVal, oldVal) => {

    if (JSON.stringify(newVal) === JSON.stringify(oldVal)) return;

    window.clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(() => {
        cargarDatos(1);
    }, 300);
}, { deep: true });

const cambiarPagina = (page: number) => cargarDatos(page);

const abrirModal = (raza?: Record<string, any>) => {
    razaSeleccionada.value = raza || { visibilidad: 'visible' };
    showModal.value = true;
};

const handleSave = async (data: Record<string, any>) => {
    const id = razaSeleccionada.value?.id;
    if (id) setLoading(id, true);
    try {
        if (id) {
            await editarRaza(id, data, paginacion.value.current_page);
        } else {
            loaderState.value = 'loading';
            const res = await guardarRaza(data, 1);
            loaderState.value = res ? 'success' : 'error';
        }
    } finally {
        if (id) clearLoading(id);
        showModal.value = false;
        window.setTimeout(() => { loaderState.value = null; }, 800);
    }
};

const handleToggle = async (id: number) => {
    setLoading(id, true);
    await alternarVisibilidad(id, paginacion.value.current_page);
    clearLoading(id);
};

const handleEliminar = async (id: number) => {
    loaderState.value = 'loading';
    setLoading(id, true);
    const res = await eliminarRaza(id, paginacion.value.current_page);
    clearLoading(id);
    loaderState.value = res ? 'success' : 'error';
    window.setTimeout(() => { loaderState.value = null; }, 800);
}

watch(animalList, (nuevo) => {
    const campo = camposFormulario.value.find(c => c.modelKey === 'animal_id');
    if (campo && nuevo) {
        campo.options = nuevo.map((a: any) => ({ label: a.nombre, value: a.id }));
    }
}, { immediate: true });

const camposFiltro = computed(() => {
    return camposRaza.map(campo => {
        if (campo.modelKey === 'animal_id') {
            return {
                ...campo,
                options: animalesConRazas.value.map(a => ({ label: a.nombre, value: a.id }))
            };
        }
        return campo;
    });
});

const calculatedPage = ref(1); 
watch(razas, () => { calculatedPage.value = paginacion.value.current_page; });
</script>

<template>
    <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
        
        <CatalogosOptionbar 
            titulo="Razas" 
            texto="Raza"
            :key="camposFiltro.find(c => c.modelKey === 'animal_id')?.options?.length || 0"                
            v-model="filtros"
            :advice="false" 
            @abrirModal="abrirModal" 
            :loading="loaderState !== null"
            :campos="camposFiltro"
        />

        <GenericModal 
            :header="razaSeleccionada?.id ? 'Editar raza' : 'Agregar raza'" 
            :campos="camposFormulario"
            :show="showModal" 
            :model-value="razaSeleccionada" 
            @close="showModal = false" 
            @accept="handleSave"
            @invalid="showToast({ status: ToastStatus.WARNING, customMessage: $event })" 
        />

        <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
             :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
            
            <Transition name="fade-tabla" mode="out-in">
                <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
                    <Loader :state="loaderState" />
                </div>

                <div v-else-if="razas.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
                    <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">
                        No se encontraron resultados. Prueba cambiando los filtros o agregando una nueva Raza.
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rat size-24 md:size-32 opacity-80 drop-shadow-md">
                        <path d="M13 22H4a2 2 0 0 1 0-4h12"/><path d="M13.236 18a3 3 0 0 0-2.2-5"/><path d="M16 9h.01"/><path d="M16.82 3.94a3 3 0 1 1 3.237 4.868l1.815 2.587a1.5 1.5 0 0 1-1.5 2.1l-2.872-.453a3 3 0 0 0-3.5 3"/><path d="M17 4.988a3 3 0 1 0-5.2 2.052A7 7 0 0 0 4 14.015 4 4 0 0 0 8 18"/>
                    </svg>
                </div>

                <div v-else class="w-full h-full flex flex-col items-center min-h-0">
                    <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                        <CatalogosContainer :header="true">
                            
                            <template #headers v-if="loaderState === null">
                                <div v-for="col in RazaColumns" :key="'header-' + col.key" class="flex-1 text-center w-full truncate px-1">
                                    {{ col.label }}
                                </div>
                            </template>

                            <template #content-cards>
                                <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                                    <CatalogosCard 
                                        v-for="(raza, index) in razas" :key="raza.id" :item="raza" 
                                        :numero="((calculatedPage - 1) * paginacion.per_page) + Number(index) + 1"
                                        :columnas="RazaColumns" 
                                        :isVisible="raza.visibilidad === 'visible'" 
                                        :is-fetching="isLoading(raza.id)"
                                        class="w-full"
                                        @cambiarEstado="handleToggle" 
                                        @eliminar="handleEliminar" 
                                        @editar="abrirModal(raza)" 
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
.fade-tabla-enter-active,
.fade-tabla-leave-active {
  transition: opacity 0.2s ease;
}

.fade-tabla-enter-from,
.fade-tabla-leave-to {
  opacity: 0;
}
</style>