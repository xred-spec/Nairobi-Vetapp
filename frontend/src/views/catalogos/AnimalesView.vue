<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { useToast } from '@/composables/useToast';
import { useAnimal } from '@/composables/useAnimal';
import { camposAnimal } from '@/data/CamposAnimal';
import GenericModal from '@/components/modals/GenericModal.vue';
import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import { AnimalColumns } from '@/data/ColumnasAnimal';
import CatalogosCard from '@/components/CatalogosCard.vue';
import { useItemLoading } from '@/composables/loadingItem';
import Pagination from '@/components/Pagination.vue';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import Loader from '@/components/Loader.vue';

const { showToast, ToastStatus } = useToast();
const { isLoading, setLoading, clearLoading } = useItemLoading();
const {
  animales,
  paginacion,
  obtenerAnimales,
  eliminarAnimal,
  cambiarEstadoAnimal,
  storeAnimal,
  editarAnimal,
  filtros 
} = useAnimal();

const showModal = ref<boolean>(false);
const animalSeleccionado = ref<Record<string, any> | null>(null);
const loaderState = ref<string | null>('loading');
const calculatedPage = ref(1);


const cargarDatos = async (page: number = 1) => {
  loaderState.value = 'loading';
  const success = await obtenerAnimales(page, filtros.value);
  loaderState.value = success ? 'success' : 'error';

  setTimeout(() => {
    loaderState.value = null;
  }, 800);
};

onMounted(() => cargarDatos());

let debounceTimer: number;

watch(() => filtros?.value?.nombre, () => {
  clearTimeout(debounceTimer);
  debounceTimer = window.setTimeout(() => cargarDatos(1), 300);
});

watch(() => filtros?.value?.visibilidad, () => {
  clearTimeout(debounceTimer);
  cargarDatos(1);
});

watch(animales, () => {
  calculatedPage.value = paginacion.value.current_page;
});

const abrirModal = (animal?: Record<string, any>) => {
  animalSeleccionado.value = animal ? { ...animal } : { visibilidad: 'visible' };
  showModal.value = true;
};

const handleToggle = async (id: number) => {
  setLoading(id, true);
  try {
    await cambiarEstadoAnimal(id, paginacion.value.current_page);
  } finally {
    clearLoading(id);
  }
};

const handleSave = async (data: Record<string, any>) => {
  const id = animalSeleccionado.value?.id;
  if (id) setLoading(id, true);
  
  try {
    if (id) {
      await editarAnimal(id, data, paginacion.value.current_page);
    } else {
      loaderState.value = 'loading';
      const success = await storeAnimal(data, 1);
      loaderState.value = success ? 'success' : 'error';
    }
  } finally {
    if (id) clearLoading(id);
    showModal.value = false;
    animalSeleccionado.value = null;
    setTimeout(() => { loaderState.value = null; }, 500);
  }
};

const handleEliminar = async (id: number) => {
  loaderState.value = 'loading';
  setLoading(id, true);
  try {
    const success = await eliminarAnimal(id, paginacion.value.current_page);
    loaderState.value = success ? 'success' : 'error';
  } finally {
    clearLoading(id);
    setTimeout(() => { loaderState.value = null; }, 500);
  }
};

const cambiarPagina = (page: number) => cargarDatos(page);
</script>

<template>
  <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
      
      <CatalogosOptionbar 
        titulo="Animales" 
        texto="Animal" 
        v-model="filtros"
        :advice="false" 
        @abrirModal="abrirModal" 
        :loading="loaderState !== null"
        :campos="camposAnimal"  
      />

      <GenericModal 
        :header="animalSeleccionado?.id ? 'Editar animal' : 'Agregar animal'" 
        :campos="camposAnimal"
        :show="showModal" 
        :model-value="animalSeleccionado" 
        @accept="handleSave"
        @invalid="showToast({ status: ToastStatus.WARNING, customMessage: $event })"
        @close="() => { showModal = false; animalSeleccionado = null; }" 
      />

      <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
           :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
        
        <Transition name="fade-tabla" mode="out-in">
          <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
            <Loader :state="loaderState" />
          </div>

          <div v-else-if="animales.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
            <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">No se encontraron animales</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bone size-24 md:size-32 opacity-80 drop-shadow-md">
                <path d="M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5Z"/>
            </svg>
          </div>

          <div v-else class="w-full h-full flex flex-col items-center min-h-0">
            <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                <CatalogosContainer :header="true">
                  
                  <template #headers>
                    <div v-for="col in AnimalColumns" :key="'header-' + col.key" class="flex-1 text-center w-full truncate px-1">
                      {{ col.label }}
                    </div>
                  </template>

                  <template #content-cards>
                    <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                        <CatalogosCard 
                          v-for="(animal, index) in animales" :key="animal.id" :item="animal" 
                          :numero="((calculatedPage - 1) * paginacion.per_page) + Number(index) + 1"
                          :columnas="AnimalColumns" 
                          :isVisible="animal.visibilidad === 'visible'" 
                          :is-fetching="isLoading(animal.id)"
                          class="w-full"
                          @cambiarEstado="handleToggle" 
                          @eliminar="handleEliminar" 
                          @editar="abrirModal(animal)" 
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
/* Agregamos las animaciones de la tabla que faltaban */
.fade-tabla-enter-active,
.fade-tabla-leave-active {
  transition: opacity 0.2s ease;
}

.fade-tabla-enter-from,
.fade-tabla-leave-to {
  opacity: 0;
}
</style>