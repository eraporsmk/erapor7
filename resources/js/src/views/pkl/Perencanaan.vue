<template>
  <b-card>
    <datatable :isBusy="isBusy" :loading="loading" :fields="fields" :items="items" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handleAksi" />
    <add-new @reload="handleReload"></add-new>
    <edit-data @reload="handleReload"></edit-data>
    <detil-data></detil-data>
  </b-card>
</template>

<script>
import { BCard } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import eventBus from '@core/utils/eventBus'
import AddNew from './modal/AddNew.vue'
import EditData from './modal/EditData.vue'
import DetilData from './modal/DetilData.vue'
export default {
  components: {
    BCard,
    Datatable,
    AddNew,
    EditData,
    DetilData,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      fields: [
        {
          key: 'kelas',
          label: 'Kelas',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'dudi',
          label: 'DUDI',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'pks',
          label: 'PKS',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'tanggal_mulai_str',
          label: 'Tanggal Mulai',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'tanggal_selesai_str',
          label: 'Tanggal Selesai',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'pd_pkl_count',
          label: 'JML Peserta',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'actions',
          label: 'Aksi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: true,
    }
  },
  created() {
    eventBus.$on('rencana-pkl', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-rencana-pkl');
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/praktik-kerja-lapangan', {
        params: {
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
          role_id: this.role_id,
          roles: response.data.roles,
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
      if(val.aksi === 'detil'){
        this.$http.post('/praktik-kerja-lapangan/detil', {
          id: val.id,
        }).then(response => {
          eventBus.$emit('open-detil-pkl', response.data);
        })
      }
      if(val.aksi === 'edit'){
        this.$http.post('/praktik-kerja-lapangan/detil', {
          id: val.id,
        }).then(response => {
          eventBus.$emit('open-edit-pkl', response.data);
        })
      }
      if(val.aksi === 'hapus'){
        this.swalConfirm('Apakah Anda Yakin? Tindakan ini tidak dapat dikembalikan!', '/praktik-kerja-lapangan/hapus', {id: val.id}, 'refresh')
      }
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
        allowOutsideClick: () => !this.$swal.isLoading(),
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
    keluarkan(id){
      var text = 'Tindakan ini tidak dapat dikembalikan!'
      var aksi = '/rombongan-belajar/keluarkan'
      var params = {anggota_rombel_id: id}
      this.swalConfirm(text, aksi, params, 'keluar')
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>