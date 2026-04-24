import { createRouter, createWebHashHistory /*, createWebHistory*/ } from 'vue-router'
import { useUserStore } from '@/stores/user'
import CitasView from '@/views/perfil/CitasView.vue';
import MascotasView from '@/views/perfil/MascotasView.vue';
//import Sidebar from '@/components/Sidebar.vue';
import type { RouteRecordRaw } from 'vue-router'


const routes: RouteRecordRaw[] = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/about',
    name: 'about',
    component: () => import('../views/AboutView.vue'),
  },
  // Login
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/login/LoginView.vue'),
  },
  {
    path: '/registro',
    name: 'registro',
    component: () => import('../views/login/RegistroView.vue'),
  },
  {
    path:'/verification',
    name: 'verification',
    component: () => import('../views/login/VerificationView.vue'),
  },
  {
    path:'/reclamacion',
    name: 'reclamacion',
    component: () => import('../views/login/ReclamacionView.vue'),
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('../views/DashboardView.vue'),
    meta: {
      requiresAuth: true
    }, 
    children: [
      // Perfil
      {
      path: 'main',
      name: 'main',
      component: () => import('@/views/perfil/MainView.vue'),
      meta: {
        requiresAuth: true
      }
      },
      {
      path: 'perfil',
      name: 'perfil',
      component: () => import('../views/perfil/PerfilView.vue'),
      meta: {
        requiresAuth: true
      },
      },
      {
        path: 'perfil/mascotas',
        name: 'perfil-mascotas',
        component: MascotasView,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'perfil/citas',
        name: 'perfil-citas',
       // query:'',
        component: CitasView,
        meta: {
          requiresAuth: true
        }
      },
      { path: 'perfil', name: 'perfil', component: () => import('@/views/perfil/PerfilView.vue') },
      // Catalogos
      {
        path: 'animales',
        name: 'animales',
        component: () => import('../views/catalogos/AnimalesView.vue'),
        meta: {
          requiresAuth: true,
          requireStaff: true
        }
      },
      {
        path: 'productos',
        name: 'productos',
        component: () => import('../views/catalogos/ProductosView.vue'),
        meta: {
          requiresAuth: true,
          requireStaff: true
        }
      },
      {
        path: 'servicios',
        name: 'servicios',
        component: () => import('../views/catalogos/ServiciosView.vue'),
        meta: {
          requiresAuth: true,
          requireStaff: true
        }
      },
      {
        path: 'razas',
        name: 'razas',
        component: () => import('../views/catalogos/RazasView.vue'),
        meta: {
          requiresAuth: true,
          requireStaff: true
        }
      },
      // Admin
      {
        path: 'admin/reportes',
        name: 'admin-reportes',
        component: () => import('../views/admin/ReportesView.vue'),
        meta: {
          requiresAuth: true,
          requiresAdmin: true
        }
      },
      {
        path: 'admin/usuarios',
        name: 'admin-usuarios',
        component: () => import('../views/admin/UsersView.vue'),
        meta: {
          requiresAuth: true,
          requiresAdmin: true
        }
      },
      {
        path: 'users/clientes',
        name: 'users-clientes',
        component: () => import('../views/admin/ClientesView.vue'),
        meta: {
          requiresAuth: true,
          requireStaff: true
        }
      },
      {
        path: 'users/trabajadores',
        name: 'users-trabajadores',
        component: () => import('../views/admin/TrabajadoresView.vue'),
        meta: {
          requiresAuth: true,
          requiresAdmin: true
        }
      }
    ]
  },
]

const router = createRouter({
  // history: createWebHistory(import.meta.env.BASE_URL),
  history: createWebHashHistory(),
  routes,
})

router.beforeEach((to, _from, next) => {
  const userStore = useUserStore(); 
  const isAuthenticated = localStorage.getItem('token');
  
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);
  const requireStaff = to.matched.some(record => record.meta.requireStaff);

  if (requiresAuth && !isAuthenticated) {
    return next('/login');
  }

  if (requiresAdmin && !userStore.isAdmin()) {
    return next({ name: 'main' });
  }

  if (requireStaff && userStore.isCliente()) {
    return next({ name: 'main' });
  }
  next();
});

export default router
