<template>
  <div>
    <b-card no-body>
      <b-card-body>
        <div v-if="isBusy" class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
        <div v-else>
          <h4 class="card-title">Mata Pelajaran yang diampu di Tahun Pelajaran {{semester}}</h4>
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Mata Pelajaran</b-th>
                <b-th class="text-center">Rombel</b-th>
                <b-th class="text-center">Wali Kelas</b-th>
                <b-th class="text-center">Jml Peserta Didik</b-th>
                <b-th class="text-center">Jml Peserta Didik Dinilai</b-th>
                <b-th class="text-center">Detil</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-if="pembelajaran.length">
                <template v-for="(item, index) in pembelajaran">
                  <b-tr :variant="(item.induk_pembelajaran_id) ? 'warning' : null">
                    <b-td class="text-center">{{item.no}}</b-td>
                    <b-td>{{item.nama_mata_pelajaran}}</b-td>
                    <b-td>{{item.rombel}}</b-td>
                    <b-td>{{item.wali_kelas}}</b-td>
                    <template v-if="item.mata_pelajaran_id === 800001000">
                      <b-td class="text-center">{{item.pd_pkl_count}}</b-td>
                      <b-td class="text-center">{{item.pd_pkl_dinilai}}</b-td>
                    </template>
                    <template v-else>
                      <b-td class="text-center">{{item.pd}}</b-td>
                      <b-td class="text-center">{{item.pd_dinilai}}</b-td>
                    </template>
                    <b-td class="text-center">
                      <b-button variant="success" size="sm" @click="detil(item)">Detil</b-button>
                    </b-td>
                  </b-tr>
                </template>
              </template>
              <template v-else>
                <b-tr>
                  <b-td class="text-center" colspan="7">Tidak ada data untuk ditampilkan</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </div>
      </b-card-body>
      <detil-nilai :title="title" :data_siswa="data_siswa" :merdeka="merdeka" :is_ppa="is_ppa" :sub_mapel="sub_mapel" :induk="induk" :meta="meta" @detil="HandleDetil"></detil-nilai>
    </b-card>
    <dashboard-walas v-if="hasRole('wali')" @detil="HandleDetil"></dashboard-walas>
  </div>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton, BOverlay } from 'bootstrap-vue'
import DashboardWalas from './DashboardWalas.vue'
import DetilNilai from './../components/modal/dashboard/DetilNilai.vue'
import eventBus from '@core/utils/eventBus';
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
    BButton,
    BOverlay,
    DashboardWalas,
    DetilNilai,
  },
  computed: {
    semester(){
      return (this.user) ? this.user.semester.nama : '-'
    }
  },
  data() {
    return {
      isBusy: true,
      pembelajaran: [],
      title: '',
      loading_modal: false,
      data_siswa: [],
      merdeka: false,
      is_ppa: false,
      sub_mapel: 0,
      pembelajaran_id: null,
      rombongan_belajar_id: null,
      meta: {},
      induk: null,
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.$http.post('/dashboard', {
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        periode_aktif: this.user.semester.nama,
        guru_id: this.user.guru_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.pembelajaran = getData.pembelajaran
      }).catch(error => {
        console.log(error)
      })
    },
    detil(item){
      this.pembelajaran_id = item.pembelajaran_id
      this.rombongan_belajar_id = item.rombongan_belajar_id
      this.meta = {
        kkm: item.kkm,
        kelompok_id: item.kelompok_id,
        semester_id: item.semester_id,
      }
      this.loading_modal = true
      this.$http.post('/dashboard/detil-penilaian', {
        pembelajaran_id: this.pembelajaran_id,
        rombongan_belajar_id: this.rombongan_belajar_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.induk = getData.pembelajaran.induk
        if(getData.pembelajaran.mata_pelajaran_id == 800001000){
          this.sub_mapel = 1
        } else {
          this.sub_mapel = getData.pembelajaran.tema_count
        }
        this.title = getData.title
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
        this.is_ppa = getData.is_ppa
        //this.$refs['detil-modal'].show()
        eventBus.$emit('open-modal-detil-nilai', {
          data: {
            pembelajaran_id: this.pembelajaran_id,
            mata_pelajaran_id: getData.pembelajaran.mata_pelajaran_id,
            rombongan_belajar_id: this.rombongan_belajar_id,
          }
        })
      }).catch(error => {
        console.log(error)
      })
    },
    HandleDetil(item){
      this.detil(item)
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>