<script setup lang="ts">
defineProps({
    texto: String
});
</script>

<template>
    <button class="btn-void flex items-center justify-center text-sm md:text-base gap-1 rounded-md px-2 md:px-4 py-1.5 md:py-1 border-2 font-extrabold cursor-pointer transition-all"> 
        
        <slot>
            <span v-if="texto" class="px-1">{{ texto }}</span>
        </slot>
        
        <slot name="icono-button"></slot>
    </button>
</template>

<style scoped>
@reference "tailwindcss";

.btn-void:hover {
        @apply scale-105 shadow-xl;
    }
</style>