<script setup lang="ts">
import router from '@/router';
const props = defineProps<{
    onAcceptedMessage:string,
    TextMessage:string
}>();

</script>

<template>
    <div class="modal-fondo fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center px-4">
        
        <div class="modal-wrapper bg-[#eddcce] rounded-[20px] px-6 py-6 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center flex-col w-full max-w-sm md:max-w-md lg:max-w-none lg:w-[30%] min-h-[30vh] h-auto">
            
            <h2 class="header text-xl md:text-2xl text-center text-[#523013] font-extrabold leading-tight">{{props.onAcceptedMessage}}</h2>
            
            <p class="text text-sm md:text-base text-center text-[#523013] font-medium my-5">{{props.TextMessage}}</p>
            
            <button type="button" @click="router.push('/login')"
                class="w-full mt-auto py-3 flex items-center font-extrabold justify-center text-base md:text-lg rounded-md bg-[#fcf7f2] text-[#812c8f] hover:scale-105 cursor-pointer shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f] transition-all">
                <span class="px-1">Ir al Login</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in-icon lucide-log-in ml-1"><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/></svg>
            </button>
        </div>
    </div>
</template>

<style scoped >
    .onError{
        color: #b51f1f;
    }
    .onErrorMessage{
        margin-top: 8px;
        font-size: 20px;
    }
</style>