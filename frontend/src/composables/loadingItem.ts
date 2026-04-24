import {ref} from 'vue';

export const useItemLoading = () => {
    const loadingItems = ref<Record<number|string, boolean>>({});
    const setLoading = (id: number|string, value: boolean) => {
        loadingItems.value[id] = value;
    };

    const clearLoading = (id: number|string) => {
        delete loadingItems.value[id];
    };

    const isLoading = (id: number|string) => !!loadingItems.value[id]; 

    return{
        isLoading,
        setLoading,
        clearLoading
    }
}