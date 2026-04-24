<script setup lang="ts">
const emit = defineEmits<{
    (e: 'abrirModal'): void
}>();
defineProps<{
    texto?: string
    loading: boolean
}>();
</script>

<template>
    <button @click="emit('abrirModal')" 
            class="flex items-center justify-center gap-1.5 md:gap-2 px-3 md:px-5 py-2 md:py-2.5 rounded-md font-extrabold text-sm md:text-base cursor-pointer shadow-sm transition-all duration-200 bg-[#fcf7f2] text-[#dc7474] hover:scale-105 hover:bg-[#dc7474] hover:text-[#fcf7f2] hover:shadow-md disabled:opacity-70"
            :disabled="loading"
            :class="loading ? 'cursor-wait' : ''">
        
        <span class="truncate">{{ texto || 'Agregar Cita' }}</span>
        
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" 
             class="lucide lucide-plus-icon lucide-plus size-4 md:size-5 shrink-0">
             <path d="M5 12h14"/><path d="M12 5v14"/>
        </svg>
    </button>
</template>