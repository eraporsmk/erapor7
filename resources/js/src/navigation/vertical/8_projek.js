export default [
    {
      icon: 'file-circle-check',
      title: 'Nilai Projek',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Perencanaan',
          route: 'perencanaan-projek',
          resource: 'Projek',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Penilaian',
          route: 'penilaian-projek',
          resource: 'Projek',
          action: 'read',
        },
      ]
    },
  ]
  