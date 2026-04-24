import { api } from './api';

export const HorarioService = {
    getHorarios() {
        return api('/v1/horarios').get().json();
    }
};