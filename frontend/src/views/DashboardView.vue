<script setup lang="ts">
import Sidebar from '@/components/Sidebar.vue';
import ButtonVoid from '@/components/ButtonVoid.vue';
import { fetchWithVerifying } from '@/services/api2';
import router from '@/router';
import { useUserStore } from '@/stores/user';
import { onMounted, onUnmounted, ref, watch, nextTick } from 'vue';
import { computePosition, shift, flip } from '@floating-ui/dom';
import { createSocket, adminConnection, userConnection, leaveConnection } from '@/services/broadcastingService.ts';
import { useRoute } from 'vue-router'
import ModalConfirm from '@/components/ModalConfirm.vue';
import CitaModal from '@/components/modals/CitaModal.vue'

const route = useRoute()
const UserStore = useUserStore()

const notifications = ref<any[]>([]);
const notifyme = ref<boolean>(false);
const showCitaModal = ref<boolean>(false);
const selectedCita = ref<any>(null);
const confirmAction = ref<string | null>('');

const button = ref<any>(null);
const notwrapper = ref<any>(null);

const visibilityHanddler = (event: MouseEvent) => {
    const btnEl = button.value?.$el || button.value;
    if (notwrapper.value && btnEl && !btnEl.contains(event.target as Node) && !notwrapper.value.contains(event.target as Node)) {
        notifyme.value = false;
    }
}

const getChannelName = () => {
    const user = UserStore.getUser;
    if (!user?.id) return null;
    return user.rol === 'administrador' 
        ? `App.Appointment.Admin.${user.id}` 
        : `App.Appointment.User.${user.id}`;
};

// MEJORA: Sacamos updatePosition afuera para poder llamarla cuando se abra el menú
const updatePosition = async () => {
    // Si no está visible, no gastamos recursos calculando
    if (!notifyme.value) return; 

    // nextTick asegura que Vue ya le quitó el "display: none" antes de medir
    await nextTick(); 
    
    const referenceEl = button.value?.$el || button.value; 
    const floatingEl = notwrapper.value;

    if (!referenceEl || !floatingEl) return;
    
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        Object.assign(floatingEl.style, {
            position: 'fixed',
            top: '60px',
            left: '50%',
            transform: 'translateX(-50%)',
            width: '90vw',
            maxWidth: '400px'
        });
    } else {
        floatingEl.style.width = '350px';
        
        computePosition(referenceEl, floatingEl, {
            placement: 'bottom-end', 
            strategy: 'fixed', 
            middleware: [flip(), shift({ padding: 20 })] 
        }).then(({ x, y }) => {
            Object.assign(floatingEl.style, {
                position: 'fixed',
                transform: 'none',
                left: `${x}px`,
                top: `${y}px`
            });
        });
    }
}

// MEJORA: Vigila cuando notifyme cambia a true y recalcula
watch(notifyme, (newVal) => {
    if (newVal) {
        updatePosition();
    }
});

onMounted(() => {
    // Escuchamos el resize de la pantalla
    window.addEventListener('resize', updatePosition);
    window.addEventListener('click', visibilityHanddler, true);

    createSocket();
    
    const user = UserStore.getUser;
    if (user?.rol === 'administrador') {
        adminConnection((e) => { notifications.value.push(e); });
    } else if (user?.rol === 'cliente' || user?.rol === 'trabajador') {
        userConnection((e) => { notifications.value.push(e); });
    }
});

onUnmounted(() => {
    window.removeEventListener('resize', updatePosition);
    window.removeEventListener('click', visibilityHanddler, true);
    
    const channel = getChannelName();
    if (channel) leaveConnection(channel);
});

async function cerrarSesion() {
    const response = await fetchWithVerifying('auth/logout').post().json();
    if (response.statusCode.value == 200) {
        const channel = getChannelName();
        if (channel) leaveConnection(channel);

        UserStore.removeToken();
        UserStore.setUser({ rol: "", name: "", id: "" });
        router.push('/login');
    }
}

function asRead(notification: any) {
    const index = notifications.value.findIndex(n => n.data.uuid === notification.data.uuid);
    if (index !== -1) notifications.value.splice(index, 1);
}

const pendingAppointment = ref<any>(null);
watch(() => route.path, async (newPath) => {
    if (newPath === '/dashboard/perfil/citas' && pendingAppointment.value) {
        await nextTick();
        selectedCita.value = pendingAppointment.value;
        pendingAppointment.value = null;
        showCitaModal.value = true;
    }
})

const miPerfil = (nombreRuta: string) => {
    router.push({ name: nombreRuta });
};

async function verCita(notification: any) {
    notifyme.value = false;
    if (route.path === '/dashboard/perfil/citas') {
        selectedCita.value = notification.cita;
        showCitaModal.value = true;
    } else {
        pendingAppointment.value = notification.cita;
        await router.push('/dashboard/perfil/citas'); 
    }
    asRead(notification);
}

const abrirModalConfirm = () => { confirmAction.value = 'logout'; };
</script>

<template>
    <ModalConfirm :action="confirmAction !== '' ? 'logout' : ''" 
    @confirm="cerrarSesion(), confirmAction = ''"
    @close="confirmAction = ''"
    />
    <CitaModal v-if="showCitaModal" :cita="selectedCita" @cerrar="showCitaModal=false" />

    <div v-show="notifyme" :class="['wrapper-notis w-[90vw] sm:w-[350px]', { visible: notifyme }]" id="wrapper-not" ref="notwrapper">
        <div class="text-wrapper" v-for="notification in notifications" >
            <div>
                <p class="text-notification">{{notification.cita.mascota.nombre}}</p>
                <div class="notification-wrapper" >
                    <div>
                        <p class="notification-message">{{notification.data?.mensaje}}</p>
                        <div class="status-row" v-if="notification?.action=='estado'">
                            <span :class="['tag', notification.data.antiguo]">{{notification.data.antiguo.replace('_', ' ')}}</span>
                            <span> → </span>
                            <span :class="['tag', notification.data.nuevo]">{{notification.data.nuevo.replace('_', ' ')}}</span>
                        </div>
                        <div class="status-row" v-else-if="notification?.action=='creation'">
                            <span class="tag creation">Cita nueva</span>
                        </div>
                        <div class="status-row" v-else-if="notification?.action=='modificacion'">
                            <span class="tag modification">Cita modificada</span>
                        </div>
                        <p class="name-noti">por {{notification.sendBy.nombre}}</p>
                    </div>
                    
                    <div class="btn-action-wrapper mt-2">
                        <button type="button" class="btn-actions" @click="asRead(notification)">Descartar</button>
                        <button type="button" class="btn-actions primary" @click.stop="verCita(notification)">Ver cita</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="notifications.length <= 0" class="empty-state">
            <p class="empty-title">Nada por aquí</p>
            <p class="empty-subtitle">Te notificaremos cuando algo nuevo succeda</p>
        </div>
    </div>

    <div class="dashboard-layout flex flex-col md:flex-row w-full h-screen overflow-hidden">
        
        <Sidebar class="z-50" /> 
        
        <div class="flex-1 flex flex-col w-full h-full relative overflow-hidden"> 
            
            <header class="topbar flex w-full h-auto min-h-[48px] items-center justify-between bg-[#dc7474] px-3 md:px-5 py-2 border-b-2 border-[#a07a58] z-20 shadow-md">
                
                <span class="font-extrabold text-[#fcf7f2] text-sm md:text-lg truncate max-w-[40%] md:max-w-none">
                    ¡Hola, {{UserStore.getUser?.name?.split(' ')[0] || "Usuario"}}!
                </span>

                <div class="header-butons flex items-center gap-2 ml-auto">
    
                    <ButtonVoid ref="button" class="w-10 md:w-auto !px-0 md:!px-4 !justify-center relative shadow-2xl hover:bg-[#523013] hover:border-[#523013] hover:text-[#fcf7f2] transition-all" 
                        :class="notifyme ? 'bg-[#523013] text-[#fcf7f2] border-[#523013]' : 'bg-[#fcf7f2] text-[#523013] border-[#fcf7f2]'"
                        @click="notifyme=!notifyme">
                        <template #icono-button>
                            <div class="relative flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="size-5 md:mr-2">
                                    <path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/>
                                </svg>
                                <span v-if="notifications.length > 0" class="absolute -top-1.5 -right-2 bg-[#812c8f] text-[#fcf7f2] text-[9px] font-bold px-1 min-w-[16px] h-[16px] flex items-center justify-center rounded-full border border-[#fcf7f2] shadow-sm leading-none z-10">
                                    {{ notifications.length > 99 ? '99+' : notifications.length }}
                                </span>
                            </div>
                        </template>
                        <span class="hidden md:inline font-bold">Notificaciones</span>
                    </ButtonVoid>

                    <ButtonVoid class="w-10 md:w-auto !px-0 md:!px-4 !justify-center shadow-2xl hover:bg-[#812c8f] hover:border-[#812c8f] hover:text-[#fcf7f2] transition-all" 
                        :class="$route.path === '/perfil' || $route.name === 'perfil' ? 'bg-[#812c8f] border-[#812c8f] text-[#fcf7f2]' : 'bg-[#fcf7f2] border-[#fcf7f2] text-[#812c8f]'"
                        @click="miPerfil('perfil')">
                        <template #icono-button>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5 md:mr-2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </template>
                        <span class="hidden md:inline font-bold">Mi Perfil</span>
                    </ButtonVoid>

                    <ButtonVoid class="w-10 md:w-auto !px-0 md:!px-4 !justify-center bg-[#fcf7f2] text-[#b51f1f] border-[#fcf7f2] shadow-2xl hover:bg-[#b51f1f] hover:border-[#b51f1f] hover:text-[#fcf7f2] transition-all" 
                        @click="abrirModalConfirm">
                        <template #icono-button>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5 md:mr-2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>
                        </template>
                        <span class="hidden md:inline font-bold">Salir</span>
                    </ButtonVoid>

                </div>
            </header>

            <main class="w-full flex-1 relative overflow-y-auto overflow-x-hidden px-0 py-2">
                <div class="fixed inset-0 flex flex-col md:flex-row items-center justify-center gap-10 md:gap-20 pt-10 md:pt-40 px-5 md:px-20 pointer-events-none z-0 opacity-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[40%] md:w-[70%] h-auto -rotate-90 text-[#523013]" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[40%] md:w-[70%] h-auto text-[#523013]" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/>
                    </svg>
                </div>
                
                <div class="absolute inset-0 px-2 md:px-4 pt-0 py-3.5 z-10 min-h-full flex flex-col">
                    <router-view></router-view>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";
.dashboard-layout {
    background: #eddcce;
}
.circle{
    @apply bg-[#812c8f] rounded-full;
}
.notification-message{
    @apply text-xs font-bold text-[#523013]/80 mb-1;
}

/* MEJORA: Cambio a fixed puro, esquinas totalmente redondeadas y z-[150] asegurado */
.wrapper-notis {
    @apply fixed px-5 py-2 mt-2 rounded-[20px] bg-[#e2d2c4] border border-[#523013] overflow-y-auto flex flex-col gap-2 z-[150] shadow-2xl max-h-[calc(100vh-100px)];
}

.visible{
    visibility:visible;
}
.text-wrapper{
    @apply text-[#523013] text-base w-full font-bold text-start my-1 pt-2 border-t border-[#a07a58];
}
.agendada{
    @apply text-[#fcf7f2] bg-[#dc7474];
}
.en_proceso{
    @apply text-[#fcf7f2] bg-[#523013];
}
.cancelada{
    @apply text-[#fcf7f2] bg-[#b51f1f];
}
.consulta{
    @apply text-[#fcf7f2] bg-[#523013];
}
.event-tag{
    @apply block text-xs font-bold py-1 px-2 rounded-[20px];
}
.tag.creation{
    @apply font-bold text-xs text-[#fcf7f2] bg-[#dc7474];
}
.tag.modification{
    @apply font-bold text-xs text-[#fcf7f2] bg-[#523013];
}
.tag{
    @apply font-bold text-xs py-0.5 px-2 rounded-[20px];
}
.realizada{
    @apply text-[#fcf7f2] bg-[#812c8f];
}
.name-noti{
    @apply text-xs font-bold text-[#523013]/80 mt-1;
}
.modificacion{
    @apply font-bold text-xs text-[#fcf7f2] bg-[#523013];
}
.notification-number{
    @apply absolute text-xs bg-[#812c8f] text-[#fcf7f2] p-2 rounded-full font-bold;
}
.notification-wrapper{
    @apply w-full grid grid-cols-1 gap-0.5;
}
.btn-actions{
    @apply flex flex-1 px-5 py-2 justify-center text-xs font-bold text-[#b51f1f] bg-[#fcf7f2] rounded-md cursor-pointer;
}
.btn-actions:hover{
    @apply scale-102 text-[#fcf7f2] bg-[#b51f1f];
}
.btn-actions.primary{
    @apply text-[#812c8f] bg-[#fcf7f2];
}
.btn-actions.primary:hover{
    @apply scale-102 text-[#fcf7f2] bg-[#812c8f];
}
.btn-action-wrapper{
    @apply flex items-center justify-between gap-2
}
.text-notification{
    @apply text-sm font-bold text-[#523013] mb-1;
}
.status-row{
    @apply flex items-center gap-1;
}
.empty-state {
    @apply flex flex-col items-center justify-center text-center;
}
.empty-title {
    @apply text-base font-bold text-[#523013] mb-2;
}
.empty-subtitle {
    @apply text-xs font-bold text-[#523013]/80 mb-2;
}
</style>