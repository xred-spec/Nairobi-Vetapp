import { fetchWithVerifying } from './api2';

export const profileService = {
    getProfile() {
        return fetchWithVerifying('/user/get').get().json();
    },

    updateProfile(data: {
        nombre: string;
        telefono: string;
        email: string;
    }) {
        return fetchWithVerifying('/user/updatedatos', {
            body: JSON.stringify(data),
        }).patch().json();
    },

    updateDireccion(data: {
        municipio: string;
        colonia: string;
        codigo_postal: string;
        calle: string;
        numero_exterior: string;
    }) {
        return fetchWithVerifying('/user/updatecasa', {
            body: JSON.stringify(data),
        }).patch().json();
    },

    updatePassword(data: {
        password_actual: string;
        password: string;
    }) {
        return fetchWithVerifying('/user/password', {
            body: JSON.stringify(data),
        }).patch().json();
    },
};