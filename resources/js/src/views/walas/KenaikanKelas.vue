<template>
  <b-card no-body>
    <b-overlay :show="loadingForm" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <div v-if="isBusy" class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
        <div v-else>
          <b-form @submit="onSubmit">
            <b-table-simple bordered striped responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">Nama Peserta Didik</b-th>
                  <b-th class="text-center">Status Kenaikan</b-th>
                  <b-th class="text-center">Ke Kelas</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(item, index) in data_siswa">
                  <b-tr>
                    <b-td>{{item.nama}}</b-td>
                    <b-td>
                      <b-form-select v-model="form.status[item.anggota_rombel.anggota_rombel_id]" :options="options" @change="statusKenaikan(form.status[item.anggota_rombel.anggota_rombel_id], item.anggota_rombel.anggota_rombel_id, item.anggota_rombel.rombongan_belajar_id)"></b-form-select>
                    </b-td>
                    <b-td>
                      <b-form-input v-model="form.nama_kelas[item.anggota_rombel.anggota_rombel_id]" />
                    </b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
            <b-form-group label-cols-md="3">
              <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
            </b-form-group>
          </b-form>
        </div>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BForm, BFormSelect, BFormGroup, BFormInput, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol } from 'bootstrap-vue'
import Formulir from './../components/Formulir.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BForm, BFormSelect, BFormGroup, BFormInput,
    BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol,
    Formulir
  },
  data() {
    return {
      loadingForm: false,
      isBusy: true,
      form: {
        aksi: 'kenaikan-kelas',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        status: {},
        nama_kelas: {},
        rombongan_belajar_id: {},
        tingkat: '',
        id_rombel: '',
      },
      data_siswa: [],
      options: [],
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
      this.$http.post('/walas/kenaikan-kelas', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data_siswa = getData.data_siswa
        this.options = getData.options
        this.form.tingkat = (getData.rombel) ? getData.rombel.tingkat : 0
        this.form.id_rombel = (getData.rombel) ? getData.rombel.rombongan_belajar_id : null
        var status = {}
        var nama_kelas = {}
        var rombongan_belajar_id = {}
        this.data_siswa.forEach(function(siswa, key) {
          status[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_kenaikan_kelas) ? siswa.anggota_rombel.single_kenaikan_kelas.status : null
          nama_kelas[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_kenaikan_kelas) ? siswa.anggota_rombel.single_kenaikan_kelas.nama_kelas : null
          rombongan_belajar_id[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_kenaikan_kelas) ? siswa.anggota_rombel.single_kenaikan_kelas.rombongan_belajar_id : null
        });
        this.form.status = status
        this.form.nama_kelas = nama_kelas
        this.form.rombongan_belajar_id = rombongan_belajar_id
      })
    },
    statusKenaikan(val, anggota_rombel_id, rombongan_belajar_id){
      if(val === 1){
        this.loadingForm = true
        this.$http.post('/walas/get-next-rombel', this.form).then(response => {
          this.loadingForm = false
          let getData = response.data
          console.log(getData);
          this.$swal({
            title: 'Pilih Rombongan Belajar',
            input: 'select',
            inputOptions: getData,
            inputPlaceholder: '== Pilih Rombongan Belajar ==',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
              confirmButton: 'btn btn-success mr-1',
            },
            inputValidator: (value) => {
              return new Promise((resolve) => {
                if (value) {
                  if(value == rombongan_belajar_id){
                    this.form.rombongan_belajar_id[anggota_rombel_id] = rombongan_belajar_id
                    resolve()
                  } else {
                    this.$http.post('/walas/find-rombel', {
                      rombongan_belajar_id: value
                    }).then(response => {
                      let getData = response.data
                      this.form.rombongan_belajar_id[anggota_rombel_id] = rombongan_belajar_id
                      this.form.nama_kelas[anggota_rombel_id] = getData.nama
                      resolve()
                    });
                  }
                } else {
                  resolve('Rombongan Belajar tidak boleh kosong!')
                }
              })
            }
          })
        })
      }
    },
    onSubmit(event) {
      event.preventDefault()
      this.loadingForm = true
      this.$http.post('/walas/save', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        })
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>