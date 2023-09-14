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
    <b-modal ref="detil-p5" size="xl" scrollable title="Detil Projek P5" ok-only ok-title="Tutup" ok-variant="secondary">
      <detil-p5 :data_projek="data_projek" @detil_projek="HandleDetilProjek"></detil-p5>
    </b-modal>
    <b-modal ref="detil-projek" size="xl" scrollable :title="title" ok-only ok-title="Tutup" ok-variant="secondary">
      <detil-projek :data_rencana="data_rencana" :data_siswa="data_siswa"></detil-projek>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import DetilP5 from './modal/DetilP5.vue'
import DetilProjek from './modal/DetilProjek.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
    DetilP5,
    DetilProjek,
  },
  data() {
    return {
      title: '',
      data_rencana: null,
      data_siswa: [],
      opsi_budaya_kerja: [],
      data_projek: [],
      isBusy: true,
      fields: [
        {
          key: 'rombel_p5',
          label: 'Rombel',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'koordinator',
          label: 'Koord Projek',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'tema_count',
          label: 'Jumlah Tema',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'rencana_projek_count',
          label: 'Jumlah Projek',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'aspek_projek_count',
          label: 'Jumlah Sub Elemen',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'detil_p5',
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
      sortBy: 'tingkat',
      sortByDesc: false,
    }
  },
  created() {
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/progress/nilai-projek', {
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
    HandleDetil(val){
      //this.data_projek = val
      //console.log(val);
      //this.$emit('detil', val)
      this.$http.post('/progress/detil', {
        aksi: 'projek',
        pembelajaran_id: val,
      }).then(response => {
        this.data_projek = response.data.tema
        this.$refs['detil-p5'].show()
      }).catch(error => {
        console.log(error)
      })
    },
    detil(item){
      this.$http.post('/progress/detil', {
        aksi: 'rencana',
        rombongan_belajar_id: item.rombongan_belajar_id,
        rencana_budaya_kerja_id: item.projek.rencana_budaya_kerja_id,
      }).then(response => {
        let getData = response.data
        this.data_rencana = getData.rencana
        this.data_siswa = getData.data_siswa
        this.opsi_budaya_kerja = getData.opsi_budaya_kerja
        this.title = `Detil Penilaian Projek ${this.data_rencana.nama}`
        this.$refs['detil-projek'].show()
      }).catch(error => {
        console.log(error)
      })
    },
    HandleDetilProjek(item){
      this.detil(item)
    }
  },
}
</script>