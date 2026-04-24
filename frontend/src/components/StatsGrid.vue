<script setup lang="ts">
import { Cita } from '@/Types/Cita';
import { computed } from 'vue';

type StatsData = {
  pendientes?: number,
  usuarios?: number,
  mascotas?: number
}


const props = defineProps<{
  stats?: StatsData
  proximaCita: Cita | null
  loading: boolean
}>();

const modoReporte = computed(() => {
  return props.stats?.usuarios !== undefined || props.stats?.mascotas !== undefined;
});

const valorPrincipal = computed(() => {
  if (modoReporte.value) return props.stats?.usuarios ?? 0;
  return props.stats?.pendientes ?? 0;
});

const valorSecundario = computed(() => {
  if (modoReporte.value) return props.stats?.mascotas ?? 0;
  return null;
});

const fechaFormato = computed(() => {
    if(!props.proximaCita) return '';
    const fecha = new Date(props.proximaCita.fecha);
    return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
});

const horaFormato = computed(() => {
    const hora = props.proximaCita?.horario_trabajador?.horario?.inicio_hora;
    if(!hora) return 'Pendiente';
    return hora.split(':').slice(0, 2).join(':') + ' hrs';
});
</script>

<template>
  <div class="flex flex-col md:flex-row gap-3 md:gap-6 py-2 md:py-4 w-full shrink-0">
    
    <div class="flex-1 bg-[#fcf7f2] rounded-[20px] p-4 md:p-5 flex items-center justify-between transition-all duration-300 shadow-md md:shadow-lg">
      <div class="flex flex-col gap-0.5 md:gap-1">
        <span class="text-[#523013] text-[10px] font-bold uppercase opacity-60">
          {{ modoReporte ? 'Registros Globales' : '' }}
        </span>
        <h4 class="text-[#523013] text-lg md:text-xl font-black leading-tight">
          {{ modoReporte ? 'Nuevos Usuarios' : 'Citas Pendientes' }}
        </h4>
      </div>

      <div v-if="loading">
        <p class="text-[#812c8f] text-sm md:text-base font-bold">Cargando...</p> 
      </div>
      <div v-else class="text-[#812c8f] text-xl md:text-2xl font-black">
        {{ valorPrincipal }}
      </div>
    </div>

    <div class="flex-1 bg-[#fcf7f2] rounded-[20px] p-4 md:p-5 flex items-center justify-between transition-all duration-300 shadow-md md:shadow-lg">
      <div class="flex flex-col gap-0.5 md:gap-1">
        <span class="text-[#523013] text-[10px] font-bold uppercase opacity-60">
           {{ modoReporte ? 'Globales' : '' }}
        </span>
        <h3 class="text-[#523013] text-lg md:text-xl font-black leading-tight">
          {{ modoReporte ? 'Nuevas Mascotas' : 'Próxima Cita' }}
        </h3>
      </div>
      
      <div class="text-right">
        <p v-if="loading" class="text-[#b51f1f] text-sm md:text-base font-bold">Cargando...</p>

        <div v-else-if="modoReporte" class="text-[#b51f1f] text-2xl md:text-4xl font-black">
          {{ valorSecundario }}
        </div>

        <div v-else-if="proximaCita" class="flex flex-col md:flex-row md:items-baseline md:gap-3">
          <p class="text-[#b51f1f] text-sm md:text-base font-bold">
            {{ fechaFormato }}
          </p>
          <p class="text-[#b51f1f] text-sm md:text-base font-bold">
            {{ horaFormato }}
          </p>
        </div>

        <p v-else class="text-[#523013]/40 text-xs md:text-sm font-bold italic">
          Sin registros
        </p>
      </div>
    </div>
    
  </div>
</template>

<style scoped>
div {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>