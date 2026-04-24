<script setup lang="ts">
import { Columna } from '@/interfaces/CardColumn';
import CatalogosCardBotones from '../CatalogosCardBotones.vue';

defineProps<{
    item: Record<string, any>;
    numero: number;
    columnas: Columna[];
    isVisible: boolean;
    isFetching: boolean;
}>();

const emits = defineEmits<{
    (e: 'cambiarEstado', id: number): void
    (e: 'editar', id: number): void
    (e: 'eliminar', id: number): void
}>();
</script>

<template>
    <div class="flex items-center">
        <div v-if="isFetching" 
            class="flex items-center w-11/12 h-14 my-2 rounded-md mr-2 shadow-sm overflow-hidden bg-gradient-to-r from-[#f3f4f6] to-[#e5e7eb] animate-pulse">
            <div class="flex justify-center items-center h-full w-16 bg-gray-300 rounded animate-pulse"></div>
            <div class="flex flex-1 px-5 h-full items-center space-x-4">
                <div v-for="c in columnas" :key="'skel-' + c.key" class="flex-1 h-6 bg-gray-300 rounded animate-pulse">
                </div>
            </div>
        </div>

        <div v-else class="flex items-center justify-center w-full rounded-md shadow-sm overflow-hidden"
            :class="isVisible ? 'bg-[#fcf7f2] text-[#523013]' : 'bg-[#fcf7f290] text-[#523013b2]'">

            <div class="flex justify-center items-center h-full w-[5%] font-extrabold text-base"
                :class="isVisible ? 'bg-[#dc7474] text-[#fcf7f2]' : 'bg-[#812c8fb2] text-[#fcf7f290]'">
                {{ numero }}
            </div>

            <div class="flex items-center justify-center flex-col w-[75%]">
                <div class="px-5 pt-5 grid grid-cols-2 grid-rows-3">

                </div>
                <div>
                    <CatalogosCardBotones :isVisible="isVisible" :id="item.id" @editar="emits('editar', item.id)"
                            @eliminar="emits('eliminar', item.id)"/>
                </div>
            </div>

            <button @click="emits('cambiarEstado', item.id)"
            class="flex h-14 px-5 py-4 ml-2 items-center justify-center cursor-pointer" :class="[
                isVisible ? 'bg-[#fcf7f2] border-[#fcf7f2] text-[#dc7474] hover:border-[#dc7474] hover:bg-[#dc7474] hover:text-[#fcf7f2]' : 'bg-[#fcf7f2] border-[#fcf7f2] text-[#812c8f] hover:border-[#812c8f] hover:bg-[#812c8f] hover:text-[#fcf7f2]'
            ]">
            <svg v-if="isVisible" xmlns="http://www.w3.org/2000/svg" width="120%" height="120%" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-eye-icon lucide-eye">
                <path
                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="120%" height="120%" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-eye-closed-icon lucide-eye-closed">
                <path d="m15 18-.722-3.25" />
                <path d="M2 8a10.645 10.645 0 0 0 20 0" />
                <path d="m20 15-1.726-2.05" />
                <path d="m4 15 1.726-2.05" />
                <path d="m9 18 .722-3.25" />
            </svg>
        </button>
        </div>
    </div>
</template>