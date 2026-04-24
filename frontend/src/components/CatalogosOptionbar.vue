<script setup lang="ts">
import { ref, watch } from 'vue';
import type { CampoForm } from '@/interfaces/CamposForm';

const props = defineProps<{
    titulo: string;
    texto: string;
    advice: boolean;
    loading: boolean;
    modelValue: Record<string, any>;
    campos: CampoForm[];
}>();

const emit = defineEmits(['abrirModal', 'update:modelValue']);

const camposFiltrables = props.campos.filter(c => c.type !== 'password' && c.type !== 'textarea');
const criterioActual = ref<CampoForm>(camposFiltrables[0]);

const filtros = ref({ ...props.modelValue });

const actualizarPadre = () => {
    emit('update:modelValue', { ...filtros.value });
};

const cambiarCriterio = (key: string) => {
    const campo = camposFiltrables.find(c => c.modelKey === key);
    if (campo) {
        criterioActual.value = campo;
        Object.keys(filtros.value).forEach(k => filtros.value[k] = '');
        actualizarPadre();
    }
};

watch(() => props.modelValue, (newVal) => {
    if (JSON.stringify(newVal) !== JSON.stringify(filtros.value)) {
        filtros.value = { ...newVal };
    }
}, { deep: true });

watch(() => props.campos, (newCampos) => {
    if (!newCampos || newCampos.length === 0) return;
    
    const campoFresco = newCampos.find(c => c.modelKey === criterioActual.value.modelKey);
    
    if (campoFresco) {
        criterioActual.value = campoFresco;
    }
}, { deep: true, immediate: true });
</script>

<template>
    <div class="w-full flex flex-col shrink-0">
        
        <div class="main-header w-full flex flex-col md:flex-row items-center md:justify-between py-3 px-4 md:px-5 mt-2 rounded-xl bg-[#523013] text-[#fcf7f2] gap-2 md:gap-0 shrink-0 shadow-sm">
            <h1 class="z-10 flex justify-center md:justify-start text-center md:text-start text-xl md:text-2xl font-black w-full md:w-auto">
                {{ titulo }}
            </h1>
            <div v-if="advice" class="z-10 flex items-center justify-center md:justify-end gap-2 w-full md:w-auto opacity-90">
                <label class="text-xs md:text-sm font-medium text-center md:text-right">
                    Haz click en un registro para ver todos sus datos
                </label>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info shrink-0 hidden md:block size-5">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                </svg>
            </div>
        </div>

        <div class="option-bar flex flex-col md:flex-row items-center justify-between py-3 w-full gap-3 shrink-0">
            
            <div class="flex w-full md:flex-1 md:max-w-2xl shadow-sm rounded-md overflow-hidden border-b-2 border-b-[#812c8f] hover:scale-[1.01] transition-transform duration-200 shrink-0">
                <select 
                    :value="criterioActual.modelKey"
                    @change="cambiarCriterio(($event.target as HTMLSelectElement).value)"
                    class="bg-[#523013] text-white pl-3 pr-8 md:pl-4 md:pr-9 py-2 md:py-2.5 font-extrabold text-xs md:text-sm outline-none cursor-pointer hover:bg-[#3d240e] transition-colors border-none appearance-none shrink-0"
                    :disabled="loading"
                >
                    <option v-for="campo in camposFiltrables" :key="campo.modelKey" :value="campo.modelKey">
                        {{ campo.label }}
                    </option>
                </select>

                <div class="flex-1 bg-[#fcf7f2] relative min-w-0">
                    <select 
                        v-if="criterioActual.type === 'select'"
                        v-model="filtros[criterioActual.modelKey]"
                        @change="actualizarPadre"
                        class="search-bar w-full h-full px-3 md:px-5 py-2 md:py-2.5 text-[#523013] font-extrabold text-sm md:text-base outline-none bg-transparent appearance-none truncate"
                        :disabled="loading"
                    >
                        <option value="">Cualquier {{ criterioActual.label.toLowerCase() }}...</option>
                        <option v-for="opt in criterioActual.options" :key="opt.value" :value="opt.value">
                            {{ opt.label }}
                        </option>
                    </select>

                    <input 
                        v-else
                        :type="criterioActual.type === 'date' ? 'date' : 'text'"
                        v-model="filtros[criterioActual.modelKey]"
                        @input="actualizarPadre"
                        :placeholder="`Buscar por ${criterioActual.label.toLowerCase()}...`"
                        class="search-bar w-full h-full px-3 md:px-5 py-2 md:py-2.5 text-[#523013] font-extrabold text-sm md:text-base outline-none bg-transparent placeholder-[#5230139e] truncate"
                        :disabled="loading"
                        :class="loading ? 'cursor-wait' : ''"
                    />
                </div>
            </div>

            <button @click="emit('abrirModal')" 
                class="btn-optionbar flex items-center bg-[#fcf7f2] text-[#dc7474] w-full md:w-auto gap-1.5 md:gap-2 px-3 md:px-5 py-2 md:py-2.5 rounded-md justify-center font-extrabold text-sm md:text-base cursor-pointer shadow-sm shrink-0 disabled:opacity-70"
                :disabled="loading"
                :class="loading ? 'cursor-wait' : ''">
                <span class="truncate">Agregar {{ texto }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus size-4 md:size-5 shrink-0">
                    <path d="M5 12h14"/><path d="M12 5v14"/>
                </svg>
            </button>
            
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.btn-optionbar {
    transition: all 0.2s ease;
}

.btn-optionbar:hover {
    @apply scale-105 border-[#dc7474] bg-[#dc7474] text-[#fcf7f2];
}

select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23523013' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
    background-size: 0.8rem;
}

select.bg-\[\#523013\] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
    background-position: right 0.6rem center;
}
</style>