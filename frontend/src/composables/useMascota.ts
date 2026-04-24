import { mascotaService } from "@/services/MascotaService";
import { useToast } from './useToast';
import { useRaza } from './useRaza';
import { ref, computed } from "vue";
import { useCliente } from "./useCliente";

const mascotas = ref<any[]>([]);
const loading = ref<boolean>(false);
const filtros = ref<any>({});

export function useMascota() {
    const paginacion = ref({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
    });

    const { showToast, ToastStatus, showToastFromCode } = useToast();
    const { razaList, animalList, getFullListRazas, getFullList } = useRaza();
    const { clientesList, getFullClientes } = useCliente();

    const animalesConRazas = computed(() => {
        if (!animalList.value.length) return [];
        return animalList.value.filter(animal => {
            return razaList.value.some(raza => raza.animal_id === animal.id);
        });
    });

    const filtrarRazas = (animalId: any) => {
        if (!animalId) return [];
        return razaList.value.filter((r: any) => Number(r.animal_id) === Number(animalId));
    };

    const obtenerMascotas = async (page: number = 1) => {
        try {
            loading.value = true;

            const { data } = await mascotaService.getMascotas(filtros.value, page);
            const response = data.value?.data;

            mascotas.value = response?.data ?? [];


            paginacion.value = {
                current_page: response?.current_page ?? 1,
                last_page: response?.last_page ?? 1,
                per_page: response?.per_page ?? 15,
                total: response?.total ?? 0
            };

            if (mascotas.value.length === 0 && paginacion.value.current_page > 1) {
                await obtenerMascotas(paginacion.value.current_page - 1);
            }
            return true;
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando las mascotas'
            });
            return false;
        } finally {
            loading.value = false;
        }
    };


    const storeMascota = async (data: any) => {
        let success = false;
        try {
            const { response } = await mascotaService.store(data);
            showToastFromCode(response.value?.status ?? 500, 'mascota');

            if (response.value?.ok) {
                success = await obtenerMascotas(1);
            }
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error creando mascota' });
        } finally {
            return success;
        }
    };


    const editarMascota = async (id: number, data: any) => {
        try {
            const { response } = await mascotaService.update(id, data);
            showToastFromCode(response.value?.status ?? 500, 'mascota');

            if (response.value?.ok) {
                await obtenerMascotas(paginacion.value.current_page);
            }
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error actualizando mascota' });
        }
    };


    const eliminarMascota = async (id: number) => {
        let success = false;
        try {
            const { response } = await mascotaService.delete(id);
            showToastFromCode(response.value?.status ?? 500, 'mascota');

            if (response.value?.ok) {
                success = await obtenerMascotas(paginacion.value.current_page);
            }
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error eliminando mascota' });
        } finally {
            return success;
        }
    };

    const cambiarEstadoMascota = async (id: number) => {
        try {
            const { response } = await mascotaService.cambiarEstado(id);
            if (response.value?.ok) {
                showToast({ status: ToastStatus.STATUS_CHANGED, itemName: 'mascota' });
                await obtenerMascotas(paginacion.value.current_page);
            }
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al cambiar visibilidad' });
        }
    };

    const configurarCamposMascota = (camposBase: any[], userStore: any) => {
        return camposBase
            .filter(campo => !(userStore.isCliente() && campo.modelKey === 'cliente_id'))
            .map(campo => {
                const nuevoCampo = { ...campo };

                if (nuevoCampo.modelKey === 'animal_id') {
                    nuevoCampo.options = animalesConRazas.value.map((a: any) => ({ label: a.nombre, value: a.id }));
                }

                if (nuevoCampo.modelKey === 'cliente_id') {
                    nuevoCampo.options = clientesList.value.map((c: any) => ({
                        label: c.user?.nombre ?? c.nombre,
                        value: c.id
                    }));
                }

                if (nuevoCampo.modelKey === 'raza_id') {
                    nuevoCampo.options = razaList.value.map((r: any) => ({ label: r.nombre, value: r.id }));
                    nuevoCampo.dependsOn = 'animal_id';
                    nuevoCampo.filterFn = (animalId: any) => {
                        return filtrarRazas(animalId).map((r: any) => ({ label: r.nombre, value: r.id }));
                    };
                }

                return nuevoCampo;
            });
    };

    return {
        mascotas,
        loading,
        paginacion,
        filtros,
        clientesList,
        razaList,
        animalList,
        animalesConRazas,
        configurarCamposMascota,
        obtenerMascotas,
        storeMascota,
        editarMascota,
        eliminarMascota,
        cambiarEstadoMascota,
        getFullList,
        getFullListRazas,
        getFullClientes
    };
}