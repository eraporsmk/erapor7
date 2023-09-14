<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <b-form @submit.prevent="handleSubmit">
            <b-row>
              <b-col cols="7">
                <b-row>
                  <b-col cols="12">
                    <b-form-group label="Periode Aktif" label-for="periode-aktif" :invalid-feedback="feedback.semester_id" :state="state.semester_id">
                      <v-select id="periode-aktif" v-model="form.semester_id" :options="semester" :reduce="nama => nama.semester_id" label="nama" placeholder="== Pilih Periode Aktif ==" :clearable="false">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                    <b-form-group label="Tanggal Rapor Semester" label-for="tanggal_rapor" :invalid-feedback="feedback.tanggal_rapor" :state="state.tanggal_rapor">
                      <b-form-datepicker v-model="form.tanggal_rapor" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_rapor" @context="onContext" placeholder="== Pilih Tanggal Rapor Semester ==" />
                    </b-form-group>
                    <b-form-group label="Tanggal Rapor Kelas Akhir" label-for="tanggal_rapor_kelas_akhir" v-if="periode == '2'">
                      <b-form-datepicker v-model="form.tanggal_rapor_kelas_akhir" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_rapor" @context="onContext" placeholder="== Pilih Tanggal Rapor Kelas Akhir ==" />
                    </b-form-group>
                    <b-form-group label="Zona Waktu" label-for="zona-waktu" :invalid-feedback="feedback.zona" :state="state.zona">
                      <v-select id="zona-waktu" v-model="form.zona" :options="data_zona" :reduce="text => text.value" label="text" placeholder="== Pilih Zona Waktu ==" :clearable="false" :searchable="false">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                    <b-form-group label="Kepala Sekolah" label-for="kepala-sekolah">
                      <v-select id="kepala-sekolah" v-model="form.kepala_sekolah" :options="data_guru" :reduce="nama_lengkap => nama_lengkap.guru_id" label="nama_lengkap" placeholder="== Pilih Kepala Sekolah ==" :clearable="false">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                    <b-form-group label="Rombongan Belajar 4 Tahun" label-for="rombel-4-tahun">
                      <v-select v-model="form.rombel_4_tahun" multiple :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar 4 Tahun ==">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-col>
              <b-col cols="5">
                <b-row>
                  <b-col cols="12" class="text-center">
                    <p>Logo Sekolah</p>
                    <b-img thumbnail fluid :src="`/storage/images/${logo_sekolah}`" alt="Logo Sekolah" class="mb-1" v-if="logo_sekolah"></b-img>
                    <b-form-file v-model="form.file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange" :state="state.file" />
                    <p v-show="feedback.file" class="text-danger">{{feedback.file}}</p>
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12">
                <b-button type="submit" variant="primary">Simpan</b-button>
              </b-col>
            </b-row>
          </b-form>
        </b-overlay>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BForm, BRow, BCol, BFormGroup, BInputGroup, BFormDatepicker, BFormInput, BFormFile, BButton, BOverlay, BImg } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BForm, BRow, BCol, BFormGroup, BInputGroup, BFormDatepicker, BFormInput,
    vSelect,
    BFormFile,
    BButton,
    BOverlay,
    BImg
  },
  data() {
    return {
      loading: false,
      isBusy: true,
      semester: [],
      form: {
        semester_id: '',
        tanggal_rapor: '',
        tanggal_rapor_kelas_akhir: '',
        zona: '',
        kepala_sekolah: '',
        rombel_4_tahun: [],
        file: null,
      },
      feedback: {
        tanggal_rapor: '',
        zona: '',
        file: '',
      },
      state: {
        tanggal_rapor: null,
        zona: null,
        file: null,
      },
      periode: null,
      /*
      semester_id: null,
      semester_id: '',
      semester_id: null,
      tanggal_rapor_uts: '',
      tanggal_rapor_uts: '',
      tanggal_rapor_uts: null,
      tanggal_rapor: '',
      tanggal_rapor: '',
      tanggal_rapor: null,
      zona: null,
      zona: '',
      zona: null,
      kepala_sekolah: null,
      kepala_sekolah: '',
      kepala_sekolah: null,
      file: null,
      fileState: null,
      feedback_file: '',
      */
      data_zona: [
        {value: 'Asia/Jakarta', text: 'Waktu Indonesia Barat (WIB)'},
        {value: 'Asia/Makassar', text: 'Waktu Indonesia Tengah (WITA)'},
        {value: 'Asia/Jayapura', text: 'Waktu Indonesia Timur (WIT)'},
      ],
      data_guru: [],
      rombel_4_tahun: [],
      data_rombel: [],
      logo_sekolah: null,
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.isBusy = true
      this.file = null
      this.fileState = null
      this.feedback_file = ''
      this.zona = null
      this.zona = ''
      this.kepala_sekolah = null
      this.kepala_sekolah = ''
      this.$http.post('/setting', {
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.periode = getData.periode
        this.form.semester_id = getData.semester_id
        this.form.tanggal_rapor = getData.tanggal_rapor
        this.form.tanggal_rapor_kelas_akhir = getData.tanggal_rapor_kelas_akhir
        this.form.zona = getData.zona
        this.form.kepala_sekolah = getData.kepala_sekolah
        this.form.rombel_4_tahun = getData.rombel_4_tahun
        this.data_guru = getData.data_guru
        this.data_rombel = getData.data_rombel
        this.semester = getData.semester
        this.logo_sekolah = getData.logo_sekolah
      })
    },
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    handleSubmit(){
      this.loading = true
      const data = new FormData();
      data.append('photo', (this.form.file) ? this.form.file : '');
      data.append('semester_id', this.form.semester_id);
      data.append('sekolah_id', this.user.sekolah_id);
      data.append('semester_aktif', this.user.semester.semester_id);
      //data.append('tanggal_rapor_uts', (this.tanggal_rapor_uts) ? this.tanggal_rapor_uts : '')
      data.append('tanggal_rapor', (this.form.tanggal_rapor) ? this.form.tanggal_rapor : '')
      data.append('tanggal_rapor_kelas_akhir', (this.form.tanggal_rapor_kelas_akhir) ? this.form.tanggal_rapor_kelas_akhir : '')
      data.append('zona', (this.form.zona) ? this.form.zona : '')
      data.append('kepala_sekolah', (this.form.kepala_sekolah) ? this.form.kepala_sekolah : '')
      data.append('rombel_4_tahun', JSON.stringify(this.form.rombel_4_tahun))
      this.$http.post('/setting/update', data).then(response => {
        this.loading = false
        let data = response.data
        this.state.tanggal_rapor = null
        this.state.tanggal_rapor_kelas_akhir = null
        this.state.zona = null
        this.state.file = null
        if(data.errors){
          this.state.tanggal_rapor = (data.errors.tanggal_rapor) ? false : null
          this.state.zona = (data.errors.zona) ? false : null
          this.state.file = (data.errors.photo) ? false : null
          this.feedback.tanggal_rapor = (data.errors.tanggal_rapor) ? data.errors.tanggal_rapor.join(', ') : ''
          this.feedback.zona = (data.errors.zona) ? data.errors.zona.join(', ') : ''
          this.feedback.file = (data.errors.photo) ? data.errors.photo.join(', ') : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            html: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostData()
          })
        }
      }).catch(error => {
        console.log(error);
        this.fileState = false
        this.feedback_file = 'Isian salah. Silahkan periksa kembali!!!'
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>