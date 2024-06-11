export default [
    {
      icon: 'chart-line',
      title: 'Monitoring',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Nilai Akademik',
          route: 'progress-penilaian',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Nilai Projek',
          route: 'progress-nilai-projek',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Nilai Ekstrakurikuler',
          route: 'progress-nilai-ekskul',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Nilai UKK',
          route: 'progress-nilai-ukk',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Nilai PKL',
          route: 'progress-nilai-pkl',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Cetak Rapor Semester',
          route: 'progress-cetak-rapor',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Unduh Leger',
          route: 'progress-unduh-legger',
          resource: 'Waka',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Cetak Buku Induk',
          route: 'progress-buku-induk',
          resource: 'Waka',
          action: 'read',
          uts: true,
        },
      ]
    },
  ]
  