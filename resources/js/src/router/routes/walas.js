export default [
  {
    path: '/wali-kelas/catatan-sikap',
    name: 'walas-catatan-sikap',
    component: () => import('@/views/walas/CatatanSikap.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Catatan Sikap',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Catatan Sikap',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/praktik-kerja-lapangan',
    name: 'walas-pkl',
    component: () => import('@/views/walas/Pkl.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Praktik Kerja Lapangan',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Praktik Kerja Lapangan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/ketidakhadiran',
    name: 'walas-ketidakhadiran',
    component: () => import('@/views/walas/Ketidakhadiran.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Ketidakhadiran',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Ketidakhadiran',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/nilai-ekstrakurikuler',
    name: 'walas-nilai-ekstrakurikuler',
    component: () => import('@/views/walas/NilaiEkskul.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Nilai Ekstrakurikuler',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Nilai Ekstrakurikuler',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/kenaikan-kelas',
    name: 'walas-kenaikan-kelas',
    component: () => import('@/views/walas/KenaikanKelas.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Kenaikan Kelas',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Kenaikan Kelas',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/cetak-rapor',
    name: 'walas-cetak-rapor',
    component: () => import('@/views/walas/CetakRapor.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Cetak Rapor Semester',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Cetak Rapor Semester',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/unduh-legger',
    name: 'walas-unduh-legger',
    component: () => import('@/views/walas/UnduhLegger.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Unduh Leger',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Unduh Leger',
          active: true,
        },
      ],
      tombol_add: {
        action: 'unduhLegger',
        link: '',
        variant: 'success',
        text: 'Unduh Legger'
      },
    },
  },
]
