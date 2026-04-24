<script setup lang="ts">
import { ref } from 'vue'
import { useFetch } from '@vueuse/core'

const baseURL:string = 'http://127.0.0.1:8000/api/v1/'
const nombre = ref<string>('')

const token:string = '8|w33lllxgoOro5SMYV9qOhcplg64wqCmAd74mIwKrda36d489'

const {data,isFetching,error,execute} = useFetch(`${baseURL}animal/store`,{
    headers:{
        'Accept':'application/json',
        'Content-type':'application/json',
        'Authorization':`bearer ${token}`
    },
},{immediate:false}).post(()=>({
    nombre:nombre.value
})).json()

async function enviar(){
const res = await execute()
console.log(data);
console.log(res);
}
</script>

<template>
  <div class="flex flex-col h-39 ml-10 mt-10" >
  <label for="nombre">Ingrese el nombre del animal</label>
    <input class="bg-amber-900 border h-10 w-50 border-gray-300 rounded px-1 py-1 focus:outline-none focus:ring focus:ring-blue-500"
    v-model="nombre" placeholder="Nombre animal" name="nombre">

    <button @click="enviar" :disabled="isFetching" class=" mt-3 h-10 w-40 py-2 bg-gray-900">
      {{ isFetching ? 'Enviando...' : 'Enviar' }}
    </button>
    <p v-if="error">{{error}}</p>
  </div>
</template>