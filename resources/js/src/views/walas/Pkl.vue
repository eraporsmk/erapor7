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
            <template v-if="allowed">
              <template v-if="error">
                <b-alert variant="danger" show>
                  <div class="alert-body">
                    Mohon periksa kembali isian Anda. Pastikan kolom yang berwarna merah telah terisi!
                  </div>
                </b-alert>
              </template>
              <b-row>
                <b-col cols="12">
                  <b-form-group label="Pilih DUDI" label-for="dudi_id" label-cols-md="3" :invalid-feedback="meta.dudi_id_feedback" :state="meta.dudi_id_state">
                    <v-select id="dudi_id" v-model="form.dudi_id" :reduce="nama => nama.dudi_id" label="nama" :options="data_dudi" placeholder="== Pilih DUDI ==" @input="changeDudi" :state="meta.dudi_id_state">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-form-group>
                </b-col>
              </b-row>  
              <b-table-simple bordered striped responsive>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">Nama Peserta Didik</b-th>
                    <b-th class="text-center">Alamat DUDI</b-th>
                    <b-th class="text-center">Skala Kesesuaian dengan Kompetensi Keahlian (1-10)</b-th>
                    <b-th class="text-center">Lamanya (bulan)</b-th>
                    <b-th class="text-center">Keterangan</b-th>
                    <b-th class="text-center">Aksi</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="(item, index) in data_siswa">
                    <b-tr>
                      <b-td>{{item.nama}}</b-td>
                      <b-td>
                        <b-form-input v-model="form.lokasi_prakerin[item.anggota_rombel.anggota_rombel_id]" :state="lokasi_prakerin_state[item.anggota_rombel.anggota_rombel_id]" />
                      </b-td>
                      <b-td>
                        <b-form-group :state="skala_state[item.anggota_rombel.anggota_rombel_id]" style="margin-bottom: 0">
                          <v-select id="dudi_id" v-model="form.skala[item.anggota_rombel.anggota_rombel_id]" :options="[1,2,3,4,5,6,7,8,9,10]" placeholder="== Pilih Skala ==" :searchable="false" :disabled="disabled"></v-select>
                        </b-form-group>
                      </b-td>
                      <b-td>
                        <b-form-input v-model="form.lama_prakerin[item.anggota_rombel.anggota_rombel_id]" :state="lama_prakerin_state[item.anggota_rombel.anggota_rombel_id]" />
                      </b-td>
                      <b-td>
                        <b-form-input v-model="form.keterangan_prakerin[item.anggota_rombel.anggota_rombel_id]" :state="keterangan_prakerin_state[item.anggota_rombel.anggota_rombel_id]" />
                      </b-td>
                      <b-td class="text-center">
                        <a class="text-danger" @click="hapus(prakerin_id[item.anggota_rombel.anggota_rombel_id])" v-if="prakerin_id[item.anggota_rombel.anggota_rombel_id] && hasRole('wali')"><font-awesome-icon icon="fa-solid fa-trash" /></a>
                      </b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
              <b-form-group label-cols-md="3" v-if="show_form">
                <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
              </b-form-group>
            </template>
            <template v-else>
              <b-alert variant="danger" show>
                <div class="alert-body text-center">
                  <p><font-awesome-icon icon="fa-solid fa-ban" /> <strong>Akses Ditutup!</strong></p>
                  <p v-if="semester_allowed">Kurikulum <strong>{{nama_kurikulum}}</strong>, Praktik Kerja Lapangan hanya untuk kelas <strong>{{tingkat}}</strong>, Semester Genap</p>
                  <p v-else>Kurikulum <strong>{{nama_kurikulum}}</strong>, Praktik Kerja Lapangan hanya untuk kelas <strong>{{tingkat}}</strong></p>
                </div>
              </b-alert>
            </template>
          </b-form>
        </div>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BForm, BFormInput, BFormGroup, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BAlert} from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BForm, BFormInput, BFormGroup, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BRow, BCol,
    BAlert,
    FormulirWaka,
    vSelect
  },
  data() {
    return {
      loadingForm: false,
      isBusy: true,
      form: {
        aksi: 'pkl',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        dudi_id: '',
        lokasi_prakerin: {},
        skala: {},
        lama_prakerin: {},
        keterangan_prakerin: {},
        tingkat: '',
        rombongan_belajar_id: '',
      },
      meta: {
        dudi_id_feedback: '',
        dudi_id_state: null,
        tingkat_feedback: '',
        tingkat_state: null,
        rombongan_belajar_id_feedback: '',
        rombongan_belajar_id_state: '',
      },
      data_dudi: [],
      allowed: false,
      semester_allowed: false,
      nama_kurikulum: '',
      tingkat: '',
      prakerin_id: {},
      lokasi_prakerin_state: {},
      skala_state: {},
      lama_prakerin_state: {},
      keterangan_prakerin_state: {},
      error: false,
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
      this.$http.post('/walas/praktik-kerja-lapangan', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.show_table = getData.show
        this.show_form = getData.form
        this.allowed = getData.allowed
        this.semester_allowed = getData.semester_allowed
        this.nama_kurikulum = getData.nama_kurikulum
        this.tingkat = getData.tingkat
        this.data_dudi = getData.data_dudi
      })
    },
    onSubmit(event) {
      event.preventDefault()
      this.error = false
      this.loadingForm = true
      this.$http.post('/walas/save', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        if(getData.errors){
          this.error = true
          var lokasi_prakerin_state = {}
          var skala_state = {}
          var lama_prakerin_state = {}
          var keterangan_prakerin_state = {}
          this.data_siswa.forEach(function(siswa, key) {
            lokasi_prakerin_state[siswa.anggota_rombel.anggota_rombel_id] = (getData.errors[`lokasi_prakerin.${siswa.anggota_rombel.anggota_rombel_id}`]) ? false : null
            skala_state[siswa.anggota_rombel.anggota_rombel_id] = (getData.errors[`skala.${siswa.anggota_rombel.anggota_rombel_id}`]) ? false : null
            lama_prakerin_state[siswa.anggota_rombel.anggota_rombel_id] = (getData.errors[`lama_prakerin.${siswa.anggota_rombel.anggota_rombel_id}`]) ? false : null
            keterangan_prakerin_state[siswa.anggota_rombel.anggota_rombel_id] = (getData.errors[`keterangan_prakerin.${siswa.anggota_rombel.anggota_rombel_id}`]) ? false : null
          })
          this.lokasi_prakerin_state = lokasi_prakerin_state
          this.skala_state = skala_state
          this.lama_prakerin_state = lama_prakerin_state
          this.keterangan_prakerin_state = keterangan_prakerin_state
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }
      })
    },
    changeDudi(val){
      if(val){
        this.loadingForm = true
        this.$http.post('/walas/anggota-pkl', this.form).then(response => {
          this.loadingForm = false
          let getData = response.data
          this.data_siswa = getData.data_siswa
          var lokasi_prakerin = {}
          var skala = {}
          var lama_prakerin = {}
          var keterangan_prakerin = {}
          var prakerin_id = {}
          this.data_siswa.forEach(function(siswa, key) {
            prakerin_id[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_prakerin) ? siswa.anggota_rombel.single_prakerin.prakerin_id : null
            lokasi_prakerin[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_prakerin) ? siswa.anggota_rombel.single_prakerin.lokasi_prakerin : getData.dudi.alamat_jalan
            skala[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_prakerin) ? siswa.anggota_rombel.single_prakerin.skala : null
            lama_prakerin[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_prakerin) ? siswa.anggota_rombel.single_prakerin.lama_prakerin : null
            keterangan_prakerin[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.single_prakerin) ? siswa.anggota_rombel.single_prakerin.keterangan_prakerin : null
          })
          this.prakerin_id = prakerin_id
          this.form.lokasi_prakerin = lokasi_prakerin
          this.form.skala = skala
          this.form.lama_prakerin = lama_prakerin
          this.form.keterangan_prakerin = keterangan_prakerin
        })
      }
    },
    hapus(prakerin_id){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.loading_modal = true
          this.$http.post('walas/hapus-prakerin', {
            prakerin_id: prakerin_id,
          }).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              this.changeDudi(this.form.dudi_id)
            })
          });
        }
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>