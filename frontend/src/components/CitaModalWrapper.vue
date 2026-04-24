<script setup lang="ts">
import AppointmentModalAdd from './modals/AppointmentModalAdd.vue';

defineProps<{
    show: boolean
}>();

const emit = defineEmits(['close', 'accept']);

const handleEnviado = (mensaje: string) => {
    emit('accept', mensaje);
};

const handleClose = () => {
    emit('close');
};
</script>

<template>

        <AppointmentModalAdd v-if="show"
            @cerrar="handleClose" 
            @enviado="handleEnviado" 
        />

</template>

