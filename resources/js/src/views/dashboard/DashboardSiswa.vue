<template>
  <b-card no-body>
    <b-card-body>
      <h2>Selamat Datang {{username}}</h2>
      <p>Anda terdeteksi belum memiliki Hak Akses Pengguna.</p>
      <p>Silahkan hubungi Administrator!</p>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
  },
  computed: {
    username(){
      console.log(this.user);
      return (this.user) ? this.user.name : ''
    }
  },
}
</script>