<template>
  <!-- <b-modal ref="add-modal" size="xl" title="Tambah Data Tujuan Pembelajaran" @hidden="resetModal" hide-footer>
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
              <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="tingkat" :state="tingkat">
              <v-select id="tingkat" v-model="tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="tingkat">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="rombongan_belajar_id" :state="rombongan_belajar_id">
              <b-overlay :show="loading_rombel" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="rombongan_belajar_id" v-model="rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="rombongan_belajar_id">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="mata_pelajaran_id" :state="mata_pelajaran_id">
              <b-overlay :show="loading_mapel" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="mata_pelajaran_id" v-model="mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" @input="changeMapel" :state="mata_pelajaran_id">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_cp">
            <b-form-group label="Capaian Pembelajaran (CP)" label-for="cp_id" label-cols-md="3" :invalid-feedback="cp_id" :state="cp_id">
              <b-overlay :show="loading_cp" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="cp_id" v-model="cp_id" :reduce="deskripsi => deskripsi.cp_id" label="deskripsi" :options="data_cp" placeholder="== Pilih Capaian Pembelajaran (CP) ==" :state="cp_id" @input="changeCp">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_kd">
            <b-form-group label="Kompetensi" label-for="kompetensi_id" label-cols-md="3" :invalid-feedback="kompetensi_id" :state="kompetensi_id">
              <b-overlay :show="loading_kompetensi" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="kompetensi_id" v-model="kompetensi_id" :reduce="nama => nama.id" label="nama" :options="data_kompetensi" placeholder="== Pilih Kompetensi ==" :searchable="false" :state="kompetensi_id" @input="changeKompetensi">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_kd">
            <b-form-group label="Kompetensi Dasar (KD)" label-for="kd_id" label-cols-md="3" :invalid-feedback="kd_id" :state="kd_id">
              <b-overlay :show="loading_kd" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="kd_id" v-model="kd_id" :reduce="kompetensi_dasar => kompetensi_dasar.kompetensi_dasar_id" label="kompetensi_dasar" :options="data_kd" placeholder="== Pilih Kompetensi Dasar (KD) ==" :state="kd_id" @input="changeKd">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" class="mb-5" v-show="show">
            <b-row>
              <b-col cols="6">
                <b-form-group :invalid-feedback="template_excel" :state="template_excel">
                  <b-form-file v-model="template_excel" :state="template_excel" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-button block variant="primary" :href="link_template_tp" target="_blank">Unduh Template TP</b-button>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
      </b-overlay>
    </b-modal>
  -->
  <b-modal v-model="addTp" size="xl" title="Tambah Data Tujuan Pembelajaran" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
            <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
            <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="state.tingkat">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
            <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="state.rombongan_belajar_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
            <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id" @input="changeMapel">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12" v-show="show_cp">
            <b-form-group label="Capaian Pembelajaran (CP)" label-for="cp_id" label-cols-md="3" :invalid-feedback="feedback.cp_id" :state="state.cp_id">
              <b-overlay :show="loading_cp" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="cp_id" v-model="form.cp_id" :reduce="deskripsi => deskripsi.cp_id" label="deskripsi" :options="data_cp" placeholder="== Pilih Capaian Pembelajaran (CP) ==" :state="state.cp_id" @input="changeCp">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_kd">
            <b-form-group label="Kompetensi" label-for="kompetensi_id" label-cols-md="3" :invalid-feedback="feedback.kompetensi_id" :state="state.kompetensi_id">
              <b-overlay :show="loading_kompetensi" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="kompetensi_id" v-model="form.kompetensi_id" :reduce="nama => nama.id" label="nama" :options="data_kompetensi" placeholder="== Pilih Kompetensi ==" :searchable="false" :state="state.kompetensi_id" @input="changeKompetensi">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_kd">
            <b-form-group label="Kompetensi Dasar (KD)" label-for="kd_id" label-cols-md="3" :invalid-feedback="feedback.kd_id" :state="state.kd_id">
              <b-overlay :show="loading_kd" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="kd_id" v-model="form.kd_id" :reduce="kompetensi_dasar => kompetensi_dasar.kompetensi_dasar_id" label="kompetensi_dasar" :options="data_kd" placeholder="== Pilih Kompetensi Dasar (KD) ==" :state="state.kd_id" @input="changeKd">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" class="mb-5" v-show="show">
            <b-row>
              <b-col cols="6">
                <b-form-group :invalid-feedback="feedback.template_excel" :state="state.template_excel">
                  <b-form-file v-model="template_excel" :state="state.template_excel" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-button block variant="primary" :href="link_template_tp" target="_blank">Unduh Template TP</b-button>
              </b-col>
            </b-row>
          </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
  <!--b-modal v-model="addTp" size="xl" title="Tambah Data Kompetensi Dasar" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
            <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
            <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="state.tingkat">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
            <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="state.rombongan_belajar_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
            <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Aspek Penilaian" label-for="kompetensi_id" label-cols-md="3" :invalid-feedback="feedback.kompetensi_id" :state="state.kompetensi_id">
            <b-overlay :show="loading_kompetensi" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="kompetensi_id" v-model="form.kompetensi_id" :reduce="nama => nama.id" label="nama" :options="data_kompetensi" placeholder="== Pilih Aspek Penilaian ==" :searchable="false" :state="state.kompetensi_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kode Kompetensi Dasar" label-for="id_kompetensi" label-cols-md="3" :invalid-feedback="feedback.id_kompetensi" :state="state.id_kompetensi">
            <b-form-input id="id_kompetensi" v-model="form.id_kompetensi" placeholder="3.x untuk pengetahuan, 4.x untuk keterampilan" :state="state.id_kompetensi"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Deskripsi Kompetensi Dasar" label-for="kompetensi_dasar" label-cols-md="3" :invalid-feedback="feedback.kompetensi_dasar" :state="state.kompetensi_dasar">
            <b-form-textarea id="kompetensi_dasar" v-model="form.kompetensi_dasar" placeholder="Deskripsi Kompetensi Dasar" rows="3" max-rows="6" :state="state.kompetensi_dasar"></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal-->
</template>

<script>
import { BOverlay, BFormInput, BRow, BCol, BFormGroup, BButton, BFormTextarea, BFormFile} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay, 
    BFormInput, 
    BRow, 
    BCol, 
    BFormGroup, 
    BButton, 
    BFormTextarea,
    BFormFile,
    vSelect
  },
  data() {
    return {
      addTp: false,
      loading_form: false,
      loading_rombel: false,
      loading_mapel: false,
      loading_cp: false,
      loading_kompetensi: false,
      loading_kd: false,
      show: false,
      show_kd: false,
      show_cp: false,
      template_excel: null,
      link_template_tp: '',
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
        mata_pelajaran_id: '',
        pembelajaran_id: '',
        cp_id: '',
        kompetensi_id: '',
        kd_id: '',
        merdeka: false,
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        mata_pelajaran_id: null,
        cp_id: null,
        kompetensi_id: null,
        kd_id: null,
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        mata_pelajaran_id: '',
        cp_id: '',
        kompetensi_id: '',
        kd_id: '',
      },
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
        },
      ],
      data_rombel: [],
      data_mapel: [],
      data_cp: [],
      data_kd: [],
      data_kompetensi: [
        {
          id: 1,
          nama: 'Pengetahuan',
        },
        {
          id: 2,
          nama: 'Keterampilan',
        },
      ],
    }
  },
  created() {
    this.tahun_pelajaran = this.user.semester.nama
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.tahun_pelajaran
    eventBus.$on('open-modal-tp', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.addTp = true
    },
    hideModal(){
      this.addTp = false
      this.resetModal()
    },
    resetModal(){
      this.show = false
      this.show_kd = false
      this.show_cp = false
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.cp_id = ''
      this.form.kompetensi_id = ''
      this.form.kd_id = ''
      this.state.tingkat = null
      this.state.rombongan_belajar_id = null
      this.state.mata_pelajaran_id = null
      this.state.cp_id = null
      this.state.kompetensi_id = null
      this.state.kd_id = null
      this.feedback.tingkat = ''
      this.feedback.rombongan_belajar_id = ''
      this.feedback.mata_pelajaran_id = ''
      this.feedback.cp_id = ''
      this.feedback.kompetensi_id = ''
      this.feedback.kd_id = null
    },
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.cp_id = ''
      this.form.kompetensi_id = ''
      this.form.kd_id = ''
      this.show = false
      this.show_kd = false
      this.show_cp = false
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.show = false
      this.form.mata_pelajaran_id = ''
      this.form.cp_id = ''
      this.form.kompetensi_id = ''
      this.form.kd_id = ''
      if(val){
        this.loading_mapel = true
        this.$http.post('/referensi/get-mapel', this.form).then(response => {
          this.loading_mapel = false
          let getData = response.data
          this.data_mapel = getData.data
          this.form.merdeka = getData.merdeka
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeMapel(val){
      this.show = false
      this.form.cp_id = ''
      this.form.kompetensi_id = ''
      this.form.kd_id = ''
      if(val){
        this.loading_mapel = true
        this.$http.post('/referensi/get-cp-tp', this.form).then(response => {
          this.loading_mapel = false
          let getData = response.data
          this.data_cp = getData.data_cp
          this.data_kd = getData.data_kd
          this.show_kd = getData.show_kd
          this.show_cp = getData.show_cp
          this.form.pembelajaran_id = getData.pembelajaran_id
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeKompetensi(val){
      this.show = false
      if(val){
        this.loading_cp = true
        this.$http.post('/referensi/get-cp-tp', this.form).then(response => {
          this.loading_cp = false
          let getData = response.data
          this.data_cp = getData.data_cp
          this.data_kd = getData.data_kd
          this.show_kd = getData.show_kd
          this.show_cp = getData.show_cp
          this.form.pembelajaran_id = getData.pembelajaran_id
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeCp(){
      this.show = true
      this.link_template_tp = '/downloads/template-tp/'+this.form.cp_id+'/'+this.form.rombongan_belajar_id+'/'+this.form.pembelajaran_id
    },
    changeKd(){
      this.show = true
      this.link_template_tp = '/downloads/template-tp/'+this.form.kd_id+'/'+this.form.rombongan_belajar_id+'/'+this.form.pembelajaran_id
    },
    onFileChange(e) {
      this.loading_form = true
      this.template_excel = e.target.files[0];
      const data = new FormData();
      data.append('template_excel', (this.template_excel) ? this.template_excel : '');
      data.append('mata_pelajaran_id', (this.form.mata_pelajaran_id) ? this.form.mata_pelajaran_id : '')
      data.append('pembelajaran_id', (this.form.pembelajaran_id) ? this.form.pembelajaran_id : '')
      data.append('cp_id', (this.form.cp_id) ? this.form.cp_id : '')
      data.append('kd_id', (this.form.kd_id) ? this.form.kd_id : '')
      this.$http.post('/referensi/upload-tp', data).then(response => {
        this.loading_form = false
        let data = response.data
        if(data.errors){
          this.template_excel_state = (data.errors.template_excel) ? false : null
          this.template_excel_feedback = (data.errors.template_excel) ? data.errors.template_excel.join(', ') : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            html: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      });
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_form = true
      this.$http.post('/referensi/add-cp', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.state.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? false : null
          this.state.cp_id = (getData.errors.cp_id) ? false : null
          this.state.kompetensi_id = (getData.errors.kompetensi_id) ? false : null
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.feedback.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
          this.feedback.cp_id = (getData.errors.cp_id) ? getData.errors.cp_id.join(', ') : ''
          this.feedback.kompetensi_id = (getData.errors.kompetensi_id) ? getData.errors.kompetensi_id.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>