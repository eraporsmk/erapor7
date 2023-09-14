<template>
  <b-card no-body :title="`Progres Penilaian Tahun Pelajaran ${periode_aktif}`">
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
        <detil-modal :data_siswa="data_siswa"></detil-modal>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import DetilModal from './modal/DetilModal.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
    DetilModal
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      periode_aktif: '',
      fields: [
        {
          key: 'rombel',
          label: 'Rombel',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nama_mata_pelajaran',
          label: 'Mata Pelajaran',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'guru',
          label: 'Guru Mata Pelajaran',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'pd',
          label: 'Jumlah Peserta Didik',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'pd_dinilai',
          label: 'Jml Peserta Didik Dinilai',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'detil',
          label: 'Detil',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      current_page: 1, 
      per_page: 10,
      search: '',
      sortBy: 'mata_pelajaran_id',
      sortByDesc: false,
      data_siswa: [],
      merdeka: false,
      title: '',
    }
  },
  created() {
    this.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/progress/penilaian', {
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
    detil(pembelajaran_id){
      this.loading = true
      this.$http.post('/progress/detil', {
        aksi: 'pembelajaran',
        pembelajaran_id: pembelajaran_id,
      }).then(response => {
        this.loading = false
        let getData = response.data
        this.title = getData.title
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
        this.$refs['detil-modal'].show()
        console.log(getData);
      }).catch(error => {
        console.log(error)
      })
    },
    HandleDetil(pembelajaran_id){
      console.log('HandleDetil');
      console.log(pembelajaran_id);
      this.detil(pembelajaran_id)
    },
  },
}
</script>