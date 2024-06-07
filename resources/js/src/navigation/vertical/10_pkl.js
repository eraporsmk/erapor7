export default [
    {
      icon: 'layer-group',
      title: 'Praktik Kerja Lapangan',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Perencanaan',
          route: 'pkl-perencanaan',
          resource: 'Pkl',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Penilaian',
          route: 'pkl-penilaian',
          resource: 'Pkl',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Ketidakhadiran',
          route: 'pkl-kehadiran',
          resource: 'Pkl',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Daftar Nilai',
          route: 'daftar-nilai',
          resource: 'Pkl',
          action: 'read',
        },
      ]
    },
  ]
  