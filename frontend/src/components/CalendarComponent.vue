<script setup lang="ts">
import { ref, computed,watch } from 'vue'
import {useUserStore} from "@/stores/user";
import { Cita } from '@/Types/Cita';
const pinia = useUserStore();
const props = defineProps<{
  modelValue?: string       // fecha seleccionada en formato YYYY-MM-DD
  citas?: Cita[]
  allowFuture?: boolean     // permite fechas futuras
  allowPast?: boolean       // permite fechas pasadas
  allowFetcing?:boolean
  isForAppointment?:boolean,
  shouldBeClienteDissable?:boolean,
  shouldJustView?:boolean
}>();
  const shouldBeClienteDissableref = ref<boolean|null>(props.shouldBeClienteDissable);
if(shouldBeClienteDissableref.value && pinia.getUser.rol=="cliente"){
  shouldBeClienteDissableref.value=true
}else{
  shouldBeClienteDissableref.value=false
}


const emit = defineEmits<{
  (e: 'update:modelValue', value: string | undefined): void
}>()

const Meses: string[] = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
const selectedDate = ref<string | undefined>(props.modelValue)
const now: Date =  new Date()
const month = ref<any>(now.getMonth())
const year = ref<any>(now.getFullYear())
const ThisYear = ref<any>(now.getFullYear());
const ThisMonth = ref<any>(now.getMonth());
const isCurrentMonth = computed(() =>
  month.value === now.getMonth() && year.value === now.getFullYear()
)
const shouldBeDissabled = ref<boolean>(false);

watch(() => props.modelValue, (val) => {
  if (!val) {
    month.value = now.getMonth();
    year.value = now.getFullYear();
    selectedDate.value = undefined;
    return;
  }

  const parts = val.split('-');
  year.value = Number(parts[0]);
  month.value = Number(parts[1]) - 1;
 /* ThisYear.value = Number(parts[0]);
  ThisMonth.value = Number(parts[1]-1);*/
  selectedDate.value = val;
}, { immediate: true });


if(props.isForAppointment && month.value == ThisMonth.value && year.value <= ThisYear.value ){
  shouldBeDissabled.value = true;
}
// Días del mes anterior que se muestran
  const prevDays = computed(() => {
  const startDay: any = new Date(year.value, month.value, 1).getDay()
  const endPrev: any = new Date(year.value, month.value, 0).getDate()
  return Array.from({ length: startDay }, (_, i) => endPrev - startDay + i + 1)
})

// Días del mes actual
const currentDays = computed(() => {
  const endDate: any =  new Date(year.value, month.value + 1, 0).getDate()
  return Array.from({ length: endDate }, (_, i) => i + 1)
})

// Días del mes siguiente para completar la grilla
const nextDays = computed(() => {
  const total = prevDays.value.length + currentDays.value.length
  const remaining = total % 7 === 0 ? 0 : 7 - (total % 7)
  return Array.from({ length: remaining }, (_, i) => i + 1)
})

function pad(n: number) {
  return String(n).padStart(2, '0')
}

function dateStr(day: number) {
  return `${year.value}-${pad(month.value + 1)}-${pad(day)}`
}

function isToday(day: number) {
  return day === now.getDate() && month.value === now.getMonth() && year.value === now.getFullYear()
}

function isSelected(day: number) {
   return selectedDate.value === dateStr(day)
}

function isDisabled(day: number) {
  const date = new Date(year.value, month.value, day)
  const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
  const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1)
  if(props.shouldJustView)return true;
  if(date.getDay()==0 && props.isForAppointment)return true
  if(props.isForAppointment){
    const allowed = new Date().getTime()
    const isNow = date.getTime();
    if(allowed<isNow)return false;
  }
  if (props.allowFuture && !props.allowPast){
    if(pinia.getUser.rol=='cliente'){
       return date < tomorrow
    }else{
      return (!(today.getTime()==date.getTime()) && (date<tomorrow))
    }
  } 
  if (props.allowPast && !props.allowFuture) return date > today      // solo pasadas
  return false  // ambos o ninguno = todas permitidas
}

function selectDay(day: number) {
  if (isDisabled(day) || shouldBeClienteDissableref.value) return

  const fechaStr = dateStr(day);
  if(selectedDate.value === fechaStr) {
    selectedDate.value = undefined
    emit('update:modelValue', undefined)
  }else{
    selectedDate.value = fechaStr
    emit('update:modelValue', dateStr(day))
  }  
}

function prevMonth() {
  if (month.value === 0) {
    month.value = 11
    year.value--
  } else {
    month.value--
  }
  if(props.isForAppointment && month.value == ThisMonth.value && year.value <= ThisYear.value){
      shouldBeDissabled.value = true;
  }
}

function nextMonth() {
  if (!props.allowFuture && isCurrentMonth.value) return
  if (month.value === 11) {
    month.value = 0
    year.value++
  } else {
    month.value++
  }
  if(props.isForAppointment && month.value > ThisMonth.value && year.value >= ThisYear.value){
      shouldBeDissabled.value = false;
    }else{
      console.error('esto bajo ningun motivo deberia de existir');
  }
}

// para verificar si un dia tiene al menos una cita registrada
/*function tieneCita(dia: number){
  if(!props.citas) return false;

  const fechaActual = dateStr(dia); 
  return props.citas.some(cita => {
    const fechaCita = cita.fecha.split('T')[0]; 
    return fechaCita === fechaActual;
  });
}*/

// contar las citas en un dia
function conteoCitas(dia: number){
  if(!props.citas) return 0;
  const fechaActual = dateStr(dia); 
  
  return props.citas.filter(cita => {
    const fechaCIta = cita.fecha.split('T')[0];
    return fechaCIta === fechaActual;
  }).length;
}

// funcion para la intensidad
function getIntensidad(dia: number){
  const total = conteoCitas(dia);
  if(total === 0) return '';
  if(total === 1) return 'has-appointment-low';
  if(total >= 2 && total <= 3) return 'has-appointment-medium';
  return 'has-appointment-high';
}

</script>




<template>
  <div class="calendar bg-[#fcf7f2] flex flex-col w-full h-full p-3 md:p-5 rounded-[20px] shadow-sm border border-[#a07a58]/20">
    
    <div class="calendar-header flex font-extrabold text-sm md:text-base text-[#523013] w-full items-center justify-between mb-3">
      <button class="nav-btn p-1.5 md:p-2 hover:bg-[#523013]/10 rounded-full transition-colors cursor-pointer disabled:cursor-not-allowed disabled:opacity-30" 
              @click="prevMonth" 
              :disabled="shouldBeDissabled || shouldJustView">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" 
             class="lucide size-4 md:size-5 rotate-180"><path d="m9 18 6-6-6-6"/></svg>
      </button>
      
      <span class="month-label truncate px-1 uppercase tracking-wide">{{ Meses[month] }} {{ year }}</span>
      
      <button class="nav-btn p-1.5 md:p-2 hover:bg-[#523013]/10 rounded-full transition-colors cursor-pointer disabled:cursor-not-allowed disabled:opacity-30" 
              @click="nextMonth" 
              :disabled="shouldJustView || (!allowFuture && isCurrentMonth)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" 
             class="lucide size-4 md:size-5"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>

    <div class="calendar-grid grid grid-cols-7 gap-y-1 gap-x-1 place-items-center w-full">
      <div class="day-name w-full text-center text-[10px] md:text-xs font-black opacity-50 uppercase pb-2 tracking-wider text-[#523013]" 
           v-for="d in ['Do','Lu','Ma','Mi','Ju','Vi','Sa']" :key="d">
        {{ d }}
      </div>

      <div v-for="(day, i) in prevDays" :key="'prev-'+i" class="day other-month">
        {{ day }}
      </div>

      <div v-for="day in currentDays" :key="'cur-'+day"
           class="day"
           :class="[{
             'today': isToday(day),
             'selected': isSelected(day),
             'disabled': isDisabled(day) || shouldBeClienteDissableref,
             'allowed': !isDisabled(day) && !shouldBeClienteDissableref,
           }, getIntensidad(day)]"
           :title="conteoCitas(day) > 0 ? `${conteoCitas(day)} cita(s)` : ''"
           @click="selectDay(day)">
        {{ day }}
      </div>

      <div v-for="(day, i) in nextDays" :key="'next-'+i" class="day other-month">
        {{ day }}
      </div>
    </div>
  </div>
</template>

<style scoped>
@reference "tailwindcss";

.day {
  /* mx-auto es vital para que las bolitas se centren si el calendario se hace muy ancho */
  @apply aspect-square w-full mx-auto flex items-center justify-center text-center text-[11px] md:text-sm rounded-full p-0 transition-all duration-200;
  max-width: 40px; /* Aumentado a 40px para mejor toque en celular */
}

.day.allowed {
  @apply cursor-pointer font-bold text-[#523013];
}

.day.allowed:hover {
  @apply bg-[#523013]/10 scale-105;
}

.day.has-appointment-high {
  @apply text-[#b51f1f] border-[2px] border-[#b51f1f] font-black bg-[#b51f1f]/5;
}

.day.has-appointment-medium {
  @apply text-[#812c8f] border-[2px] border-[#812c8f] font-extrabold bg-[#812c8f]/5;
}

.day.has-appointment-low {
  @apply border-[1px] border-[#a07a58] text-[#523013] bg-[#a07a58]/10;
}

.day.today {
  @apply ring-2 ring-[#523013] ring-offset-2 font-black;
}

.day.disabled {
  @apply text-[#523013]/25 cursor-not-allowed scale-90 bg-transparent border-transparent;
}

.day.other-month {
  @apply text-[#523013]/20 scale-90 font-medium;
}

.day.today.has-appointment-high {
  @apply outline-[2px] outline-[#b51f1f] outline-offset-2;
}

/* IMPORTANTE: Cuando está seleccionado, forzamos los colores para que no choquen con las citas rojas/moradas */
.day.selected {
  @apply bg-[#523013] !text-[#fcf7f2] shadow-lg scale-110 z-10 !border-transparent font-black; 
} 

@media (max-width: 320px) {
  .day { font-size: 10px; max-width: 32px; }
  .month-label { font-size: 12px; }
  .calendar { padding: 8px; }
}
</style>