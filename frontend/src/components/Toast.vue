<script setup lang="ts">
import { computed } from 'vue';
import { useToast } from '../composables/useToast';

const { toastState, hideToast } = useToast();

const toastClasses = computed(() => {
    const baseClasses = 'fixed bottom-5 right-5 z-[9999] p-4 rounded-2xl shadow-xl max-w-sm transform transition-all duration-300 border-l-4';
    
    const typeClasses = {
        success: 'bg-emerald-50 border-emerald-400 text-emerald-900 shadow-emerald-100/50',
        error: 'bg-rose-50 border-rose-400 text-rose-900 shadow-rose-100/50',
        warning: 'bg-amber-50 border-amber-400 text-amber-900 shadow-amber-100/50',
        info: 'bg-sky-50 border-sky-400 text-sky-900 shadow-sky-100/50'
    };
    
    return `${baseClasses} ${typeClasses[toastState.type]}`;
});

const iconSvg = computed(() => {
    const icons = {
        success: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`,
        error: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`,
        warning: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>`,
        info: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`
    };
    
    return icons[toastState.type];
});
</script>

<template>
    <Transition name="toast-fade">
        <div 
            v-if="toastState.show" 
            :class="toastClasses"
            role="alert"
        >
            <div class="flex items-start gap-3">
                <div 
                    class="flex-shrink-0" 
                    :class="{
                        'text-emerald-500': toastState.type === 'success',
                        'text-rose-500': toastState.type === 'error',
                        'text-amber-500': toastState.type === 'warning',
                        'text-sky-500': toastState.type === 'info'
                    }" 
                    v-html="iconSvg">
                </div>

                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="font-bold text-sm">{{ toastState.title }}</p>
                    </div>
                    <p class="text-sm opacity-85">{{ toastState.message }}</p>
                </div>

                <button 
                    @click="hideToast" 
                    class="flex-shrink-0 ml-2 opacity-50 hover:opacity-100 transition-opacity rounded-full hover:bg-black/5 p-1"
                    aria-label="Cerrar"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.toast-fade-enter-active {
    animation: toast-in 0.3s ease-out;
}

.toast-fade-leave-active {
    animation: toast-out 0.3s ease-in forwards;
}

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes toast-out {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
</style>