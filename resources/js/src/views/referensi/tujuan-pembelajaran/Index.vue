<template>
  <div>
    <b-card>
      <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handleAksi" />
    </b-card>
    <add-tp @reload="handleReload"></add-tp>
    <edit-tp @reload="handleReload"></edit-tp>
    <tp-mapel @reload="handleReload"></tp-mapel>
  </div>
</template>

<script>
import { BCard, BOverlay, BButton, BRow, BCol, BFormGroup, BFormInput, BFormTextarea, BFormFile } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import Datatable from './Datatable.vue'
import AddTp from './../modal/AddTp.vue'
import EditTp from './../modal/EditTp.vue'
import TpMapel from './../modal/TpMapel.vue'
export default {
  components: {
    AddTp,
    EditTp,
    TpMapel,
    BCard,
    BOverlay,
    BButton,
    BRow, BCol, BFormGroup, BFormInput,
    BFormGroup, BFormTextarea, BFormFile,
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
      sortByDesc: true, //ASCEDING
    }
  },
  created() {
    this.loadPostsData()
    this.tahun_pelajaran = this.user.semester.nama
    eventBus.$on('add-tp', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.show = false
      eventBus.$emit('open-modal-tp');
    },
    handleReload(){
      this.loadPostsData()
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
    handleAksi(val){
      console.log(val);
      if(val.aksi == 'edit'){
        eventBus.$emit('open-modal-edit-tp', val.item);
      }
      if(val.aksi == 'copy'){
        eventBus.$emit('open-modal-tp-mapel', val.item);
      }
      if(val.aksi == 'hapus'){
        var text = 'Tindakan ini tidak dapat dikembalikan!'
        var aksi = '/referensi/hapus-tp'
        var params = {
          tp_id: val.item.tp_id,
        }
        this.swalConfirm(text, aksi, params, 'refresh')
      }
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
        periode_aktif: this.user.semester.nama,
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
        periode_aktif: this.user.semester.nama,
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