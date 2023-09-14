<template>
  <div>
    <b-row class="match-height">
      <b-col cols="6" xl="8" md="8" sm="6">
        <b-card class="text-center text-danger my-2" v-if="isBusy">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </b-card>
        <b-card no-body v-else>
          <b-card-body class="p-0">
            <b-table-simple bordered>
              <b-tr>
                <b-td rowspan="4" width="10%">
                  <b-img src="/logo.png" style="max-width: 100px"></b-img>
                </b-td>
                <b-td width="30%">NPSN Sekolah</b-td>
                <b-td width="60%"><strong>{{user.sekolah.npsn}}</strong></b-td>
              </b-tr>
              <b-tr>
                <b-td>Nama Sekolah</b-td>
                <b-td><strong>{{user.sekolah.nama}}</strong></b-td>
              </b-tr>
              <b-tr>
                <b-td>Alamat Sekolah</b-td>
                <b-td><strong>{{user.sekolah.alamat}}</strong></b-td>
              </b-tr>
              <b-tr>
                <b-td>Desa Kelurahan Sekolah</b-td>
                <b-td><strong>{{user.sekolah.desa_kelurahan}}</strong></b-td>
              </b-tr>
            </b-table-simple>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col cols="6" xl="4" md="4" sm="6">
        <b-card class="text-center text-danger my-2" v-if="isBusy">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </b-card>
        <b-card class="text-center" v-else>
          <p>Pengiriman data terakhir dilakukan pada <br>
            <strong>{{last_sync}}</strong>
          </p>
          <b-overlay :show="loading" rounded opacity="0.6" size="sm" spinner-variant="danger">
            <b-button size="lg" variant="success" @click="kirimData" :disabled="aktif"><font-awesome-icon icon="fa-solid fa-cloud-arrow-up" /> <strong>KIRIM DATA</strong></b-button>
          </b-overlay>
        </b-card>
      </b-col>
    </b-row>
    <template v-if="!isBusy">
      <b-card bg-variant="dark" class="text-center">
        <b-card-text class="h2" style="color:#fff">DATA YANG MENGALAMI PERUBAHAN</b-card-text>
      </b-card>
      <b-card>
        <b-overlay :show="loading" rounded opacity="0.6" size="xl" spinner-variant="danger">
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr variant="light">
                <b-th class="text-center" width="5%">No</b-th>
                <b-th class="text-center" width="80%">Data</b-th>
                <b-th class="text-center" width="15%">Jml Data</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="(item, index) in table_sync">
                <b-tr>
                  <b-td class="text-center">{{index + 1}}</b-td>
                  <b-td>{{item.data}}</b-td>
                  <b-td class="text-center">{{item.count}}</b-td>
                </b-tr>
              </template>
            </b-tbody>
            <b-tfoot>
              <b-tr variant="light">
                <b-td colspan="2" variant="light" class="text-right">
                  JUMLAH
                </b-td>
                <b-td variant="light" class="text-center">
                  {{jumlah}}
                </b-td>
              </b-tr>
            </b-tfoot>
          </b-table-simple>
        </b-overlay>
      </b-card>
    </template>
  </div>
</template>

<script>
import { BCard, BCardBody, BCardText, BSpinner, BRow, BCol, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd, BImg, BButton, BOverlay } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BCardText,
    BSpinner,
    BRow,
    BCol,
    BTableSimple,
    BThead,
    BTbody,
    BTfoot,
    BTr,
    BTh,
    BTd,
    BImg,
    BButton,
    BOverlay,
  },
  data() {
    return {
      loading: false,
      isBusy: true,
      table_sync: [],
      jumlah: 0,
      last_sync: '',
      form: {
        user_id: '',
        sekolah_id: '',
        semester_id: '',
      },
      aktif: true,
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.$http.post('/sinkronisasi/erapor', this.form).then(response => {
        this.isBusy = false
        this.loading = false
        let getData = response.data
        this.last_sync = getData.last_sync
        this.table_sync = getData.table_sync
        this.jumlah = getData.jumlah
        //this.aktif = (this.jumlah) ? false : true
      })
    },
    kirimData(){
      this.loading = true
      this.$http.post('/sinkronisasi/kirim-data', this.form).then(response => {
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.loadPostsData()
        })
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>