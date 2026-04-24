import Service from '@/services/CitaService';
export function useCita(){
    const ObtenerCita = (payload:any,page:number)=>{
        return  Service.CitaService.searchWithoutHistory(payload,page);
    }
    const ObtenerPrefetchingCita = () =>{
        return Service.CitaService.prefetchingCita();
    }

    const getMascotasCitas = (id:number)=>{
        return Service.CitaService.getMascotasCitas(id);
    }

    const getClientesCitas = (payload:string)=>{
        console.log(payload);
        return Service.CitaService.getClientesCitas(payload);
    }
    const getDisponibilidadHora = (id:number,fecha:string)=>{
        return Service.CitaService.getDisponibilidadHora(id,fecha)
    }

    const citastore = (payload:any)=>{
            return Service.CitaService.citastore(payload)
    }
    
    const changestatus = (id:number,payload:any)=>{
        return Service.CitaService.changestatus(id,payload);
    }

    const prefetchingEditCita = (id:number)=>{
        return Service.CitaService.prefetchingEditCita(id);
    }

    const cancelCita = (id:number)=>{
        return Service.CitaService.cancelCita(id);
    }

    const updateCita = (id:number,payload:any)=>{
        return Service.CitaService.updateCita(id,payload);
    }

    const consultaStore = (payload:any)=>{
        return Service.CitaService.consultaStore(payload);
    }

    const consultaUpdate = (id:number,payload:any)=>{
        return Service.CitaService.consultaUpdate(id,payload);
    }

    const consultaProductos= ()=>{
        return Service.CitaService.consultaProductos();
    }

    const consultaServicios= ()=>{
        return Service.CitaService.consultaServicios();
    }
    
    return {ObtenerCita,cancelCita,ObtenerPrefetchingCita,getMascotasCitas,getClientesCitas, getDisponibilidadHora, citastore, changestatus, prefetchingEditCita, updateCita, consultaStore,consultaUpdate,consultaServicios,consultaProductos
    }
}