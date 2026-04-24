<script setup lang="ts">
    import {User} from '@/data/RegistroCliente';
    import {registerValidator} from '@/validations/AuthValidation'
    import { ref,reactive } from 'vue';
    import useAuth from '@/composables/useAuth';
    
    const props = defineProps<{
        required : boolean
    }>();

    const emit = defineEmits<{
        (e:'success'):void
    }>();

    const fields = User(props.required).filter(function(value){
        return value.required === true;
    });
    const errors = reactive<Record<string,string>>({});
    const formData = reactive<Record<string,any>>({});

    const response = ref<any>(null)
    fields.forEach(field => {
        formData[field.modelKey] = '';
    })

    async function submit(){
        const result = registerValidator(formData);
        Object.keys(errors).forEach(key => delete errors[key])
        Object.assign(errors, result.errors);
 
        if(result.isValid){
            const payload = {...formData};
            response.value = await useAuth().createUserDigital(payload);
            if(response.value.statusCode == 200){
                emit('success');
            }
        }
    }
</script>

<template>
    <div class="flex flex-col md:flex-row-reverse w-full max-w-5xl bg-[#eddcce] border-2 border-[#523013] rounded-[20px] overflow-hidden drop-shadow-2xl">
        
        <div class="flex flex-col justify-center py-8 md:py-0 px-8 bg-[#523013] text-[#fcf7f2] md:w-2/5">
            <h1 class="text-center font-extrabold text-3xl md:text-4xl mb-2">Nairobi veterinaria</h1>
            <h2 class="text-center font-bold text-lg md:text-2xl">Ellos no pueden hablar. Déjanos hablar por ellos.</h2>
        </div>

        <div class="flex flex-col justify-center px-6 md:px-10 py-8 bg-[#eddcce] md:w-3/5">
            <h2 class="text-2xl md:text-3xl text-[#523013] font-extrabold text-center mb-6">Crear cuenta</h2>
            
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                    <div v-for="field in fields" :key="field.modelKey" class="mb-3">
                        <div>
                            <label :for="field.modelKey" class="block text-[#523013] mb-1 font-bold text-sm md:text-base">{{field.label}}</label>
                            <p class="text-[#b51f1f] block text-xs md:text-sm font-bold">{{errors[field.modelKey]}}{{response?.data?.errors?.[field.modelKey]?.[0]}}</p>
                            <input :type="field.type" :name="field.modelKey" :placeholder="field.placeholder" v-model="formData[field.modelKey]"
                            class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] focus:outline-none text-sm md:text-base">
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="w-full py-3 flex items-center font-extrabold justify-center text-lg md:text-xl rounded-md bg-[#fcf7f2] text-[#812c8f] hover:scale-105 cursor-pointer shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f] transition-all">
                        <span class="px-1">Registrar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus ml-1"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>  
                </div>
            </form>
        </div>
        
    </div>    
</template>

<style scoped>
</style>