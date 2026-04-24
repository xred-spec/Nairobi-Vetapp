import { razaService } from "@/services/RazaService";
import { useToast } from '../composables/useToast';
import { ref, computed } from "vue";
import { useAnimal } from "./useAnimal";

const razas = ref<any[]>([]);
const loading = ref<boolean>(false);
const razaList = ref<any[]>([]);
const filtros = ref<Object>({
    nombre: '',
    visibilidad: '',
});

export function useRaza() {
    const paginacion = ref<any>({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
    });

    const {
        animalList,
        getFullList
    } = useAnimal();


    const { showToast, ToastStatus, showToastFromCode } = useToast();

    const animalesConRazas = computed(() => {
        if(!animalList.value.length || !razaList.value.length) return [];

        return animalList.value.filter( animal => {
            return razaList.value.some(raza => raza.animal_id === animal.id);
        });

    });

    const getFullListRazas = async () => {
        try {
            const { data } = await razaService.fullRazas();
            razaList.value = data.value?.data ?? [];
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Ocurrio un error cargando las razas'
            });
        }
    };


    const obtenerRazas = async (page: number = 1, filtros = {}) => {
        try {
            loading.value = true;
            const { data } = await razaService.getRazas(page, filtros);
            if (data.value?.status === 403) {
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: 'No tienes permiso para ver las razas'
                });
            }   
            razas.value = data.value?.data?.data ?? [];

            paginacion.value = data.value?.data?.paginacion ?? { current_page: 1, last_page: 1 };

            if (razas.value.length === 0 && paginacion.value.current_page > 1) {
                await obtenerRazas(paginacion.value.current_page - 1, filtros);
            }
            return true;
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Ocurrió un error cargando las razas'
            });
            return false;
        } finally {
            loading.value = false;
        }
    }

    const guardarRaza = async (data: Record<string, any>, page: number = 1) => {
        let success = false;
        try {
            loading.value = true;
            const { response } = await razaService.store(data);
            showToastFromCode(response.value?.status ?? 500, 'raza');
            success = await obtenerRazas(page, filtros.value);
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Ocurrió un error creando raza'
            });
        } finally {
            loading.value = false;
            return success;
        }
    }

    const editarRaza = async (id: number, data: Record<string, any>, page: number = 1) => {
        try {
            loading.value = true;
            const { response } = await razaService.update(id, data);
            showToastFromCode(response.value?.status ?? 500, 'raza');
            if (response.value?.ok) {
                await obtenerRazas(page, filtros.value);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Ocurrió un error actualizando raza'
            });
        } finally {
            loading.value = false;
        }
    }

    const alternarVisibilidad = async (id: number, page: number = 1) => {
        try {
            loading.value = true;
            const { response } = await razaService.cambiarEstado(id);
            if (response.value?.ok) {
                showToast({
                    status: ToastStatus.STATUS_CHANGED,
                    itemName: 'raza'
                });

                await obtenerRazas(page, filtros.value);
            } else {
                showToastFromCode(response.value?.status ?? 500, 'raza');
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Ocurrió un error actualizando raza'
            });
        } finally {
            loading.value = false;
        }
    }

    const eliminarRaza = async (id: number, page: number = 1) => {
        let success = false;
        try {
            loading.value = true;
            const { response } = await razaService.delete(id);
            showToastFromCode(response.value?.status ?? 500, 'raza');
            success = await obtenerRazas(page, filtros);
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Ocurrió un error eliminando la raza'
            });
        } finally {
            loading.value = false;
            return success;
        }
    }

    return {
        razas,
        loading,
        animalList,
        razaList,
        paginacion,
        filtros,
        animalesConRazas,
        obtenerRazas,
        guardarRaza,
        editarRaza,
        alternarVisibilidad,
        eliminarRaza,
        getFullList,
        getFullListRazas
    }
}