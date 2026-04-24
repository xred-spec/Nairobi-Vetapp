<script setup lang="ts">
defineProps<{
    estado: string,
    id: number,
    view?: string
}>();

const emits = defineEmits<{
    (e: 'showMascotas', id: number): void;
    (e: 'asignarHorario', id: number): void;
}>();
</script>

<template>
    <div class="flex items-center justify-end md:justify-center w-full z-10">
        
        <button v-if="view === 'clientes'"
            @click="emits('showMascotas', id)"
            class="flex items-center justify-center gap-1.5 md:gap-2 w-full md:w-auto py-2 md:py-2.5 px-3 md:px-4 rounded-md transition-all duration-200 font-extrabold text-sm md:text-base shadow-sm disabled:shadow-none"
            :disabled="estado !== 'registrado'"
            :class="estado === 'registrado' ? 'bg-[#fcf7f2] text-[#812c8f] hover:bg-[#812c8f] hover:text-[#fcf7f2] cursor-pointer hover:scale-[1.02]' : 'bg-transparent text-[#812c8f]/70 cursor-not-allowed'">
            <span class="truncate">Ver mascotas</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bone-icon lucide-bone size-4 md:size-5 shrink-0">
                <path d="M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5Z"/>
            </svg>
        </button>
        
        <button v-else-if="view === 'trabajadores'"
            @click="emits('asignarHorario', id)"
            class="flex items-center justify-center gap-1.5 md:gap-2 w-full md:w-auto py-2 md:py-2.5 px-3 md:px-4 rounded-md transition-all duration-200 font-extrabold text-sm md:text-base shadow-sm disabled:shadow-none"
            :disabled="estado !== 'registrado'"
            :class="estado === 'registrado' ? 'bg-[#fcf7f2] text-[#812c8f] hover:bg-[#812c8f] hover:text-[#fcf7f2] cursor-pointer hover:scale-[1.02]' : 'bg-transparent text-[#812c8f]/70 cursor-not-allowed'">
            <span class="truncate">Asignar horario</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock size-4 md:size-5 shrink-0">
                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
            </svg>
        </button>
        
    </div>
</template>