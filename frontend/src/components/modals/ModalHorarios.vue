<script setup lang="ts">
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';

const props = defineProps<{
    show: boolean;
    nombreTrabajador: string;
    todosLosHorarios: {
        id: number;
        inicio_hora: string;
        final_hora: string;
    }[];
    horariosDelTrabajador: {
        id: number;
        inicio_hora: string;
        final_hora: string;
        asignado: string;
    }[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'accept', ids: number[]): void;
}>();

const isActive = ref(false);

const noAsignados = ref<{ id: number; inicio_hora: string; final_hora: string }[]>([]);
const asignados   = ref<{ id: number; inicio_hora: string; final_hora: string }[]>([]);

watch(() => props.show, (nuevoValor) => {
    isActive.value = nuevoValor;

    if (!nuevoValor) return;

    const idsAsignados = props.horariosDelTrabajador
        .filter(h => h.asignado === 'asignado')
        .map(h => h.id);

    asignados.value = props.todosLosHorarios.filter(h =>
        idsAsignados.includes(h.id)
    );

    noAsignados.value = props.todosLosHorarios.filter(h =>
        !idsAsignados.includes(h.id)
    );
});

const cerrar = () => {
    isActive.value = false;
    setTimeout(() => emit('close'), 300);
};

const guardar = () => {
    const idsAsignados = asignados.value.map(h => h.id);
    emit('accept', idsAsignados);
    cerrar();
};
</script>

<template>
    <Teleport to="body">
        
        <Transition name="fade">
            <div v-if="isActive"
                class="fixed inset-0 bg-black/50 z-[9999998] backdrop-blur-sm"
                @click="cerrar">
            </div>
        </Transition>

        <Transition name="slide-up">
            <div v-if="isActive"
                class="fixed z-[9999999] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                       w-[95%] max-w-[960px] max-h-[90vh] bg-[#e2d2c4] rounded-[20px] p-4 md:p-6
                       shadow-[0_20px_60px_rgba(0,0,0,0.3)] flex flex-col">

                 <div class="flex justify-center items-center w-full mb-4 md:mb-6 shrink-0">
                    <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] text-center">
                        Asignar Horarios - <span class="text-[#523013b2] block md:inline">{{ nombreTrabajador }}</span>
                    </h2>
                </div>

                <div class="flex flex-col md:flex-row gap-2 md:gap-4 flex-1 min-h-0">

                    <div class="flex-1 flex flex-col min-h-0">
                        <h3 class="font-extrabold text-[#523013] text-center mb-1 md:mb-2 shrink-0">
                            No Asignados
                        </h3>

                        <div class="bg-[#fcf7f2] rounded-[20px] p-2 md:p-3 flex-1 overflow-y-auto custom-scrollbar">
                            <draggable
                                v-model="noAsignados"
                                group="horarios"
                                item-key="id"
                                class="grid grid-cols-2 lg:grid-cols-2 gap-2 min-h-[100px] md:min-h-[120px]"
                                ghost-class="opacity-40">

                                <template #item="{ element }">
                                    <div class="bg-[#e2d2c4] text-[#523013] font-extrabold
                                                py-2 md:py-3 px-1 md:px-2 rounded-md cursor-grab text-center text-xs md:text-sm
                                                shadow-sm select-none
                                                hover:shadow-md transition-shadow duration-200
                                                active:cursor-grabbing">
                                        {{ element.inicio_hora }} - {{ element.final_hora }}
                                    </div>
                                </template>

                                <template #footer>
                                    <div v-if="noAsignados.length === 0"
                                        class="col-span-2 text-[#523013a4] text-center font-bold text-xs md:text-sm py-4 md:py-6 h-full flex items-center justify-center">
                                        Sin horarios disponibles
                                    </div>
                                </template>
                            </draggable>
                        </div>
                    </div>

                    <div class="flex items-center justify-center text-[#523013a4] shrink-0 py-1 md:py-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-right-icon lucide-arrow-left-right size-6 md:size-8 rotate-90 md:rotate-0 transition-transform"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <h3 class="font-extrabold text-[#812c8f] text-center mb-1 md:mb-2 shrink-0">
                            Asignados
                        </h3>

                        <div class="bg-[#fcf7f2] rounded-[20px] p-2 md:p-3 flex-1 overflow-y-auto custom-scrollbar">
                            <draggable
                                v-model="asignados"
                                group="horarios"
                                item-key="id"
                                class="grid grid-cols-2 lg:grid-cols-2 gap-2 min-h-[100px] md:min-h-[120px]"
                                ghost-class="opacity-40">

                                <template #item="{ element }">
                                    <div class="bg-[#812c8f] text-white font-extrabold
                                                py-2 md:py-3 px-1 md:px-2 rounded-md cursor-grab text-center text-xs md:text-sm
                                                shadow-sm select-none
                                                hover:shadow-md transition-shadow duration-200
                                                active:cursor-grabbing">
                                        {{ element.inicio_hora }} - {{ element.final_hora }}
                                    </div>
                                </template>

                                <template #footer>
                                    <div v-if="asignados.length === 0"
                                        class="col-span-2 text-[#812c8f] text-center font-bold text-xs md:text-sm py-4 md:py-6
                                               border-2 border-dashed border-[#812c8f50] rounded-md h-full flex items-center justify-center">
                                        Arrastra aquí
                                    </div>
                                </template>
                            </draggable>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-6 pt-4 border-t-2 border-[#a07a58] flex-shrink-0">
                    <button
                        class="flex-1 py-3 font-extrabold rounded-md cursor-pointer
                               bg-[#fcf7f2] text-[#b51f1f]
                               hover:bg-[#b51f1f] hover:text-[#fcf7f2]
                               transition-all duration-200 hover:scale-[1.02] hover:shadow-md"
                        @click="cerrar">
                        Cerrar
                    </button>
                    <button
                        class="flex-1 py-3 font-extrabold rounded-md cursor-pointer
                               bg-[#fcf7f2] text-[#812c8f]
                               hover:bg-[#812c8f] hover:text-[#fcf7f2]
                               transition-all duration-200 hover:scale-[1.02] hover:shadow-md"
                        @click="guardar">
                        Aceptar
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.3s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
    opacity: 0;
    transform: translate(-50%, -45%);
}

/* Scrollbar sutil estandarizada */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(160, 122, 88, 0.3);
  border-radius: 10px;
}
</style>