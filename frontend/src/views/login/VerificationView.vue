<script setup lang="ts">
import {useRoute} from "vue-router";
import {onMounted,ref} from 'vue';
const route = useRoute();
import useAuth from '@/composables/useAuth';
const data = ref<any>(null)

onMounted(async () => {
    data.value = await useAuth().verificarCuenta(route.query.token as string);
});
</script>

<template>
    <div class="modal-fondo fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center w-full h-dvh px-4">
       
       <div v-if="data==null" class="modal">
             <p class="text-base text-center text-[#523013] font-medium my-5">Cargando...</p>
       </div>
       
       <div v-else-if="data?.statusCode!=200" class="modal">
            <p class="text text-base text-center text-[#523013] font-normal my-5">Error al verificar</p>
            <img class="m-2" width="50" height="50" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/..." alt="Error">
       </div>
       
        <div v-else class="modal">
            <p class="text-lg md:text-xl text-center text-[#523013] font-extrabold my-5">¡¡¡Verificadísimo!!!</p>
        </div>
    </div>
</template>

<style scoped>
@reference 'tailwindcss';  
    .modal {
        @apply bg-[#eddcce] rounded-[20px] px-6 py-8 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center items-center flex-col w-full max-w-sm md:w-[50%] lg:w-[40%] min-h-[30vh] h-auto;
    }
</style>