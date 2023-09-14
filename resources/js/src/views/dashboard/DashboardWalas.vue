<template>
  <div>
    <b-card no-body v-if="isBusy">
      <b-card-body>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </b-card-body>
    </b-card>
    <b-card no-body v-if="rombel">
      <b-card-body>
        <h2>Anda adalah Wali Kelas Rombongan Belajar {{rombel}}</h2>
        <h2>Daftar Mata Pelajaran di Rombongan Belajar {{rombel}}</h2>
        <b-table-simple bordered responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Mata Pelajaran</b-th>
              <b-th class="text-center">Guru Mata Pelajaran</b-th>
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
                  <b-td>{{item.guru}}</b-td>
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
      </b-card-body>
    </b-card>
    <b-card no-body v-if="rombel_pilihan">
      <b-card-body>
        <template v-if="isBusy">
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-else>
          <h2>Anda adalah Wali Kelas Rombongan Belajar {{rombel_pilihan}}</h2>
          <h2>Daftar Mata Pelajaran di Rombongan Belajar {{rombel_pilihan}}</h2>
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
              <template v-if="pembelajaran_pilihan.length">
                <template v-for="(item, index) in pembelajaran_pilihan">
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
        </template>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton
  },
  data() {
    return {
      isBusy: true,
      rombel: '',
      rombel_pilihan: '',
      pembelajaran: [],
      pembelajaran_pilihan: [],
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.$http.post('/dashboard/wali', {
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        periode_aktif: this.user.semester.nama,
        guru_id: this.user.guru_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.rombel = getData.rombel
        this.rombel_pilihan = getData.rombel_pilihan
        this.pembelajaran = getData.pembelajaran
        this.pembelajaran_pilihan = getData.pembelajaran_pilihan
      }).catch(error => {
        console.log(error)
      })
    },
    detil(pembelajaran_id){
      this.$emit('detil', pembelajaran_id)
    },
  }
}
</script>