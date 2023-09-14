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
]
