export default [
    {
      icon: 'list',
      title: 'Referensi',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Referensi GTK',
          resource: 'Ref_Guru',
          action: 'read',
          children: [
            {
              icon: 'graduation-cap',
              title: 'Guru',
              route: 'referensi-guru',
              resource: 'Ref_Guru',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'Tendik',
              route: 'referensi-tendik',
              resource: 'Ref_Guru',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'Instruktur',
              route: 'referensi-instruktur',
              resource: 'Ref_Guru',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'Asesor',
              route: 'referensi-asesor',
              resource: 'Ref_Guru',
              action: 'read',
            },
          ]
        },
        {
          icon: 'hand-point-right',
          title: 'Rombongan Belajar',
          resource: 'Rombel',
          action: 'read',
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
          children: [
            {
              icon: 'hand-point-right',
              title: 'PD Aktif',
              route: 'referensi-peserta-didik-aktif',
              resource: 'Ref_Siswa',
              action: 'read',
            },
            {
              icon: 'graduation-cap',
              title: 'PD Keluar',
              route: 'referensi-peserta-didik-keluar',
              resource: 'Ref_Siswa_Keluar',
              action: 'read',
              variant: 'danger',
            },
            {
              icon: 'hand-point-right',
              title: 'Password PD',
              route: 'referensi-password-pd',
              resource: 'Password_pd',
              action: 'read',
            },
          ]
        },
        /*{
          icon: 'hand-point-right',
          title: 'Peserta Didik',
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
        },*/
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
  