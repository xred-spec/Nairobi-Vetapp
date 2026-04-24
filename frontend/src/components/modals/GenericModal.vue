<script setup lang="ts">
import { ref, reactive, watch, onMounted } from 'vue';
import { CampoForm } from '../../interfaces/CamposForm';

const props = defineProps<{
    header: string;
    campos: CampoForm[];
    show: boolean;
    modelValue?: Record<string, any> | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void,
    (e: 'accept', data: Record<string, any>): void,
    (e: 'invalid', message: string): void
    (e: 'update:modelValue', value: Record<string, any>): void
}>();

const isActive = ref<boolean>(false);
const formData = reactive<Record<string, any>>({});
const isEdit = ref<boolean>(false);

const cerrarModal = () => {
    isActive.value = false;
    resetModal();
    setTimeout(() => {
        emit('close');
    }, 400)
};

const aceptarModal = () => {

    for (const c of props.campos) {

        const value = formData[c.modelKey];

        if (c.required && !value) {
            emit('invalid', `Falta el campo: ${c.label}`);
            return;
        }

        if (c.pattern) {
            const regex = new RegExp(c.pattern);

            if (!regex.test(value)) {
                emit('invalid', `Formato inválido en: ${c.label}`);
                return;
            }
        }

    }

    emit('accept', { ...formData });
    cerrarModal();
};

const resetModal = () => {
    props.campos.forEach(c => {
        if (c.type === 'number') {
            formData[c.modelKey] = 0;
        } else if (c.type === 'select') {
            formData[c.modelKey] = null;
        } else {
            formData[c.modelKey] = '';
        }
    });
};

onMounted(() => {
    if (props.show) {
        isActive.value = true;
    }
});

watch(formData, (newForm) => {

    emit('update:modelValue', { ...newForm });

    props.campos.forEach(campo => {

        if (campo.dependsOn && campo.filterFn) {

            const parentValue = newForm[campo.dependsOn];

            campo.options = campo.filterFn(parentValue, newForm);

            const existe = campo.options?.some(
                opt => opt.value === newForm[campo.modelKey]
            );

            if (!existe && campo.options?.length) {
                formData[campo.modelKey] = campo.options[0].value;
            }
        }

    });
}, { deep: true });

watch(() => props.show, (val) => {
    isActive.value = val;
    if (!val) return;

    if (props.modelValue?.id) {
        isEdit.value = true;
        resetModal();
        Object.assign(formData, props.modelValue);

        props.campos.forEach(c => {
            if (c.type === 'select' && c.options?.length) {
                formData[c.modelKey] = formData[c.modelKey]
                    || props.modelValue?.[c.modelKey]
                    || c.options[0].value;
            }
        });

    } else {
        isEdit.value = false;
        resetModal();

        props.campos.forEach(c => {
            if (c.type === 'select' && c.options?.length) {
                formData[c.modelKey] = c.options[0].value;
            }
        });
    }
}, {immediate: true});

</script>

<template>
    <Teleport to="body">
        <div class="modal-fondo fixed inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 px-4" style="z-index: 9999999"
            :class="{ 'opacity-100 visible': isActive, 'opacity-0 invisible': !isActive }" @click="cerrarModal">
            
            <div class="modal-wrapper z-[10000] fixed max-h-[90vh] transition-all duration-300 gap-20 flex flex-col w-full max-w-sm md:max-w-2xl"
                :class="{
                    'opacity-100 visible -translate-x-1/2 -translate-y-1/2 scale-100 top-1/2 left-1/2': isActive,
                    'opacity-0 invisible': !isActive
                }" @click.stop>

                <div class="modal-contenido rounded-[20px] p-4 md:p-6 bg-[#e2d2c4] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col max-h-[90vh]">
                    
                    <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] mb-2 shrink-0">{{ header }}</h2>

                    <div class="flex-1 overflow-y-auto pr-2 -mr-2 pt-3 border-t-2 border-[#a07a58] custom-scrollbar">
                        
                        <form class="grid gap-x-4 gap-y-3 w-full" 
                               :class="campos.length > 4 ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'">
                            
                            <div v-for="campo in campos" :key="campo.modelKey" class="campos-form" 
                                 :class="{ 'md:col-span-2': campo.type === 'textarea' && campos.length > 4}">
                                
                                <label class="block text-[#523013] mb-[6px] font-bold text-sm md:text-base">
                                    {{ campo.label }}
                                    <span v-if="campo.required" class="text-[#b51f1f]">*</span>
                                </label>

                                <template v-if="campo.type === 'select'">
                                    <select v-model="formData[campo.modelKey]"
                                        class="modal-select w-full py-2.5 px-4 rounded-md bg-[#fcf7f2] cursor-pointer max-h-12 font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] overflow-hidden text-sm md:text-base focus:outline-none appearance-none"
                                        :required="campo.required" :disabled="campo.disabled">
                                        <option v-for="opt in campo.options" :key="opt.value" :value="opt.value">
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </template>

                                <template v-else-if="campo.type === 'textarea'">
                                    <textarea v-model="formData[campo.modelKey]" :placeholder="campo.placeholder"
                                        :required="campo.required" :disabled="campo.disabled" :rows="campo.rows || 3"
                                        class="modal-input w-full py-2.5 px-4 rounded-md bg-[#fcf7f2] box-border font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none text-sm md:text-base focus:outline-none custom-scrollbar"></textarea>
                                </template>

                                <template v-else>
                                    <input :type="campo.type" v-model="formData[campo.modelKey]"
                                        :placeholder="campo.placeholder" :required="campo.required"
                                        :disabled="campo.disabled" :min="campo.min" :max="campo.max"
                                        :maxlength="campo.max"
                                        :pattern="campo.pattern"
                                        @input="campo.type === 'tel' ? formData[campo.modelKey] = String(formData[campo.modelKey]).replace(/[^0-9]/g, '').slice(0, campo.max || 10) : null"
                                        class="modal-input w-full py-2.5 px-4 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] placeholder-[#523013a4] box-border rounded-t-md border-b-2 border-b-[#812c8f] text-sm md:text-base focus:outline-none">
                                </template>
                            </div>
                        </form>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-4 pt-4 border-t-2 border-[#a07a58] shrink-0">
                        <button type="button"
                            class="w-full sm:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-[1.02] hover:shadow-md hover:text-[#fcf7f2] hover:bg-[#b51f1f]"
                            @click="cerrarModal">Cerrar</button>
                        <button type="button"
                            class="w-full sm:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#812c8f] hover:scale-[1.02] hover:shadow-md hover:text-[#fcf7f2] hover:bg-[#812c8f]"
                            @click="aceptarModal">{{ isEdit ? 'Actualizar' : 'Guardar' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

/* Sincronizamos la flecha del select para que sea igual que en la barra de opciones */
select.modal-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23523013' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
    background-size: 0.8rem;
}

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