<template>
  <b-row v-if="$route.meta.breadcrumb || $route.meta.pageTitle" class="content-header">

    <!-- Content Left -->
    <b-col class="content-header-left mb-2" cols="12" md="9">
      <b-row class="breadcrumbs-top">
        <b-col cols="12">
          <h2 class="content-header-title float-left pr-1 mb-0">
            {{ $route.meta.pageTitle }}
          </h2>
          <div class="breadcrumb-wrapper">
            <b-breadcrumb>
              <b-breadcrumb-item to="/">
                <feather-icon icon="HomeIcon" size="16" class="align-text-top" />
              </b-breadcrumb-item>
              <b-breadcrumb-item v-for="item in $route.meta.breadcrumb" :key="item.text" :active="item.active" :to="item.to">
                {{ item.text }}
              </b-breadcrumb-item>
            </b-breadcrumb>
          </div>
        </b-col>
      </b-row>
    </b-col>
    <template v-if="$route.meta.tombol_add && !hide_button">
      <!-- Content Right -->
      <b-col class="content-header-right text-md-right d-md-block d-none mb-1" md="3" cols="12">
        <template v-if="$route.meta.tombol_add.action">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" :variant="$route.meta.tombol_add.variant" @click="action($route.meta.tombol_add.action)" :disabled="loading">
            <b-spinner small type="grow" v-show="loading"></b-spinner>
            {{$route.meta.tombol_add.text}}
          </b-button>
        </template>
        <template v-else>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" :variant="$route.meta.tombol_add.variant" :href="$route.meta.tombol_add.link" target="_blank">
            {{$route.meta.tombol_add.text}}
          </b-button>
        </template>
      </b-col>
    </template>
  </b-row>
</template>

<script>
import { BBreadcrumb, BBreadcrumbItem, BRow, BCol, BDropdown, BDropdownItem, BButton, BSpinner } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { useUtils as useI18nUtils } from '@core/libs/i18n'
import eventBus from '@core/utils/eventBus';
export default {
  directives: {
    Ripple,
  },
  components: {
    BBreadcrumb,
    BBreadcrumbItem,
    BRow,
    BCol,
    BDropdown,
    BDropdownItem,
    BButton,
    BSpinner,
  },
  setup() {
    const { t } = useI18nUtils()

    return {
      // i18n
      t,
    }
  },
  data() {
    return {
      hide_button: false,
      loading: false,
    }
  },
  created() {
    eventBus.$on('hide-button', this.handleEvent);
    eventBus.$on('loading', (val) => {
      this.loading = val
    })
  },
  methods: {
    handleEvent(val){
      if(val){
        this.hide_button = val
      } else {
        this.hide_button = true
      }
    },
    action(val){
      eventBus.$emit(val);
      console.log('posisi: src/@core/layouts/components/AppBreadcrumb.vue => '+val);
    },
  }
}
</script>