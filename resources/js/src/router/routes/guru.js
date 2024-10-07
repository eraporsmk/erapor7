export default [
  {
    path: '/referensi/password-pd',
    name: 'referensi-password-pd',
    component: () => import('@/views/referensi/peserta-didik/Password.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Data Password Peserta Didik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Password Peserta Didik',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/kompetensi-dasar',
    name: 'referensi-kompetensi-dasar',
    component: () => import('@/views/referensi/kompetensi-dasar/Index.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Data KD',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Kompetensi Dasar',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-kd',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/capaian-pembelajaran',
    name: 'referensi-capaian-pembelajaran',
    component: () => import('@/views/referensi/capaian-pembelajaran/Index.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Data Capaian Pembelajaran',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Capaian Pembelajaran',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-cp',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/tujuan-pembelajaran',
    name: 'referensi-tujuan-pembelajaran',
    component: () => import('@/views/referensi/tujuan-pembelajaran/Index.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Data Tujuan Pembelajaran',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Tujuan Pembelajaran',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-tp',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/bobot-penilaian',
    name: 'referensi-bobot-penilaian',
    component: () => import('@/views/referensi/bobot-penilaian/Index.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Bobot Penilaian',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Bobot Penilaian',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/uji-kompetensi-keahlian',
    name: 'referensi-ukk',
    component: () => import('@/views/referensi/ukk/Index.vue'),
    meta: {
      resource: 'Kaprog',
      action: 'read',
      pageTitle: 'UKK',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Uji Kompetensi Keahliann',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-ukk',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/penilaian/input-nilai',
    name: 'input-nilai',
    component: () => import('@/views/penilaian/InputNilai.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Input Asesmen',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Input Asesmen',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/nilai-tengah-semester',
    name: 'penilaian-nilai-tengah-semester',
    component: () => import('@/views/penilaian/tengah-semester/Index.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Input Nilai Tengah Semester',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Input Nilai Tengah Semester',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/nilai-akhir',
    name: 'penilaian-nilai-akhir',
    component: () => import('@/views/penilaian/NilaiAkhir.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Input Nilai Akhir',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Input Nilai Akhir',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/capaian-kompetensi',
    name: 'penilaian-capaian-kompetensi',
    component: () => import('@/views/penilaian/CapaianKompetensi.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Capaian Kompetensi',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Capaian Kompetensi',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/sikap',
    name: 'penilaian-sikap',
    component: () => import('@/views/penilaian/PenilaianSikap.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Penilaian Sikap',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Penilaian Sikap',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-sikap',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/penilaian/input-nilai-sikap',
    name: 'penilaian-input-sikap',
    component: () => import('@/views/penilaian/InputPenilaianSikap.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Tambah Data Nilai Sikap',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Tambah Data Nilai Sikap',
          active: true,
        },
      ],
      navActiveLink: 'penilaian-sikap',
    },
  },
  {
    path: '/penilaian/edit-nilai-sikap/:id',
    name: 'penilaian-edit-sikap',
    component: () => import('@/views/penilaian/InputPenilaianSikap.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Ubah Data Nilai Sikap',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Ubah Data Nilai Sikap',
          active: true,
        },
      ],
      navActiveLink: 'penilaian-sikap',
    },
  },
  {
    path: '/penilaian/ekstrakurikuler',
    name: 'penilaian-ekstrakurikuler',
    component: () => import('@/views/penilaian/Ekstrakurikuler.vue'),
    meta: {
      resource: 'Ekskul',
      action: 'read',
      pageTitle: 'Penilaian Ekstrakurikuler',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Nilai Ekstrakurikuler ',
          active: true,
        },
      ],
    },
  },
  {
    path: '/nilai-projek/perencanaan',
    name: 'perencanaan-projek',
    component: () => import('@/views/penilaian/projek/Perencanaan.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Rencana Penilaian P5',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Rencana Penilaian P5',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-projek',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/nilai-projek/penilaian',
    name: 'penilaian-projek',
    component: () => import('@/views/penilaian/projek/Penilaian.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Proses Penilaian P5',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Input Nilai Projek',
          active: true,
        },
      ],
    },
  },
  {
    path: '/nilai-ukk/perencanaan',
    name: 'perencanaan-ukk',
    component: () => import('@/views/penilaian/ukk/Perencanaan.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Rencana Penilaian UKK',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Rencana Penilaian UKK',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-rencana',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/nilai-ukk/penilaian',
    name: 'penilaian-ukk',
    component: () => import('@/views/penilaian/ukk/Penilaian.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Proses Penilaian UKK',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Input Nilai Projek',
          active: true,
        },
      ],
    },
  },
]
