export default [
  {
    path: '/pkl/perencanaan',
    name: 'pkl-perencanaan',
    component: () => import('@/views/pkl/Perencanaan.vue'),
    meta: {
      resource: 'Pkl',
      action: 'read',
      pageTitle: 'Perencanaan Penilaian PKL',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Perencanaan',
          active: true,
        },
      ],
      tombol_add: {
        action: 'rencana-pkl',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/pkl/penilaian',
    name: 'pkl-penilaian',
    component: () => import('@/views/pkl/Penilaian.vue'),
    meta: {
      resource: 'Pkl',
      action: 'read',
      pageTitle: 'Input Penilaian PKL',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Input Penilaian',
          active: true,
        },
      ],
    },
  },
  {
    path: '/pkl/kehadiran',
    name: 'pkl-kehadiran',
    component: () => import('@/views/pkl/Kehadiran.vue'),
    meta: {
      resource: 'Pkl',
      action: 'read',
      pageTitle: 'Kehadiran',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Kehadiran',
          active: true,
        },
      ],
    },
  },
  {
    path: '/pkl/cetak-rapor',
    name: 'pkl-rapor',
    component: () => import('@/views/pkl/CetakRapor.vue'),
    meta: {
      resource: 'Pkl',
      action: 'read',
      pageTitle: 'Cetak Rapor',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Cetak Rapor',
          active: true,
        },
      ],
    },
  },
]
