<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <span v-html="data"></span>
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
      data: '',
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.isBusy = true
      this.$http.get('/setting/changelog').then(response => {
        this.isBusy = false
        let getData = response.data
        this.data = getData.data
      })
    },
  },
}
</script>