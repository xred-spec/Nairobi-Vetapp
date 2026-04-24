<script setup lang="ts">
const props = defineProps<{
    onAcceptedMessage:string,
    TextMessage:string
}>();
</script>

<template>
    <div class="fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center px-4">
        
        <div class="bg-[#eddcce] rounded-[20px] px-6 md:px-8 py-6 md:py-8 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center items-center flex-col w-full max-w-sm md:max-w-lg h-auto min-h-[30vh] md:min-h-[40vh]">
            
            <h2 class="text-xl md:text-[25px] text-center text-[#523013] font-extrabold mt-2 leading-tight">
                {{props.onAcceptedMessage}}
            </h2>
            
            <p class="text-lg md:text-[30px] text-center text-[#523013] font-medium mt-4 mb-6 leading-tight">
                {{props.TextMessage}}
            </p>
            
        </div>
    </div>    
</template>