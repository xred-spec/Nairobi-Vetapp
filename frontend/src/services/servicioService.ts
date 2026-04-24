import { api } from './api';
import { buildQuery } from './helpers';
export const servicioService = {
    getServicios(page: number = 1, filtros = {}) {
        const params = buildQuery({page, ...filtros});
        return api(`/v1/servicio/index?${params}`).get().json();
    },
    store(data: any) {
        return api('/v1/servicio/store', {
            body: JSON.stringify(data),
        }).post().json();
    },
    update(id: number, data: any) {
        return api('/v1/servicio/update/' + id, {
            body: JSON.stringify(data),
        }).patch().json();
    },
    delete(id: number) {
        return api('/v1/servicio/delete/' + id).delete().json();
    },
    cambiarEstado(id: number) {
        return api('/v1/servicio/cambiar-estado/' + id).put().json();
    },
    fullServicios() {
        return api('/v1/servicio/full').get().json();
    }
}