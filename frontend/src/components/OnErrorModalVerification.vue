<script setup lang="ts">

const props = defineProps<{
    onErrorMessage:string
}>();

</script>

<template>
    <div class="fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center px-4">
        
        <div class="bg-[#eddcce] rounded-[20px] px-6 md:px-8 py-6 md:py-8 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center items-center flex-col w-full max-w-sm md:max-w-lg h-auto min-h-[30vh] md:min-h-[40vh]">
            
            <h2 class="text-xl md:text-[25px] text-center text-[#523013] font-extrabold mt-2 leading-tight">
                Algo acaba de salir terriblemente mal
            </h2>
            
            <p class="text-lg md:text-[30px] text-center text-[#523013] font-medium mt-3 md:mt-4 mb-4 md:mb-6 leading-tight">
                No se pudo verificar tu cuenta
            </p>
            
            <p class="text-base md:text-[20px] text-center text-[#523013] font-medium leading-snug">
                Mensaje de error: 
                <span class="text-[#b51f1f] font-bold block mt-1">{{props.onErrorMessage}}</span>
            </p>
            
        </div>
    </div>   
</template>