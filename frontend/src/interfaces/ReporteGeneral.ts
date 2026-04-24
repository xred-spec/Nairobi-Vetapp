import ApiResponse from "@/Types/ApiResponse";
export interface TrabajadorReporte {
  nombre_trabajador: string;
  cantidad_citas: number;
  completadas: number;
}

export interface GeneralReporte {
  total_citas: number;
  citas_agendadas: number;
  citas_realizadas: number;
  citas_canceladas: number;
  citas_proceso: number;
  mascotas_atendidas: number;
  trabajadores_activos: number;
  total_consultas: number;
  nuevas_mascotas: number;
  nuevos_clientes: number;
}

export interface ReporteGeneralData {
  trabajadores: TrabajadorReporte[];
  general: GeneralReporte;
}


//reporte 2
export interface Ingresos{
  tipo:string,
  total_citas:number,
  promedio_producto:number,
  promedio_servicios:number
}

export interface finanzas{
  total_citas:number,
  diferencia:number,
  sutotal:number,
  tipo:number,
  total:number
}

export interface veterinarioFinanzas{
  nombre:string,
  ganancia_productos:number,
  ganancia_servicios:number,
  total_citas:number,
  total:number
}

export interface IngresosPromedios {
  ingresos: Ingresos[]
}
export interface finanzasTipoCita{
  finanzas : finanzas[]
}

export interface finanzasPorVeterinario{
  veterinarioFinanzas : veterinarioFinanzas[]
}


export interface FinanzasResponse {
  ingresos_promedios:IngresosPromedios[],
  finanzas_tipo_cita:finanzasTipoCita[],
  finanzas_por_veterinario:finanzasPorVeterinario[]
}

export interface DistribucionTipos{
  tipo:string,
  cantidad:number
}
export type ReporteFinanzasResponse = ApiResponse<FinanzasResponse>;
export type ReporteGeneralResponse = ApiResponse<ReporteGeneralData>;
export type DistribucionTiposResponse = ApiResponse<DistribucionTipos[]>


