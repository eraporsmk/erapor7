export default [
  {
    path: '/',
    name: 'beranda',
    component: () => import('@/views/dashboard/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Beranda',
      breadcrumb: [
        {
          text: 'Beranda',
          active: true,
        },
      ],
    },
  },
  {
    path: '/login',
    name: 'auth-login',
    component: () => import('@/views/pages/Login.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
      pageTitle: 'Login Pengguna',
    },
  },
  {
    path: '/reset-password/:token?',
    name: 'auth-reset-password',
    component: () => import('@/views/pages/LupaPassword.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
      pageTitle: 'Reset Password',
    },
  },
  {
    path: '/register',
    name: 'auth-register',
    component: () => import('@/views/pages/Register.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
      pageTitle: 'Registrasi Pengguna',
    },
  },
  {
    path: '/logout',
    name: 'logout',
    component: () => import('@/views/pages/Logout.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
    }
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/pages/Profile.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Profil Pengguna',
      breadcrumb: [
        {
          text: 'Profil Pengguna',
          active: true,
        },
      ],
    }
  },
  {
    path: '/beranda',
    name: 'home',
    component: () => import('@/views/pages/profile/Profile.vue'),
    /*meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Profil Pengguna',
      breadcrumb: [
        {
          text: 'Profil Pengguna',
          active: true,
        },
      ],
    }*/
    meta: {
      layout: 'full',
      resource: 'Web',
      action: 'read',
      pageTitle: 'Profil Pengguna',
    },
  },
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/error/Error404.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'read',
      pageTitle: 'Laman tidak ditemukan',
    },
  },
  {
    path: '/not-authorized',
    name: 'misc-not-authorized',
    component: () => import('@/views/error/NotAuthorized.vue'),
    meta: {
      layout: 'full',
      resource: 'Web',
      action: 'read',
      pageTitle: 'Akses Terbatas! 🔐',
    },
  },
  {
    path: '/detil-nilai/:pembelajaran_id',
    name: 'detil-nilai',
    component: () => import('@/views/dashboard/DetilNilai.vue'),
    meta: {
      navActiveLink: 'beranda',
      resource: 'Siswa',
      action: 'read',
      pageTitle: 'Detil Nilai',
      breadcrumb: [
        {
          text: 'Detil Nilai',
          active: true,
        },
      ],
    },
  },
]
