<script setup lang="ts">
</script>

<template>
    <div class="fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center p-4">
      
      <div class="modal-wrapper bg-[#eddcce] rounded-[20px] px-6 md:px-8 py-8 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center items-center flex-col w-full max-w-sm md:max-w-[500px] h-auto min-h-[40vh]">
        
        <h2 class="header text-xl md:text-[25px] text-center text-[#523013] font-extrabold mt-2 leading-tight">Verificando tu cuenta</h2>
        <p class="text text-lg md:text-[30px] text-center text-[#523013] font-medium mt-3 md:mt-4 mb-6 leading-tight">¡Esto puede tardar unos segundos!</p>
        
        <div class="flex justify-center items-center h-24 md:h-32 w-full mt-2 md:mt-4">
            <div class="circle-red w-8 h-8 rounded-full bg-[#b51f1f]"></div>
            <div class="circle-pink w-4 h-4 rounded-full bg-[#dc7474] mx-1"></div>
            <div class="circle-purple w-8 h-8 rounded-full bg-[#812c8f]"></div>            
        </div>
        
        <h2 class="font-extrabold text-xl md:text-2xl text-center text-[#dc7474] my-2">Cargando...</h2>
      </div>
    </div>
</template>

<style scoped>
.circle-red {
    animation: redMove 1s ease-in-out infinite;
}

.circle-purple {
    animation: purpleMove 1s ease-in-out infinite;
}

@keyframes redMove {
    0% {
        transform: translateX(0px);
        scale: 0.5;
    }
    50% {
        transform: translateX(80px);
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
        transform: translateX(-80px);
        scale: 1;
    }
    100% {
        transform: translateX(0px);
        scale: 0.5;
    }
}
</style>