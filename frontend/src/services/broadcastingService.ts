import { useUserStore } from '@/stores/user';
import { token } from '@/stores/auth';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
/*
console.log('=== VITE ENV ===', {
    broadcaster: import.meta.env.VITE_BROADCAST_CONNECTION,
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST,
    port: import.meta.env.VITE_REVERB_PORT,
})
*/

window.Pusher = Pusher;

const listeners = new Map<string, Array<(e: any) => void>>();

export const createSocket = () => {

    const authStore = token();
    
    window.Echo = new Echo({
        broadcaster: import.meta.env.VITE_BROADCAST_CONNECTION,
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        authEndpoint: import.meta.env.VITE_AUTH_ENDPOINT,
        auth: {
            headers: {
                Accept:'application/json',
                Authorization: `Bearer ${authStore.getToken()}`
            }
        }
    });
};

function subscribeToSharedChannel(channelName: string, callback: (e: any) => void) {
    createSocket();

    if (!listeners.has(channelName)) {
        listeners.set(channelName, []);
        
        console.log(`[WS] Estableciendo suscripción privada a: ${channelName}`);
        
        window.Echo.private(channelName)
            .listen('.AppointmentEvent', (e: any) => {
                console.log(`[WS] Evento recibido en ${channelName}:`, e.action);
                const callbacks = listeners.get(channelName);
                callbacks?.forEach(cb => cb(e));
            });
    }

    listeners.get(channelName)?.push(callback);
}

export function adminConnection(callback: (e: any) => void): void {
    const userStore = useUserStore();
    const userId = userStore.getUser?.id;
    
    if (userId) {
        subscribeToSharedChannel(`App.Appointment.Admin.${userId}`, callback);
    }
}

export function userConnection(callback: (e: any) => void): void {
    const userStore = useUserStore();
    const userId = userStore.getUser?.id;

    if (userId) {
        subscribeToSharedChannel(`App.Appointment.User.${userId}`, callback);
    }
}

export function leaveConnection(connectionName: string) {
    if (listeners.has(connectionName)) {
        window.Echo.leave(connectionName);
        listeners.delete(connectionName);
        console.log(`[WS] Canal abandonado y listeners limpiados: ${connectionName}`);
    }
}