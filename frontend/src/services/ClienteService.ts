import {api} from './api';
import { buildQuery } from './helpers';
export const clienteService = {

    getFullClientes(){
        return api('v1/user/full').get().json();
    },

    getClientes(page: number = 1, filtros = {}){
        const params = buildQuery({page, ...filtros});
        return api('/v1/user/index?' + params).get().json();
    },
    store(data:any){
        return api('/v1/auth/createUserirl', {
            body: JSON.stringify(data),
        }).post().json();
    }
}