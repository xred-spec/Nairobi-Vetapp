import { ref, markRaw, type Component, type DefineComponent } from "vue";
import GenericModal from "@/components/modals/GenericModal.vue";

type VueComponent = Component | DefineComponent<any, any, any, any, any>;

interface ModalConfig {
    component?: Component;
    header: string;
    props?: Record<string, any>;
    onAccept: (data: any) =>Promise<void> | void;
}

const isVisible = ref<Boolean>(false);
const config = ref<ModalConfig | null>(null);

export function useGlobalModal(){

    const openGlobalModal = (newConfig: ModalConfig) => {
        const componentToRender = newConfig.component 
            ? markRaw(newConfig.component) 
            : markRaw(GenericModal as VueComponent);

        config.value = { 
            ...newConfig, 
            component: componentToRender,
            onAccept: markRaw(newConfig.onAccept) 
        };
        isVisible.value = true;
    };

    const closeGlobalModal = () => {
        isVisible.value = false;
        setTimeout(() => {
            config.value = null;
        }, 300);
    };

    return {
        isVisible,
        config,
        openGlobalModal,
        closeGlobalModal
    };
}
