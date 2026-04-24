import { api } from "./api";
import type { ReporteGeneralResponse, ReporteFinanzasResponse, DistribucionTiposResponse } from "@/interfaces/ReporteGeneral";
type ReporteFiltros = {
  fecha_inicio: string;
  fecha_fin: string;
  [key: string]: any;
}
export const ReporteService = {
  getReporteGeneral(filtros: ReporteFiltros){
    return api<ReporteGeneralResponse>('v1/reporte/get-reporte')
    .post(filtros)
    .json();
  },

  getReporteGeneral2(filtros: ReporteFiltros){
    return api<ReporteFinanzasResponse>('v1/reporte/getReporte2')
    .post(filtros)
    .json();
  },

  getDistribucion(fecha: string){
    return api<DistribucionTiposResponse>('v1/reporte/distribucion-tipo')
    .post({fecha})
    .json();
  }
}
