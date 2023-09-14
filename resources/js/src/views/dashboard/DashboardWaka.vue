<template>
  <div>
    <b-card no-body v-if="isBusy">
      <b-card-body>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </b-card-body>
    </b-card>
    <b-card no-body v-else>
      <b-card-body>
        <h2>Progres Penilaian Tahun Pelajaran {{periode_aktif}}</h2>
        <datatable :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @detil="HandleDetil" />
      </b-card-body>
    </b-card>
    <b-card no-body v-if="isBusy">
      <b-card-body>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </b-card-body>
    </b-card>
    <b-card no-body v-else>
      <b-card-body>
        <h2>Progres Penilaian Projek Pancasila</h2>
        <datatable :isBusy="p5_isBusy" :items="p5_items" :fields="p5_fields" :meta="p5_meta" @per_page="handlePerPageProjek" @pagination="handlePaginationProjek" @search="handleSearchProjek" @sort="handleSortProjek" @detil="HandleDetilProjek" />
      </b-card-body>
    </b-card>
    <b-modal ref="detil-p5" size="xl" scrollable title="Detil Projek P5" ok-only ok-title="Tutup" ok-variant="secondary">
      <b-table-simple bordered responsive>
        <b-thead>
          <b-tr>
            <b-th class="text-center">No</b-th>
            <b-th class="text-center">Tema</b-th>
            <b-th class="text-center">Projek</b-th>
            <b-th class="text-center">Detil</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-if="data_projek.length">
            <template v-for="(item, index) in data_projek">
              <b-tr>
                <b-td class="text-center">{{index + 1}}</b-td>
                <b-td>{{item.nama_mata_pelajaran}}</b-td>
                <b-td>{{(item.projek) ? item.projek.nama : '-'}}</b-td>
                <b-td class="text-center">
                  <b-button variant="success" size="sm" @click="detilProjek(item)" v-if="item.projek">Detil</b-button>
                </b-td>
              </b-tr>
            </template>
          </template>
        </b-tbody>
      </b-table-simple>
    </b-modal>
    <b-modal ref="detil-projek" size="xl" scrollable :title="title_projek" ok-only ok-title="Tutup" ok-variant="secondary">
      <b-table-simple bordered responsive v-if="data_rencana">
        <b-tr>
          <b-td>Nama Projek</b-td>
          <b-td>{{data_rencana.nama}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Deskripsi Projek</b-td>
          <b-td>{{data_rencana.deskripsi}}</b-td>
        </b-tr>
      </b-table-simple>
      <b-table-simple bordered stripped responsive v-if="data_rencana">
        <b-thead>
          <b-tr>
            <b-th class="text-center">No</b-th>
            <b-th class="text-center">Nama Peserta Didik</b-th>
            <b-th class="text-center">NISN</b-th>
            <b-th class="text-center">Dimensi</b-th>
            <b-th class="text-center">Elemen</b-th>
            <b-th class="text-center">Sub Elemen</b-th>
            <b-th class="text-center">Nilai Projek</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-if="data_siswa.length">
            <template v-for="(siswa, index) in data_siswa">
              <b-tr :variant="isGanjil(index)">
                <b-td class="text-center" :rowspan="siswa.anggota_rombel.nilai_budaya_kerja.length + 1" style="vertical-align:top;">{{index + 1}}</b-td>
                <b-td :rowspan="siswa.anggota_rombel.nilai_budaya_kerja.length + 1" style="vertical-align:top;">{{siswa.nama}}</b-td>
                <b-td class="text-center" :rowspan="siswa.anggota_rombel.nilai_budaya_kerja.length + 1" style="vertical-align:top;">{{siswa.nisn}}</b-td>
              </b-tr>
              <template v-for="(nilai, urut) in siswa.anggota_rombel.nilai_budaya_kerja">
                <b-tr :variant="isGanjil(index)">
                  <b-td>{{nilai.elemen_budaya_kerja.budaya_kerja.aspek}}</b-td>
                  <b-td>{{nilai.elemen_budaya_kerja.elemen}}</b-td>
                  <b-td>{{nilai.elemen_budaya_kerja.deskripsi}}</b-td>
                  <b-td>{{nilai.opsi_budaya_kerja.nama}}</b-td>
                </b-tr>
              </template>    
            </template>
          </template>
        </b-tbody>
      </b-table-simple>
      {{data_rencana}}
    </b-modal>
  </div>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple, BTbody, BThead, BTr, BTd, BTh,
    BButton,
    Datatable
  },
  data() {
    return {
      title_projek: '',
      data_rencana: null,
      data_projek: [],
      data_siswa: [],
      opsi_budaya_kerja: [],
      isBusy: true,
      p5_isBusy: true,
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
      //p5
      p5_fields: [
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
      p5_items: [],
      p5_meta: {},
      p5_current_page: 1, 
      p5_per_page: 10,
      p5_search: '',
      p5_sortBy: 'tingkat',
      p5_sortByDesc: false,
    }
  },
  created() {
    this.periode_aktif = this.user.semester.nama
    this.loadPostsData()
    this.loadPostsProjek()
  },
  methods: {
    loadPostsData(){
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/dashboard/waka', {
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
    loadPostsProjek(){
      this.p5_isBusy = true
      let current_page = this.p5_current_page
      this.$http.get('/dashboard/projek', {
        params: {
          user_id: this.user.user_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.p5_per_page,
          q: this.p5_search,
          sortby: this.p5_sortBy,
          sortbydesc: this.p5_sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.p5_isBusy = false
        this.p5_items = getData.data//MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
        //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
        this.p5_meta = {
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
    detil(pembelajaran_id){
      this.$emit('detil', pembelajaran_id)
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
      this.$emit('detil', val)
    },
    handlePerPageProjek(val) {
      this.p5_per_page = val 
      this.loadPostsProjek()
    },
    handlePaginationProjek(val) {
      this.p5_current_page = val 
      this.loadPostsProjek()
    },
    handleSearchProjek(val) {
      this.p5_search = val
      this.loadPostsProjek()
    },
    handleSortProjek(val) {
      if (val.sortBy) {
        this.p5_sortBy = val.sortBy
        this.p5_sortByDesc = val.sortDesc
        this.loadPostsProjek()
      }
    },
    HandleDetilProjek(val){
      //this.data_projek = val
      //console.log(val);
      //this.$emit('p5_detil', val)
      this.$http.post('/dashboard/detil-projek', {
        pembelajaran_id: val,
      }).then(response => {
        this.data_projek = response.data.tema
        this.$refs['detil-p5'].show()
      }).catch(error => {
        console.log(error)
      })
    },
    detilProjek(item){
      this.$http.post('/dashboard/detil-rencana', {
        rombongan_belajar_id: item.rombongan_belajar_id,
        rencana_budaya_kerja_id: item.projek.rencana_budaya_kerja_id,
      }).then(response => {
        let getData = response.data
        console.log(getData);
        this.data_rencana = getData.rencana
        this.data_siswa = getData.data_siswa
        this.opsi_budaya_kerja = getData.opsi_budaya_kerja
        this.title_projek = `Detil Penilaian Projek ${this.data_rencana.nama}`
        this.$refs['detil-projek'].show()
      }).catch(error => {
        console.log(error)
      })
    },
    isGanjil(number){
      if(number % 2 == 0) {
        return 'secondary';
          //console.log("The number is even.");
      } else {
        return 'warning'
          //console.log("The number is odd.");
      }
    },
  }
}
</script>