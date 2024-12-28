export default [
  {
    path: '/sinkronisasi/dapodik',
    name: 'sinkronisasi-dapodik',
    component: () => import('@/views/sinkronisasi/Index.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Ambil Data Dapodik',
      breadcrumb: [
        {
          text: 'Sinkronisasi',
        },
        {
          text: 'Ambil Data Dapodik',
          active: true,
        },
      ],
    },
  },
  {
    path: '/sinkronisasi/erapor',
    name: 'sinkronisasi-erapor',
    component: () => import('@/views/sinkronisasi/Erapor.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Kirim Data e-Rapor',
      breadcrumb: [
        {
          text: 'Sinkronisasi',
        },
        {
          text: 'Kirim Data e-Rapor',
          active: true,
        },
      ],
    },
  },
  {
    path: '/sinkronisasi/kirim-nilai-dapodik',
    name: 'sinkronisasi-nilai',
    component: () => import('@/views/sinkronisasi/Dapodik.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Kirim Nilai ke Dapodik',
      breadcrumb: [
        {
          text: 'Sinkronisasi',
        },
        {
          text: 'Kirim Nilai ke Dapodik',
          active: true,
        },
      ],
    },
  },
  {
    path: '/setting/umum',
    name: 'setting-umum',
    component: () => import('@/views/pengaturan/Umum.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Pengaturan Umum',
      breadcrumb: [
        {
          text: 'Pengaturan',
        },
        {
          text: 'Pengaturan Umum',
          active: true,
        },
      ],
    },
  },
  {
    path: '/setting/users',
    name: 'setting-users',
    component: () => import('@/views/pengaturan/Pengguna.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Akses Pengguna',
      breadcrumb: [
        {
          text: 'Pengaturan',
        },
        {
          text: 'Akses Pengguna',
          active: true,
        },
      ],
      tombol_add: {
        action: 'generatePengguna',
        link: '',
        variant: 'success',
        text: 'Atur Ulang Pengguna'
      },
    },
  },
  {
    path: '/referensi/guru',
    name: 'referensi-guru',
    component: () => import('@/views/referensi/gtk/Guru.vue'),
    meta: {
      resource: 'Ref_Guru',
      action: 'read',
      pageTitle: 'Data Guru',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/tendik',
    name: 'referensi-tendik',
    component: () => import('@/views/referensi/gtk/Tendik.vue'),
    meta: {
      resource: 'Ref_Guru',
      action: 'read',
      pageTitle: 'Data Tendik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Tenaga kependidikan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/instruktur',
    name: 'referensi-instruktur',
    component: () => import('@/views/referensi/gtk/Instruktur.vue'),
    meta: {
      resource: 'Ref_Guru',
      action: 'read',
      pageTitle: 'Data Instruktur',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Instruktur',
          active: true,
        },
      ],
      tombol_add: {
        action: 'modal-instruktur',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/asesor',
    name: 'referensi-asesor',
    component: () => import('@/views/referensi/gtk/Asesor.vue'),
    meta: {
      resource: 'Ref_Guru',
      action: 'read',
      pageTitle: 'Data Asesor',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Asesor',
          active: true,
        },
      ],
      tombol_add: {
        action: 'modal-asesor',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/rombongan-belajar',
    name: 'referensi-rombongan-belajar',
    component: () => import('@/views/referensi/rombongan-belajar/Reguler.vue'),
    meta: {
      resource: 'Rombel',
      action: 'read',
      pageTitle: 'Data Rombongan Belajar',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Rombongan Belajar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/rombel-pilihan',
    name: 'referensi-rombel-pilihan',
    component: () => import('@/views/referensi/rombongan-belajar/MatpelPilihan.vue'),
    meta: {
      resource: 'Rombel',
      action: 'read',
      pageTitle: 'Rombel Matpel Pilihan',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Rombel Matpel Pilihan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/peserta-didik-aktif',
    name: 'referensi-peserta-didik-aktif',
    component: () => import('@/views/referensi/peserta-didik/Aktif.vue'),
    meta: {
      resource: 'Ref_Siswa',
      action: 'read',
      pageTitle: 'Data Peserta Didik Aktif',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Peserta Didik Aktif',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/peserta-didik-keluar',
    name: 'referensi-peserta-didik-keluar',
    component: () => import('@/views/referensi/peserta-didik/Keluar.vue'),
    meta: {
      resource: 'Ref_Siswa_Keluar',
      action: 'read',
      pageTitle: 'Data Peserta Didik Keluar',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Peserta Didik Keluar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/mata-pelajaran',
    name: 'referensi-mata-pelajaran',
    component: () => import('@/views/referensi/mata-pelajaran/Index.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Mata Pelajaran',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Mata Pelajaran',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/ekstrakurikuler',
    name: 'referensi-ekstrakurikuler',
    component: () => import('@/views/referensi/ekstrakurikuler/Index.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Ekstrakurikuler',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Ekstrakurikuler',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/dudi',
    name: 'referensi-dudi',
    component: () => import('@/views/referensi/dudi/Index.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data DUDI',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data DUDI',
          active: true,
        },
      ],
    },
  },
  {
    path: '/unduhan',
    name: 'unduhan',
    component: () => import('@/views/pages/Unduhan.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Pusat Unduhan',
      breadcrumb: [
        {
          text: 'Pusat Unduhan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/changelog',
    name: 'changelog',
    component: () => import('@/views/pages/Changelog.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Daftar Perubahan',
      breadcrumb: [
        {
          text: 'Daftar Perubahan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/check-update',
    name: 'check-update',
    component: () => import('@/views/pages/Update.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Cek Pembaharuan',
      breadcrumb: [
        {
          text: 'Cek Pembaharuan',
          active: true,
        },
      ],
    },
  },
]
