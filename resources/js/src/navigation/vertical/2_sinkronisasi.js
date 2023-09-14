export default [
    {
      icon: 'arrows-rotate',
      title: 'Sinkronisasi',
      children: [
        {
          icon: 'download',
          title: 'Dapodik',
          route: 'sinkronisasi-dapodik',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'upload',
          title: 'e-Rapor',
          route: 'sinkronisasi-erapor',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'database',
          title: 'Nilai Dapodik',
          route: 'sinkronisasi-nilai',
          resource: 'Administrator',
          action: 'read',
        },
      ]
    },
  ]
  