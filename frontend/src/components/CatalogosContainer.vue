<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

defineProps<{
    header: boolean
}>();

const scrollContainer = ref<HTMLElement | null>(null);
const hasScrollbar = ref(false);
let resizeObserver: ResizeObserver | null = null;

const checkScrollbar = () => {
    if (scrollContainer.value) {
        hasScrollbar.value = scrollContainer.value.scrollHeight > scrollContainer.value.clientHeight;
    }
};

onMounted(() => {
    if (scrollContainer.value) {
        resizeObserver = new ResizeObserver(checkScrollbar);
        resizeObserver.observe(scrollContainer.value);
    }
});

onBeforeUnmount(() => {
    if (resizeObserver) resizeObserver.disconnect();
});
</script>

<template>
    <div class="flex flex-col h-full w-full min-h-0">
        
        <div v-if="header" class="hidden md:flex items-center w-full shrink-0 mb-2" :class="hasScrollbar ? 'pr-4' : 'pr-1'">
            
            <div class="flex items-center justify-center w-full h-auto mr-2 bg-[#52301300] text-[#523013] text-lg font-extrabold shrink-0">
                <div class="flex justify-center items-center h-full w-16 shrink-0">
                    <span class="text-lg text-center">#</span>
                </div>

                <div class="flex flex-1 px-5 py-2 h-full items-center min-w-0">
                    <slot name="headers"></slot>
                </div>
            </div>
        </div>

        <div ref="scrollContainer" class="flex flex-col gap-3 overflow-y-auto flex-1 min-h-0 custom-scrollbar pb-2 pr-1">
            <slot name="content-cards"></slot>
        </div>

        <div class="shrink-0 pt-2 md:pt-4 mt-auto">
            <slot name="pagination"></slot>
        </div>
        
    </div>
</template>

<style scoped>
@reference "tailwindcss";

/* Scrollbar sutil estandarizado para la app */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(160, 122, 88, 0.3);
  border-radius: 10px;
}
</style>