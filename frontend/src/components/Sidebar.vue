<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useSidebar } from '@/composables/useSidebar';
import SidebarItem from './SidebarItem.vue';
import SidebarItemDesplegable from './SidebarItemDesplegable.vue';
import SidebarShortcut from './SidebarShortcut.vue';
import { useRouter,useRoute } from 'vue-router';
import { useUserStore } from '@/stores/user';
import { useToast } from '@/composables/useToast';
import { useGlobalModal } from '@/composables/useGlobalModal';
import CitaModalWrapper from './CitaModalWrapper.vue';
import { camposCliente } from '@/data/CamposCliente';
import { camposMascota } from '@/data/CamposMascota';
import { useMascota } from '@/composables/useMascota';
import { useCliente } from '@/composables/useCliente';
import { useCita } from '@/composables/useCita';
import GenericModal from './modals/GenericModal.vue';
import { User } from '@/data/RegistroCliente';

const {openGlobalModal, closeGlobalModal} = useGlobalModal();
const {storeMascota, 
    animalList,
    clientesList,
    getFullList,
    getFullListRazas,
    getFullClientes,
    filtrarRazas, 
    configurarCamposMascota} = useMascota();
const {storeCliente} = useCliente();
const {citastore} = useCita();
const {showToast, ToastStatus} = useToast();

const UserStore = useUserStore();
const router = useRouter();
const route = useRoute();

// Inicializamos sidebarExpanded en false para móviles, true para escritorio
const sidebarExpanded = ref(typeof window !== 'undefined' ? window.innerWidth >= 768 : true);

const { activeDropdown, toggle } = useSidebar();

const permiso = computed(() => {
    return UserStore.isAdmin() || UserStore.isTrabajador()
});

const toggleSidebar = () => {
    sidebarExpanded.value = !sidebarExpanded.value;
};

// Auto-cerrar en móvil
const checkMobileClose = () => {
    if (typeof window !== 'undefined' && window.innerWidth < 768) {
        sidebarExpanded.value = false;
    }
};

const itemSelected = ref(localStorage.getItem('menuSeleccionado') || 'main');

const navegarA = (nombreRuta) => {
    itemSelected.value = nombreRuta;
    localStorage.setItem('menuSeleccionado', nombreRuta); 
    router.push({ name: nombreRuta }); 
    checkMobileClose(); // Cierra en móvil
}

watch(() => route.name, (nuevoNombreRuta) => {
    if (nuevoNombreRuta === 'perfil') {
        itemSelected.value = null; 
    } else {
        itemSelected.value = nuevoNombreRuta || 'main';
    }
});

const mantenerMenuAbierto = () => {
    const rutasCatalogos = ['animales', 'razas', 'productos', 'servicios'];
    const rutasUsuarios = ['users-trabajadores', 'users-clientes'];
    if (rutasCatalogos.includes(itemSelected.value)) {
        activeDropdown.value = 'catalogos';
    } else if (rutasUsuarios.includes(itemSelected.value)) {
        activeDropdown.value = 'usuarios';
    }
};

onMounted(() => {
    mantenerMenuAbierto();
    // Escuchar cambios de tamaño para ajustar el menú automáticamente
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && !sidebarExpanded.value) {
            sidebarExpanded.value = true;
        } else if (window.innerWidth < 768 && sidebarExpanded.value) {
            sidebarExpanded.value = false;
        }
    });
});

watch(itemSelected, () => {
    mantenerMenuAbierto();
});

const nuevoCliente = () => {
    checkMobileClose();
    openGlobalModal({
        component: GenericModal,
        header: 'Añadir nuevo cliente',
        props: { campos: camposCliente },
        onAccept: async (data) => {
            await storeCliente(data);
        }
    });
};

const nuevaMascota = async () => {
    checkMobileClose();
    if (animalList.value.length === 0 || clientesList.value.length === 0) {
        await Promise.all([
            getFullList(),
            getFullListRazas(),
            getFullClientes()
        ]);
    }
    const campos = configurarCamposMascota(camposMascota, UserStore);
    openGlobalModal({
        component: GenericModal,
        header: 'Añadir nueva mascota',
        props: { campos: campos, show: true },
        onAccept: async (data) => { await storeMascota(data); }
    });
}

const agendarCita = () => {
    checkMobileClose();
    openGlobalModal({
        component: CitaModalWrapper,
        header: 'Agendar cita',
        onAccept: (mensaje) => {
            showToast({ status: ToastStatus.CREATED, customMessage: mensaje });
            closeGlobalModal();
        }
    })
};
</script>

<template>
    <button v-if="!sidebarExpanded" @click="toggleSidebar"
        class="md:hidden fixed bottom-6 right-6 z-[100] w-14 h-14 bg-[#523013] text-[#fcf7f2] rounded-full shadow-2xl flex items-center justify-center border-2 border-[#a07a58] hover:scale-105 transition-transform">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div v-if="sidebarExpanded" @click="toggleSidebar" class="md:hidden fixed inset-0 bg-black/50 z-[105] backdrop-blur-sm"></div>

    <aside :class="[
        'bg-[#fce9d8] border-[#a07a58] transition-all duration-300 flex flex-col',
        sidebarExpanded ? 'fixed inset-y-0 left-0 z-[110] w-[80vw] max-w-[320px] shadow-2xl translate-x-0' : 'fixed inset-y-0 left-0 z-[110] w-[80vw] max-w-[320px] -translate-x-full',
        'md:relative md:h-screen md:translate-x-0 md:shadow-none md:border-r-2',
        sidebarExpanded ? 'md:w-70' : 'md:w-15'
    ]">

        <div class="flex items-center h-12 px-3 md:px-5 py-2 bg-[#523013] border-[#a07a58] border-b-2 shrink-0 justify-between md:justify-center"
             :class="{'md:justify-between': sidebarExpanded}">
            
            <span :class="sidebarExpanded ? 'block' : 'block md:hidden'" class="font-extrabold text-[#fcf7f2] text-lg tracking-wider mr-auto">
                VetApp
            </span>

            <button @click="toggleSidebar"
                class="btn-expand text-[#fcf7f2] transition-transform cursor-pointer font-extrabold flex items-center justify-center">
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 block md:hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6" />
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-6 transition-transform duration-400 hidden md:block"
                    :class="sidebarExpanded ? 'rotate-180' : 'rotate-0'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-col flex-1 pb-2 overflow-y-auto overflow-x-hidden">
            <SidebarItem texto="Inicio" :isExpanded="sidebarExpanded" :isSelected="itemSelected === 'main'" @select="navegarA('main')">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </template>
            </SidebarItem>

            <SidebarItemDesplegable texto="Catálogos" :isExpanded="sidebarExpanded" :open="activeDropdown === 'catalogos'"
                @toggle="toggle('catalogos')" v-if="permiso">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                    </svg>
                </template>
                <template #sub-items>
                    <SidebarItem texto="Animales" :isExpanded="sidebarExpanded"
                        :isSelected="itemSelected === 'animales'"
                        :is-sub-item="true"
                        @select="navegarA('animales')"
                        :class="itemSelected === 'animales' ? 'pl-20 hover:pl-20' : 'pl-16 hover:pl-20'">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="size-5 shrink-0">
                                <path
                                    d="M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5Z" />
                            </svg>
                        </template>
                    </SidebarItem>

                    <SidebarItem texto="Razas" :isExpanded="sidebarExpanded"
                        :isSelected="itemSelected === 'razas'"
                        :is-sub-item="true"
                        @select="navegarA('razas')"
                        :class="itemSelected === 'razas' ? 'pl-20 hover:pl-20' : 'pl-16 hover:pl-20'">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="size-5 shrink-0">
                                <path d="M13 22H4a2 2 0 0 1 0-4h12" />
                                <path d="M13.236 18a3 3 0 0 0-2.2-5" />
                                <path d="M16 9h.01" />
                                <path
                                    d="M16.82 3.94a3 3 0 1 1 3.237 4.868l1.815 2.587a1.5 1.5 0 0 1-1.5 2.1l-2.872-.453a3 3 0 0 0-3.5 3" />
                                <path d="M17 4.988a3 3 0 1 0-5.2 2.052A7 7 0 0 0 4 14.015 4 4 0 0 0 8 18" />
                            </svg>
                        </template>
                    </SidebarItem>

                    <SidebarItem texto="Productos" :isExpanded="sidebarExpanded"
                        :isSelected="itemSelected === 'productos'"
                        :is-sub-item="true"
                        @select="navegarA('productos')"
                        :class="itemSelected === 'productos' ? 'pl-20 hover:pl-20' : 'pl-16 hover:pl-20'">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="size-5 shrink-0">
                                <circle cx="8" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path
                                    d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                            </svg>
                        </template>
                    </SidebarItem>

                    <SidebarItem texto="Servicios" :isExpanded="sidebarExpanded"
                        :isSelected="itemSelected === 'servicios'"
                        :is-sub-item="true"
                        @select="navegarA('servicios')"
                        :class="itemSelected === 'servicios' ? 'pl-20 hover:pl-20' : 'pl-16 hover:pl-20'">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="size-5 shrink-0">
                                <path d="m4 4 2.5 2.5" />
                                <path d="M13.5 6.5a4.95 4.95 0 0 0-7 7" />
                                <path d="M15 5 5 15" />
                                <path d="M14 17v.01" />
                                <path d="M10 16v.01" />
                                <path d="M13 13v.01" />
                                <path d="M16 10v.01" />
                                <path d="M11 20v.01" />
                                <path d="M17 14v.01" />
                                <path d="M20 11v.01" />
                            </svg>
                        </template>
                    </SidebarItem>
                </template>
            </SidebarItemDesplegable>

            <SidebarItem texto="Mascotas" :isExpanded="sidebarExpanded"
                :isSelected="itemSelected === 'perfil-mascotas'"
                @select="navegarA('perfil-mascotas')">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6 shrink-0">
                        <circle cx="11" cy="4" r="2" />
                        <circle cx="18" cy="8" r="2" />
                        <circle cx="20" cy="16" r="2" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
                    </svg>
                </template>
            </SidebarItem>

            <SidebarItem texto="Citas" :isExpanded="sidebarExpanded"
                :isSelected="itemSelected === 'perfil-citas'"
                @select="navegarA('perfil-citas')">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </template>
            </SidebarItem>

            <SidebarItemDesplegable texto="Usuarios" :isExpanded="sidebarExpanded" :open="activeDropdown === 'usuarios'"
                @toggle="toggle('usuarios')" v-if="permiso">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                    </svg>
                </template>

                <template #sub-items>
                    <SidebarItem texto="Trabajadores" :isExpanded="sidebarExpanded"
                        :isSelected="itemSelected === 'users-trabajadores'"
                        :is-sub-item="true"
                        @select="navegarA('users-trabajadores')"
                        :class="itemSelected === 'users-trabajadores' ? 'pl-20 hover:pl-20' : 'pl-16 hover:pl-20'"
                        v-if="UserStore.isAdmin()">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                            </svg>
                        </template>
                    </SidebarItem>

                    <SidebarItem texto="Clientes" :isExpanded="sidebarExpanded"
                        :isSelected="itemSelected === 'users-clientes'"
                        :is-sub-item="true"
                        @select="navegarA('users-clientes')"
                        :class="itemSelected === 'users-clientes' ? 'pl-20 hover:pl-20' : 'pl-16 hover:pl-20'"
                        v-if="permiso">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </template>
                    </SidebarItem>
                </template>
            </SidebarItemDesplegable>

            <SidebarItem texto="Reportes" :isExpanded="sidebarExpanded"
                :isSelected="itemSelected === 'admin-reportes'"
                @select="navegarA('admin-reportes')" v-if="UserStore.isAdmin()">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </template>
            </SidebarItem>

            <SidebarItemDesplegable texto="Atajos" :isExpanded="sidebarExpanded" :open="activeDropdown === 'atajos'" @toggle="toggle('atajos')" class="mt-auto border-t border-[#a07a58]">
                <template #icono>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                        class="size-6 shrink-0">
                        <circle cx="6" cy="19" r="3" />
                        <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15" />
                        <circle cx="18" cy="5" r="3" />
                    </svg>
                </template>

                <template #sub-items>
                    <SidebarShortcut texto="Añadir Cliente" @click="nuevoCliente" :isExpanded="sidebarExpanded"
                        class="pl-16 hover:pl-20" v-if="permiso">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                            </svg>
                        </template>
                    </SidebarShortcut>

                    <SidebarShortcut texto="Añadir Mascota" @click="nuevaMascota" :isExpanded="sidebarExpanded"
                        class="pl-16 hover:pl-20">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5 shrink-0">
                                <circle cx="11" cy="4" r="2" />
                                <circle cx="18" cy="8" r="2" />
                                <circle cx="20" cy="16" r="2" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
                            </svg>
                        </template>
                    </SidebarShortcut>

                    <SidebarShortcut texto="Agendar Cita" @click="agendarCita" :isExpanded="sidebarExpanded"
                        class="pl-16 hover:pl-20">
                        <template #icono>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </template>
                    </SidebarShortcut>
                </template>
            </SidebarItemDesplegable>
        </nav>
    </aside>
</template>

<style scoped>

@reference "tailwindcss";
.btn-expand:hover {
@apply scale-110 text-[#dc7474];
}
</style>