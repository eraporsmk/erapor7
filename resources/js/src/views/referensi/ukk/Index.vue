<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isBusy="isBusy" :loading="loading" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handeleAksi" />
      </div>
    </b-card-body>
    <add-unit @reload="handleReload"></add-unit>
    <edit-ukk @reload="handleReload" @reload-unit="reloadUnit"></edit-ukk>
    <add-ukk @reload="handleReload"></add-ukk>
    <detil-ukk></detil-ukk>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BForm, BFormGroup, BFormInput, BFormSelect, BButton, BOverlay, BCol, BRow, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import AddUkk from './../modal/AddUkk.vue'
import DetilUkk from './../modal/DetilUkk.vue'
import EditUkk from './../modal/EditUkk.vue'
import AddUnit from './../modal/AddUnit.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody, BSpinner, BForm, BFormGroup, BFormInput, BFormSelect, BButton, BOverlay, BCol, BRow,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    Datatable, AddUkk, DetilUkk, EditUkk, AddUnit,
  },
  data() {
    return {
      detil_ukk: null,
      jumlah_form: 5,
      isBusy: true,
      loading: false,
      loading_form: false,
      loading_kurikulum: false,
      fields: [
        {
          key: 'jurusan',
          label: 'Kompetensi Keahlian',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nomor_paket',
          label: 'Nomor Paket',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nama_paket_en',
          label: 'Nama Paket',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'unit_ukk_count',
          label: 'Jml Unit',
          sortable: false,
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
      current_page: 1, 
      per_page: 10,
      search: '',
      sortBy: 'nama',
      sortByDesc: false,
    }
  },
  created() {
    eventBus.$on('add-ukk', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('loading', true);
      this.$http.post('/ukk/get-jurusan', {
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        eventBus.$emit('loading', false);
        eventBus.$emit('open-modal-add-ukk', response.data);
      });
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/ukk/paket-ukk', {
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
        //this.items = response.data.all_pd
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
      })
    },
    //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
    handlePerPage(val) {
      this.per_page = val //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
      this.loadPostsData() //DAN REQUEST DATA BARU KE SERVER
    },
    //JIKA ADA EMIT PAGINATION YANG DIKIRIM, MAKA FUNGSI INI AKAN DIEKSEKUSI
    handlePagination(val) {
      this.current_page = val //SET CURRENT PAGE YANG AKTIF
      this.loadPostsData()
    },
    //JIKA ADA DATA PENCARIAN
    handleSearch(val) {
      this.search = val //SET VALUE PENCARIAN KE VARIABLE SEARCH
      this.loadPostsData() //REQUEST DATA BARU
    },
    //JIKA ADA EMIT SORT
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
      }
    },
    reloadUnit(paket_ukk_id){
      this.handeleAksi({aksi: 'edit', id: paket_ukk_id})
    },
    handeleAksi(val){
      this.loading = true
      if(val.aksi === 'add_unit'){
        this.$http.post('/ukk/detil-ukk', {
          paket_ukk_id: val.id,
        }).then(response => {
          this.loading = false
          eventBus.$emit('open-modal-add-unit-ukk', response.data);
        })
      }
      if(val.aksi === 'detil'){
        this.$http.post('/ukk/detil-ukk', {
          paket_ukk_id: val.id,
        }).then(response => {
          this.loading = false
          eventBus.$emit('open-modal-detil-ukk', response.data);
        })
      }
      if(val.aksi === 'status'){
        var text = (val.id.status) ? 'Tindakan ini akan menonaktifkan data Paket UKK!' : 'Tindakan ini akan mengaktifkan data Paket UKK!'
        var params = {
          paket_ukk_id: val.id.paket_ukk_id,
          status: val.id.status,
        }
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
            this.$http.post('/ukk/status-ukk', params).then(response => {
              this.loading = false
              let getData = response.data
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
            });
          }
        })
      }
      if(val.aksi === 'edit'){
        this.loading_form = true
        this.$http.post('/ukk/detil-ukk', {
          paket_ukk_id: val.id,
        }).then(response => {
          this.loading = false
          eventBus.$emit('open-modal-edit-ukk', response.data);
        })
      }
      if(val.aksi === 'hapus'){
        this.$swal({
          title: 'Apakah Anda yakin?',
          text: 'Tindakan ini tidak dapat dikembalikan!',
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
            this.loading = false
            this.$http.post('/ukk/delete-paket-ukk', {
              paket_ukk_id: val.id,
            }).then(response => {
              this.loading = false
              let getData = response.data
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
            });
          }
        })
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>