<template>
  <div>
    <b-card>
      <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @copy="handleCopy" @edit="handleEdit" @hapus="handleHapus" />
    </b-card>
    <b-modal ref="edit-modal" size="lg" title="Ubah Data Tujuan Pembelajaran (TP)" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-form-group :invalid-feedback="deskripsi_feedback" :state="deskripsi_state">
          <b-form-textarea id="deskripsi" v-model="deskripsi" placeholder="Deskripsi Tujuan Pembelajaran" rows="3" max-rows="6" :state="deskripsi_state"></b-form-textarea>
        </b-form-group>
      </b-overlay>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
          <b-button variant="primary" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
    <b-modal ref="add-modal" size="xl" title="Tambah Data Tujuan Pembelajaran" @hidden="resetModal" hide-footer>
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
              <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="tingkat_feedback" :state="tingkat_state">
              <v-select id="tingkat" v-model="tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="tingkat_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="rombongan_belajar_id_feedback" :state="rombongan_belajar_id_state">
              <v-select id="rombongan_belajar_id" v-model="rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="rombongan_belajar_id_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="mata_pelajaran_id_feedback" :state="mata_pelajaran_id_state">
              <v-select id="mata_pelajaran_id" v-model="mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" @input="changeMapel" :state="mata_pelajaran_id_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_cp">
            <b-form-group label="Capaian Pembelajaran (CP)" label-for="cp_id" label-cols-md="3" :invalid-feedback="cp_id_feedback" :state="cp_id_state">
              <v-select id="cp_id" v-model="cp_id" :reduce="deskripsi => deskripsi.cp_id" label="deskripsi" :options="data_cp" placeholder="== Pilih Capaian Pembelajaran (CP) ==" :state="cp_id_state" @input="changeCp">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_kd">
            <b-form-group label="Kompetensi" label-for="kompetensi_id" label-cols-md="3" :invalid-feedback="kompetensi_id_feedback" :state="kompetensi_id_state">
              <v-select id="kompetensi_id" v-model="kompetensi_id" :reduce="nama => nama.id" label="nama" :options="data_kompetensi" placeholder="== Pilih Kompetensi ==" :searchable="false" :state="kompetensi_id_state" @input="changeKompetensi">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="show_kd">
            <b-form-group label="Kompetensi Dasar (KD)" label-for="kd_id" label-cols-md="3" :invalid-feedback="kd_id_feedback" :state="kd_id_state">
              <v-select id="kd_id" v-model="kd_id" :reduce="kompetensi_dasar => kompetensi_dasar.kompetensi_dasar_id" label="kompetensi_dasar" :options="data_kd" placeholder="== Pilih Kompetensi Dasar (KD) ==" :state="kd_id_state" @input="changeKd">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" class="mb-5" v-show="show">
            <b-row>
              <b-col cols="6">
                <b-form-group :invalid-feedback="template_excel_feedback" :state="template_excel_state">
                  <b-form-file v-model="template_excel" :state="template_excel_state" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
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
    <b-modal ref="tp-mapel" size="xl" title="Petakan TP ke Rombongan Belajar" @show="resetModalTp" @hidden="resetModalTp" @ok="handleTp" ok-title="Simpan" ok-variant="primary">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat_tp" label-cols-md="3" :invalid-feedback="feedback.tingkat_tp" :state="state.tingkat_tp">
              <v-select id="tingkat" v-model="tingkat_tp" :reduce="nama => nama.id" label="nama" :options="data_tingkat_tp" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkatTp" :searchable="false" :state="state.tingkat_tp">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Rombongan Belajar" label-for="rombel_tp" label-cols-md="3" :invalid-feedback="feedback.rombel_tp" :state="state.rombel_tp">
              <b-overlay :show="loading_rombel" rounded opacity="0.6" size="lg" spinner-variant="danger">
                <v-select id="rombel_tp" v-model="rombel_tp" multiple :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel_tp" placeholder="== Pilih Rombongan Belajar ==" :state="state.rombel_tp">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
        </b-row>
      </b-overlay>
    </b-modal>
  </div>
</template>

<script>
import { BCard, BOverlay, BButton, BRow, BCol, BFormGroup, BFormInput, BFormTextarea, BFormFile } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import vSelect from 'vue-select'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
export default {
  components: {
    BCard,
    BOverlay,
    BButton,
    BRow, BCol, BFormGroup, BFormInput,
    BFormGroup, BFormTextarea, BFormFile,
    vSelect,
    Datatable
  },
  data() {
    return {
      loading_rombel: false,
      loading: false,
      loading_modal: false,
      isBusy: true,
      fields: [
        {
          key: 'mata_pelajaran_id',
          label: 'Mata Pelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'rombel',
          label: 'Rombel',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'kd_cp',
          label: 'Capaian Pembelajaran/Kompetensi Dasar',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'kelas',
          label: 'Fase/Tingkat',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'deskripsi',
          label: 'Tujuan Pembelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'actions',
          label: 'Aksi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
      search: '',
      sortBy: 'updated_at', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false, //ASCEDING
      tahun_pelajaran: '',
      tp_id: '',
      deskripsi: '',
      deskripsi_state: null,
      deskripsi_feedback: '',
      tingkat: '',
      tingkat_state: null,
      tingkat_feedback: '',
      rombongan_belajar_id: '',
      rombongan_belajar_id_state : null,
      rombongan_belajar_id_feedback: '',
      mata_pelajaran_id: '',
      mata_pelajaran_id_state : null,
      mata_pelajaran_id_feedback: '',
      show_cp: false,
      cp_id: '',
      cp_id_state: null,
      cp_id_feedback: '',
      data_cp: [],
      show_kd: false,
      kompetensi_id: '',
      kompetensi_id_state : null,
      kompetensi_id_feedback: '',
      kd_id: '',
      kd_id_state : null,
      kd_id_feedback: '',
      data_kd: [],
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
      show: false,
      merdeka: false,
      template_excel: null,
      template_excel_feedback: '',
      template_excel_state: null,
      link_template_tp: '',
      pembelajaran_id: '',
      data_tingkat_tp: [],
      data_rombel_tp: [],
      tingkat_tp: '',
      rombel_tp: [],
      feedback: {
        tingkat_tp: '',
        rombel_tp: '',
      },
      state: {
        tingkat_tp: null,
        rombel_tp: null,
      }
    }
  },
  created() {
    this.loadPostsData()
    this.tahun_pelajaran = this.user.semester.nama
    eventBus.$on('add-tp', this.handleEvent);
  },
  methods: {
    changeTingkat(val){
      this.show = false
      this.rombongan_belajar_id = ''
      this.mata_pelajaran_id = ''
      this.show_kd = false
      this.show_cp = false
      this.loading_modal = true
      this.$http.post('/referensi/get-rombel', {
        tingkat: val,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_rombel = getData.data
      }).catch(error => {
        console.log(error);
      })
    },
    changeRombel(val){
      this.show = false
      this.mata_pelajaran_id = ''
      this.loading_modal = true
      this.$http.post('/referensi/get-mapel', {
        rombongan_belajar_id: val,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_mapel = getData.data
        this.merdeka = getData.merdeka
      }).catch(error => {
        console.log(error);
      })
    },
    changeMapel(val){
      this.show = false
      this.loading_modal = true
      this.$http.post('/referensi/get-cp-tp', {
        merdeka: this.merdeka,
        mata_pelajaran_id: val,
        rombongan_belajar_id: this.rombongan_belajar_id,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_cp = getData.data_cp
        this.data_kd = getData.data_kd
        this.show_kd = getData.show_kd
        this.show_cp = getData.show_cp
        this.pembelajaran_id = getData.pembelajaran_id
      }).catch(error => {
        console.log(error);
      })
    },
    changeKompetensi(val){
      this.show = false
      this.loading_modal = true
      this.$http.post('/referensi/get-cp-tp', {
        merdeka: this.merdeka,
        tingkat: this.tingkat,
        kompetensi_id: val,
        mata_pelajaran_id: this.mata_pelajaran_id,
        rombongan_belajar_id: this.rombongan_belajar_id,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_cp = getData.data_cp
        this.data_kd = getData.data_kd
        this.show_kd = getData.show_kd
        this.show_cp = getData.show_cp
        this.pembelajaran_id = getData.pembelajaran_id
      }).catch(error => {
        console.log(error);
      })
    },
    changeCp(){
      this.show = true
      this.link_template_tp = '/downloads/template-tp/'+this.cp_id+'/'+this.rombongan_belajar_id+'/'+this.pembelajaran_id
    },
    changeKd(){
      this.show = true
      this.link_template_tp = '/downloads/template-tp/'+this.kd_id+'/'+this.rombongan_belajar_id+'/'+this.pembelajaran_id
    },
    onFileChange(e) {
      this.loading_modal = true
      this.template_excel = e.target.files[0];
      const data = new FormData();
      data.append('template_excel', (this.template_excel) ? this.template_excel : '');
      data.append('mata_pelajaran_id', (this.mata_pelajaran_id) ? this.mata_pelajaran_id : '')
      data.append('pembelajaran_id', (this.pembelajaran_id) ? this.pembelajaran_id : '')
      data.append('cp_id', (this.cp_id) ? this.cp_id : '')
      data.append('kd_id', (this.kd_id) ? this.kd_id : '')
      this.$http.post('/referensi/upload-tp', data).then(response => {
        this.loading_modal = false
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
            this.$refs['add-modal'].hide()
            this.loadPostsData()
          })
        }
      });
    },
    handleEvent(){
      this.show = false
      this.$refs['add-modal'].show()
    },
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/referensi/tujuan-pembelajaran', {
        params: {
          user_id: this.user.user_id,
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.isBusy = false
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    handlePerPage(val) {
      this.per_page = val 
      this.loadPostsData() 
    },
    handlePagination(val) {
      this.current_page = val 
      this.loadPostsData()
    },
    handleSearch(val) {
      this.search = val
      this.loadPostsData()
    },
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData()
      }
    },
    resetModal(){
      this.tp_id = ''
      this.tingkat = ''
      this.tingkat_state = null
      this.tingkat_feedback = ''
      this.rombongan_belajar_id = ''
      this.rombongan_belajar_id_state  = null
      this.rombongan_belajar_id_feedback = ''
      this.mata_pelajaran_id = ''
      this.mata_pelajaran_id_state  = null
      this.mata_pelajaran_id_feedback = ''
      this.show_cp = false
      this.cp_id = ''
      this.cp_id_state = null
      this.cp_id_feedback = ''
      this.data_cp = []
      this.show_kd = false
      this.kompetensi_id = ''
      this.kompetensi_id_state  = null
      this.kompetensi_id_feedback = ''
      this.kd_id = ''
      this.kd_id_state  = null
      this.kd_id_feedback = ''
      this.data_kd = []
    },
    resetModalTp(){
      this.tingkat_tp = ''
      this.rombel_tp = []
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      var aksi = '/referensi/update-tp'
      var params = {
        tp_id: this.tp_id,
        deskripsi: this.deskripsi,
      }
      this.submitted(aksi, params, 'edit-modal', 'refresh')
    },
    submitted(aksi, params, ref_modal, after){
      this.loading_modal = true
      this.$http.post(aksi, params).then(response => {
        this.loading_modal = false
        let getData = response.data
        if(getData.errors){
          this.deskripsi_state = (getData.errors.deskripsi) ? false : null
          this.deskripsi_feedback = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
        } else {
          this.$refs[ref_modal].hide()
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostsData()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    handleEdit(val) {
      this.tp_id = val.tp_id
      this.deskripsi = val.deskripsi
      this.$refs['edit-modal'].show()
    },
    handleHapus(val) {
      this.tp_id = val
      var text = 'Tindakan ini tidak dapat dikembalikan!'
      var aksi = '/referensi/hapus-tp'
      var params = {
        tp_id: this.tp_id,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    swalConfirm(text, aksi, params, after){
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
          this.loading_modal = true
          this.$http.post(aksi, params).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              if(after == 'refresh'){
                this.loadPostsData()
              }
            })
          });
        }
      })
    },
    handleCopy(val){
      this.tp_id = val.tp_id
      this.loading = true
      this.$http.post('/referensi/rombel-tp', {
        tp_id: val.tp_id,
      }).then(response => {
        this.loading = false
        let getData = response.data
        this.data_tingkat_tp = getData.tingkat
        this.$refs['tp-mapel'].show()
        console.log(getData);
      });
    },
    changeTingkatTp(val){
      this.rombel_tp = []
      this.loading_rombel = true
      this.$http.post('/referensi/get-rombel', {
        tingkat: val,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_rombel = false
        let getData = response.data
        this.data_rombel_tp = getData.data
      }).catch(error => {
        console.log(error);
      })
    },
    handleTp(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmitTp()
    },
    handleSubmitTp(){
      this.loading_modal = true
      this.$http.post('referensi/add-rombel-tp', {
        tp_id: this.tp_id,
        tingkat: this.tingkat_tp,
        rombel_tp: this.rombel_tp,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        if(getData.errors){
          this.state.tingkat_tp = (getData.errors.tingkat) ? false : null
          this.feedback.tingkat_tp = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.state.rombel_tp = (getData.errors.rombel_tp) ? false : null
          this.feedback.rombel_tp = (getData.errors.rombel_tp) ? getData.errors.rombel_tp.join(', ') : ''
        } else {
          this.$refs['tp-mapel'].hide()
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostsData()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>