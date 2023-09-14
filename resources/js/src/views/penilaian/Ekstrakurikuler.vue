<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-overlay :show="loading_form" opacity="0.6" size="md" spinner-variant="danger">
          <b-form @submit="onSubmit">
            <b-row>
              <b-col cols="12">
                <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
                  <b-form-input id="tahun_pelajaran" :value="form.periode_aktif" disabled />
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Ekstrakurikuler" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="meta.rombongan_belajar_id_feedback" :state="meta.rombongan_belajar_id_state">
                  <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama_ekskul => nama_ekskul.rombongan_belajar_id" label="nama_ekskul" :options="data_rombel" placeholder="== Pilih Nama Ekstrakurikuler ==" @input="changeRombel" :state="meta.rombongan_belajar_id_state">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12" v-if="rombel_reguler.length">
                <b-form-group label="Filter Kelas" label-for="rombongan_belajar_id_reguler" label-cols-md="3" :invalid-feedback="meta.rombongan_belajar_id_reguler_feedback" :state="meta.rombongan_belajar_id_reguler_state">
                  <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id_reguler" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="rombel_reguler" placeholder="== Semua Kelas ==" @input="changeRombelReguler" :state="meta.rombongan_belajar_id_reguler_state">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-form-group>
              </b-col>
              <b-col cols="12" v-if="is_reset">
                <b-form-group label="Reset Nilai Ekstrakurikuler" label-cols-md="3">
                  <b-button variant="danger" @click="resetNilai">Reset Nilai {{nama_ekskul}} {{(nama_rombel) ? `Kelas ${nama_rombel}` : ''}}</b-button>
                </b-form-group>
              </b-col>
              <b-col cols="12" class="mt-2" v-if="show_table">
                <b-table-simple bordered striped responsive>
                  <b-thead>
                    <b-tr>
                      <b-th class="text-center" width="20%">Nama Peserta Didik</b-th>
                      <b-th class="text-center" width="10%">Kelas</b-th>
                      <b-th class="text-center" width="20%">Predikat</b-th>
                      <b-th class="text-center" width="50%">Deskripsi</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <template v-for="(item, index) in data_siswa">
                      <b-tr>
                        <b-td>{{item.nama}}</b-td>
                        <b-td>{{(item.kelas) ? item.kelas.nama : ''}}</b-td>
                        <b-td>
                          <v-select v-model="form.nilai_ekskul[item.anggota_ekskul.anggota_rombel_id]" :reduce="name => name.id" label="name" :options="data_nilai" placeholder="== Pilih Predikat ==" :searchable="false" @input="changeNilai(form.nilai_ekskul[item.anggota_ekskul.anggota_rombel_id], item.anggota_ekskul.anggota_rombel_id)"></v-select>
                        </b-td>
                        <b-td>
                          <b-form-input v-model="form.deskripsi_ekskul[item.anggota_ekskul.anggota_rombel_id]" />
                        </b-td>
                      </b-tr>
                    </template>
                  </b-tbody>
                </b-table-simple>
              </b-col>
              <b-col cols="12" v-if="show_table">
                <b-form-group label-cols-md="3">
                  <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-overlay>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton} from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
    vSelect,
  },
  data() {
    return {
      isBusy: true,
      loading_form: false,
      loading_rombel: false,
      is_reset: false,
      show_table: false,
      form: {
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        rombongan_belajar_id: '',
        ekstrakurikuler_id: '',
        rombongan_belajar_id_reguler: '',
        nilai_ekskul: {},
        deskripsi_ekskul: {},
      },
      meta: {
        rombongan_belajar_id_feedback: '',
        rombongan_belajar_id_state: null,
        rombongan_belajar_id_reguler_feedback: '',
        rombongan_belajar_id_reguler_state: null
      },
      data_rombel: [],
      rombel_reguler: [],
      data_siswa: [],
      nama_ekskul: '',
      nama_rombel: '',
      data_nilai: [],
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
      this.$http.post('/penilaian/get-ekskul', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data_rombel = getData.data_ekskul
        this.data_nilai = getData.data_nilai
        
      })
    },
    changeRombel(val){
      this.show_table = false
      this.rombel_reguler = []
      if(val){
        this.loading_form = true
        this.$http.post('/penilaian/get-rombel-reguler', this.form).then(response => {
          this.loading_form = false
          let getData = response.data
          this.form.ekstrakurikuler_id = getData.ekstrakurikuler.ekstrakurikuler_id
          this.nama_ekskul = getData.ekstrakurikuler.nama_ekskul
          this.rombel_reguler = getData.data_rombel
          this.data_siswa = getData.data_siswa
          this.show_table = true
          var nilai_ekskul = {}
          var deskripsi_ekskul = {}
          this.data_siswa.forEach(function(value, key) {
            nilai_ekskul[value.anggota_ekskul.anggota_rombel_id] = (value.anggota_ekskul.single_nilai_ekstrakurikuler) ? value.anggota_ekskul.single_nilai_ekstrakurikuler.nilai : ''
            deskripsi_ekskul[value.anggota_ekskul.anggota_rombel_id] = (value.anggota_ekskul.single_nilai_ekstrakurikuler) ? value.anggota_ekskul.single_nilai_ekstrakurikuler.deskripsi_ekskul : ''
          })
          this.form.nilai_ekskul = nilai_ekskul
          this.form.deskripsi_ekskul = deskripsi_ekskul
          this.is_reset = Object.keys(this.removeEmptyValues(nilai_ekskul)).length
          
        }).catch(error => {
          console.log(error);
        })
      }
    },
    removeEmptyValues(obj) {
      Object.keys(obj).forEach(key => {
        if (obj[key] && typeof obj[key] === 'object') {
          removeEmptyValues(obj[key]);
          if (Object.keys(obj[key]).length === 0) {
            delete obj[key];
          }
        } else if (obj[key] === '' || obj[key] === null || obj[key] === undefined) {
          delete obj[key];
        }
      });
      return obj;
    },
    changeRombelReguler(val){
      this.show_table = false
      if(val){
        this.loading_form = true
        this.$http.post('/penilaian/get-kelas', this.form).then(response => {
          this.loading_form = false
          let getData = response.data
          this.nama_rombel = getData.nama_rombel
          this.data_siswa = getData.data_siswa
          this.show_table = true
          var nilai_ekskul = {}
          var deskripsi_ekskul = {}
          this.data_siswa.forEach(function(value, key) {
            nilai_ekskul[value.anggota_ekskul.anggota_rombel_id] = (value.anggota_ekskul.single_nilai_ekstrakurikuler) ? value.anggota_ekskul.single_nilai_ekstrakurikuler.nilai : ''
            deskripsi_ekskul[value.anggota_ekskul.anggota_rombel_id] = (value.anggota_ekskul.single_nilai_ekstrakurikuler) ? value.anggota_ekskul.single_nilai_ekstrakurikuler.deskripsi_ekskul : ''
          })
          this.form.nilai_ekskul = nilai_ekskul
          this.form.deskripsi_ekskul = deskripsi_ekskul
          this.is_reset = Object.keys(this.removeEmptyValues(nilai_ekskul)).length
          
        }).catch(error => {
          console.log(error);
        })
      }
    },
    resetNilai(){
      //Nilai {{nama_ekskul}} {{(nama_rombel) ? `Kelas ${nama_rombel}` : ''
      var text = `Seluruh Nilai ${this.nama_ekskul} akan dihapus!`
      if(this.nama_rombel){
        text = `Seluruh Nilai ${this.nama_ekskul} di Kelas ${nama_rombel} akan dihapus!`
      }
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: text,
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
          this.loading_form = true
          this.$http.post('/penilaian/hapus-nilai-ekskul', this.form).then(response => {
            this.loading_form = false
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              this.resetForm()
            })
          });
        }
      })
    },
    onSubmit(event) {
      event.preventDefault()
      this.loading_form = true
      this.$http.post('/penilaian/simpan-nilai-ekskul', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.resetForm()
        })
      })
    },
    resetForm(){
      this.rombel_reguler = []
      this.data_siswa = []
      this.nama_ekskul = ''
      this.nama_rombel = ''
      this.form.rombongan_belajar_id = ''
      this.form.rombongan_belajar_id_reguler = ''
      this.form.nilai_ekskul = {}
      this.form.deskripsi_ekskul = {}
      this.show_table = false
      this.is_reset = false
    },
    changeNilai(val, anggota_rombel_id){
      var template_desk = {
        1: 'Sangat Baik',
        2: 'Baik',
        3: 'Cukup',
        4: 'Kurang',
      }
      this.form.deskripsi_ekskul[anggota_rombel_id] = (val) ? `Melaksanakan kegiatan ${this.nama_ekskul} dengan ${template_desk[val]}` : ''
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>