<script setup lang="ts">
defineProps<
    {
        titulo: string;
    }
>();
</script>

<template>
        <h1 class="w-full z-50 bg-[#523013] rounded-md px-5 py-3.5 flex justify-start items-center text-[#fcf7f2] text-3xl font-extrabold border-2 border-[#523013]">
            {{ titulo }}
        </h1>
</template>
