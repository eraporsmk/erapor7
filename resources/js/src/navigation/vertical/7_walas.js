export default [
    {
      icon: 'copy',
      title: 'Wali Kelas',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Catatan Sikap',
          route: 'walas-catatan-sikap',
          resource: 'Wali',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Praktik Kerja Lapangan',
          route: 'walas-pkl',
          resource: 'Wali',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Ketidakhadiran',
          route: 'walas-ketidakhadiran',
          resource: 'Wali',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Nilai Ekstrakurikuler',
          route: 'walas-nilai-ekstrakurikuler',
          resource: 'Wali',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Kenaikan Kelas',
          route: 'walas-kenaikan-kelas',
          resource: 'Kenaikan',
          action: 'read',
        },
        {
          icon: 'print',
          title: 'Cetak Rapor',
          route: 'walas-cetak-rapor',
          resource: 'Wali',
          action: 'read',
        },
        {
          icon: 'download',
          title: 'Unduh Leger',
          route: 'walas-unduh-legger',
          resource: 'Wali',
          action: 'read',
        },
      ]
    },
    {
      icon: 'copy',
      title: 'Wali Kelas (Pilihan)',
      children: [
        {
          icon: 'download',
          title: 'Unduh Leger',
          route: 'pilihan-unduh-legger',
          resource: 'Pilihan',
          action: 'read',
        },
      ],
    },
  ]
  