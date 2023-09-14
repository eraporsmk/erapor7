export default [
    {
      icon: 'list',
      title: 'Referensi',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Referensi GTK',
          children: [
            {
              icon: 'graduation-cap',
              title: 'Guru',
              route: 'referensi-guru',
              resource: 'Administrator',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'Tendik',
              route: 'referensi-tendik',
              resource: 'Administrator',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'Instruktur',
              route: 'referensi-instruktur',
              resource: 'Administrator',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'Asesor',
              route: 'referensi-asesor',
              resource: 'Administrator',
              action: 'read',
            },
          ]
        },
        {
          icon: 'hand-point-right',
          title: 'Rombongan Belajar',
          children: [
            {
              icon: 'hand-point-right',
              title: 'Reguler',
              route: 'referensi-rombongan-belajar',
              resource: 'Rombel',
              action: 'read',
            },
            {
              icon: 'hand-point-right',
              title: 'Matpel Pilihan',
              route: 'referensi-rombel-pilihan',
              resource: 'Rombel',
              action: 'read',
            },
          ]
        },
        {
          icon: 'hand-point-right',
          title: 'Peserta Didik',
          //route: 'referensi-peserta-didik-aktif',
          //resource: 'Guru',
          //action: 'read',
          children: [
            {
              icon: 'hand-point-right',
              title: 'PD Aktif',
              route: 'referensi-peserta-didik-aktif',
              resource: 'Guru',
              action: 'read',
            },
            {
              icon: 'hand-point-right',
              title: 'Password PD',
              route: 'referensi-password-pd',
              resource: 'Password_pd',
              action: 'read',
            },
          ],
        },
        {
          icon: 'hand-point-right',
          title: 'Kompetensi Dasar',
          route: 'referensi-kompetensi-dasar',
          resource: 'Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Capaian Pembelajaran',
          route: 'referensi-capaian-pembelajaran',
          resource: 'Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Tujuan Pembelajaran',
          route: 'referensi-tujuan-pembelajaran',
          resource: 'Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Bobot Penilaian',
          route: 'referensi-bobot-penilaian',
          resource: 'Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Uji Kompetensi Keahlian',
          route: 'referensi-ukk',
          resource: 'Kaprog',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Peserta Didik',
          children: [
            {
              icon: 'hand-point-right',
              title: 'PD Aktif',
              route: 'referensi-peserta-didik-aktif',
              resource: 'Administrator',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'PD Keluar',
              route: 'referensi-peserta-didik-keluar',
              resource: 'Administrator',
              action: 'read',
              variant: 'danger',
            },
          ]
        },
        {
          icon: 'hand-point-right',
          title: 'Mata Pelajaran',
          route: 'referensi-mata-pelajaran',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Ekstrakurikuler',
          route: 'referensi-ekstrakurikuler',
          resource: 'Administrator',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'DUDI',
          route: 'referensi-dudi',
          resource: 'Administrator',
          action: 'read',
        },
      ]
    },
  ]
  