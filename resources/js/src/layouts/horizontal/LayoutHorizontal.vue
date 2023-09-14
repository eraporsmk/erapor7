<template>
  <layout-horizontal>

    <router-view />
  </layout-horizontal>

</template>

<script>
import LayoutHorizontal from '@core/layouts/layout-horizontal/LayoutHorizontal.vue'
import { $themeConfig } from '@themeConfig'

export default {
  components: {
    LayoutHorizontal,
  },
}
</script>
