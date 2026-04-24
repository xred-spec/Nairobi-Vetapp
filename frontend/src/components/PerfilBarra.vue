<script setup lang="ts">
defineProps<{
    titulo: string;
}>();
</script>

<template>
    <h1 class="w-full bg-[#523013] rounded-[12px] px-4 md:px-5 py-3 my-2 flex justify-center md:justify-start items-center text-[#fcf7f2] text-xl md:text-2xl font-black shadow-sm shrink-0">
        {{ titulo }}
    </h1>
</template>