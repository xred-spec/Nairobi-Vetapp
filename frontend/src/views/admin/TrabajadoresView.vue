<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { useToast } from '@/composables/useToast';
import { useTrabajador } from '@/composables/useTrabajador';
import { useHorario } from '@/composables/useHorario';
import { useItemLoading } from '@/composables/loadingItem';
import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import GenericModal from '@/components/modals/GenericModal.vue';
import ModalHorarios from '@/components/modals/ModalHorarios.vue';
import UserCards from '@/components/UserCards.vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import { camposTrabajador } from '@/data/CamposTrabajador';
import { ColumnasTrabajador } from '@/data/ColumnasTrabajador';

const { showToast, ToastStatus } = useToast();
const { isLoading, setLoading, clearLoading } = useItemLoading();
const { horarios, obtenerHorarios } = useHorario();
const {
    trabajadores,
    paginacion,
    filtros,
    obtenerTrabajadores,
    crearTrabajador,
    actualizarTrabajador,
    sincronizarHorarios,
    cambiarEstadoTrabajador
} = useTrabajador();

const showModal = ref(false);
const showModalHorarios = ref(false);
const trabajadorSeleccionado = ref<any>(null);
const trabajadorParaHorarios = ref<any>(null);
const loaderState = ref<string | null>('loading');

const cargarDatos = async (page: number = 1) => {
    loaderState.value = 'loading';
    const success = await obtenerTrabajadores(page);
    loaderState.value = success ? 'success' : 'error';
    window.setTimeout(() => { loaderState.value = null; }, 800);
};

onMounted(async () => {
    await Promise.all([
        cargarDatos(1),
        obtenerHorarios()
    ]);
});

let debounceTimer: number;
watch(filtros, (newVal, oldVal) => {
    if (JSON.stringify(newVal) === JSON.stringify(oldVal)) return;

    window.clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(() => {
        cargarDatos(1);
    }, 300);
}, { deep: true });

// Lógica de Modales
const abrirModal = (t?: any) => {
    if (t) {
        trabajadorSeleccionado.value = {
            id: t.id,
            nombre: t.usuario?.nombre,
            email: t.usuario?.email,
            telefono: t.usuario?.telefono,
        };
    } else {
        trabajadorSeleccionado.value = null;
    }
    showModal.value = true;
};

const abrirModalHorarios = (t: any) => {
    trabajadorParaHorarios.value = t;
    showModalHorarios.value = true;
};

const handleSave = async (data: any) => {
    const id = trabajadorSeleccionado.value?.id;
    if (id) setLoading(id, true);

    try {
        if (id) {
            await actualizarTrabajador(id, data);
        } else {
            loaderState.value = 'loading';
            const success = await crearTrabajador({ ...data, horarios: [] });
            loaderState.value = success ? 'success' : 'error';
        }
    } finally {
        if (id) clearLoading(id);
        showModal.value = false;
        window.setTimeout(() => { loaderState.value = null; }, 800);
    }
};

const handleSyncHorarios = async (idsAsignados: number[]) => {
    if (!trabajadorParaHorarios.value) return;
    const id = trabajadorParaHorarios.value.id;
    
    loaderState.value = 'loading';
    const success = await sincronizarHorarios(id, idsAsignados);
    
    showModalHorarios.value = false;
    trabajadorParaHorarios.value = null;
    loaderState.value = success ? 'success' : 'error';
    window.setTimeout(() => { loaderState.value = null; }, 800);
};

const handleToggle = async (t: any) => {
    setLoading(t.id, true);
    const nuevoEstado = t.usuario?.estado !== 'activo';
    await cambiarEstadoTrabajador(t.id, nuevoEstado);
    clearLoading(t.id);
};

const calculatedPage = ref(1);
watch(trabajadores, () => { 
    calculatedPage.value = paginacion.value.current_page; 
});
</script>

<template>
    <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
        <CatalogosOptionbar 
            titulo="Trabajadores" 
            texto="Trabajador" 
            v-model="filtros"
            :campos="camposTrabajador"
            :advice="false" 
            :loading="loaderState !== null"
            @abrirModal="abrirModal" 
        />

        <GenericModal 
            :header="trabajadorSeleccionado?.id ? 'Editar trabajador' : 'Agregar trabajador'" 
            :campos="camposTrabajador" 
            :show="showModal" 
            :model-value="trabajadorSeleccionado" 
            @accept="handleSave" 
            @invalid="showToast({ status: ToastStatus.WARNING, customMessage: $event })"
            @close="() => { showModal = false; trabajadorSeleccionado = null; }" 
        />

        <ModalHorarios
            :show="showModalHorarios"
            :nombre-trabajador="trabajadorParaHorarios?.usuario?.nombre ?? ''"
            :todos-los-horarios="horarios"
            :horarios-del-trabajador="trabajadorParaHorarios?.horarios ?? []"
            @close="() => { showModalHorarios = false; trabajadorParaHorarios = null; }"
            @accept="handleSyncHorarios"
        />

        <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
            :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
            
            <Transition name="fade-tabla" mode="out-in">
                <div v-if="loaderState !== null" class="w-full h-full flex justify-center items-center">
                    <Loader :state="loaderState" />
                </div>

                <div v-else-if="trabajadores.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
                    <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">
                        No se encontraron trabajadores registrados
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-24 md:size-32 opacity-80 drop-shadow-md">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>

                <div v-else class="w-full h-full flex flex-col items-center min-h-0">
                    <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                        <CatalogosContainer :header="true">
                            <template #headers v-if="loaderState === null">
                                <div v-for="col in ColumnasTrabajador" :key="col.key" class="flex-1 text-center w-full truncate px-1">
                                    {{ col.label }}
                                </div>
                            </template>
                            
                            <template #content-cards>
                                <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                                    <UserCards 
                                        v-for="(t, index) in trabajadores" 
                                        :key="t.id" 
                                        :item="t" 
                                        :numero="((calculatedPage - 1) * paginacion.per_page) + index + 1"
                                        :columnas="ColumnasTrabajador" 
                                        :estado="t.usuario?.estado" 
                                        :is-fetching="isLoading(t.id)"
                                        :clickable="false"
                                        view="trabajadores"
                                        class="w-full"
                                        @asignar-horario="abrirModalHorarios(t)"
                                        @editar="abrirModal(t)"
                                        @cambiarEstado="handleToggle(t)"
                                    />
                                </div>
                            </template>

                            <template #pagination>
                                <Pagination 
                                    :pages="paginacion.last_page"
                                    v-model="paginacion.current_page"
                                    @update:modelValue="cargarDatos" 
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