import {api} from './api';
import { buildQuery } from './helpers';
export const razaService = {
    getRazas(page: number = 1, filtros: any = {}){
        const params = buildQuery({page, ...filtros});
        return api(`/v1/raza/raza?${params}`).get().json();
    },
    store(data:any){
        return api('/v1/raza/raza',{
            body: JSON.stringify(data),
        }).post().json();
    },
    update(id:number,data:any){
        return api('/v1/raza/raza/' + id,{
            body: JSON.stringify(data),
        }).patch().json();
    },
    delete(id:number){
        return api('/v1/raza/raza/' + id).delete().json();
    },
    cambiarEstado(id:number){
        return api('/v1/raza/cambiar-estado/' + id).patch().json();
    },
    fullRazas(){
        return api('/v1/raza/full').get().json();
    }
};
