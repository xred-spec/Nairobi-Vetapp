<script setup lang="ts">
const props = defineProps<{
    message:string,
    state?:string,
    id:number
}>();

const emit = defineEmits<{
    (e:'modal',state:string|undefined):void
}>()

</script>

<template>
    <div class="w-full md:w-auto border-t-2 border-[#a07a58]/20 pt-3 md:border-t-0 md:pt-0 md:mt-0 flex justify-end">
        
        <button type="button" 
        class="w-full md:w-auto px-5 py-2.5 md:py-2 rounded-md bg-[#fcf7f2] text-[#812c8f] font-extrabold transition-all duration-200 hover:bg-[#812c8f] hover:text-[#fcf7f2] hover:scale-105 cursor-pointer shadow-sm md:shadow-none" 
        @click.stop="emit('modal',state)">
            {{props.message}}
        </button>
        
    </div>
</template>