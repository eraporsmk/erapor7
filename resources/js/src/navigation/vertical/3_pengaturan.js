export default [
    {
      icon: 'wrench',
      title: 'Pengaturan',
      children: [
        {
          icon: 'gear',
          title: 'Umum',
          route: 'setting-umum',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'user-lock',
          title: 'Akses Pengguna',
          route: 'setting-users',
          resource: 'Administrator',
          action: 'read',
        },
      ]
    },
  ]
  