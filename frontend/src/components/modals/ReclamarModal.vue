<script setup lang="ts">
import { ref } from 'vue';
// @ts-ignore
import {registerValidator} from '@/validations/AuthValidation'
// @ts-ignore
import useAuth from '@/composables/useAuth';
// @ts-ignore
import {camposReclamar} from '@/data/CamposReclamar';
const props_ = defineProps<{
    type:string
}>();
const emit = defineEmits<{
    (e:'cerrar'):void;
}>();

const response = ref<any>(null);
const errors = ref<Record<string,string>>({});
const formData = ref<Record<string,any>>({});
camposReclamar.forEach((field: any) => {
    (formData.value as any)[field.modelKey] = "";
})
async function submit(){
  const result = registerValidator(formData.value as Record<string,string>);
  Object.keys(errors.value).forEach((key: string) => delete (errors.value as any)[key])
  Object.assign(errors.value, result.errors);
 if(result.isValid){
    const payload = {...formData.value};
    if(props_.type=="reclamar"){
         response.value = await useAuth().reclamarCuenta(payload);
    }else if(props_.type=="verificar"){
         response.value = await useAuth().generatetoken(payload);
    }else if(props_.type=="recuperar"){
        response.value = await useAuth().RecuperarPassword(payload);
    }
    if(response?.value?.statusCode?.value == 200){
        emit('cerrar');
    }else{
        (errors.value as any)['responseError']=response?.value?.data?.message
    }
 }
}
</script>

<template>
  <div class="modal-fondo fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center px-4">
    
    <div class="bg-[#e2d2c4] rounded-[20px] p-5 md:p-7 shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col w-[90%] md:w-[50%] lg:w-[30%] max-h-[90vh] overflow-y-auto">
      
      <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] mb-3 text-center md:text-left">Ingresa los datos de tu cuenta</h2>
      
      <form @submit.prevent>
        <p class="text-[#b51f1f] block text-sm font-bold text-center md:text-left">{{(errors as any)?.responseError}}</p>
        
        <div v-for="field in camposReclamar" :key="field.modelKey">
          <div>
            <label :for="field.modelKey" class="block text-[#523013] mb-1 md:mb-[6px] font-bold mt-2 text-sm md:text-base">{{field.label}}</label>
            <p class="text-[#b51f1f] block text-xs md:text-sm font-bold">{{(errors as any)?.[field.modelKey]}}</p>
            <input :type="field.type" :name="field.modelKey" :placeholder="field.placeholder" v-model="formData[field.modelKey]"
              class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] focus:outline-none text-sm md:text-base">
          </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-[15px] justify-center mt-5 pt-4 border-t-2 border-[#a07a58]">
          <button type="button" @click="submit" class="w-full sm:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#812c8f] hover:scale-105 hover:shadow-2xl hover:text-[#fcf7f2] hover:bg-[#812c8f]">Enviar</button>
          <button type="button" @click="emit('cerrar')" class="w-full sm:flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-105 hover:shadow-2xl hover:text-[#fcf7f2] hover:bg-[#b51f1f]">Cerrar</button>
        </div>
      </form>
      
    </div>
  </div>
</template>

<style scoped>
</style>
