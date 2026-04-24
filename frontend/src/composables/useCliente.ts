import { ref } from 'vue';
import { clienteService } from '@/services/ClienteService';
import { useToast } from './useToast';

const clientes = ref<any[]>([]);  
const clientesList = ref<any[]>([]);
const filtros = ref<any>({
    search: '',
});
const loading = ref<boolean>(false);

export function useCliente() {
    const { showToast, ToastStatus, showToastFromCode } = useToast();
    const paginacion = ref<any>({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
    });

    const getFullClientes = async () => {
        try {
            const { data } = await clienteService.getFullClientes();
            clientesList.value = data.value?.data ?? [];
        } catch (e: any) {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error cargando lista de clientes' });
        }
    };  

    const getClientes = async (page: number = 1) => {
        try {
            loading.value = true;
            const { data } = await clienteService.getClientes(page, filtros.value);
            
            const response = data.value?.data;
            clientes.value = response?.data ?? [];
            paginacion.value = {
                current_page: response.paginacion.current_page ?? 1,
                last_page: response.paginacion.last_page ?? 1,
                per_page: response.paginacion.per_page ?? 15,
                total: response.paginacion.total ?? 0
            };

            return true;
        } catch (e) {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error cargando clientes' });
            return false;
        } finally {
            loading.value = false;
        }
    };

    const storeCliente = async (data: any) => {
        let success = false;
        try {
            const { response } = await clienteService.store(data);
            showToastFromCode(response.value?.status ?? 500, 'cliente');
            if (response.value?.ok) success = await getClientes(1);
        } catch (e) {
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al crear cliente' });
        } finally {
            return success;
        }
    };

    return {
        clientes,
        clientesList,
        loading,
        paginacion,
        getClientes,
        storeCliente,
        getFullClientes,
        filtros
    };
}