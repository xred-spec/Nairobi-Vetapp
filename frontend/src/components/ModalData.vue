<script setup lang="ts">
import { ref, watch } from 'vue';
import { CampoForm } from '@/interfaces/CamposForm';

const props = defineProps<{
    header: string;
    campos: CampoForm[];
    show: boolean;
    modelValue: Record<string, any> | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void,
}>();

const isActive = ref<boolean>(false);

watch(() => props.show, (val) => {
    isActive.value = val;
});

const obtenerValor = (campo: CampoForm) => {
    if (!props.modelValue) return 'N/A';
    
    const key = campo.modelKey;
    const item = props.modelValue;
    
    if (['nombre', 'telefono', 'email'].includes(key)) {
        const usuarioRelacionado = item.user || item.usuario;
        if (usuarioRelacionado && usuarioRelacionado[key]) {
            return usuarioRelacionado[key];
        }
    }
    
    if (key.endsWith('_id')) {
        const relacion = key.replace('_id', ''); 
        if (relacion === 'cliente' || relacion === 'user') {
            const clienteObj = item.cliente || item.dueño;
            if (clienteObj) {
                const userObj = clienteObj.user || clienteObj.usuario;
                if (userObj && userObj.nombre) return userObj.nombre;
                if (clienteObj.nombre) return clienteObj.nombre;
            }
        }

        if (relacion === 'animal' && !item.animal) {
            if (item.raza && item.raza.animal && item.raza.animal.nombre) {
                return item.raza.animal.nombre;
            }
        }

        if (item[relacion] && typeof item[relacion] === 'object') {
            return item[relacion].nombre || item[relacion].name || item[key];
        }
    }

    if (campo.type === 'select' && campo.options?.length) {
        const opcion = campo.options.find(opt => String(opt.value) === String(item[key]));
        if (opcion) return opcion.label;
    }

    const valor = item[key];
    return (valor !== null && valor !== undefined && valor !== '') ? valor : 'N/A';
};

const cerrarModal = () => {
    isActive.value = false;
    setTimeout(() => {
        emit('close');
    }, 400)
};
</script>

<template>
    <Teleport to="body">
        <div class="modal-fondo fixed inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 px-4" style="z-index: 9999999"
            :class="{ 'opacity-100 visible': isActive, 'opacity-0 invisible': !isActive }" @click="cerrarModal">
            
            <div class="modal-wrapper z-[10000] fixed max-h-[90vh] transition-all duration-300 gap-20 flex flex-col w-full max-w-sm md:max-w-2xl"
            :class="{
                'opacity-100 visible -translate-x-1/2 -translate-y-1/2 scale-100 top-1/2 left-1/2': isActive,
                'opacity-0 invisible': !isActive
            }" 
            @click.stop>
                
                <div class="modal-contenido rounded-[20px] p-4 md:p-6 bg-[#e2d2c4] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col max-h-[90vh]">
                    
                    <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] mb-2 shrink-0">{{ header }}</h2>

                    <div class="flex-1 overflow-y-auto pr-2 -mr-2 pt-3 border-t-2 border-[#a07a58] custom-scrollbar">
                        
                        <div class="grid gap-x-4 gap-y-3 w-full" :class="campos.length > 4 ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'">
                            
                            <div v-for="campo in campos" :key="campo.modelKey" class="campos-form" 
                                 :class="{ 'md:col-span-2': campo.type === 'textarea' && campos.length > 4}">
                                
                                <label class="block text-[#523013] mb-[6px] font-bold text-sm md:text-base opacity-80">
                                    {{ campo.label }}
                                </label>

                                <div class="w-full py-2.5 px-4 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base break-words"
                                     :class="campo.type === 'textarea' ? 'min-h-[5rem] whitespace-pre-wrap' : ''">
                                    {{ obtenerValor(campo) }}
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-center mt-4 pt-4 border-t-2 border-[#a07a58] shrink-0">
                        <button type="button"
                            class="w-full sm:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-[1.02] hover:shadow-md hover:text-[#fcf7f2] hover:bg-[#b51f1f]"
                            @click="cerrarModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

/* Scrollbar sutil estandarizada */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(160, 122, 88, 0.3);
  border-radius: 10px;
}
</style>