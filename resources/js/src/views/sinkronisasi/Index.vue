<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-alert show variant="danger" v-if="jam_sinkron">
          <div class="alert-body text-center">
            <h2>Penyesuaian Waktu Layanan Sinkronisasi Dapodik</h2>
            <p>Dikarenakan adanya proses rutin sinkronisasi data Dapodik antara Server PUSDATIN dan Server Direktorat SMK, <br>maka layanan sinkronisasi hanya dapat diakses antara pukul <span class="text-danger"><b>03.00 s/d 24.00 (WIB)</b></span></p>
          </div>
        </b-alert>
        <b-table-simple responsive>
          <b-thead head-variant="dark" v-show="show">
            <b-tr>
              <b-th class="text-center">{{syncText}}</b-th>
            </b-tr>
          </b-thead>
        </b-table-simple>
        <b-table-simple bordered responsive v-if="!jam_sinkron">
          <b-thead>
            <b-tr>
              <b-th class="text-center">Data</b-th>
              <b-th class="text-center">Jml Data Dapodik</b-th>
              <b-th class="text-center">Jml Data e-Rapor</b-th>
              <b-th class="text-center">Jml Data Tersinkronisasi</b-th>
              <b-th class="text-center">Status</b-th>
              <b-th class="text-center">Aksi</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(item, index) in dapodik">
              <b-tr>
                <b-td>
                  {{item.nama}}
                  <font-awesome-icon icon="fa-solid fa-circle-question" v-b-tooltip.hover.html="item.html" v-if="item.icon" />
                </b-td>
                <b-td class="text-center">{{item.dapodik}}</b-td>
                <b-td class="text-center">{{item.erapor}}</b-td>
                <b-td class="text-center">{{item.sinkron}}</b-td>
                <b-td class="text-center">
                  <template v-if="item.sinkron">
                    <b-badge variant="warning" v-if="item.dapodik > item.erapor">Kurang</b-badge>
                    <b-badge variant="success" v-else>Lengkap</b-badge>
                  </template>
                  <template v-else>
                    <b-badge variant="danger">Belum</b-badge>
                  </template>
                </b-td>
                <b-td class="text-center">
                  <template v-if="item.dapodik">
                    <b-button variant="success" block size="sm" disabled v-if="loading">
                      <b-spinner small></b-spinner>
                      <span class="sr-only">Loading...</span>
                    </b-button>
                    <b-button block size="sm" variant="success" @click="syncSatuan(item.server, item.aksi)" v-else>Sinkronisasi</b-button>
                  </template>
                  <template v-else>
                    -
                  </template>
                </b-td>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BTbody, BThead, BTr, BTd, BTh, BBadge, BButton, VBTooltip, BAlert } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple,
    BTbody,
    BThead,
    BTr,
    BTd,
    BTh,
    BBadge, 
    BButton,
    VBTooltip,
    BAlert,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      loading: false,
      isBusy: true,
      show: false,
      jam_sinkron: false,
      syncText: '',
      dapodik: [],
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.isBusy = true
      this.$http.post('/sinkronisasi', {
        sekolah_id: this.user.sekolah_id,
        user_id: this.user.user_id,
        semester_id: this.semester_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.jam_sinkron = getData.jam_sinkron
        this.dapodik = getData.data_sinkron
        this.show = false
        this.syncText = ''
        console.log(getData);
      })
    },
    syncSatuan(server, satuan){
      if(server && satuan){
        this.show = true
        this.syncText = 'Menyiapkan proses sinkronisasi'
        this.loading = true
        console.log(server);
        console.log(satuan);
        var myInterval;
        myInterval = setInterval(this.myTimer, 500);
        this.$http.post('/sinkronisasi/dapodik', {
          email: this.user.email,
          satuan: satuan,
          tujuan: server,
          sekolah_id: this.user.sekolah_id,
          user_id: this.user.user_id,
          semester_id: this.semester_id,
        }).then(response => {
          this.loading = false
          this.show = false
          console.log(myInterval);
          console.log('selesai');
          clearInterval(myInterval);
          this.syncText = 'Proses sinkronisasi selesai'
          this.$swal({
            icon: 'success',
            title: 'Berhasil',
            text: 'Sinkronisasi Dapodik Berhasil',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostData()
          })
        })
      } else {
        console.log('ga ketemu');
      }
    },
    myTimer() {
      this.$http.get('/sinkronisasi/hitung/'+this.user.sekolah_id).then(response => {
        this.show = true
        let getData = response.data
        if(getData.output){
          if(getData.output.jumlah){
            if(getData.output.jumlah === 1 && getData.output.inserted === 1){
              this.syncText = 'Proses sinkronisasi selesai'
            } else {
              this.syncText = getData.output.table+' ('+getData.output.inserted+'/'+getData.output.jumlah+')'
            }
          } else {
            this.syncText = getData.output.table
          }
        }
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>