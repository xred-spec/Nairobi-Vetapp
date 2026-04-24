<script setup lang="ts">
defineProps({
    texto: String,
    isExpanded: Boolean,
    isSelected: Boolean,
    isSubItem: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['select']);
</script>

<template>
    <button @click="emit('select')" class="sidebar-item relative group flex items-center font-extrabold border-[#523013] cursor-pointer text-[#523013] hover:text-[#fcf7f2] transition-all duration-200"
    :class="[
        /* Padding BASE: Separamos el padding derecho (pr) para que no estorbe al izquierdo (pl) */
        isExpanded ? 'justify-start pr-4 md:pr-5 py-3 md:py-2' : 'justify-center py-4',
        
        /* Padding IZQUIERDO: SOLO se activa si es un botón principal. Si es sub-item, se apaga. */
        isExpanded && !isSubItem ? 'pl-4 md:pl-5 hover:pl-8 md:hover:pl-10' : '',
        
        /* Estilos de seleccionado */
        isSelected ? 'bg-[#523013] text-[#fcf7f2]' : '',
        
        /* Padding extra de seleccionado: SOLO para botones principales */
        isSelected && isExpanded && !isSubItem ? 'pl-8 md:pl-10' : ''
    ]" >
        <slot name="icono"></slot>
        
        <span v-if="isExpanded" class="px-3 text-sm md:text-xs">{{ texto }}</span>

        <div v-if="isExpanded" class="flex-1 flex justify-end">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="15" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot transition-all duration-200"
            :class="isSelected ? 'opacity-100 visible' : 'opacity-0 invisible'">
                <circle cx="12.1" cy="12.1" r="1"/>
            </svg>
        </div>

        <div v-if="!isExpanded" 
            class="absolute left-full ml-4 px-5 py-1.5 bg-[#9f4934] text-[#fcf7f2] border-2 border-[#a07a58] text-sm md:text-base rounded-md shadow-lg whitespace-nowrap z-[150] pointer-events-none
                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-left scale-95 group-hover:scale-100">
            <div class="absolute top-1/2 -left-3.5 -mt-1 w-2.5 h-2.5 bg-[#9f4934] border-2 border-[#a07a58] rounded-full"></div>
            {{ texto }}
        </div>
    </button>
</template> 

<style>
@reference "tailwindcss";

.sidebar-item:hover {
    @apply bg-[#dc7474];
}
</style>