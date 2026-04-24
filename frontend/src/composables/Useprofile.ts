import { ref } from 'vue';
import { profileService } from '@/services/PerfilService';
import { useToast } from './useToast';
import { useUserStore } from '@/stores/user';

const profile = ref<any>(null);
const loading = ref(false);

export function useProfile() {
    const { showToast, ToastStatus } = useToast();
    const userStore = useUserStore();

    const getProfile = async () => {
        try {
            loading.value = true;
           const result = await profileService.getProfile();
    profile.value = result.data.value?.data.data ?? null;
    return result;
        } catch {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando el perfil',
            });
        } finally {
            loading.value = false;
        }
    };

    const updateProfile = async (form: {
        nombre: string;
        telefono: string;
        email: string;
    }) => {
        try {
            loading.value = true;
            const { response, data } = await profileService.updateProfile(form);
            
            if (response.value?.ok) {
                const formLimpio = Object.fromEntries(
                    Object.entries(form).filter(([_, valor]) => valor !== '' && valor !== null)
                );

                profile.value = { ...profile.value, ...formLimpio };
                
                const userStorage = localStorage.getItem('user');
                if (userStorage) {
                    const userData = JSON.parse(userStorage);
                    const updatedUser = { ...userData, ...formLimpio };
                    localStorage.setItem('user', JSON.stringify(updatedUser)); 
                        const currentUser = userStore.getUser;
                    if (currentUser) {
                        userStore.setUser({
                            ...currentUser,
                            name: formLimpio.nombre || currentUser.name 
                        });
                    }
                }
                window.dispatchEvent(new Event('perfil-actualizado'));
                showToast({
                    status: ToastStatus.UPDATED,
                    customMessage: 'Perfil actualizado correctamente',
                });
                return true;
                
            } else {
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: data.value?.message ?? 'Error al actualizar',
                });
                return false;
            }
        } catch {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error al actualizar el perfil',
            });
            return false;
        } finally {
            loading.value = false;
        }
    };

    const updateDireccion = async (form: {
        municipio: string;
        colonia: string;
        codigo_postal: string;
        calle: string;
        numero_exterior: string;
    }) => {
        try {
            loading.value = true;
            const formLimpio = Object.fromEntries(
            Object.entries(form).filter(([_, valor]) => valor !== '' && valor !== null)
            ) as typeof form;
            const { response, data } = await profileService.updateDireccion(formLimpio);
            if (response.value?.ok) {
                profile.value = {
                    ...profile.value,
                    cliente: { 
                        ...(profile.value?.cliente || {}), 
                        ...formLimpio 
                    },
                };
                const userStorage = localStorage.getItem('user');
                if (userStorage) {
                    const userData = JSON.parse(userStorage);
                    const updatedUser = {
                        ...userData,
                        cliente: {
                            ...(userData.cliente || {}),
                            ...formLimpio
                        }
                    };
                    localStorage.setItem('user', JSON.stringify(updatedUser));
                }

                showToast({
                    status: ToastStatus.UPDATED,
                    customMessage: 'Dirección actualizada correctamente',
                });
                return true;
            } else {
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: data.value?.message ?? 'Error al actualizar dirección',
                });
                return false;
            }
        } catch (error) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error al actualizar la dirección',
            });
            return false;
        } finally {
            loading.value = false;
        }
    };

    const updatePassword = async (form: {
        password_actual: string;
        password: string;
    }) => {
        try {
            loading.value = true;
            const { response, data } = await profileService.updatePassword(form);
            if (response.value?.ok) {
                showToast({
                    status: ToastStatus.UPDATED,
                    customMessage: 'Contraseña actualizada correctamente',
                });
                return true;
            } else {
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: data.value?.message ?? 'Error al cambiar contraseña',
                });
                return false;
            }
        } catch {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error al cambiar la contraseña',
            });
            return false;
        } finally {
            loading.value = false;
        }
    };

    return {
        profile,
        loading,
        getProfile,
        updateProfile,
        updateDireccion,
        updatePassword,
    };
}