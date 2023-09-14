<template>
  <div>
    <template v-if="hasRole('admin') || hasRole('tu')">
      <dashboard-admin></dashboard-admin>
    </template>
    <template v-else-if="hasRole('guru')">
      <dashboard-guru></dashboard-guru>
    </template>
    <template v-else-if="hasRole('siswa')">
      <dashboard-siswa></dashboard-siswa>
    </template>
    <template v-else>
      <dashboard-user></dashboard-user>
    </template>
  </div>
</template>

<script>
import DashboardAdmin from './DashboardAdmin.vue'
import DashboardGuru from './DashboardGuru.vue'
import DashboardSiswa from './DashboardSiswa.vue'
import DashboardUser from './DashboardUser.vue'

export default {
  components: {
    DashboardAdmin, 
    DashboardGuru,
    DashboardSiswa,
    DashboardUser,
  },
}
</script>