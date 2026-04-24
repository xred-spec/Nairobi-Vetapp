<script lang="ts" setup>
defineProps<{
    state:string;
}>();
</script>

<template>
    <div class="w-full h-full rounded-[20px] z-[999] transition-all duration-300 flex items-center justify-center px-4">
        
        <div class="modal-wrapper bg-[#fcf7f2] rounded-[20px] p-6 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center items-center flex-col w-full max-w-[280px] md:max-w-sm h-auto min-h-[250px]">
            
            <transition name="fade" mode="out-in">
                <div v-if="state === 'loading'" class="flex flex-col items-center w-full">
                    <div class="loading flex justify-center items-center h-32 w-full">
                        <div class="circle-red w-10 md:w-12 h-10 md:h-12 rounded-full bg-[#b51f1f] shrink-0"></div>
                        <div class="circle-pink w-5 md:w-6 h-5 md:h-6 rounded-full bg-[#dc7474] mx-1 shrink-0"></div>
                        <div class="circle-purple w-10 md:w-12 h-10 md:h-12 rounded-full bg-[#812c8f] shrink-0"></div>
                    </div>
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#dc7474] my-2 leading-tight">Cargando...</h2>
                </div>           
            
                <div v-else-if ="state === 'error'" class="flex flex-col items-center w-full">
                    <div class="success flex justify-center items-center h-32 w-full text-[#b51f1f]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 md:w-28 md:h-28 lucide lucide-circle-x shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    </div>
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#b51f1f] my-2 leading-tight">Algo salió mal...</h2>
                </div>

                <div v-else-if ="state === 'success'" class="flex flex-col items-center w-full">
                    <div class="success flex justify-center items-center h-32 w-full text-[#812c8f]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 md:w-28 md:h-28 lucide lucide-circle-check shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#812c8f] my-2 leading-tight">¡Hecho!</h2>
                </div>
            </transition>    
        </div>
    </div>
</template>

<style scoped>
.circle-red {
    animation: redMove 1.2s ease-in-out infinite;
}

.circle-purple {
    animation: purpleMove 1.2s ease-in-out infinite;
}

/* Ajusté el movimiento de 110px a 90px para que las bolitas quepan perfecto en la tarjeta en celulares pequeños */
@keyframes redMove {
    0% {
        transform: translateX(0px);
        scale: 0.5;
    }
    50% {
        transform: translateX(90px); 
        scale: 1;
    }
    100% {
        transform: translateX(0px);
        scale: 0.5;
    }
}

@keyframes purpleMove {
    0% {
        transform: translateX(0px);
        scale: 0.5;
    }
    50% {
        transform: translateX(-90px);
        scale: 1;
    }
    100% {
        transform: translateX(0px);
        scale: 0.5;
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.95); 
}
</style>