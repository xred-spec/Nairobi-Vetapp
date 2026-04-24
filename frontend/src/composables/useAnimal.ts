import { animalService } from "@/services/animalService";
import { useToast } from '../composables/useToast';
import { ref } from "vue";
import { Animal } from "@/Types/Animal";
const animales = ref<any>([]);
const animalList = ref<any[]>([]);
const loading = ref<boolean>(false);
const filtros = ref<Animal>({
    nombre: '',
    visibilidad: '',
});
export function useAnimal() {
    const paginacion = ref<any>({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
    });

    const { showToast, ToastStatus, showToastFromCode } = useToast();

    const getFullList = async () => {
        try {
            const { data } = await animalService.fullAnimales();
            animalList.value = data.value?.data ?? [];
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando los animales'
            });
        }
    }

    const obtenerAnimales = async (page: number = 1, filtros = {}) => {
        try {
            loading.value = true;
            const { data } = await animalService.getAnimales(page, filtros);
            if(data.value?.status === 403){
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: 'No tienes permiso para ver los animales'
                });
            }

            animales.value = data.value?.data?.data ?? [];

            paginacion.value = data.value?.data?.paginacion ?? { current_page: 1, last_page: 1 };

            if(animales.value.length === 0 && paginacion.value.current_page > 1){
                await obtenerAnimales(paginacion.value.current_page - 1, filtros);
            }
            return true;
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando los animales'
            });
            return false;
        } finally {
            loading.value = false;
        }
    }

    const eliminarAnimal = async (id: number, page: number = 1) => {
        let success = false;
        try {
            const { response } = await animalService.delete(id);
            showToastFromCode(response.value?.status ?? 500, 'animal');

            if (response.value?.ok) {
                success = await obtenerAnimales(page, filtros.value);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando los animales'
            });
        } finally {
            return success;
        }
    }

    const cambiarEstadoAnimal = async (id: number, page: number = 1) => {
        try {
            const { response } = await animalService.cambiarEstado(id);
            if (response.value?.ok) {
                showToast({
                    status: ToastStatus.STATUS_CHANGED,
                    itemName: 'animal'
                });

                await obtenerAnimales(page, filtros.value);
            }else{
                showToastFromCode(response.value?.status ?? 500, 'animal');
            }


        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cambiando estado'
            });
        }
    }

    const storeAnimal = async (data: any, page: number = 1) => {
        let success = false;
        try {
            const { response } = await animalService.store(data);
            showToastFromCode(response.value?.status ?? 500, 'animal');
            if (response.value?.ok) {
                success = await obtenerAnimales(page, filtros.value);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error creando animal'
            });
        } finally {
            return success;
        }
    }

    const editarAnimal = async (id: number, data: any, page: number = 1) => {
        try {
            const { response } = await animalService.update(id, data);

            showToastFromCode(response.value?.status ?? 500, 'animal');

            if (response.value?.ok) {
                await obtenerAnimales(page, filtros.value);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error actualizando animal'
            });
        }
    }

    return {
        animales,
        loading,
        animalList,
        paginacion,
        filtros,
        obtenerAnimales,
        eliminarAnimal,
        cambiarEstadoAnimal,
        storeAnimal,
        editarAnimal,
        getFullList
    }
}
