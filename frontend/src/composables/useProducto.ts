
import { ProductoService } from "@/services/ProductoService";
import { useToast } from './useToast';
import { ref } from "vue";
const productos = ref<any>([]);
const filtros = ref<any>({
    nombre: '',
    visibilidad: "visible",
    marca: '',
});
export function useProducto() {
    
    const paginacion = ref<any>({ 
        current_page: 1, 
        last_page: 1, 
        per_page: 15, 
        total: 0, 
    });
    const loading = ref<boolean>(false);
    const { showToast, ToastStatus, showToastFromCode } = useToast();

    const obtenerProductos = async (page: number = 1) => {
        try {
            loading.value = true;
            const { data } = await ProductoService.getProductos(page, filtros.value);

            if(data.value?.status === 403){
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: 'No tienes permiso para ver los clientes'
                });
            }

            productos.value = data.value?.data?.data ?? [];
            paginacion.value = data.value?.data?.paginacion ?? { current_page: 1, last_page: 1 };

            if(productos.value.length === 0 && paginacion.value.current_page > 1) {
                await obtenerProductos(paginacion.value.current_page - 1);
            }
            return true;
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando los productos'
            });
            return false;
        } finally {
            loading.value = false;
        }
    }

    const storeProducto = async (data: any) => {
        let success = false;
        try {
            const { response } = await ProductoService.store(data);

            let status = 500;
            if (response.value && response.value.status) {
                status = response.value.status;
            }
            showToastFromCode(status, 'producto');

            if (response.value && response.value.ok) {
                success = await obtenerProductos();
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error creando producto'
            });
        } finally {
            return success;
        }
    }

    const editarProducto = async (id: number, data: any) => {
        try {
            const { response } = await ProductoService.update(id, data);

            let status = 500;
            if (response.value && response.value.status) {
                status = response.value.status;
            }
            showToastFromCode(status, 'producto');

            if (response.value && response.value.ok) {
                const idx = productos.value.findIndex((p: any) => p.id === id);
                if (idx !== -1) {
                    productos.value[idx] = { ...productos.value[idx], ...data };
                }
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error actualizando producto'
            });
        }
    }

    const eliminarProducto = async (id: number, page: number = 1) => {
        let success = false;
        try {
            const { response } = await ProductoService.delete(id);

            let status = 500;
            if (response.value && response.value.status) {
                status = response.value.status;
            }
            showToastFromCode(status, 'producto');

            if (response.value && response.value.ok) {
                //success = productos.value = productos.value.filter((p: any) => p.id !== id);
                success = await obtenerProductos(page);
            }
        } catch (e) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error eliminando producto'
            });
        } finally {
            return success;
        }
    } 
    
    const cambiarEstadoProducto = async (id: number) => {
        try {
            const productoActual = productos.value.find((p: any) => p.id === id);
            
            if (!productoActual) {
                return false;
            }
            
            let nuevaVisibilidad = 'visible';
            if (productoActual.visibilidad === 'visible') {
                nuevaVisibilidad = 'invisible';
            }
            
            const dataAActualizar = { ...productoActual, visibilidad: nuevaVisibilidad };

            await editarProducto(id, dataAActualizar);
            return true;
        } catch (e) {
            return false;
        }
    };

    return {
        productos,
        paginacion,
        loading,
        filtros,
        obtenerProductos,
        storeProducto,
        editarProducto,
        eliminarProducto,
        cambiarEstadoProducto,
    }
} 