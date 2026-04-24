<script setup lang="ts">
import ReclamarModal from '@/components/modals/ReclamarModal.vue';
import { ref,computed,onMounted } from 'vue';

import {token} from '@/stores/auth';
//import {useRouter} from 'vue-router';
//import {loginForm} from '@/data/loginForm';
//import AuthService from '@/services/AuthService';
import {useUserStore} from '@/stores/user';
// import router from '@/router';
import { useRouter } from 'vue-router';
import {fetchWithoutVerifying, fetchWithVerifying} from '@/services/api2';
import Loader from '@/components/Loader.vue';

const router = useRouter();

const visibility = ref<boolean>(false);
const type = ref<string>('');
const authStore = token(); 
const UserStore = useUserStore();
const campo = ref<string>('');
const password = ref<string>('');
const CampoError = ref<boolean>(false);
const PasswordError = ref<boolean>(false);
const name = ref<string>('');
const form = computed<any>(() => ({
    [name.value]: campo.value,
    password: password.value
}));

const loaderState = ref<any>(null);

onMounted(async () => {
  const automaticLogin = await fetchWithVerifying('auth/user').get().json();

  if (automaticLogin.statusCode.value == 200) {
    loaderState.value = 'loading';
    const user = automaticLogin.data.value.data;

    UserStore.setUser({
      name: user.nombre,
      rol: user.rol.nombre
    });
    router.push({ name: 'main' });
  }
});

const loginService = ref<any>(null);
async function loginFuncion(){
    loginService.value = await fetchWithoutVerifying('auth/login').post(form).json();
    if(loginService.value.statusCode==200){
        loaderState.value = 'loading';
        authStore.setToken(loginService.value.data.data.token);
        UserStore.setUser({
            name:loginService.value.data.data.user.nombre,
            rol:loginService.value.data.data.user.rol.nombre,
            id:loginService.value.data.data.user.id
        })
        await router.push({name:'main'});
    }
}

 function validator(){
    if(regex()){
        campo.value = campo.value.toLowerCase();
        loginFuncion();
    }
}

function showModal(value:string){
    type.value=value;
    visibility.value=true
}
function regex():boolean{
    PasswordError.value = false;
    CampoError.value=false;
    const EmailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const TelefonoPattern = /^[0-9]{10}$/;
    if(!EmailPattern.test(campo.value) && !TelefonoPattern.test(campo.value)){
        CampoError.value=true;
        if(password.value.length <= 8){
        PasswordError.value = true;
        }
       return false;
    }else{
        CampoError.value = false;
    }
      if(password.value.length <= 8){
        PasswordError.value = true;
        return false;
        }

    if(EmailPattern.test(campo.value) && password.value.length >= 8){
         CampoError.value = false;
         name.value = 'email';
        return true;
    }
    
    if(TelefonoPattern.test(campo.value) && password.value.length >= 8){
        CampoError.value = false;
        name.value = 'telefono';
        return true;
    }
    PasswordError.value = true;
    return false;
}

</script>

<template>
    <div v-if="loaderState !== null" class="modal-fondo fixed inset-0 bg-black/40 z-[999] backdrop-blur-sm transition-all duration-300 flex items-center justify-center">
        <Loader :state="loaderState" />
    </div>

    <div class="bg-[#eddcce] w-screen min-h-screen flex relative items-center justify-center p-4">
        
        <div class="wrapper-login flex flex-col md:flex-row items-stretch justify-center drop-shadow-2xl w-full max-w-4xl"> 
            
            <div class="left-wrapper flex flex-col justify-center py-8 md:py-0 px-8 bg-[#523013] rounded-t-[20px] md:rounded-t-none md:rounded-l-[20px] md:w-1/2">
                <div class="header-margin text-[#fcf7f2] items-center justify-center">
                    <h1 class="text-center text-[#fcf7f2] font-extrabold text-3xl md:text-4xl mb-2">Nairobi veterinaria</h1>
                    <h2 class="text-center text-[#fcf7f2] font-bold text-lg md:text-2xl">Ellos no pueden hablar. Déjanos hablar por ellos.</h2>
                </div> 
            </div>

            <div class="flex flex-col w-full md:w-1/2">
                
                <div class="right-wrapper px-6 md:px-10 py-8 pb-5 bg-[#eddcce] border-x-2 border-t-0 md:border-2 md:border-l-0 md:border-b-0 border-[#523013] md:rounded-tr-[20px] flex-grow">
                    <div class="header-right mb-6"> 
                        <h2 class="text-2xl md:text-3xl text-[#523013] font-extrabold text-center">Inicio de sesión</h2>
                        <h3 class="block mt-2 md:mt-4 font-medium text-[#523013] text-sm text-center">Inicia sesión con los datos que ingresaste durante tu registro</h3>
                    </div> 
                    
                    <form @submit.prevent="validator">
                        <div class="mb-4">
                            <label for="campo" class="block text-[#523013] mb-1 font-bold text-sm md:text-base">Ingresa tu correo o teléfono</label>
                            <p v-if="loginService?.data?.error" class="text-[#b51f1f] block text-xs md:text-sm">{{loginService?.data?.message}}</p>
                            <span v-if="CampoError" class="text-[#b51f1f] block font-bold text-xs md:text-sm">Telefono o correo no validos</span>
                            <input class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] focus:outline-none text-sm md:text-base" type="text" name="campo" v-model="campo" placeholder="example@example.com">
                        </div>
               
                        <div class="mb-4">
                            <label for="password" class="block text-[#523013] mb-1 font-bold text-sm md:text-base">Ingresa tu contraseña</label>
                            <span v-if="PasswordError" class="text-[#b51f1f] block font-bold text-xs md:text-sm">El campo es incorrecto</span>
                            <input class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] focus:outline-none text-sm md:text-base" type="password" name="password" v-model="password" placeholder="Ingresa tu contraseña">
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 text-xs md:text-sm gap-2 sm:gap-0">
                            <span class="cursor-pointer font-bold text-[#b51f1f] hover:underline" @click="showModal('reclamar')">Reclamar cuenta</span>
                            <span class="cursor-pointer font-bold text-[#b51f1f] hover:underline" @click="showModal('recuperar')">¿Olvidaste tu contraseña?</span>
                        </div>
                        
                        <div class="flex justify-center">
                            <button type="submit" class="w-full py-3 flex items-center font-extrabold justify-center text-lg md:text-xl rounded-md bg-[#fcf7f2] text-[#812c8f] hover:scale-105 cursor-pointer shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f] transition-all">
                                <span class="px-1">Iniciar sesión</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="ml-1"><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/></svg>
                            </button>
                        </div>
                    </form> 
                </div> 
                
                <div class="bottom-wrapper px-6 md:px-10 py-6 bg-[#eddcce] border-2 border-t md:border-l-0 border-[#523013] rounded-b-[20px] md:rounded-bl-none md:rounded-br-[20px] flex flex-col gap-3">
                    <button @click="showModal('verificar')" class="w-full py-2.5 md:py-3 flex items-center font-extrabold justify-center text-sm md:text-lg rounded-md bg-[#fcf7f2] text-[#b51f1f] hover:scale-105 cursor-pointer shadow-lg hover:text-[#fcf7f2] hover:bg-[#b51f1f] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                        <span>Verificar correo</span>
                    </button>           
                    
                    <RouterLink to="/registro" class="w-full">
                        <button class="w-full py-2.5 md:py-3 flex items-center font-extrabold justify-center text-sm md:text-lg rounded-md bg-[#fcf7f2] text-[#812c8f] hover:scale-105 cursor-pointer shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            <span>Crear cuenta nueva</span>
                        </button>
                    </RouterLink>
                </div>
                
            </div>  
        </div>

        <div v-if="visibility" class="absolute inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
           <ReclamarModal :type="type" @cerrar="visibility=false"/>
        </div>
    </div>
</template>


<style scoped>
   

</style>