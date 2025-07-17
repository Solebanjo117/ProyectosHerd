import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}
export interface Producto {
  id: number;
    nombre: string;
    descripcion?: string | null;
    precio: number;
    cantidad: number;
    categoria?: string | null;
    codigo_barras?: string | null;
    imagen?: string | null;
    activo: boolean;
    created_at: string;
    updated_at: string;
}
export interface Salida{
  id: number;
  referencia?: string | null;
  tipo: string | null;
  cliente_id?: number | null; // 👈 relación con cliente
  cliente?: Cliente | null; // 👈 relación con cliente
  estado: string; // 'pendiente', 'completada', 'cancelada'
  observaciones?: string | null;
  detalles?: SalidaDetalle[]; // 👈 relación con salida_detalles
  created_at: string;
  updated_at: string;
}
export interface SalidaDetalle {
  id: number;
  salida_id: number;
  producto_id: number;
  cantidad: number;
  producto: Producto; // 👈 relación con producto
  precio_unitario?: number;
  created_at: string;
  updated_at: string;
}
export interface Entrada {
  id: number;
  referencia?: string | null;
  tipo: string | null;
  observaciones?: string | null;
  detalles?: EntradaDetalle[]; // 👈 relación con entrada_detalles
  created_at: string;
  updated_at: string;
}
export interface EntradaDetalle {
  id: number;
  entrada_id: number;
  producto_id: number;
  cantidad: number;
  producto: Producto; // 👈 relación con producto
  precio_unitario: number;
  created_at: string;
  updated_at: string;
}
export interface Cliente {
  id: number;
  nombre: string;
  correo?: string | null;
  telefono?: string | null;
  direccion?: string | null;
  deuda: number;
  created_at: string;
  updated_at: string;
}
export interface Pagination<T> {
  data: T[];
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
  meta: {
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    total: number;
  };
}
export type BreadcrumbItemType = BreadcrumbItem;
