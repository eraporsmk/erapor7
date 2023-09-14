<template>
  <!-- Error page-->
  <div class="misc-wrapper">
    <b-link class="brand-logo">
      <vuexy-logo />
      <h2 class="brand-text text-primary ml-1">
        {{app.name}} Versi {{app.version}}
      </h2>
    </b-link>

    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          Halaman tidak ditemukan 🕵🏻‍♀️
        </h2>
        <p class="mb-2">
          Oops! 😖 Alamat yang diminta tidak ditemukan di aplikasi ini.
        </p>

        <b-button
          variant="primary"
          class="mb-2 btn-sm-block"
          :to="{path:'/'}"
        >
          Kembali ke Beranda
        </b-button>

        <!-- image -->
        <b-img
          fluid
          :src="imgUrl"
          alt="Error page"
        />
      </div>
    </div>
  </div>
<!-- / Error page-->
</template>

<script>
/* eslint-disable global-require */
import { BLink, BButton, BImg } from 'bootstrap-vue'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import store from '@/store/index'

export default {
  components: {
    VuexyLogo,
    BLink,
    BButton,
    BImg,
  },
  data() {
    return {
      downImg: '/images/pages/error.svg',
      app: store.state.appConfig.app,
    }
  },
  computed: {
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = '/images/pages/error-dark.svg'
        return this.downImg
      }
      return this.downImg
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-misc.scss';
</style>
