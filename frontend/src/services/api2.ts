import { createFetch } from '@vueuse/core';
import {token} from '@/stores/auth';

import router from '@/router';
//const baseUrl = 'http://localhost:8000/api/v1';
const baseUrl = 'https://vetbackend.shop/api/v1';

export const fetchWithoutVerifying  = createFetch({
    baseUrl:baseUrl,
    combination: 'chain', 
    fetchOptions: {
        headers:{
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    },
    options:{
        updateDataOnError:true,
    }
});

export const fetchWithVerifying  = createFetch({
    baseUrl:baseUrl,
    combination: 'overwrite', 
    fetchOptions: {
        headers:{
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    },options:{
        updateDataOnError:true,
        async beforeFetch({ options,cancel  }) {
            const authToken = token();
            if(!authToken.getToken()){
                if(router.currentRoute.value.name !== 'login') router.push({name:'login'});
                cancel();
            };
            options.headers = {
                ...options.headers,
                Authorization: `Bearer ${authToken.getToken()}`,
            };
            return { options };
        },
        async onFetchError(ctx) {
            if(ctx.response?.status==401){
                const authToken = token();
                authToken.removeToken();
                if(router.currentRoute.value.name !== 'login') router.push({name:'login'});
            }
            return ctx ;
        }
    }
});


