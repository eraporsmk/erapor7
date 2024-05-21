<template>
  <b-card>
    <h5 class="text-capitalize mb-75">Nama</h5>
    <b-card-text>{{ aboutData.nama }}</b-card-text>
    <h5 class="text-capitalize mb-75">NISN</h5>
    <b-card-text>{{ aboutData.nisn }}</b-card-text>
    <h5 class="text-capitalize mb-75">Kelas</h5>
    <b-card-text>{{ (aboutData.kelas) ? aboutData.kelas.nama : '-' }}</b-card-text>
    <h5 class="text-capitalize mb-75">Tempat, Tanggal Lahir</h5>
    <b-card-text>{{ aboutData.tempat_lahir }}, {{ aboutData.tanggal_lahir_indo }}</b-card-text>
    <h5 class="text-capitalize mb-75">Email</h5>
    <b-card-text>{{ aboutData.email }}</b-card-text>
  </b-card>
</template>

<script>
import { BCard, BCardText } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardText,
  },
  props: {
    aboutData: {
      type: Object,
      default: () => {},
    },
  },
}
</script>
