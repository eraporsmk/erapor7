<template>
  <div>
    <p class="clearfix mb-0">
      <span class="float-md-left d-block d-md-inline-block mt-25">
        Hak Cipta &copy {{ new Date().getFullYear() }}
        <b-link class="ml-25" href="https://smk.kemdikbud.go.id/" target="_blank">Direktorat SMK Kemdikbud RI</b-link>
      </span>
      <span class="float-md-right d-none d-md-block">Versi {{app.appVersion}}
        <feather-icon
          icon="HeartIcon"
          size="21"
          class="text-danger stroke-current"
        />
      </span>
    </p>
    <p class="d-block d-md-none small">Untuk tampilan terbaik minimal resolusi 1360 x 768</p>
  </div>
</template>

<script>
import { BLink } from 'bootstrap-vue'
import { $themeConfig } from '@themeConfig'
export default {
  components: {
    BLink,
  },
  data() {
    return {
      app: $themeConfig.app
    }
  },
}
</script>
