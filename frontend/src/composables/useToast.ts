import { reactive } from 'vue';

export type ToastStatus = 'created' | 'updated' | 'deleted' | 'status_changed' | 'warning' | 'error';

interface ToastOptions {
    status: ToastStatus;
    itemName?: string;
    customMessage?: string;
}

const defaultMessages: Record<ToastStatus, (itemName?: string) => string> = {
    created: (itemName = 'Elemento') => {
        const messages: Record<string, string> = {
            'mascota': 'Mascota registrada exitosamente',
            'producto': 'Producto agregado al inventario',
            'servicio': 'Servicio creado correctamente',
            'cita': 'Cita agendada exitosamente',
            'cliente': 'Cliente registrado en el sistema',
            'raza': 'Raza agregada al catálogo',
            'animal': 'Animal registrado en el sistema',
            'usuario': 'Usuario creado correctamente',
            
        };
        const key = itemName?.toLowerCase() || 'elemento';
        return messages[key] || `${itemName} creado correctamente`;
    },
    updated: (itemName = 'Elemento') => {
        const messages: Record<string, string> = {
            'mascota': 'Datos de mascota actualizados',
            'producto': 'Producto actualizado en el inventario',
            'servicio': 'Servicio modificado correctamente',
            'cita': 'Cita reprogramada exitosamente',
            'cliente': 'Información del cliente actualizada',
            'perfil': 'Tu perfil ha sido actualizado',
            'usuario': 'Usuario actualizado correctamente'
        };
        const key = itemName?.toLowerCase() || 'elemento';
        return messages[key] || `${itemName} actualizado correctamente`;
    },
    deleted: (itemName = 'Elemento') => {
        const messages: Record<string, string> = {
            'mascota': 'Mascota eliminada del registro',
            'producto': 'Producto eliminado del inventario',
            'servicio': 'Servicio eliminado del catálogo',
            'cita': 'Cita cancelada exitosamente',
            'cliente': 'Cliente eliminado del sistema'
        };
        const key = itemName?.toLowerCase() || 'elemento';
        return messages[key] || `${itemName} eliminado correctamente`;
    },
    status_changed: (itemName = 'Elemento') => {
        const messages: Record<string, string> = {
            'cita': 'Estado de la cita actualizado',
            'consulta': 'Estado de la consulta modificado',
            'mascota': 'Estado de la mascota actualizado'
        };
        const key = itemName?.toLowerCase() || 'elemento';
        return messages[key] || `Estado de ${itemName} actualizado`;
    },
    warning: (itemName = 'Elemento') => {
        const messages: Record<string, string> = {
            'cita': '¿Estás seguro de cancelar esta cita?',
            'mascota': '¿Deseas eliminar esta mascota?',
            'producto': 'Producto con stock bajo',
            'servicio': 'El servicio está inactivo',
            'logout': '¿Cerrar sesión?',
            'delete': 'Esta acción no se puede deshacer',
            'permiso': 'No tienes permiso para realizar esta operación'
        };
        const key = itemName?.toLowerCase() || 'elemento';
        return messages[key] || `Advertencia: ${itemName}`;
    },
    error: (itemName = 'Elemento') => {
        const messages: Record<string, string> = {
            'cita': 'No se pudo agendar la cita',
            'mascota': 'Error al procesar la mascota',
            'producto': 'Error con el producto',
            'servicio': 'Error al procesar el servicio',
            'login': 'Credenciales incorrectas',
            'registro': 'Error al crear la cuenta',
            'consulta': 'Error al guardar la consulta',
            'conexion': 'Error de conexión',
            'servidor': 'Error en el servidor'
        };
        const key = itemName?.toLowerCase() || 'elemento';
        return messages[key] || `Error al procesar ${itemName}`;
    }
};

const defaultTitles: Record<ToastStatus, string> = {
    created: 'Registrado',
    updated: 'Actualizado',
    deleted: 'Eliminado',
    status_changed: 'Estado',
    warning: 'Advertencia',
    error: 'Error'
};

const defaultTypes: Record<ToastStatus, 'success' | 'error' | 'warning' | 'info'> = {
    created: 'success',
    updated: 'success',
    deleted: 'success',
    status_changed: 'info',
    warning: 'warning',
    error: 'error'
};

interface ToastState {
    show: boolean;
    title: string;
    message: string;
    type: 'success' | 'error' | 'warning' | 'info';
    duration: number;
    persist?: boolean;
}

const toastState = reactive<ToastState>({
    show: false,
    title: '',
    message: '',
    type: 'success',
    duration: 4000
});

let hideTimer: ReturnType<typeof setTimeout> | null = null;

export function useToast() {
    const showToast = (options: ToastOptions & { persist?: boolean }) => {
        if (hideTimer) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }
        
        const { status, itemName, customMessage, persist = false } = options;
        
        toastState.title = defaultTitles[status];
        toastState.message = customMessage || defaultMessages[status](itemName);
        toastState.type = defaultTypes[status];
        toastState.show = true;
        toastState.persist = persist;
        
        hideTimer = setTimeout(() => {
            toastState.show = false;
        }, toastState.duration);
    };
    
    const hideToast = () => {
        toastState.show = false;
        if (hideTimer) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }
    };

    // funcion para obtener el ToastStatus según el código HTTP
    const getStatusFromCode = (code: number): ToastStatus => {
        switch (code) {
            case 201:
                return 'created';
            case 200:
                return 'updated';
            case 204:
                return 'deleted';
            case 403:
            case 401:    
            case 409:
                return 'warning';
            case 422:
                return 'warning';
            case 500:
            case 502:
            case 503:
                return 'error';
            default:
                return 'error';
        }
    };

    // funcion para obtener el mensaje según el código HTTP
    const getMessageFromCode = (code: number, itemName: string = 'elemento'): string => {
        const messages: Record<number, Record<string, string>> = {
            201: {
                'producto': 'Producto agregado al inventario',
                'mascota': 'Mascota registrada exitosamente',
                'servicio': 'Servicio creado correctamente',
                'cita': 'Cita agendada exitosamente',
                'cliente': 'Cliente registrado en el sistema',
                'animal': 'Animal registrado en el sistema',
                'raza': 'Raza agregada al catálogo'
            },
            200: {
                'producto': 'Producto actualizado correctamente',
                'mascota': 'Datos de mascota actualizados',
                'servicio': 'Servicio modificado correctamente',
                'cita': 'Cita reprogramada exitosamente',
                'cliente': 'Información del cliente actualizada',
                'animal': 'Información del animal actualizada',
                'raza': 'Información de la raza actualizada'
            },
            204: {
                'producto': 'Producto eliminado del inventario',
                'mascota': 'Mascota eliminada del registro',
                'servicio': 'Servicio eliminado del catálogo',
                'cita': 'Cita cancelada exitosamente',
                'animal': 'Animal eliminado del sistema',
                'raza': 'Raza eliminada del catálogo'

            },
            403: {
                'producto': 'No tienes permiso para realizar esta operación',
                'mascota': 'No tienes permiso para modificar esta mascota',
                'servicio': 'No tienes permiso para modificar este servicio',
                'cita': 'No tienes permiso para modificar esta cita',
                'default': 'No tienes permiso para realizar esta operación',
                'animal': 'No tienes permiso para modificar este animal',
                'raza': 'No tienes permiso para modificar esta raza'
            },
            404:{
                'producto': 'El producto no existe en el inventario',
                'mascota': 'La mascota no existe en el sistema',
                'servicio': 'El servicio no existe en el catálogo',
                'cita': 'La cita no existe en el sistema',
                'cliente': 'El cliente no existe en el sistema',
                'raza': 'La raza no existe en el catálogo',
                'animal': 'El animal no existe en el sistema',
                'default': 'El elemento no existe en el sistema'
            },
            409:{
                'producto': 'El producto ya existe en el inventario',
                'mascota': 'La mascota ya existe en el sistema',
                'servicio': 'El servicio ya existe en el catálogo',
                'cita': 'La cita ya existe en el sistema',
                'cliente': 'El cliente ya existe en el sistema',
                'raza': 'La raza ya existe en el catálogo',
                'animal': 'El animal ya existe en el sistema',
                'default': 'El elemento ya existe en el sistema'
            },
            401: {
                'default': 'Necesitas autorizacion para realizar esta operación'
            },
            422: {
                'default': 'Datos inválidos. Verifica la información'
            },
            500: {
                'default': 'Error en el servidor'
            },
            502: {
                'default': 'Error de conexión con el servidor'
            },
            503: {
                'default': 'Servicio temporalmente no disponible'
            }
        };

        const codeMessages = messages[code];
        if (!codeMessages) return 'Error al procesar la solicitud';
        
        const key = itemName.toLowerCase();
        return codeMessages[key] || codeMessages['default'] || `Operación completada`;
    };


    const showToastFromCode = (code: number, itemName: string = 'elemento') => {
        const status = getStatusFromCode(code);
        const message = getMessageFromCode(code, itemName);
        showToast({ status, itemName, customMessage: message });
    };
    
    return {
        toastState,
        showToast,
        hideToast,
        showToastFromCode,
        getStatusFromCode,
        getMessageFromCode,
        ToastStatus: {
            CREATED: 'created' as ToastStatus,
            UPDATED: 'updated' as ToastStatus,
            DELETED: 'deleted' as ToastStatus,
            STATUS_CHANGED: 'status_changed' as ToastStatus,
            WARNING: 'warning' as ToastStatus,
            ERROR: 'error' as ToastStatus
        }
    };
}
