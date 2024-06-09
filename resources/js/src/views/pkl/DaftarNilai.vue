<template>
  <b-card>
    <datatable :isBusy="isBusy" :loading="loading" :fields="fields" :items="items" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handleAksi" @rombel="handleRombel" />
  </b-card>
</template>

<script>
import { BCard } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
export default {
  components: {
    BCard,
    Datatable,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      fields: [
        {
          key: 'nama',
          label: 'Nama',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'nisn',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'rombel',
          label: 'kelas',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'cetak',
          label: 'Cetak',
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
      sortBy: 'nama',
      sortByDesc: true,
      rombongan_belajar_id: '',
    }
  },
  created() {
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/praktik-kerja-lapangan/rapor', {
        params: {
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
          rombongan_belajar_id: this.rombongan_belajar_id,
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
          data_rombel: response.data.data_rombel
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
    handleRombel(val){
      this.rombongan_belajar_id = val
      this.loadPostsData()
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>