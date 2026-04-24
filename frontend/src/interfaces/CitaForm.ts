export interface CitaInterface{
    estado?: string;
    fecha?: string;
    fecha_final?:string;
    tipo?: string;
    exclude_deleted?: boolean;
    raza?:string;
    animal?:string;
    mascota?:string;
    trabajador?:string;
    cliente?:string;
}

export interface serviceWrapper{
    servicio?:any;
    productList: any[];
    servicio_id?:number;
    detalles_servicio?:string;
    precio_servicio?:number;
    precio_producto?:number;
    total?:number;
    productos:ProductoWrapper[];
    visibilidad?:boolean;
}
export interface ProductoWrapper{
    producto:any;
    producto_id?:number;
    cantidad?:number;
    tipo_venta?:any
    subtotal?:number
}
export interface consultafinalInterface{
      pre_diagnostico:string;
      descripcion_consulta:string;
      indicaciones:string;
      total_servicios:number;
      total_producto:number;
      total:number;
      servicios:serviceWrapper[]
}