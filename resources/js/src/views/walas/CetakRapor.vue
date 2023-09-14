<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-table-simple bordered striped responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">Nama Peserta Didik</b-th>
              <b-th class="text-center">NISN</b-th>
              <b-th class="text-center">Halaman Depan</b-th>
              <b-th class="text-center">Rapor Akademik</b-th>
              <b-th class="text-center" v-if="merdeka">Rapor P5</b-th>
              <b-th class="text-center">Dokumen Pendukung</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(item, index) in data_siswa">
              <b-tr>
                <b-td>{{item.nama}}</b-td>
                <b-td class="text-center">{{item.nisn}}</b-td>
                <b-td class="text-center">
                  <b-button variant="success" :href="`/cetak/rapor-cover/${item.anggota_rombel.anggota_rombel_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file" size="2xl" /></b-button>
                </b-td>
                <b-td class="text-center">
                  <b-button variant="warning" :href="`/cetak/rapor-nilai-akhir/${item.anggota_rombel.anggota_rombel_id}/${form.sekolah_id}/${form.semester_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file-pdf" size="2xl" /></b-button>
                </b-td>
                <b-td class="text-center" v-if="merdeka">
                  <b-button variant="info" :href="`/cetak/rapor-p5/${item.anggota_rombel.anggota_rombel_id}/${form.semester_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file-pdf" size="2xl" /></b-button>
                </b-td>
                <b-td class="text-center">
                  <b-button variant="danger" :href="`/cetak/rapor-pelengkap/${item.anggota_rombel.anggota_rombel_id}/${item.anggota_rombel.rombongan_belajar_id}`" target="_blank"><font-awesome-icon icon="fa-solid fa-file-pdf" size="2xl" /></b-button>
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
import { BCard, BCardBody, BSpinner, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton } from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton,
    FormulirWaka
  },
  data() {
    return {
      isBusy: true,
      merdeka: false,
      form: {
        aksi: 'cetak-rapor',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
      },
      data_siswa: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.$http.post('/walas/cetak-rapor', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>