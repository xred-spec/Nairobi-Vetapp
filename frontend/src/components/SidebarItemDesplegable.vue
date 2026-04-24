<script setup>
defineProps({
    texto: String,
    isExpanded: Boolean,
    open: Boolean, // Controlado por el padre
});

const emit = defineEmits(['toggle']);
</script>

<template>
    <div class="flex flex-col">
        <button 
            @click="$emit('toggle')" 
            class="sidebar-item relative group flex flex-row items-center font-extrabold border-[#523013] cursor-pointer text-[#523013] transition-all duration-200"
            :class="[
                isExpanded ? 'justify-start px-4 md:px-5 py-3 md:py-2 hover:pl-6 md:hover:pl-8' : 'justify-center py-4',
                open ? 'bg-[#b15b45] text-[#fcf7f2]' : 'hover:bg-[#dc7474] hover:text-[#fcf7f2]',
            ]"
        >
            <slot name="icono"></slot>
            
            <span v-if="isExpanded" class="px-3 text-sm md:text-xs uppercase tracking-wider">{{ texto }}</span>

            <div v-if="isExpanded" class="flex-1 flex justify-end">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" 
                    class="size-4 md:size-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>

            <div v-if="!isExpanded" 
                class="absolute left-full ml-4 px-3 py-1 bg-[#9f4934] text-[#fcf7f2] border-2 border-[#a07a58] text-sm rounded-md shadow-lg whitespace-nowrap z-[150] pointer-events-none opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-left scale-95 group-hover:scale-100">
                {{ texto }}
            </div>
        </button>

        <div v-show="open && isExpanded" class="flex flex-col bg-[#fce9d8] brightness-95 md:brightness-100 md:bg-[#f5dec8]/50 transition-all">
            <slot name="sub-items"></slot>
        </div>
    </div>
</template>

<style>
@reference "tailwindcss";

.sidebar-item:hover {
    @apply bg-[#dc7474];
}
</style>