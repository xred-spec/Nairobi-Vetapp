<script setup lang="ts">


import RegistroCliente from '@/components/modals/RegistroCliente.vue'; 

import RegisterModal from '@/components/RegisterModal.vue';

import {ref} from 'vue';

const showmodal = ref(false);

</script>

<template>
   <div class="flex items-center justify-center min-h-screen w-full bg-[#eddcce] p-4 py-8">
    <RegistroCliente :required="true" @success="showmodal=true"/>
    <RegisterModal v-if="showmodal" :onAcceptedMessage="'Usuario registrado correctamente'" TextMessage="'Se te ha enviado un correo a tu correo para verificar tu cuenta, revisa la bandeja de spam'"/>
  </div>
</template>