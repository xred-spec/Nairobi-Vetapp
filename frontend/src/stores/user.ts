import {defineStore } from 'pinia';
import {watch,reactive} from 'vue';
import {token} from './auth';
export const useUserStore = defineStore('user',()=>{
    const stored = localStorage.getItem('user');
    const {removeToken} = token();
    const getUser = reactive<Record<string,any>>(stored ? JSON.parse(stored):{});
    const setUser = (data:any)=>{
        Object.assign(getUser,data);
    };

    const isCliente = () => getUser.rol == 'cliente';
    const isTrabajador = () => getUser.rol == 'trabajador';
    const isAdmin = () => getUser.rol == 'administrador';

    watch(getUser,() => {
        localStorage.setItem('user',JSON.stringify(getUser));
    })
    return {setUser,getUser,removeToken, isCliente, isTrabajador, isAdmin};
});