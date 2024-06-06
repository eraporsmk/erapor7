<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @detil="HandleDetil" />
      </div>
    </b-card-body>
    <b-modal ref="detil-modal" size="xl" scrollable :title="title" ok-only ok-title="Tutup" ok-variant="secondary">
        <detil-modal :isBusy="isBusy" :data_siswa="data_siswa"></detil-modal>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import DetilModal from './modal/DetilNilaiPkl.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
    DetilModal,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      title: '',
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
          key: 'detil_pkl',
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
      pkl_id: '',
      data_siswa: [],
    }
  },
  created() {
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.loading = true
      let current_page = this.current_page
      this.$http.get('/progress/nilai-pkl', {
        params: {
          user_id: this.user.user_id,
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
        this.loading = false
        this.items = getData.data//MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
        //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      }).catch(error => {
        console.log(error)
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
    detil(){
      this.loading = true
      this.$http.post('/progress/detil', {
        aksi: 'pkl',
        pkl_id: this.pkl_id,
      }).then(response => {
        this.loading = false
        let getData = response.data
        this.title = getData.title
        this.data_siswa = getData.data_siswa
        this.$refs['detil-modal'].show()
      }).catch(error => {
        console.log(error)
      })
    },
    HandleDetil(val){
      this.pkl_id = val
      this.detil()
    },
  },
}
</script>