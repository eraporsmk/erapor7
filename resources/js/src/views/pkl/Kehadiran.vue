<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        Blank Page
      </div>
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
  data() {
    return {
      isBusy: true,
    }
  },
  created() {
    this.$http.get('/dashboard').then(response => {
      this.isBusy = false
      this.data = response.data
    })
  },
  methods: {
  },
}
</script>