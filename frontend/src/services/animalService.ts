import {api} from './api';
import { buildQuery } from './helpers';
export const animalService = {
    getAnimales(page: number = 1, filtros = {}){
        const params = buildQuery({page, ...filtros});
        return api(`/v1/animal/index?${params}` ).get().json();
    },
    store(data:any){
        return api('/v1/animal/store',{
            body: JSON.stringify(data),
        }).post().json();
    },
    update(id:number,data:any){
        return api('/v1/animal/update/' + id,{
            body: JSON.stringify(data),
        }).patch().json();
    },
    delete(id:number){
        return api('/v1/animal/delete/' + id).delete().json();
    },
    cambiarEstado(id:number){
        return api('/v1/animal/cambiar-estado/' + id).put().json();
    },
    fullAnimales(){
        return api('/v1/animal/full').get().json();
    }
};
