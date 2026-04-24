<script setup lang="ts">
import { ref, onMounted } from 'vue';

const emit = defineEmits<{
    (e: 'close'): void
}>();

const nombre = ref<string>("");

const isActive = ref<boolean>(false);

defineProps<{
    tiposAnimal: Array<{ id: number; nombre: string }>
}>();

onMounted(() => {
    // Small delay to ensure DOM is ready for animation
    setTimeout(() => {
        isActive.value = true;
    }, 10);
});

const closeModal = () => {
    isActive.value = false;
    setTimeout(() => {
        emit('close');
    }, 300);
};

const handleSubmit = (event: Event) => {
    event.preventDefault();
    // Handle form submission
    const formData = new FormData(event.target as HTMLFormElement);
    console.log('Form submitted:', Object.fromEntries(formData));
    closeModal();
};
</script>

<template>
    <div 
        class="modal-fondo fixed inset-0 bg-black/50 z-[999] backdrop-blur-sm transition-all duration-300"
        :class="{ 'opacity-100 visible': isActive, 'opacity-0 invisible': !isActive }"
        @click="closeModal"
    >
        <div 
            class="modal-wrapper fixed w-[90%] max-w-[420px] transition-all duration-300"
            :class="{ 
                'opacity-100 visible -translate-x-1/2 -translate-y-1/2 scale-100 top-1/2 left-1/2': isActive,
                'opacity-0 invisible': !isActive 
            }"
            @click.stop
        >
            <div class="modal-contenido rounded-[20px] p-[30px_25px] bg-[#FDFAF5] shadow-[0_10px_30px_rgba(0,0,0,0.15)]">
                <form id="form-raza" @submit="handleSubmit">
                    <div class="campos-form mb-4">
                        <label for="nombreRaza" class="block text-sm font-medium text-gray-700 mb-1">Raza</label>
                        <input 
                            v-model="nombre"
                            type="text" 
                            id="nombreRaza" 
                            name="nombre"
                            class="modal-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c59] focus:border-transparent"
                            placeholder="Ingresa el nombre de la raza"
                            required
                        >
                    </div>

                    <div class="campos-form mb-6">
                        <label for="tipoAnimal" class="block text-sm font-medium text-gray-700 mb-1">Tipo de animal</label>
                        <select 
                            name="idtipo_animal" 
                            id="tipoAnimal" 
                            class="modal-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c59] focus:border-transparent bg-white"
                            required
                        >
                            <option value="" disabled selected>Tipo de animal</option>
                            <option 
                                v-for="tipo in tiposAnimal" 
                                :key="tipo.id" 
                                :value="tipo.id"
                            >
                                {{ tipo.nombre }}
                            </option>
                        </select>
                    </div>

                    <div class="botones-modal flex gap-3 justify-end">
                        <button 
                            id="btn_cerrar" 
                            class="generic-button modal-btn-cancel px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
                            type="button"
                            @click="closeModal"
                        >
                            Cancelar
                        </button>
                        <button 
                            id="btn_aceptar" 
                            class="generic-button modal-btn-accept px-4 py-2 rounded-lg bg-[#4a7c59] text-white hover:bg-[#3d6549] transition-colors duration-200"
                            type="submit"
                        >
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Additional scoped styles if needed */
</style>

