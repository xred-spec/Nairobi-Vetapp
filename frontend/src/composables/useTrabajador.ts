import { ref } from 'vue';
import { TrabajadorService } from '@/services/TrabajadorService';
import { useToast } from '@/composables/useToast';

const trabajadores = ref<any[]>([]);
const filtros = ref<any>({
    search: '',
});
const loading = ref<boolean>(false);

export function useTrabajador() {
    const paginacion = ref({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
    });

    const { showToast, ToastStatus, showToastFromCode } = useToast();

    const obtenerTrabajadores = async (page: number = 1): Promise<boolean> => {
        try {
            loading.value = true;
            const { data, response } = await TrabajadorService.getTrabajadores(page, filtros.value);
            
            if (response.value?.ok) {
                const res = data.value.data;
                trabajadores.value = res?.data ?? [];
                
                const pag = res?.paginacion;
                paginacion.value = {
                    current_page: pag?.current_page ?? 1,
                    last_page: pag?.last_page ?? 1,
                    per_page: pag?.per_page ?? 15,
                    total: pag?.total ?? 0,
                };
                return true;
            }
            showToastFromCode(response.value?.status ?? 500, 'trabajadores');
            return false;
        } catch (error) {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al cargar los trabajadores' });
            return false;
        } finally {
            loading.value = false;
        }
    };

    const crearTrabajador = async (dataFormulario: any) => {
        let success = false;
        try {
            const { response } = await TrabajadorService.store(dataFormulario);
            showToastFromCode(response.value?.status ?? 500, 'trabajador');
            if (response.value?.ok) success = await obtenerTrabajadores(1);
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al crear el trabajador' });
        } finally { return success; }
    };

    const actualizarTrabajador = async (id: number, dataFormulario: any) => {
        let success = false;
        try {
            const { response } = await TrabajadorService.update(id, dataFormulario);
            showToastFromCode(response.value?.status ?? 500, 'trabajador');
            if (response.value?.ok) {
                await obtenerTrabajadores(paginacion.value.current_page);
                success = true;
            }
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al actualizar el trabajador' });
        } finally { return success; }
    };

    const sincronizarHorarios = async (id: number, horariosIds: number[]) => {
        let success = false;
        try {
            const { response } = await TrabajadorService.syncHorarios(id, horariosIds);
            showToastFromCode(response.value?.status ?? 500, 'horarios');
            if (response.value?.ok) {
                await obtenerTrabajadores(paginacion.value.current_page);
                success = true;
            }
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al sincronizar horarios' });
        } finally { return success; }
    };

    const cambiarEstadoTrabajador = async (id: number, activar: boolean) => {
        try {
            const { response } = activar 
                ? await TrabajadorService.activar(id) 
                : await TrabajadorService.desactivar(id);
            
            showToastFromCode(response.value?.status ?? 500, 'trabajador');
            if (response.value?.ok) await obtenerTrabajadores(paginacion.value.current_page);
        } catch {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al cambiar estado' });
        }
    };

    return {
        trabajadores,
        filtros,
        loading,
        paginacion,
        obtenerTrabajadores,
        crearTrabajador,
        actualizarTrabajador,
        sincronizarHorarios,
        cambiarEstadoTrabajador
    };
}