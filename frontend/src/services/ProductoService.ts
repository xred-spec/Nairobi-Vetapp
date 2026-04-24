import {api} from './api';
import { buildQuery } from './helpers';
export const ProductoService = {
    getProductos(page: number = 1, filtros = {}){
        const params = buildQuery({page, ...filtros});
        return api(`/v1/producto/index?${params}`).get().json();
    },
    search(data:any){
        return api('/v1/producto/search',{
            body: JSON.stringify(data),
        }).post().json();
    },
    store(data:any){
        return api('/v1/producto/store',{
            body: JSON.stringify(data),
        }).post().json();
    },
    update(id:number,data:any){
        return api('/v1/producto/update/' + id,{
            body: JSON.stringify(data),
        }).patch().json();
    },

    delete(id:number){
        return api('/v1/producto/delete/' + id).delete().json();
    }
}