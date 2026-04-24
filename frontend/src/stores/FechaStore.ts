import {reactive, toRefs} from 'vue';

// estado global
const state = reactive({
    inicio: new Date().toISOString().split('T')[0],
    fin: new Date().toISOString().split('T')[0],
});

export function useFechaStore(){
    const setFechas = (inicio: string, fin: string) => {
        state.inicio = inicio;
        state.fin = fin;   
    };

    return {
        ...toRefs(state),
        setFechas
    }
};