<script setup lang="ts">
import { ref, watch } from 'vue';
const props = defineProps <{
    pages: number;
    modelValue: number;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: number): void
}>();

const paginaActual = ref <number> (props.modelValue || 1);

watch(() => props.modelValue, (valor) => {
    paginaActual.value = valor;
});

const pagPrevia = () => {
    if (paginaActual.value > 1) {
        paginaActual.value--;
        emit('update:modelValue', paginaActual.value);
    }
};

const pagSiguiente = () => {
    if (paginaActual.value < props.pages) {
        paginaActual.value++;
        emit('update:modelValue', paginaActual.value);
    }
};

</script>

<template>
    <div class="flex items-center mt-2 gap-2 md:gap-3 justify-center w-full">
        
        <button @click="pagPrevia" :disabled="paginaActual === 1"
            class="btn-pag flex justify-center items-center px-6 sm:px-10 md:px-20 py-2.5 font-extrabold text-xs md:text-sm rounded-md text-[#523013] bg-[#fcf7f2] transition-colors duration-200 enabled:hover:bg-[#523013] enabled:hover:text-[#fcf7f2] disabled:bg-gray-400/50 disabled:text-gray-500 enabled:cursor-pointer disabled:cursor-not-allowed">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right rotate-180 size-4 md:size-5 shrink-0"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>

        <div class="flex justify-center items-center px-4 sm:px-6 md:px-10 py-2.5 border-none rounded-md text-[#523013] bg-[#fcf7f2] text-center font-extrabold text-xs md:text-sm min-w-[3rem] md:min-w-[4rem] shadow-sm shrink-0">
            {{ paginaActual }}
        </div>

        <button @click="pagSiguiente" :disabled="paginaActual === props.pages"
            class="btn-pag flex justify-center items-center px-6 sm:px-10 md:px-20 py-2.5 font-extrabold text-xs md:text-sm rounded-md text-[#523013] bg-[#fcf7f2] transition-colors duration-200 enabled:hover:bg-[#523013] enabled:hover:text-[#fcf7f2] disabled:bg-gray-400/50 disabled:text-gray-500 enabled:cursor-pointer disabled:cursor-not-allowed">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right size-4 md:size-5 shrink-0"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
        
    </div>
</template>