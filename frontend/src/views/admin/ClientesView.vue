<script setup lang="ts">
import { onMounted, ref, watch, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from '@/composables/useToast';
import { useCliente } from '@/composables/useCliente';
import { useItemLoading } from '@/composables/loadingItem';
import { camposCliente } from '@/data/CamposCliente';
import { ColumnasCliente } from '@/data/ColumnasCliente';

import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import GenericModal from '@/components/modals/GenericModal.vue';
import UserCards from '@/components/UserCards.vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import ModalData from '@/components/ModalData.vue';

const router = useRouter();
const { showToast, ToastStatus } = useToast();
const { isLoading } = useItemLoading();

const { filtros, clientes, paginacion, getClientes, storeCliente } = useCliente();

const showModal = ref(false);
const showModalData = ref(false);
const clienteSeleccionado = ref<any>(null);
const loaderState = ref<string | null>('loading');

const cargarDatos = async (page: number = 1) => {
    loaderState.value = 'loading';
    const success = await getClientes(page);
    loaderState.value = success ? 'success' : 'error';
    setTimeout(() => loaderState.value = null, 600);
};

onMounted(() => cargarDatos(1));

// Buscador dinámico
let debounceTimer: number;
watch(filtros, () => {
    clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(() => cargarDatos(1), 300);
}, { deep: true });

const abrirModal = () => {
    clienteSeleccionado.value = { visibilidad: 'visible' };
    showModal.value = true;
};

const abrirModalData = (cliente: any) => {
    clienteSeleccionado.value = cliente;
    showModalData.value = true;
};

const handleSave = async (data: any) => {
    loaderState.value = 'loading';
    const success = await storeCliente(data);
    loaderState.value = success ? 'success' : 'error';
    showModal.value = false;
    setTimeout(() => loaderState.value = null, 600);
};

const irAMascotas = (clienteId: number) => {
    router.push({ name: 'perfil-mascotas', query: { cliente_id: clienteId } });
};

const calculatedPage = computed(() => paginacion.value.current_page);
</script>

<template>
    <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
        <CatalogosOptionbar 
            titulo="Clientes" 
            texto="Cliente" 
            v-model="filtros"
            :campos="camposCliente" 
            :advice="true" 
            :loading="!!loaderState"
            @abrirModal="abrirModal"
        />

        <GenericModal 
            header="Agregar cliente" 
            :campos="camposCliente"
            :show="showModal" 
            :model-value="clienteSeleccionado" 
            @accept="handleSave"
            @invalid="showToast({ status: ToastStatus.WARNING, customMessage: $event })"
            @close="() => showModal = false" 
        />

        <ModalData 
            header="Detalles del Cliente" 
            :campos="camposCliente"
            :show="showModalData" 
            :model-value="clienteSeleccionado"
            @close="() => { showModalData = false; clienteSeleccionado = null; }" 
        />

        <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
            :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
            
            <Transition name="fade-tabla" mode="out-in">
                <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
                    <Loader :state="loaderState" />
                </div>

                <div v-else-if="clientes.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
                    <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">No se encontraron clientes</p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users size-24 md:size-32 opacity-80 drop-shadow-md">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>

                <div v-else class="w-full h-full flex flex-col items-center min-h-0">
                    <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                        <CatalogosContainer :header="true">
                            <template #headers>
                                <div v-for="col in ColumnasCliente" :key="col.key" class="flex-1 text-center w-full truncate px-1">
                                    {{ col.label }}
                                </div>
                            </template>

                            <template #content-cards>
                                <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                                    <UserCards 
                                        v-for="(cliente, index) in clientes" 
                                        :key="cliente.id" 
                                        :item="cliente" 
                                        :numero="((calculatedPage - 1) * paginacion.per_page) + index + 1"
                                        :columnas="ColumnasCliente" 
                                        :estado="cliente.user?.estado" 
                                        :is-fetching="isLoading(cliente.id)"
                                        :clickable="true"
                                        view="clientes"
                                        class="w-full"
                                        @modal-data="abrirModalData(cliente)"
                                        @show-mascotas="irAMascotas(cliente.id)"
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