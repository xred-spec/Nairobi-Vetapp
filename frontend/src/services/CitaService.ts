import {fetchWithVerifying} from './api2';
const CitaService = {
    searchWithoutHistory(payload:any,page:number){
        return fetchWithVerifying(`cita/search/${0}?page=${page??1}`).post(payload).json();
    },
    prefetchingCita(){
        return fetchWithVerifying('cita/prefetching-cita').get().json();
    },
    getMascotasCitas(id:number){
        return fetchWithVerifying('cita/mascotas/' + id).get().json();
    },
    getClientesCitas(payload:any){
        return fetchWithVerifying('cita/clientes').post({nombre:payload}).json();
    },
    getDisponibilidadHora(id:number,fecha:string){
        return fetchWithVerifying('cita/disponibilidad/'+id+'/'+fecha).get().json();
    },
    citastore(payload:any){
        return fetchWithVerifying('cita/store').post(payload).json();
    },
    changestatus(id:number,payload:any){
    return fetchWithVerifying('cita/changeStatus/'+id).patch(payload).json();
    },
    prefetchingEditCita(id:number){
        return fetchWithVerifying('cita/prefetchingeditcita/'+id).get().json();
    },
    cancelCita(id:number){
        return fetchWithVerifying('cita/cancel/'+id).patch().json();
    },
    updateCita(id:number,payload:any){
        return fetchWithVerifying('cita/update/'+id).patch(payload).json();
    },
    consultaStore(payload:any){
        return fetchWithVerifying('consulta/store').post(payload).json();
    },
    consultaUpdate(id:number,payload:any){
        return fetchWithVerifying('consulta/update/'+id).patch(payload).json();
    },
    consultaProductos(){
        return fetchWithVerifying('producto/index2').get().json();
    },
    consultaServicios(){
        return fetchWithVerifying('servicio/index2').get().json();
    }
}
export default {CitaService}
