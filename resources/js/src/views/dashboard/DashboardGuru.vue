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
                  <b-tr>
                    <b-td class="text-center">{{item.no}}</b-td>
                    <b-td>{{item.nama_mata_pelajaran}}</b-td>
                    <b-td>{{item.rombel}}</b-td>
                    <b-td>{{item.wali_kelas}}</b-td>
                    <b-td class="text-center">{{item.pd}}</b-td>
                    <b-td class="text-center">{{item.pd_dinilai}}</b-td>
                    <b-td class="text-center">
                      <b-button variant="success" size="sm" @click="detil(item.pembelajaran_id)">Detil</b-button>
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
      <b-modal ref="detil-modal" size="xl" scrollable :title="title" ok-only ok-title="Tutup" ok-variant="secondary">
        <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Nama</b-th>
                <b-th class="text-center">NISN</b-th>
                <b-th class="text-center">Agama</b-th>
                <b-th class="text-center">Nilai Akhir</b-th>
                <b-th class="text-center">Capaian Kompetensi</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-if="data_siswa.length">
                <template v-for="(pd, index) in data_siswa">
                  <b-tr>
                    <b-td class="text-center">{{index + 1}}</b-td>
                    <b-td>{{pd.nama}}</b-td>
                    <b-td class="text-center">{{pd.nisn}}</b-td>
                    <b-td class="text-center">{{pd.agama.nama}}</b-td>
                    <b-td class="text-center" v-if="merdeka">
                      {{(pd.nilai_akhir_kurmer) ? pd.nilai_akhir_kurmer.nilai : '-'}}
                    </b-td>
                    <b-td class="text-center" v-else>
                      {{(pd.nilai_akhir_pengetahuan) ? pd.nilai_akhir_pengetahuan.nilai : '-'}}
                    </b-td>
                    <b-td v-if="pd.deskripsi_mapel">
                      <template v-if="pd.deskripsi_mapel.deskripsi_pengetahuan && pd.deskripsi_mapel.deskripsi_keterampilan">
                        {{pd.deskripsi_mapel.deskripsi_pengetahuan}}
                        <hr>
                        {{pd.deskripsi_mapel.deskripsi_keterampilan}}
                      </template>
                      <template v-if="pd.deskripsi_mapel.deskripsi_pengetahuan && !pd.deskripsi_mapel.deskripsi_keterampilan">
                        {{pd.deskripsi_mapel.deskripsi_pengetahuan}}
                      </template>
                      <template v-if="!pd.deskripsi_mapel.deskripsi_pengetahuan && pd.deskripsi_mapel.deskripsi_keterampilan">
                        {{pd.deskripsi_mapel.deskripsi_keterampilan}}
                      </template>
                    </b-td>
                    <b-td v-else>-</b-td>
                  </b-tr>
                </template>
              </template>
            </b-tbody>
          </b-table-simple>
      </b-modal>
    </b-card>
    <dashboard-walas v-if="hasRole('wali')" @detil="HandleDetil"></dashboard-walas>
  </div>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton } from 'bootstrap-vue'
import DashboardWalas from './DashboardWalas.vue'
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
    DashboardWalas,
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
    detil(pembelajaran_id){
      this.loading_modal = true
      this.$http.post('/dashboard/detil-penilaian', {
        pembelajaran_id: pembelajaran_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.title = getData.title
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
        this.$refs['detil-modal'].show()
        console.log(getData);
      }).catch(error => {
        console.log(error)
      })
    },
    HandleDetil(pembelajaran_id){
      console.log('HandleDetil');
      console.log(pembelajaran_id);
      this.detil(pembelajaran_id)
    },
  },
}
</script>