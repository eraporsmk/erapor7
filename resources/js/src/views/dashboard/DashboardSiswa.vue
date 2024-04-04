<template>
  <b-card no-body>
    <b-card-body>
      <template v-if="isBusy">
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
      <template v-else>
        <h2>Selamat Datang {{username}}</h2>
        <p>Anda sedang berada di Rombongan Belajar <b-badge variant="info">{{ kelas }}</b-badge> Wali Kelas <b-badge variant="info">{{ wali_kelas }}</b-badge>.</p>
        <h2>Daftar Mata Pelajaran</h2>
        <b-table-simple bordered>
          <b-thead>
            <template v-if="merdeka">
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Mata Pelajaran</b-th>
                <b-th class="text-center">Guru Mata Pelajaran</b-th>
                <b-th class="text-center">Nilai Rapor</b-th>
                <b-th class="text-center">Detil Nilai</b-th>
              </b-tr>
            </template>
            <template v-else>
              <b-tr>
                <b-th rowspan="2" class="text-center align-middle">No</b-th>
                <b-th rowspan="2" class="text-center align-middle">Mata Pelajaran</b-th>
                <b-th rowspan="2" class="text-center align-middle">Guru Mata Pelajaran</b-th>
                <b-th colspan="2" class="text-center">Nilai Pengetahuan</b-th>
                <b-th colspan="2" class="text-center">Nilai Keterampilan</b-th>
                <b-th rowspan="2" class="text-center align-middle">Detil Nilai</b-th>
              </b-tr>
              <b-tr>
                <b-th class="text-center">Angka</b-th>
                <b-th class="text-center">Predikat</b-th>
                <b-th class="text-center">Angka</b-th>
                <b-th class="text-center">Predikat</b-th>
              </b-tr>
            </template>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(item, index) in pembelajaran" :key="item.pembelajaran_id">
              <b-td class="text-center">{{ index + 1 }}</b-td>
              <b-td>{{ item.nama_mata_pelajaran }}</b-td>
              <b-td>{{ (item.guru_pengajar_id) ? item.pengajar.nama_lengkap : item.guru.nama_lengkap }}</b-td>
              <template v-if="merdeka">
                <b-td class="text-center">
                  {{ (item.nilai_akhir_kurmer) ? item.nilai_akhir_kurmer.nilai : (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}
                </b-td>
              </template>
              <template v-else>
                <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}</b-td>
                <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? predikat(item.nilai_akhir_pengetahuan.nilai) : '-' }}</b-td>
                <b-td class="text-center">{{ (item.nilai_akhir_keterampilan) ? item.nilai_akhir_keterampilan.nilai : '-' }}</b-td>
                <b-td class="text-center">{{ (item.nilai_akhir_keterampilan) ? predikat(item.nilai_akhir_keterampilan.nilai) : '-' }}</b-td>
              </template>
              <b-td class="text-center"><b-button size="sm" variant="success" :to="{ name: 'detil-nilai', params: { pembelajaran_id: item.pembelajaran_id } }">Detil Nilai</b-button></b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </template>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BBadge, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BButton } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BBadge,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BButton,
  },
  data() {
    return {
      isBusy: true,
      kelas: '',
      wali_kelas: '',
      pembelajaran: [],
      merdeka: false,
    }
  },
  computed: {
    username(){
      return (this.user) ? this.user.name : ''
    }
  },
  created() {
    this.$http.post('/dashboard', {
      sekolah_id: this.user.sekolah_id,
      semester_id: this.user.semester.semester_id,
      periode_aktif: this.user.semester.nama,
    }).then(response => {
      this.isBusy = false
      let getData = response.data
      this.kelas = getData.pd.kelas?.nama
      this.wali_kelas = getData.pd.kelas?.wali_kelas.nama_lengkap
      this.pembelajaran = getData.pd.kelas?.pembelajaran
      this.merdeka = this.isMerdeka(getData.pd.kelas?.kurikulum.nama_kurikulum)
    })
  },
  methods: {
    isMerdeka(kurikulum){
      if((parseInt(this.user.semester.semester_id) > 20222)){
        return (parseInt(this.user.semester.semester_id) > 20222)
      }
      return kurikulum.includes('Merdeka')
    },
    predikat(angka){
      return angka
    }
  },
}
</script>