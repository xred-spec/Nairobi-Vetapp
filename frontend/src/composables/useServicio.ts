import { servicioService } from "@/services/servicioService";
import { useToast } from '../composables/useToast';
import { ref } from "vue";
import { Servicio } from "@/Types/Servicio";
const servicios = ref<any>([]);
const servicioList = ref<any[]>([]);
const loading = ref<boolean>(false);
const filtros = ref<Servicio>({
    nombre: '',
    visibilidad: '',
});
export function useServicio() {
    
    const paginacion = ref<any>({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
    });

    const { showToast, ToastStatus, showToastFromCode } = useToast();

    const getFullList = async () => {
        try {
            const { data } = await servicioService.fullServicios();
            servicioList.value = data.value?.data.data?? [];
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando los servicios'
            });
        }
    }

    const obtenerServicios = async (page: number = 1) => {
        try {
            loading.value = true;
            const { data } = await servicioService.getServicios(page, filtros.value);
            if (data.value?.status === 403) {
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: 'No tienes permiso para ver los servicios'
                });
            }
        
            servicios.value = data.value?.data?.data ?? [];
            paginacion.value = data.value?.data?.paginacion ?? { current_page: 1, last_page: 1 };

            if (servicios.value.length === 0 && page > 1) {
                await obtenerServicios(page - 1);
            }
            return true;
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando los servicios'
            });
            return false;
        } finally {
            loading.value = false;
        }
    }

    const eliminarServicio = async (id: number, page: number = 1) => {
        let success = false;
        try {
            const { response } = await servicioService.delete(id);
            showToastFromCode(response.value?.status ?? 500, 'servicio');

            success = await obtenerServicios(page);
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error eliminando el servicio'
            });
        } finally {
            loading.value = false;
        
            return success;
        }
    }

    const cambiarEstadoServicio = async (id: number, page: number = 1) => {
        try {
            const { response } = await servicioService.cambiarEstado(id);
            if (response.value?.ok) {
                showToast({
                    status: ToastStatus.STATUS_CHANGED,
                    itemName: 'servicio'
                });
                await obtenerServicios(page);
            } else {
                showToastFromCode(response.value?.status ?? 500, 'servicio');
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cambiando estado'
            });
        }
    }

    const storeServicio = async (data: any, page: number = 1) => {
        let success = false;
        try {
            const { response } = await servicioService.store(data);
            showToastFromCode(response.value?.status ?? 500, 'servicio');
            if (response.value?.ok) {
                success = await obtenerServicios(page);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error creando servicio'
            });
        } finally {
            loading.value = false;
            return success;
        }
    }

    const editarServicio = async (id: number, data: any, page: number = 1) => {
        try {
            const { response } = await servicioService.update(id, data);
            showToastFromCode(response.value?.status ?? 500, 'servicio');
            if (response.value?.ok) {
                await obtenerServicios(page);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error actualizando servicio'
            });
        }
    }

    return {
        servicios,
        loading,
        servicioList,
        paginacion,
        filtros,
        obtenerServicios,
        eliminarServicio,
        cambiarEstadoServicio,
        storeServicio,
        editarServicio,
        getFullList
    }
}