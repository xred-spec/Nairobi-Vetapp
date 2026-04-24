<script setup>
defineProps({
    texto: String,
    isExpanded: Boolean,
});

const emit = defineEmits(['select']);
</script>

<template>
    <button @click="emit('select')" class="sidebar-item relative group flex items-center font-extrabold border-[#523013]
    cursor-pointer text-[#523013] transition-all duration-200"
    :class="[
        isExpanded ? 'justify-start py-3 md:py-2' : 'justify-center py-4 md:py-4',
    ]" >
        <slot name="icono"></slot>
        
        <span v-if="isExpanded" class="px-3 text-xs whitespace-nowrap">{{ texto }}</span>

        <div v-if="!isExpanded" 
            class="absolute left-full ml-4 px-3 py-1.5 bg-[#523013] text-[#fce9d8] text-sm md:text-base rounded-md shadow-lg whitespace-nowrap z-[150] pointer-events-none
                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-left scale-95 group-hover:scale-100">
            <div class="absolute top-1/2 -left-1 -mt-1 w-2 h-2 bg-[#523013] rotate-45"></div>
            {{ texto }}
        </div>
    </button>
</template> 

<style>
@reference "tailwindcss";

.sidebar-item:hover {
    @apply bg-[#dc7474] text-[#fce9d8];
}
</style>