<script setup lang="ts">
defineProps<{
    action: string;
}>();

defineEmits<{
    (e: 'close'): void,
    (e: 'confirm'): void,
}>();
</script>

<template>
    <div v-if="action !== ''" class="flex w-full h-full fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 items-center justify-center px-4">
        <transition name="fade" mode="out-in">
            
            <div class="modal-wrapper bg-[#fcf7f2] rounded-[20px] p-6 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center items-center flex-col w-full max-w-sm md:max-w-md h-auto min-h-[25vh]">
                
                <div v-if="action === 'delete'" class="flex flex-col items-center w-full">
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#b51f1f] mb-4 leading-tight">¿Está seguro de eliminar registro?</h2>
                    <button class="w-full sm:w-auto px-6 py-2.5 my-1.5 rounded-md bg-[#b51f1f] text-[#fcf7f2] font-extrabold text-center cursor-pointer hover:scale-105 shadow-xl transition-all"
                    @click="$emit('confirm')">
                        Sí, eliminar
                    </button>
                    <button class="w-full sm:w-auto px-6 py-2.5 my-1.5 rounded-md bg-[#fcf7f2] text-[#812c8f] font-bold text-center cursor-pointer hover:scale-105 shadow-xl transition-all"
                    @click="$emit('close')">
                        Regresar
                    </button>
                </div>

                <div v-else-if="action === 'logout'" class="flex flex-col items-center w-full">
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#b51f1f] mb-4 leading-tight">¿Está seguro de abandonar la sesión?</h2>
                    <button class="w-full sm:w-auto px-6 py-2.5 my-1.5 rounded-md bg-[#b51f1f] text-[#fcf7f2] font-extrabold text-center cursor-pointer hover:scale-105 shadow-xl transition-all"
                    @click="$emit('confirm')">
                        Sí, salir
                    </button>
                    <button class="w-full sm:w-auto px-6 py-2.5 my-1.5 rounded-md bg-[#fcf7f2] text-[#812c8f] font-bold text-center cursor-pointer hover:scale-105 shadow-xl transition-all"
                    @click="$emit('close')">
                        Regresar
                    </button>
                </div>

                <div v-else-if="action === 'cancel'" class="flex flex-col items-center w-full">
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#b51f1f] mb-4 leading-tight">¿Está seguro de cancelar cita?</h2>
                    <button class="w-full sm:w-auto px-6 py-2.5 my-1.5 rounded-md bg-[#b51f1f] text-[#fcf7f2] font-extrabold text-center cursor-pointer hover:scale-105 shadow-xl transition-all"
                    @click="$emit('confirm')">
                        Sí, cancelar
                    </button>
                    <button class="w-full sm:w-auto px-6 py-2.5 my-1.5 rounded-md bg-[#fcf7f2] text-[#812c8f] font-bold text-center cursor-pointer hover:scale-105 shadow-xl transition-all"
                    @click="$emit('close')">
                        Regresar
                    </button>
                </div>
            </div>
            
        </transition>
    </div>
</template>