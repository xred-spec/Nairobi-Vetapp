import { api } from "./api";
import apiResponse from "@/Types/ApiResponse";
import { Cita } from "@/Types/Cita";

export const dashboardService = {
    getProximasCitas: (filtros: object = {}) => {
        return api<apiResponse<{ data: Cita[], paginacion: any }>>('v1/cita/search').post(filtros).json();
    }
};