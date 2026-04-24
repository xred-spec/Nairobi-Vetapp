<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router'; 
import { useToast } from '@/composables/useToast';
import { camposMascota } from '@/data/CamposMascota';
import CatalogosCard from '@/components/CatalogosCard.vue';
import GenericModal from '@/components/modals/GenericModal.vue';
import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import { MascotaColumns } from '@/data/ColumnasMascota';
import { useMascota } from '@/composables/useMascota';
import { useItemLoading } from '@/composables/loadingItem'; 
import Pagination from '@/components/Pagination.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import { useUserStore } from '@/stores/user';
import Loader from '@/components/Loader.vue';
import ModalData from '@/components/ModalData.vue';

const { showToast } = useToast();
const { isLoading, setLoading, clearLoading } = useItemLoading();
const userStore = useUserStore();
const route = useRoute();
const router = useRouter(); 

const showModal = ref(false);
const showModalData = ref(false);
const mascotaSeleccionada = ref<any>(null);
const loaderState = ref<string | null>('loading');

const {
  mascotas,
  paginacion,
  filtros, 
  animalesConRazas,
  obtenerMascotas,
  storeMascota,
  editarMascota,
  cambiarEstadoMascota,
  eliminarMascota,    
  getFullList,
  getFullListRazas,
  getFullClientes,
  configurarCamposMascota
} = useMascota();

const cargarDatos = async (page: number = 1) => {
  loaderState.value = 'loading';
  const success = await obtenerMascotas(page);
  loaderState.value = success ? 'success' : 'error';
  setTimeout(() => loaderState.value = null, 500);
};

onMounted(async () => {
  loaderState.value = 'loading';

  await Promise.all([
    getFullList(),
    getFullListRazas(),
    getFullClientes()
  ]);

  if (route.query.cliente_id) {
    filtros.value.cliente_id = Number(route.query.cliente_id);
  }

  await cargarDatos(1);
});

onUnmounted(() => {
  filtros.value = {}; 
  
  router.replace({ query: {} });
});

let debounceTimer: number;
watch(filtros, () => {
  clearTimeout(debounceTimer);
  debounceTimer = window.setTimeout(() => {
    cargarDatos(1);
  }, 300);
}, { deep: true });

const camposFormulario = computed(() => {
  return configurarCamposMascota(camposMascota, userStore);
});

const modalHeader = computed(() =>
  mascotaSeleccionada.value?.id ? 'Editar mascota' : 'Agregar mascota'
);

const calculatedPage = computed(() => paginacion.value.current_page);

const abrirModal = (m?: any) => {
  mascotaSeleccionada.value = m?.id
    ? { ...m }
    : { 
        visibilidad: 'visible', 
        animal_id: animalesConRazas.value[0]?.id || null 
      };
  showModal.value = true;
};

const abrirModalData = (m?: any) => {
  mascotaSeleccionada.value = m ? { ...m } : null;
  showModalData.value = true;
};

const handleSave = async (data: any) => {
  const id = mascotaSeleccionada.value?.id;
  if (id) setLoading(id, true);
  
  try {
    if (id) {
      await editarMascota(id, data);
    } else {
      loaderState.value = 'loading';
      const res = await storeMascota(data);
      loaderState.value = res ? 'success' : 'error';
    }
    await cargarDatos(id ? paginacion.value.current_page : 1);
  } finally {
    if (id) clearLoading(id);
    showModal.value = false;
    setTimeout(() => loaderState.value = null, 500);
  }
};

const handleEliminar = async (id: number) => {
  loaderState.value = 'loading';
  setLoading(id, true);
  try {
    const res = await eliminarMascota(id);
    loaderState.value = res ? 'success' : 'error';
  } finally {
    clearLoading(id);
    setTimeout(() => loaderState.value = null, 500);
  }
};

const handleCambiarEstado = async (id: number) => {
  setLoading(id, true);
  try {
    await cambiarEstadoMascota(id);
  } finally {
    clearLoading(id);
  }
};
</script>

<template>
  <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
      
      <CatalogosOptionbar 
        titulo="Mascotas" 
        texto="Mascota" 
        :advice="true" 
        v-model="filtros"
        :campos="camposFormulario"
        :loading="!!loaderState"
        @abrirModal="abrirModal" 
      />

      <GenericModal
        :header="modalHeader"
        :campos="camposFormulario"
        :show="showModal"
        :model-value="mascotaSeleccionada"
        @close="showModal = false"
        @accept="handleSave"
        @invalid="showToast({ status: 'warning', customMessage: $event })"
      />

      <ModalData 
        :header="mascotaSeleccionada?.id ? 'Datos de la Mascota' : ''" 
        :campos="camposMascota"
        :show="showModalData" 
        :model-value="mascotaSeleccionada"
        @close="() => { showModalData = false; mascotaSeleccionada = null; }" 
      />

      <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
        :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
        
        <Transition name="fade-tabla" mode="out-in">
          <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
            <Loader :state="loaderState" />
          </div>

          <div v-else-if="mascotas.length === 0"
            class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
            <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">No se encontraron mascotas, intenta agregar una</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
              class="size-24 md:size-32 opacity-80 drop-shadow-md">
              <circle cx="11" cy="4" r="2" />
              <circle cx="18" cy="8" r="2" />
              <circle cx="20" cy="16" r="2" />
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
            </svg>
          </div>

          <div v-else class="w-full h-full flex flex-col items-center min-h-0">
            <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                <CatalogosContainer :header="true">
                  
                  <template #headers>
                    <div v-for="col in MascotaColumns" :key="col.key" class="flex-1 text-center w-full truncate px-1">
                      {{ col.label }}
                    </div>
                  </template>

                  <template #content-cards>
                    <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                        <CatalogosCard
                          v-for="(m, index) in mascotas"
                          :key="m.id"
                          :item="m"
                          :numero="((calculatedPage - 1) * paginacion.per_page) + index + 1"
                          :columnas="MascotaColumns"
                          :isVisible="m.visibilidad === 'visible'"
                          :is-fetching="isLoading(m.id)"
                          :clickable="true"
                          class="w-full"
                          @cambiarEstado="handleCambiarEstado"
                          @eliminar="handleEliminar"
                          @editar="abrirModal(m)"
                          @modal-data="abrirModalData(m)"
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
.fade-tabla-enter-active,
.fade-tabla-leave-active {
  transition: opacity 0.2s ease;
}

.fade-tabla-enter-from,
.fade-tabla-leave-to {
  opacity: 0;
}
</style>