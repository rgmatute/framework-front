
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/publicaciones/Publicaciones.vue') },
      { path: 'contacts', component: () => import('pages/Usuarios/ChatContacts.vue') },
      { path: 'chat/:id', component: () => import('pages/Usuarios/chat.vue') },
      // { path: '', component: () => import('pages/Index.vue') }
      { path: 'loading', component: () => import('pages/Loading.vue') },
      { path: 'mantenimiento', component: () => import('pages/mantenimiento/Mantenimiento.vue') },
      { path: 'mantenimiento-parametros', component: () => import('pages/Usuarios/ChatContacts.vue') },
      { path: 'mantenimiento-usuarios', component: () => import('pages/Usuarios/ChatContacts.vue') },
      { path: 'mantenimiento-filtros', component: () => import('pages/mantenimiento/MantenimientoParametros.vue') }
    ]
  },
  {
    path: '/accounts',
    component: () => import('layouts/AccountsLayout.vue'),
    children: [
      { path: '', component: () => import('pages/seguridad/Login.vue') },
      { path: 'auth', component: () => import('pages/seguridad/Login.vue') },
      { path: 'register', component: () => import('pages/seguridad/Register.vue') },
      { path: 'logout', component: () => import('pages/Loading.vue') }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/error/Error404.vue')
  }
]

export default routes
