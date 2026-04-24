import type { Columna } from "@/interfaces/CardColumn";

export const MascotaColumns: Columna[] = [
  { key: 'nombre', label: 'Nombre' },
  { key: 'animal', label: 'Animal' },  
  { key: 'raza', label: 'Raza' },
  { key: 'user', label: 'Cliente' },
  { key: 'visibilidad', label: 'Estado' },
  { key: 'opciones', label: 'Opciones'}
];

/*
export const MascotaColumns: Columna[] = [
    {key: 'nombre', label: 'Nombre'},
    {key: 'animal', label: 'Animal'},
    {key: 'raza', label: 'Raza'},
    {key: 'sexo', label: 'Sexo'},
    {key: 'user', label: 'Dueño'},
    {key: 'visibilidad', label: 'Visibilidad'},
    {key: 'opciones', label: 'Opciones'}
];*/