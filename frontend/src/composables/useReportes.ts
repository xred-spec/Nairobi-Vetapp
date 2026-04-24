import { ref, watch } from "vue";
import { useFechaStore } from "@/stores/FechaStore";
import { ReporteService } from "@/services/ReporteService";
import { useToast } from "./useToast";
import type { GeneralReporte, TrabajadorReporte,Ingresos,veterinarioFinanzas,finanzas, DistribucionTipos } from "@/interfaces/ReporteGeneral";

export function useReportes() {
    const { inicio, fin } = useFechaStore();

    const datosDistribucion = ref<DistribucionTipos[]>([]);
    const loadingTipos = ref<boolean>(false);
    const infoGeneral = ref<GeneralReporte | null>(null);
    const trabajadores = ref<TrabajadorReporte[]>([]);
    const loading = ref<boolean>(false);
    const ingresosPromedios = ref<Ingresos[]>([]);
    const finanzasTipoCita = ref<finanzas[]>([]);
    const finanzasPorVeterinario = ref<veterinarioFinanzas[]>([]);
    const { showToast, ToastStatus } = useToast();

    const cargarDistribucion = async (fecha: string) => {
        loadingTipos.value = true;
        try{
            const { data} = await ReporteService.getDistribucion(fecha);
            if(data.value){
                datosDistribucion.value = data.value.data ?? [];
            }
        }catch(e){
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error cargando distribucion'
            });
        }finally{
            loadingTipos.value = false;
        }
    };

    const obtenerReporteGeneral = async () => {
        try {
            loading.value = true;

            const { data, response } = await ReporteService.getReporteGeneral({
                fecha_fin: fin.value,
                fecha_inicio: inicio.value
            });
            if (response.value && !response.value.ok) {
                const errorData = data.value; 

                const mensaje = errorData?.message || 'Error en rango de fechas';

                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: mensaje
                });
                return;
            }

            if (data.value) {
                infoGeneral.value = data.value.data.general ?? null;
                trabajadores.value = data.value.data.trabajadores ?? [];
            }

        } catch (e: any) {
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error de conexión con el servidor'
            });
        } finally {
            loading.value = false;
        }
    }
    const obtenerReporteGeneral2 = async ()=>{
        try{
            loading.value = true;
             const { data, response } = await ReporteService.getReporteGeneral2({
                fecha_fin: fin.value,
                fecha_inicio: inicio.value
            });
            if (response.value && !response.value.ok) {
                const errorData = data.value; 

                const mensaje = errorData?.message || 'Error en rango de fechas';

                showToast({
                    status: ToastStatus.ERROR,
                    customMessage: mensaje
                });
                return;
            }

            if (data.value) {
                ingresosPromedios.value = data.value.data.ingresos_promedios ?? []
                finanzasTipoCita.value = data.value.data.finanzas_tipo_cita ?? [];
                finanzasPorVeterinario.value = data.value.data.finanzas_por_veterinario ?? [];
                console.log(finanzasPorVeterinario)
                console.log(finanzasTipoCita)
                console.log(ingresosPromedios)
            }



        }catch(e: any){
            showToast({
                status: ToastStatus.ERROR,
                customMessage: 'Error de conexión con el servidor'
            });
        }finally{
            loading.value = false;
        }
    }
    // si cambia las fechas se actualiza, que increible es la reactividad
    watch([inicio, fin], () => {
        obtenerReporteGeneral();
        obtenerReporteGeneral2();
    });

    return {
        infoGeneral,
        trabajadores,
        loading,
        inicio,
        fin,
        loadingTipos,
        datosDistribucion,
        cargarDistribucion,
        obtenerReporteGeneral,
        obtenerReporteGeneral2,
        ingresosPromedios,
        finanzasTipoCita,
        finanzasPorVeterinario 
    }
}   