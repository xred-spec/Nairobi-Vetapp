<script setup lang="ts">
import type { Columna } from '@/interfaces/CardColumn';
import UsersCardButton from './UsersCardButton.vue';

defineProps<{
    item: Record<string, any>;
    numero: number;
    columnas: Columna[];
    estado: string;
    isFetching: boolean;
    view?: string;
    clickable?: boolean;
}>();

const emit = defineEmits<{
    (e: 'asignarHorario', id: number): void;
    (e: 'showMascotas', id: number): void;
    (e: 'modalData', id: number): void;
}>();

const obtenerNombreColumna = (item: Record<string, any>, key: string) => {
    const valor = item[key];

    if (key === 'nombre') {
        const user = item.user || item.usuario;

        if (typeof user === 'string') return user;
        if (typeof user === 'object') return user?.nombre;

        return '-';
    }
    if (key === 'telefono') return (item.user?.telefono || item.usuario?.telefono) ?? '-';
    if (key === 'email') return (item.user?.email || item.usuario?.email) ?? '-';
    if (key === 'estado') return (item.user?.estado || item.usuario?.estado) ?? '-';

    if (valor === null || valor === undefined) return '-';
    if (typeof valor === 'object' && !Array.isArray(valor)) return valor.nombre;
    return valor;
}
</script>

<template>
    <div class="flex items-stretch w-full mb-2 md:mb-0">
        
        <div v-if="isFetching"
            class="flex w-full min-h-[4rem] md:h-16 rounded-xl shadow-sm overflow-hidden bg-gradient-to-r from-[#f3f4f6] to-[#e5e7eb] animate-pulse">
            <div class="flex justify-center items-center h-full w-12 md:w-16 bg-gray-300 rounded animate-pulse shrink-0"></div>
            <div class="flex flex-col md:flex-row flex-1 p-3 md:p-0 md:px-5 justify-center md:items-center gap-3 md:gap-4 md:space-x-4">
                <div v-for="c in columnas" :key="'skel-' + c.key" class="w-full md:flex-1 h-4 md:h-6 bg-gray-300 rounded animate-pulse">
                </div>
            </div>
        </div>

        <div v-else class="flex w-full min-h-[4rem] md:h-16 rounded-xl shadow-sm overflow-hidden transition-all duration-200"
            :class="[
                estado === 'registrado' ? 'bg-[#fcf7f2] text-[#523013]' : 'bg-[#fcf7f290] text-[#523013b2]',
                clickable ? 'hover:scale-[1.01] hover:cursor-pointer' : ''
            ]"
           @click="clickable ? emit('modalData', item.id) : null">

            <div class="flex justify-center items-center h-auto w-12 md:w-16 font-extrabold text-sm md:text-base shrink-0"
                :class="estado === 'registrado' ? 'bg-[#dc7474] text-[#fcf7f2]' : 'bg-[#812c8fb2] text-[#fcf7f290]'">
                {{ numero }}
            </div>

            <div class="flex flex-col md:flex-row flex-1 p-3 md:p-0 md:px-5 h-full items-start md:items-center gap-2 md:gap-0 min-w-0">
                
                <div v-for="c in columnas" :key="c.key"
                    class="flex w-full md:flex-1 text-sm text-left md:text-center min-w-0 md:justify-center"
                    :class="c.key === 'opciones' ? 'items-center mt-1 pt-2 border-t border-[#a07a58]/20 md:border-none md:mt-0 md:pt-0 justify-end' : 'items-baseline md:items-center'">
                    
                    <template v-if="c.key === 'opciones'">
                        <div @click.stop class="flex justify-end w-full md:w-auto">
                            <UsersCardButton :id="item.id" :estado="estado" :view="view" 
                                @showMascotas="emit('showMascotas', item.id)"
                                @asignarHorario="emit('asignarHorario', item.id)" />
                        </div>
                    </template>
                    
                    <template v-else>
                        <span class="md:hidden font-bold text-[#812c8f] mr-2 text-[11px] shrink-0 opacity-80">
                            {{ c.label }}:
                        </span>
                        
                        <span class="whitespace-normal break-words font-extrabold md:px-2 truncate md:overflow-visible">
                            {{ obtenerNombreColumna(item, c.key) }}
                        </span>
                    </template>
                </div>

            </div>
        </div>
    </div>
</template>