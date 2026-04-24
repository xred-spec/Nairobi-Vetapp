import {ref, computed} from 'vue';
import { dashboardService } from '@/services/DashboardService';
import { useToast } from './useToast';
import { Cita } from '@/Types/Cita';

export function useDashboard(){
    const citas = ref<Cita[]>([]);
    const cantidadCitas = ref<number>(0);
    const fechaFiltro = ref<string | undefined>(undefined);
    const loading = ref<boolean>(false);
    const {showToast, ToastStatus} = useToast();

    const citasFiltradas = computed(() => {
        if(!fechaFiltro.value) return citas.value;

        return citas.value.filter(cita => {
            const fechaCita = cita.fecha.split('T')[0];
            return fechaCita === fechaFiltro.value;
        });
    });

    const obtenerCitas = async () => {
        let success = false;
        try{
            loading.value = true;
            const {data, response} = await dashboardService.getProximasCitas({
                estado: 'agendada'
            });

            if(response.value?.ok && data.value){
                citas.value = data.value.data.data  ?? [];
                cantidadCitas.value = data.value.data.total ?? 0;
                fechaFiltro.value = undefined;
                success = true;
            }else{
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: 'Error cargando citas'
                });
            }
        }catch(e){
                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: 'Error de conexion'
                })
        }finally{
            loading.value = false;
            return success;
        }
    }

    return {
        citas,
        loading,
        citasFiltradas,
        fechaFiltro,
        cantidadCitas,
        obtenerCitas
    }
}