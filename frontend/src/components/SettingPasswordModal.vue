<script setup lang="ts">
import {ref,reactive} from 'vue';
import {registerValidator} from '@/validations/AuthValidation';
import {useRoute} from 'vue-router';
import useAuth from '@/composables/useAuth';

const emit = defineEmits<{
    (e:'success'):void,
    (e:'error'):void
}>();

const route = useRoute();
const data = ref<any>(null);
const formData = reactive<Record<string,string>>({});
const errors = reactive<Record<string,string>>({});
formData['password']='';
formData['passwordConfirmation']='';

async function sendPassword(){
    const result = registerValidator(formData);
    Object.keys(errors).forEach(key => delete errors[key])
    Object.assign(errors, result.errors);
    if(result.isValid){
        const payload = {...formData};
        data.value=await useAuth().setpassword(payload,route.query.token as string);
       if(data.value.statusCode==200){
            emit('success');
        }else{
            emit('error');
        }
    }
}
</script>

<template>
    <div class="fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center px-4">
        
        <div class="bg-[#eddcce] rounded-[20px] px-6 md:px-8 py-6 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex justify-center flex-col w-full max-w-sm md:max-w-md h-auto">
            
            <h2 class="text-xl md:text-2xl text-center text-[#523013] font-extrabold mb-6">Ingresa tu contraseña 0w0</h2>
            
            <form class="flex flex-col gap-4 w-full" @submit.prevent>
                
                <div class="flex flex-col gap-1">
                    <label for="password" class="text-sm md:text-base text-[#523013] font-medium">Ingresa tu contraseña</label>
                    <input v-model="formData['password']" type="password" name="password" placeholder="********"
                           class="w-full px-4 py-3 rounded-md bg-[#fcf7f2] border border-transparent focus:border-[#812c8f] outline-none text-[#523013] transition-all text-sm md:text-base">
                    <span v-if="errors['password']" class="text-xs md:text-sm text-[#b51f1f] font-semibold">{{errors['password']}}</span>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="passwordConfirmation" class="text-sm md:text-base text-[#523013] font-medium">Repite tu contraseña</label>
                    <input v-model="formData['passwordConfirmation']" type="password" name="passwordConfirmation" placeholder="********"
                           class="w-full px-4 py-3 rounded-md bg-[#fcf7f2] border border-transparent focus:border-[#812c8f] outline-none text-[#523013] transition-all text-sm md:text-base">
                    <span v-if="errors['passwordConfirmation']" class="text-xs md:text-sm text-[#b51f1f] font-semibold">{{errors['passwordConfirmation']}}</span>
                </div>

                <button type="submit" @click="sendPassword"
                        class="w-full py-3 mt-4 flex items-center font-extrabold justify-center text-base md:text-lg rounded-md bg-[#fcf7f2] text-[#812c8f] hover:scale-105 cursor-pointer shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f] transition-all">
                    <span class="px-1">Enviar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send ml-1">
                        <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                    </svg>
                </button>
            </form>

        </div>
    </div>
</template>

<style scoped>
    .onError{
        color: #b51f1f;
    }
    .onErrorMessage{
        margin-top: 8px;
        font-size: 20px;
    }
</style>