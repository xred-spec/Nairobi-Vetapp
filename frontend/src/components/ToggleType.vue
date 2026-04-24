<script setup lang="ts">
import { ref ,watch} from 'vue';
const props = defineProps<{
    type: string,
    selected: string
}>();

const emit = defineEmits<{
    (e: 'selected', type: string): void
}>();
const style = ref<string>('no-selected');

watch(()=>props.selected,()=>{
    if(props.type == props.selected){
        style.value ="selected";
     }else{
         style.value = "no-selected";
     }
});

if(props.type == props.selected){
     style.value ="selected";
}else{
    style.value = "no-selected";
}
</script>

<template>
    <div v-if="style === 'selected' && type === 'Agendadas'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#dc7474] text-[#fcf7f2] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 mr-1.5 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
        </svg>
        <p class="font-bold type">{{props.type}}</p>
    </div>
    <div v-if="style === 'no-selected' && type === 'Agendadas'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#fcf7f2] text-[#dc7474] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 mr-1.5 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
        </svg>
        <p class="font-bold type">{{props.type}}</p>
    </div>

    <div v-if="style === 'selected' && type === 'En proceso'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#523013] text-[#fcf7f2] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope-icon lucide-stethoscope size-5 mr-1.5 shrink-0"><path d="M11 2v2"/><path d="M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/></svg>
        <p class="font-bold type">{{props.type}}</p>
    </div>
    <div v-if="style === 'no-selected' && type === 'En proceso'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#fcf7f2] text-[#523013] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope-icon lucide-stethoscope size-5 mr-1.5 shrink-0"><path d="M11 2v2"/><path d="M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/></svg>
        <p class="font-bold type">{{props.type}}</p>
    </div>

    <div v-if="style === 'selected' && type === 'Completadas'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#812c8f] text-[#fcf7f2] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check size-5 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
        <p class="font-bold type">Completas</p>
    </div>
    <div v-if="style === 'no-selected' && type === 'Completadas'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#fcf7f2] text-[#812c8f] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check size-5 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
        <p class="font-bold type">Completas</p>
    </div>

    <div v-if="style === 'selected' && type === 'Canceladas'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#b51f1f] text-[#fcf7f2] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x-icon lucide-circle-x size-5 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
        <p class="font-bold type">{{props.type}}</p>
    </div>
    <div v-if="style === 'no-selected' && type === 'Canceladas'"
        class="wrapper-toggle flex w-full md:w-auto md:flex-1 justify-center whitespace-nowrap py-2 px-4 md:px-5 rounded-md items-center cursor-pointer bg-[#fcf7f2] text-[#b51f1f] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-200 shadow-sm" 
        @click="emit('selected', props.type)">    
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x-icon lucide-circle-x size-5 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
        <p class="font-bold type">{{props.type}}</p>
    </div>
</template>