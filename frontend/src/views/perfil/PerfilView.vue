<template>
  <div class="flex flex-col h-full w-full min-h-0 px-2 lg:px-0">
    
    <barra titulo="Mi Perfil" />

    <div class="content bg-[#52301338] mt-2 rounded-[20px] flex-1 flex flex-col overflow-hidden min-h-[400px] lg:min-h-0 pb-20 lg:pb-2"
         :class="!loaderState ? 'pt-2 px-3 md:px-5' : ''">
      
      <Transition name="fade-tabla" mode="out-in">
        
        <div v-if="loaderState === 'loading'" class="w-full h-full flex justify-center items-center">
          <Loader :state="loaderState" />
        </div>

        <div v-else-if="profile" class="w-full h-full flex flex-col items-center min-h-0">
          
          <div class="w-full max-w-7xl flex flex-col h-full min-h-0 overflow-y-auto custom-scrollbar pr-1 md:pr-2">
            <div class="perfil-grid grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4 py-2 w-full">

              <div class="perfil-card">
                <div class="card-section-title">Información personal</div>
                <div class="flex-1 flex flex-col">
                  <div class="dato-row">
                    <span class="dato-label">Nombre</span>
                    <span class="dato-value">{{ profile.nombre ?? '—' }}</span>
                  </div>
                  <div class="dato-row">
                    <span class="dato-label">Teléfono</span>
                    <span class="dato-value">{{ profile.telefono ?? '—' }}</span>
                  </div>
                  <div class="dato-row border-b-0">
                    <span class="dato-label">Correo</span>
                    <span class="dato-value" :title="profile.email">{{ profile.email ?? '—' }}</span>
                  </div>
                </div>
                <div class="card-footer mt-auto">
                  <button class="btn-editar" @click="abrirModal('personal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-currentColor>
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                    Editar
                  </button>
                </div>
              </div>

              <div class="perfil-card">
                <div class="card-section-title">Seguridad</div>
                <div class="flex-1 flex flex-col">
                  <div class="dato-row">
                    <span class="dato-label">Contraseña</span>
                    <span class="dato-value">••••••••</span>
                  </div>
                  <div class="dato-row border-b-0">
                    <span class="dato-label">Correo verificado</span>
                    <span class="badge shrink-0" :class="profile.email_verified_at ? 'badge-ok' : 'badge-warn'">
                      {{ profile.email_verified_at ? 'Verificado' : 'Sin verificar' }}
                    </span>
                  </div>
                </div>
                <div class="card-footer mt-auto">
                  <button class="btn-editar" @click="abrirModal('password')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-currentColor>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Cambiar contraseña
                  </button>
                </div>
              </div>

              <div class="perfil-card h-fit">
                <div class="card-section-title">Detalles de cuenta</div>
                <div class="flex-1 flex flex-col">
                  <div class="dato-row">
                    <span class="dato-label">Rol</span>
                    <span class="badge shrink-0" :class="badgeRol">{{ profile.rol?.nombre ?? '—' }}</span>
                  </div>
                  <div class="dato-row border-b-0">
                    <span class="dato-label">Estado</span>
                    <span class="badge shrink-0" :class="badgeEstado">{{ profile.estado ?? '—' }}</span>
                  </div>
                </div>
              </div>

              <div class="perfil-card" v-if="esCliente">
                <div class="card-section-title">Dirección</div>
                <div class="flex-1 flex flex-col">
                  <div class="dato-row">
                    <span class="dato-label">Municipio</span>
                    <span class="dato-value">{{ profile.cliente?.municipio ?? '—' }}</span>
                  </div>
                  <div class="dato-row">
                    <span class="dato-label">Colonia</span>
                    <span class="dato-value">{{ profile.cliente?.colonia ?? '—' }}</span>
                  </div>
                  <div class="dato-row">
                    <span class="dato-label">Calle</span>
                    <span class="dato-value">{{ profile.cliente?.calle ?? '—' }}</span>
                  </div>
                  <div class="dato-row">
                    <span class="dato-label">Num. Exterior</span>
                    <span class="dato-value">{{ profile.cliente?.numero_exterior ?? '—' }}</span>
                  </div>
                  <div class="dato-row border-b-0">
                    <span class="dato-label">Código Postal</span>
                    <span class="dato-value">{{ profile.cliente?.codigo_postal ?? '—' }}</span>
                  </div>
                </div>
                <div class="card-footer mt-auto">
                  <button class="btn-editar" @click="abrirModal('direccion')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-currentColor>
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                    Editar dirección
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div v-else class="w-full h-full flex justify-center items-center">
          <Loader state="error" />
        </div>

      </Transition>
    </div>

    <GenericModal
      :header="modalTitulo"
      :campos="camposActivos"
      :show="modalActivo"
      :model-value="modelActivo"
      @close="cerrarModal"
      @accept="guardar"
      @invalid="(msg) => showToast({ status: ToastStatus.WARNING, customMessage: msg })"
    />

  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import Loader from '@/components/Loader.vue';
import GenericModal from '@/components/modals/GenericModal.vue';
import { useProfile } from '@/composables/Useprofile';
import { useUserStore } from '@/stores/user';
import { useToast } from '@/composables/useToast';
import type { CampoForm } from '@/interfaces/CamposForm';
import barra from '@/components/PerfilBarra.vue';

const { profile, getProfile, updateProfile, updateDireccion, updatePassword } = useProfile();
const userStore = useUserStore();
const { showToast, ToastStatus } = useToast();

const esCliente = computed(() => userStore.isCliente());
const loaderState = ref<string | null>('loading');

const badgeRol = computed(() => {
  const rol = profile.value?.rol?.nombre?.toLowerCase() ?? '';
  if (rol.includes('admin')) return 'badge-admin';
  if (rol.includes('trabajador')) return 'badge-worker';
  return 'badge-ok';
});

const badgeEstado = computed(() => {
  return profile.value?.estado === 'registrado' ? 'badge-ok' : 'badge-warn';
});

const modalActivo = ref(false);
const modalTipo = ref<'personal' | 'password' | 'direccion' | null>(null);

const modalTitulo = computed(() => {
  if (modalTipo.value === 'personal')  return 'Editar información personal';
  if (modalTipo.value === 'password')  return 'Cambiar contraseña';
  if (modalTipo.value === 'direccion') return 'Editar dirección';
  return '';
});

const camposPersonal: CampoForm[] = [
  { label: 'Nombre completo', modelKey: 'nombre',   type: 'text',     required: false,  placeholder: 'Tu nombre' },
  { label: 'Teléfono',        modelKey: 'telefono',  type: 'tel',      required: false,  placeholder: '8710000000' },
  { label: 'Correo',          modelKey: 'email',     type: 'email',    required: false, placeholder: 'correo@ejemplo.com' },
];

const camposPassword: CampoForm[] = [
  { label: 'Contraseña actual', modelKey: 'password_actual', type: 'password', required: true, placeholder: '••••••••' },
  { label: 'Nueva contraseña', modelKey: 'password',         type: 'password', required: true, placeholder: '••••••••' },
];

const camposDireccion: CampoForm[] = [
  { label: 'Municipio',        modelKey: 'municipio',       type: 'text', required: false, placeholder: '' },
  { label: 'Colonia',          modelKey: 'colonia',         type: 'text', required: false, placeholder: '' },
  { label: 'Código postal',    modelKey: 'codigo_postal',   type: 'text', required: false, placeholder: '' },
  { label: 'Calle',            modelKey: 'calle',           type: 'text', required: false, placeholder: '' },
  { label: 'Núm. exterior',    modelKey: 'numero_exterior', type: 'text', required: false, placeholder: '' },
];

const camposActivos = computed<CampoForm[]>(() => {
  if (modalTipo.value === 'personal')  return camposPersonal;
  if (modalTipo.value === 'password')  return camposPassword;
  if (modalTipo.value === 'direccion') return camposDireccion;
  return [];
});

const modelActivo = computed(() => {
  if (modalTipo.value === 'personal') {
    return {
      nombre:   profile.value?.nombre   ?? '',
      telefono: profile.value?.telefono ?? '',
      email:    profile.value?.email    ?? '',
    };
  }
  if (modalTipo.value === 'direccion') {
    const c = profile.value?.cliente ?? {};
    return {
      municipio:       c.municipio       ?? '',
      colonia:         c.colonia         ?? '',
      codigo_postal:   c.codigo_postal   ?? '',
      calle:           c.calle           ?? '',
      numero_exterior: c.numero_exterior ?? '',
    };
  }
  return null;
});

const abrirModal = (tipo: 'personal' | 'password' | 'direccion') => {
  modalTipo.value = tipo;
  modalActivo.value = true;
};

const cerrarModal = () => {
  modalActivo.value = false;
  modalTipo.value = null;
};

const guardar = async (data: Record<string, any>) => {
  if (modalTipo.value === 'personal')  await updateProfile(data as any);
  if (modalTipo.value === 'password')  await updatePassword(data as any);
  if (modalTipo.value === 'direccion') await updateDireccion(data as any);
};

onMounted(async () => {
  const result = await getProfile();
  loaderState.value = result?.data?.value?.data ? 'success' : 'error';
  setTimeout(() => { loaderState.value = null; }, 800);
});
</script>

<style scoped>
@reference "tailwindcss";

.fade-tabla-enter-active,
.fade-tabla-leave-active {
  transition: opacity 0.2s ease;
}
.fade-tabla-enter-from,
.fade-tabla-leave-to {
  opacity: 0;
}

.perfil-card {
  @apply bg-[#fcf7f2] rounded-[20px] overflow-hidden flex flex-col shadow-sm border border-[#a07a58]/30 transition-transform duration-200 hover:scale-[1.01];
}

.card-section-title {
  @apply py-3 md:py-4 px-4 md:px-5 border-b border-[#a07a58] flex justify-between items-center bg-[#fcf7f2] font-extrabold text-[#523013] text-lg md:text-xl;
}

.dato-row {
  @apply py-3 md:py-2 px-4 md:px-5 border-b border-[#a07a58]/20 flex justify-between items-center bg-[#fcf7f2] font-bold text-[#523013] gap-4;
}

.dato-label {
  @apply text-sm text-[#523013]/80 font-medium shrink-0;
}

.dato-value {
  @apply text-sm text-[#523013] font-bold truncate text-right;
}

.card-footer {
  @apply py-3 px-4 md:px-5 border-t border-[#a07a58] flex justify-center md:justify-end items-center bg-[#fcf7f2];
}

.btn-editar {
  @apply flex justify-center items-center rounded-md px-4 py-2 bg-transparent text-[#812c8f] text-sm md:text-base font-extrabold cursor-pointer hover:bg-[#812c8f] hover:text-[#fcf7f2] transition-colors duration-200;
}

.badge {
  @apply text-[10px] md:text-xs font-bold px-3 py-1 rounded-[20px] text-center;
}
.badge-admin  { background: #dc7474; color: #fcf7f2; }
.badge-worker { background: #523013; color: #fcf7f2; }
.badge-ok     { background: #812c8f; color: #fcf7f2; }
.badge-warn   { background: #b51f1f; color: #fcf7f2; }

/* Sin comentarios con emojis pq todavia queda tantita dignidad */

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(82, 48, 19, 0.2);
  border-radius: 10px;
}
</style>