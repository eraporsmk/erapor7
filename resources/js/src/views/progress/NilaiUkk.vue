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
    <detil-modal></detil-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import DetilModal from './../penilaian/ukk/modal/DetilRencana.vue'
import eventBus from '@core/utils/eventBus';
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
      periode_aktif: '',
      fields: [
        {
          key: 'jurusan',
          label: 'Program/Kompetensi Keahlian',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'kode',
          label: 'Kode Paket',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'nama',
          label: 'Nama Paket',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'pd_count',
          label: 'Jumlah PD',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'detil_ukk',
          label: 'Detil',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      meta_nilai: {},
      current_page: 1,
      current_page_nilai: 1, 
      per_page: 10,
      per_page_nilai: 25,
      search: '',
      search_nilai: '',
      sortBy: 'created_at',
      sortBy_nilai: 'nama',
      sortByDesc: false,
      sortByDesc_nilai: false,
      data_siswa: [],
      title: '',
      rombongan_belajar_id: '',
      ekstrakurikuler_id: '',
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
      this.$http.get('/progress/nilai-ukk', {
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
    handlePerPageNilai(val) {
      this.per_page_nilai = val 
      this.detil()
    },
    handlePaginationNilai(val) {
      this.current_page_nilai = val 
      this.detil()
    },
    handleSearchNilai(val) {
      this.search_nilai = val
      this.detil()
    },
    handleSortNilai(val) {
      if (val.sortBy) {
        this.sortBy_nilai = val.sortBy
        this.sortByDesc_nilai = val.sortDesc
        this.detil()
      }
    },
    detil(){
      this.loading = true
      let current_page = this.current_page_nilai
      this.$http.post('/progress/detil', {
        aksi: 'ekskul',
        rombongan_belajar_id: this.rombongan_belajar_id,
        ekstrakurikuler_id: this.ekstrakurikuler_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        page: current_page,
        per_page: this.per_page_nilai,
        q: this.search_nilai,
        sortby: this.sortBy_nilai,
        sortbydesc: this.sortByDesc_nilai ? 'DESC' : 'ASC'
      }).then(response => {
        this.loading = false
        let getData = response.data
        this.title = getData.title
        this.data_siswa = getData.data_siswa.data
        this.meta_nilai = {
          total: getData.data_siswa.total,
          current_page: getData.data_siswa.current_page,
          per_page: getData.data_siswa.per_page,
          from: getData.data_siswa.from,
          to: getData.data_siswa.to,
        }
        
      }).catch(error => {
        console.log(error)
      })
    },
    HandleDetil(val){
      this.$http.post('/ukk/detil-rencana', {
        rencana_ukk_id: val,
      }).then(response => {
        eventBus.$emit('open-modal-detil-rencana-ukk', response.data);
      }).catch(error => {
        console.log(error);
      })
    },
  },
}
</script>