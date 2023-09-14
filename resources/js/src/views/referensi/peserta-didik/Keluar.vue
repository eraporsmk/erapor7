<template>
  <b-card>
    <datatable :status="status_pd" :loading="loading" :loading_modal="loading_modal" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" :filter="filter" :data_jurusan="data_jurusan" :data_rombel="data_rombel" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @tingkat="handleTingkat" @jurusan="handleJurusan" @rombel="handleRombel" @loadingTable="handleLoading" @loadingModal="handleLoadingModal"  />
  </b-card>
</template>

<script>
import { BCard } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
export default {
  components: {
    BCard,
    Datatable
  },
  data() {
    return {
      status_pd: 'keluar',
      loading: false,
      loading_modal: false,
      isBusy: true,
      fields: [
        {
          key: 'nama',
          label: 'Nama',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'NISN',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'jenis_kelamin',
          label: 'L/P',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'ttl',
          label: 'Tempat, Tanggal Lahir',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'agama',
          label: 'Agama',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'anggota_rombel',
          label: 'Kelas',
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
      sortBy: 'nama',
      sortByDesc: false,
      filter: {
        tingkat: '',
        jurusan_sp_id: '',
        rombongan_belajar_id: '',
      },
      data_jurusan: [],
      data_rombel: [],
    }
  },
  created() {
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/peserta-didik', {
        params: {
          status: this.status_pd,
          user_id: this.user.user_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          tingkat: this.filter.tingkat,
          jurusan_sp_id: this.filter.jurusan_sp_id,
          rombongan_belajar_id: this.filter.rombongan_belajar_id,
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
        this.data_jurusan = response.data.jurusan_sp
        this.data_rombel = response.data.rombel
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
    handleTingkat(val) {
      this.filter.tingkat.val
      this.filter.jurusan_sp_id = ''
      this.filter.rombongan_belajar_id = ''
      this.data_jurusan = []
      this.data_rombel = []
      this.loadPostsData()
    },
    handleJurusan(val) {
      this.filter.jurusan_sp_id = val
      this.filter.rombongan_belajar_id = ''
      this.data_rombel = []
      this.loadPostsData()
    },
    handleRombel(val) {
      this.filter.rombongan_belajar_id = val
      this.loadPostsData()
    },
    handleLoading(val){
      this.loading = val
    },
    handleLoadingModal(val){
      this.loading_modal = val
    }
  },
}
</script>