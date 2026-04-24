<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { useToast } from '@/composables/useToast';
import { useProducto } from '@/composables/useProducto';
import { camposProducto } from '@/data/CamposProducto';
import GenericModal from '@/components/modals/GenericModal.vue';
import CatalogosOptionbar from '@/components/CatalogosOptionbar.vue';
import { ProductoColumns } from '@/data/ColumnasProducto';
import CatalogosCard from '@/components/CatalogosCard.vue';
import { useItemLoading } from '@/composables/loadingItem';
import CatalogosContainer from '@/components/CatalogosContainer.vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
import ModalData from '@/components/ModalData.vue';

const { showToast, ToastStatus } = useToast();
const { isLoading, setLoading, clearLoading } = useItemLoading();
const {
    productos,
    paginacion,
    filtros, 
    obtenerProductos,
    storeProducto,
    editarProducto,
    eliminarProducto,
    cambiarEstadoProducto 
} = useProducto();

const showModal = ref(false);
const showModalData = ref(false);
const productoSeleccionado = ref<Record<string, any> | null>(null);
const loaderState = ref<string | null>('loading');
const calculatedPage = ref(1);


const cargarDatos = async (page: number = 1) => {
    loaderState.value = 'loading';
    const success = await obtenerProductos(page);
    loaderState.value = success ? 'success' : 'error';
    setTimeout(() => { loaderState.value = null; }, 500);
};

onMounted(() => cargarDatos());

let debounceTimer: number;

watch(filtros, () => {
    clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(() => {
        cargarDatos(1); 
    }, 300);
}, { deep: true });

watch(productos, () => {
  calculatedPage.value = paginacion.value.current_page;
});

// --- Handlers ---
const abrirModal = (producto: Record<string, any> | null = null) => {
  productoSeleccionado.value = producto ? { ...producto } : { visibilidad: 'visible' };
  showModal.value = true;
};

const abrirModalData = (producto: Record<string, any>) => {
  productoSeleccionado.value = { ...producto };
  showModalData.value = true;
};

const handleToggle = async (id: number) => {
  setLoading(id, true);
  try {
    await cambiarEstadoProducto(id);
    await cargarDatos(paginacion.value.current_page);
  } finally {
    clearLoading(id);
  }
};

const handleSave = async (data: Record<string, any>) => {
  const id = productoSeleccionado.value?.id;
  if (id) setLoading(id, true);
  
  try {
    if (id) {
      await editarProducto(id, data);
    } else {
      loaderState.value = 'loading';
      const success = await storeProducto(data);
      loaderState.value = success ? 'success' : 'error';
    }
    await cargarDatos(id ? paginacion.value.current_page : 1);
  } finally {
    if (id) clearLoading(id);
    showModal.value = false;
    productoSeleccionado.value = null;
    setTimeout(() => { loaderState.value = null; }, 500);
  }
};

const handleEliminar = async (id: number) => {
    loaderState.value = 'loading';
    setLoading(id, true);
    try {
        const success = await eliminarProducto(id, paginacion.value.current_page);
        loaderState.value = success ? 'success' : 'error';
    } finally {
        clearLoading(id);
        setTimeout(() => { loaderState.value = null; }, 500);
    }
}
</script>

<template>
  <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
      <CatalogosOptionbar 
        titulo="Productos" 
        texto="Producto" 
        :advice="true" 
        v-model="filtros"
        :campos="camposProducto"
        :loading="loaderState !== null"
        @abrirModal="abrirModal" 
      />

      <GenericModal 
        :header="productoSeleccionado?.id ? 'Editar producto' : 'Agregar producto'" 
        :campos="camposProducto"
        :show="showModal" 
        :model-value="productoSeleccionado" 
        @accept="handleSave"
        @invalid="showToast({ status: ToastStatus.WARNING, customMessage: $event })"
        @close="() => { showModal = false; productoSeleccionado = null; }" 
      />

      <ModalData 
        :header="'Datos del Producto'" 
        :campos="camposProducto"
        :show="showModalData" 
        :model-value="productoSeleccionado"
        @close="() => { showModalData = false; productoSeleccionado = null; }" 
      />

      <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
           :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
        
        <Transition name="fade-tabla" mode="out-in">
          <div v-if="loaderState" class="w-full h-full flex justify-center items-center">
            <Loader :state="loaderState" />
          </div>

          <div v-else-if="productos.length === 0" class="w-full h-full flex flex-col justify-center items-center text-[#523013] text-center px-4">
            <p class="text-[#523013] font-bold text-lg md:text-xl mb-4">No se encontraron productos.</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-24 md:size-32 opacity-80 drop-shadow-md">
                <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
            </svg>
          </div>

          <div v-else class="w-full h-full flex flex-col items-center min-h-0">
            <div class="w-full max-w-7xl flex flex-col h-full min-h-0">
                <CatalogosContainer :header="true">
                  
                  <template #headers>
                    <div v-for="col in ProductoColumns" :key="col.key" class="flex-1 text-center w-full truncate px-1">
                      {{ col.label }}
                    </div>
                  </template>

                  <template #content-cards>
                    <div class="flex flex-col items-center w-full gap-2 md:gap-3">
                        <CatalogosCard 
                          v-for="(producto, index) in productos" :key="producto.id" :item="producto"
                          :numero="((calculatedPage - 1) * paginacion.per_page) + Number(index) + 1" 
                          :columnas="ProductoColumns" 
                          :isVisible="producto.visibilidad === 'visible'"
                          :is-fetching="isLoading(producto.id)"
                          :clickable="true"
                          class="w-full"
                          @cambiarEstado="handleToggle" 
                          @eliminar="handleEliminar"
                          @editar="abrirModal(producto)" 
                          @modal-data="abrirModalData(producto)"
                        />
                    </div>
                  </template>

                  <template #pagination>
                    <Pagination 
                      :pages="paginacion.last_page"
                      v-model="paginacion.current_page"
                      @update:modelValue="cargarDatos" />
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