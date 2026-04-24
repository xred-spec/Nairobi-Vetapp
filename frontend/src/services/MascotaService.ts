import {api} from './api';
import { buildQuery } from './helpers';
export const mascotaService = {
    getMascotas(filters: any = {}, page: number = 1){
        const params = buildQuery({page, ...filters});
        return api('/v1/mascotas?' + params).get().json();
    },

    getMascotasVisibles(){
        return api('/v1/mascotas/visibles').get().json();
    },
    store(data:any){
        return api('/v1/mascotas',{
            body: JSON.stringify(data),
        }).post().json();
    },
    getMascota(id:number){
        return api('/v1/mascotas/' + id).get().json();
    },
    update(id:number,data:any){
        return api('/v1/mascotas/' + id,{
            body: JSON.stringify(data),
        }).put().json();
    },
    cambiarEstado(id:number){
        return api('/v1/mascotas/cambiar-estado/' + id).put().json();
    },
    delete(id:number){
        return api('/v1/mascotas/' + id).delete().json();
    }
};