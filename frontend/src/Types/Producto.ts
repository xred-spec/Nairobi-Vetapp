export interface Producto {
  nombre: string;
  marca: string;
  stock: number;
  precio_compra: number;
  precio_venta: number;
  cantidad: number;
  medida: 'gramos' | 'kilos' | 'mililitros' | 'litros' | 'unidad';
  visibilidad: 'visible' | 'invisible';
}
