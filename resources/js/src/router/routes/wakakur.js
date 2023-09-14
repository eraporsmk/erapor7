export default [
  {
    path: '/progress/penilaian',
    name: 'progress-penilaian',
    component: () => import('@/views/progress/Penilaian.vue'),
    meta: {
      resource: 'Waka',
      action: 'read',
      pageTitle: 'Nilai Akademik',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Nilai Akademik',
          active: true,
        },
      ],
    },
  },
  {
    path: '/progress/nilai-projek',
    name: 'progress-nilai-projek',
    component: () => import('@/views/progress/NilaiProjek.vue'),
    meta: {
      resource: 'Waka',
      action: 'read',
      pageTitle: 'Nilai Projek',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Nilai Projek',
          active: true,
        },
      ],
    },
  },
  {
    path: '/progress/nilai-ekskul',
    name: 'progress-nilai-ekskul',
    component: () => import('@/views/progress/NilaiEkskul.vue'),
    meta: {
      resource: 'Waka',
      action: 'read',
      pageTitle: 'Nilai Eksktrakurikuler',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Nilai Eksktrakurikuler',
          active: true,
        },
      ],
    },
  },
  {
    path: '/progress/nilai-ukk',
    name: 'progress-nilai-ukk',
    component: () => import('@/views/progress/NilaiUkk.vue'),
    meta: {
      resource: 'Waka',
      action: 'read',
      pageTitle: 'Nilai UKK',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Nilai UKK',
          active: true,
        },
      ],
    },
  },
  {
    path: '/progress/nilai-pkl',
    name: 'progress-nilai-pkl',
    component: () => import('@/views/progress/NilaiPkl.vue'),
    meta: {
      resource: 'Waka',
      action: 'read',
      pageTitle: 'Nilai PKL',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Nilai PKL',
          active: true,
        },
      ],
    },
  },
]
