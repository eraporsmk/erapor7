<template>
  <b-img :src="imgUrl" height="24" alt="Logo" />
</template>
<script>
import { BImg } from 'bootstrap-vue'
export default {
  components: {
    BImg
  },
  data() {
    return {
      sideImg: '/images/logo/logo.png',
    }
  },
  computed: {
    imgUrl() {
      return this.sideImg
    },
  },
}
</script>