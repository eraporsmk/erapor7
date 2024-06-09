<template>
  <div>
    <b-card>
      <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @edit="handleEdit" @hapus="handleHapus" @nonAktifkan="handleNonAktifkan" @aktifkan="handleAktifkan" @hapusGanda="handleHapusGanda" @tingkat="handleTingkat" @rombel="handleRombel" @mapel="handleMapel"/>
    </b-card>
    <edit-kd @reload="handleReload"></edit-kd>
    <add-kd @reload="handleReload"></add-kd>
  </div>
</template>

<script>
import { BCard, BOverlay, BButton, BRow, BCol, BFormGroup, BFormInput } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import AddKd from './../modal/AddKd.vue'
import EditKd from './../modal/EditKd.vue'
export default {
  components: {
    BCard,
    AddKd,
    EditKd,
    BOverlay,
    BButton,
    BRow, BCol, BFormGroup, BFormInput,
    BFormGroup,
    Datatable
  },
  data() {
    return {
      loading: false,
      isBusy: true,
      fields: [
        {
          key: 'mata_pelajaran_id',
          label: 'Mata Pelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'id_kompetensi',
          label: 'Kode',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'kelas',
          label: 'Kelas',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'kompetensi_dasar',
          label: 'Deskripsi Kompetensi Dasar',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'kurikulum',
          label: 'Kurikulum',
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
      sortByDesc: true, //ASCEDING
      tingkat: '',
      rombongan_belajar_id: '',
      pembelajaran_id: '',
    }
  },
  created() {
    this.loadPostsData()
    eventBus.$on('add-kd', this.handleEvent);
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-modal-kd');
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPostsData() {
      this.loading = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/referensi/kompetensi-dasar', {
        params: {
          user_id: this.user.user_id,
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          tingkat: this.tingkat,
          rombongan_belajar_id: this.rombongan_belajar_id,
          pembelajaran_id: this.pembelajaran_id,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.isBusy = false
        this.loading = false
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
          user_id: this.user.user_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          guru_id: this.user.guru_id,
          tingkat: this.tingkat,
          rombongan_belajar_id: this.rombongan_belajar_id,
          pembelajaran_id: this.pembelajaran_id,
          add_kd: true,
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
    handleEdit(val) {
      eventBus.$emit('open-modal-edit-kd', val);
    },
    handleHapus(val) {
      this.kompetensi_dasar_id = val
      var text = 'Tindakan ini akan mengembalikan isi ringkasan ke bawaan aplikasi!'
      var aksi = '/referensi/update-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        kompetensi_dasar_alias: null,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleNonAktifkan(val) {
      this.kompetensi_dasar_id = val
      var text = 'Tindakan ini akan menonaktifkan data Kompetensi Dasar!'
      var aksi = '/referensi/status-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        aktif: 0,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleAktifkan(val) {
      this.kompetensi_dasar_id = val
      var text = 'Tindakan ini akan mengaktifkan data Kompetensi Dasar!'
      var aksi = '/referensi/status-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        aktif: 1,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleHapusGanda(params) {
      var text = 'Tindakan ini akan menghapus data ganda Kompetensi Dasar!'
      var aksi = '/referensi/hapus-kd-ganda'
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
    handleTingkat(val){
      this.tingkat = val
      this.rombongan_belajar_id = ''
      this.pembelajaran_id = ''
      this.loadPostsData()
    },
    handleRombel(val){
      this.rombongan_belajar_id = val
      this.pembelajaran_id = ''
      this.loadPostsData()
    },
    handleMapel(val){
      this.pembelajaran_id = val
      this.loadPostsData()
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>