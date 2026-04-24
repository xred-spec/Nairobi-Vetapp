<script setup lang="ts">
import LoadingModalVerification from '@/components/LoadingModalVerification.vue';
import OnErrorModalVerification from '@/components/OnErrorModalVerification.vue';
import OnAcceptedModalVerification from '@/components/OnAcceptedModalVerification.vue';
import SettingPasswordModal from '@/components/SettingPasswordModal.vue';
import useAuth from '@/composables/useAuth';
import {ref,onMounted} from 'vue';
import {useRoute} from 'vue-router';
const route = useRoute();
const data = ref<any>(null)
const success = ref(false);
onMounted(async () => {
   data.value=await useAuth().verificartoken(route.query.token as string);
})
</script>

<template>
   <div class="modal-fondo fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center p-4">
      
      <div class="wrapper-reclamacion bg-[#eddcce] rounded-[20px] px-5 md:px-8 py-6 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center flex-col w-full max-w-md md:max-w-2xl lg:w-[60%] min-h-[40vh] md:min-h-[60vh] h-auto overflow-y-auto">
         
         <LoadingModalVerification v-if="data?.statusCode==null"/>
         <SettingPasswordModal v-else-if="data?.statusCode==200 && !success" @success="success=true" @error="data.statusCode=404"/>
         <OnAcceptedModalVerification v-else-if="success" :onAcceptedMessage="'Contraseña establecida'" :TextMessage="'La contraseña ya fue establecida, ya puedes cerrar esta ventana'"/> 
         <OnErrorModalVerification v-else-if="data?.statusCode!=200" :onErrorMessage="data?.data.message"/>
         </div>
   </div>
</template>