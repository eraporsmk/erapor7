<template>
  <div>
    <b-card>
      <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @nonAktifkan="handleNonAktifkan" @aktifkan="handleAktifkan" />
    </b-card>
    <b-modal ref="add-modal" size="xl" title="Tambah Data Capaian Pembelajaran" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
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
              <v-select id="mata_pelajaran_id" v-model="mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="mata_pelajaran_id_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Elemen" label-for="elemen" label-cols-md="3" :invalid-feedback="elemen_feedback" :state="elemen_state">
              <b-form-input id="elemen" v-model="elemen" placeholder="Elemen" :state="elemen_state"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Deskripsi Capaian Kompetensi" label-for="capaian_pembelajaran" label-cols-md="3" :invalid-feedback="capaian_pembelajaran_feedback" :state="capaian_pembelajaran_state">
              <b-form-textarea id="capaian_pembelajaran" v-model="capaian_pembelajaran" placeholder="Deskripsi Capaian Kompetensi" rows="3" max-rows="6" :state="capaian_pembelajaran_state"></b-form-textarea>
            </b-form-group>
          </b-col>
        </b-row>
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
  </div>
</template>

<script>
import { BCard, BOverlay, BButton, BRow, BCol, BFormGroup, BFormInput, BFormTextarea } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import vSelect from 'vue-select'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
export default {
  components: {
    BCard,
    BOverlay,
    BButton,
    BRow, BCol, BFormGroup, BFormInput,
    BFormGroup, BFormTextarea,
    vSelect,
    Datatable
  },
  data() {
    return {
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
          key: 'fase',
          label: 'Fase',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'elemen',
          label: 'Elemen',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'deskripsi',
          label: 'Deskripsi',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'tp_count',
          label: 'Jml TP',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'status',
          label: 'Status',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
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
      cp_id: '',
      capaian_pembelajaran: '',
      rombongan_belajar_id: '',
      elemen: '',
      mata_pelajaran_id: '',
      tingkat: '',
      tingkat_state: null,
      rombongan_belajar_id_state : null,
      mata_pelajaran_id_state : null,
      elemen_state : null,
      capaian_pembelajaran_state : null,
      tingkat_feedback: '',
      rombongan_belajar_id_feedback: '',
      mata_pelajaran_id_feedback: '',
      elemen_feedback: '',
      capaian_pembelajaran_feedback: '',
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
    }
  },
  created() {
    this.loadPostsData()
    this.tahun_pelajaran = this.user.semester.nama
    eventBus.$on('add-modal', this.handleEvent);
  },
  methods: {
    changeTingkat(val){
      this.loading_modal = true
      this.$http.post('/referensi/get-rombel', {
        add_cp: 1,
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
      this.loading_modal = true
      this.$http.post('/referensi/get-mapel', {
        add_cp: 1,
        rombongan_belajar_id: val,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_mapel = getData.data
      }).catch(error => {
        console.log(error);
      })
    },
    handleEvent(){
      this.$refs['add-modal'].show()
    },
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/referensi/capaian-pembelajaran', {
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
      this.cp_id = ''
      this.capaian_pembelajaran = ''
      //add
      this.rombongan_belajar_id = ''
      this.elemen = ''
      this.mata_pelajaran_id = ''
      this.tingkat = ''
      //validation
      this.tingkat_state = null
      this.rombongan_belajar_id_state  = null
      this.mata_pelajaran_id_state  = null
      this.elemen_state  = null
      this.capaian_pembelajaran_state  = null
      this.tingkat_feedback = ''
      this.rombongan_belajar_id_feedback = ''
      this.mata_pelajaran_id_feedback = ''
      this.elemen_feedback = ''
      this.capaian_pembelajaran_feedback = ''
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_modal = true
      this.$http.post('/referensi/add-cp', {
        tingkat: this.tingkat,
        rombongan_belajar_id: this.rombongan_belajar_id,
        mata_pelajaran_id: this.mata_pelajaran_id,
        mata_pelajaran_id: this.mata_pelajaran_id,
        elemen: this.elemen,
        capaian_pembelajaran: this.capaian_pembelajaran,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        if(getData.errors){
          this.tingkat_state = (getData.errors.tingkat) ? false : null
          this.rombongan_belajar_id_state = (getData.errors.rombongan_belajar_id) ? false : null
          this.mata_pelajaran_id_state = (getData.errors.mata_pelajaran_id) ? false : null
          this.elemen_state = (getData.errors.elemen) ? false : null
          this.capaian_pembelajaran_state = (getData.errors.capaian_pembelajaran) ? false : null
          this.tingkat_feedback = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.rombongan_belajar_id_feedback = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.mata_pelajaran_id_feedback = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
          this.elemen_feedback = (getData.errors.elemen) ? getData.errors.elemen.join(', ') : ''
          this.capaian_pembelajaran_feedback = (getData.errors.capaian_pembelajaran) ? getData.errors.capaian_pembelajaran.join(', ') : ''
        } else {
          this.$refs['add-modal'].hide()
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
    handleNonAktifkan(val) {
      this.cp_id = val
      var text = 'Tindakan ini akan menonaktifkan data Capaian Kompetensi!'
      var aksi = '/referensi/status-cp'
      var params = {
        cp_id: this.cp_id,
        aktif: 0,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleAktifkan(val) {
      this.cp_id = val
      var text = 'Tindakan ini akan mengaktifkan data Capaian Kompetensi!'
      var aksi = '/referensi/status-cp'
      var params = {
        cp_id: this.cp_id,
        aktif: 1,
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
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>