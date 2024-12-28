export default [
    {
      icon: 'arrows-rotate',
      title: 'Sinkronisasi',
      children: [
        {
          icon: 'download',
          title: 'Tarik Dapodik',
          route: 'sinkronisasi-dapodik',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'database',
          title: 'Kirim Nilai ke Dapodik',
          route: 'sinkronisasi-nilai',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'upload',
          title: 'Kirim Data e-Rapor',
          route: 'sinkronisasi-erapor',
          resource: 'Administrator',
          action: 'read',
        },
      ]
    },
  ]
  