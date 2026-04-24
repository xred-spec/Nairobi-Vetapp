import {api} from './api';
import { buildQuery } from './helpers';
export const TrabajadorService = {
    getTrabajadores(page: number = 1, filtros = {}) {
        const params = buildQuery({page, ...filtros});
        return api('/v1/trabajador/trabajadores?' + params).get().json();
    },

    getTrabajador(id: number) {
        return api('/v1/trabajador/trabajadores/' + id).get().json();
    },

    update(id: number, data: any) {
        return api('/v1/trabajador/trabajadores/' + id, {
            body: JSON.stringify(data),
        }).put().json(); 
    },

    syncHorarios(id: number, horarios: number[]) {
        return api('/v1/trabajador/trabajadores/' + id + '/horarios', {
            body: JSON.stringify({ horarios: horarios }), 
        }).put().json(); 
    },

    desactivar(id: number) {
        return api('/v1/trabajador/trabajadores/' + id + '/desactivar').delete().json();
    },

    activar(id: number) {
        return api('/v1/trabajador/trabajadores/' + id + '/activar').put().json(); 
    },

    store(data: any) {
        
        return api('/v1/auth/createTrabajador', {
            body: JSON.stringify(data),
        }).post().json();
    },

    getHorarios() {
    return api('/v1/trabajador/horarios').get().json(); 
    }
}