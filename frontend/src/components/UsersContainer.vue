<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

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
    <div class="content bg-[#52301338] py-1 px-5 mt-2 rounded-[20px] h-[80vh] flex flex-col justify-center overflow-hidden">
        
        <div class="flex items-center w-full" :class="hasScrollbar ? 'pr-4' : 'pr-1'">
            <div class="flex items-center justify-center w-full h-auto mr-2 bg-[#52301300] text-[#523013] text-lg font-extrabold shrink-0">
                <div class="flex justify-center items-center h-full w-16">
                    <span class="text-lg text-center">#</span>
                </div>

                <div class="flex flex-1 px-5 py-2 h-full items-center">
                    <slot name="headers"></slot>
                </div>
            </div>
        </div>

        <div ref="scrollContainer" class="flex flex-col gap-2 overflow-y-auto flex-1">
            <slot name="content-cards"></slot>
        </div>

        <slot name="pagination"></slot>
    </div>
</template>