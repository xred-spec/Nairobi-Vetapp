<script setup lang="ts">
//import { hide } from '@popperjs/core';
import { ref,computed,onMounted,onUnmounted } from 'vue';
//import CitaInterface from '../interfaces/CitaInterface';
const isOpen = ref(false);
const props = defineProps<{
  user:any
  loading: boolean
}>();
const ownerRef = ref<HTMLElement|null>(null)
onMounted(() => document.addEventListener('click', handleClickOutside, true))
onUnmounted(() => document.removeEventListener('click', handleClickOutside, true))

const handleClickOutside = (event: MouseEvent) => {
  if (ownerRef.value && !ownerRef.value.contains(event.target as Node)) {
    isOpen.value = false
  }
}
const emit = defineEmits<{
    (e:'search',search:any):void
}>();

const raza = ref<string>('');
const animal = ref<string>('');
const mascota = ref<string>('');
const trabajador = ref<string>('');
const cliente = ref<string>('');
const exclude_deleted = ref<boolean>(false);
const tipo = ref<string>('');
const f_final = ref<string>('')
const f_inicial = ref<string>(''); 
const search = computed<any>(()=>({
  raza: raza.value,
  tipo: tipo.value,
  exclude_deleted: exclude_deleted.value,
  animal:animal.value,
  mascota:mascota.value,
  trabajador:trabajador.value,
  cliente:cliente.value,
  fecha_final:f_final,
  fecha:f_inicial
}));

const buscar = ()=>{
  isOpen.value = !isOpen.value;
  emit('search',search.value);
}
const limpiar = ()=>{
  isOpen.value = !isOpen.value;
  raza.value = '';
  tipo.value = '';
  exclude_deleted.value = false;
  animal.value = '';
  mascota.value = '';
  trabajador.value = '';
  cliente.value = '';
  f_inicial.value='';
  f_final.value='';
  emit('search',search.value);
}
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};
</script>

<template>
    <div ref="ownerRef" class="relative w-full">
        <button class="btn-search w-full flex h-auto dropdown-wrapper mr-auto text-sm md:text-base px-4 md:px-5 py-2 items-center justify-center text-[#fcf7f2] bg-[#dc7474] font-bold rounded-md hover:cursor-pointer hover:scale-[1.02] transition-transform duration-200 disabled:cursor-wait" 
        :class="{ 'open': isOpen, 'cursor-wait' : loading } " 
        @click="toggleDropdown"
        :disabled="loading"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-search-icon lucide-search size-4 shrink-0"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
          <span class="px-2">Filtrar</span>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-chevron-down-icon lucide-chevron-down size-4 md:size-5 shrink-0 transition-transform duration-200"
          :class="isOpen ? 'rotate-180' : ''"
          ><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div class="dropdown absolute top-full left-0 mt-2 bg-[#e2d2c4] w-[90vw] md:w-[600px] max-w-[95vw] shadow-2xl px-4 md:px-5 py-4 border border-[#523013] rounded-[20px] z-[1000] custom-scrollbar overflow-y-auto max-h-[80vh]" v-if="isOpen">
          
          <div class="flex items-center justify-center">
            <div class="flex-1 inline-block w-full h-0.5 bg-[#523013] px-1"></div>
            <span class="header-dropdown text-[#523013] text-base md:text-lg font-bold px-2">Filtros</span> 
            <div class="flex-1 inline-block w-full h-0.5 bg-[#523013] px-1"> </div>
          </div>
          
          <div class="wrapper-filters">
            <div class="flex items-center w-full mt-3 mb-2">
              <input type="checkbox" name="" v-model="exclude_deleted"
              class="bg-[#fcf7f2] size-4 border border-[#523013] shrink-0 accent-[#812c8f]"> 
              <span class="text-color font-size-text text-[#523013] font-bold md:font-normal text-sm md:text-base px-2">Excluir mascotas eliminadas</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 md:mt-2">
              <div class="wrap-left">
                <div class="wrapper-filter-generic">
                  <label for="animal">Animal</label>
                  <input type="text" name="animal" placeholder="Ingrese un animal" v-model="animal"> 
                </div>
                <div v-if="props.user.getUser.rol !== 'cliente'" class="wrapper-filter-generic">
                  <label for="cliente">Cliente</label>
                  <input type="text" name="cliente" placeholder="Ingrese un cliente" v-model="cliente">
                </div>
                <div class="wrapper-filter-generic">
                  <label for="tipo">Tipo</label>
                  <input type="text" name="tipo" placeholder="Ingrese el tipo de cita" v-model="tipo">
                </div>
              </div>
              <div class="wrap-right">
                <div class="wrapper-filter-generic">
                  <label for="razas">Raza</label>
                  <input type="text" name="razas" placeholder="Ingrese la raza" v-model="raza">
                </div>
                <div class="wrapper-filter-generic">
                  <label for="mascota">Mascota</label>
                  <input type="text" name="mascota" placeholder="Ingrese una mascota" v-model="mascota"> 
                </div>
                <div v-if="props.user.getUser.rol !== 'trabajador'" class="wrapper-filter-generic">
                  <label for="trabajador">Veterinario</label>
                  <input type="text" name="trabajador" placeholder="Ingrese un veterinario" v-model="trabajador">
                </div>
              </div>
            </div>

            <div class="flex items-center justify-center mt-1 md:mt-2">
              <div class="flex-1 inline-block w-full h-0.5 bg-[#523013] px-1"></div>
              <span class="header-dropdown text-[#523013] text-base md:text-lg font-bold px-2">Fechas</span> 
              <div class="flex-1 inline-block w-full h-0.5 bg-[#523013] px-1"> </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2">
              <div class="wrap-left">
                <div class="wrapper-filter-generic">
                  <label for="f_inicial">Inicial</label>
                  <input type="date" name="f_inicial" v-model="f_inicial" class="w-full">
                </div>
              </div>
              <div class="wrap-right">
                <div class="wrapper-filter-generic">
                  <label for="f_final">Final</label>
                  <input type="date" name="f_final" v-model="f_final" class="w-full">
                </div>
              </div>
            </div>

            <div class="wrapper-btn flex flex-col md:flex-row gap-3 mt-4 pt-4 border-t border-[#523013]/20">
              <button type="submit" 
              class="w-full md:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#812c8f] hover:scale-[1.02] hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f]" 
              @click="buscar">Buscar</button>
              <button type="button" 
              class="w-full md:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-[1.02] hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#b51f1f]" 
              @click="limpiar">Limpiar</button>
            </div>
          </div>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.wrapper-filter-generic{
  @apply mb-3;
  label {
    @apply block text-[#523013] font-bold mb-1 text-sm md:text-base opacity-90;
  }
  input {
    @apply w-full py-2 px-3 md:px-4 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] placeholder-[#523013a4] box-border border-b-2 border-b-[#812c8f] outline-none focus:bg-white transition-colors text-sm md:text-base;
  }
}

.wrap-left {
  @apply md:mr-2 py-1 md:py-2;
}

.wrap-right {
  @apply md:ml-2 py-1 md:py-2; 
}

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