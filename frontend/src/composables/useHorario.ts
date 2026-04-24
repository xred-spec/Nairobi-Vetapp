import { ref } from 'vue';
import { TrabajadorService } from '@/services/TrabajadorService'; // ← mismo service
import { useToast } from '@/composables/useToast';

export function useHorario() {
    const horarios = ref<{ id: number; inicio_hora: string; final_hora: string }[]>([]);
    const { showToast, ToastStatus } = useToast();

    const obtenerHorarios = async () => {
        try {
        const { data, response } = await TrabajadorService.getHorarios(); // ← aquí
            if (response.value?.ok) {
                horarios.value = data.value?.data ?? data.value ?? [];
            }
        } catch (error) {
            console.error(error);
            showToast({ status: ToastStatus.ERROR, customMessage: 'Error al cargar horarios' });
        }
    };

    return { horarios, obtenerHorarios };
}

