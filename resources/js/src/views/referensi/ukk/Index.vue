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
    <b-modal ref="add-ukk" title="Tambah Paket Uji Kompetensi Keahlian" size="xl" @hidden="resetModal" @ok="handleOk">
      <add-ukk :form="form" :state="state" :jumlah_form="jumlah_form" :data_jurusan="data_jurusan" @reload="handleReload"></add-ukk>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="danger" class="d-inline-block">
          <b-button variant="danger" @click="addForm">Tambah Form</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
    <b-modal ref="add-unit" title="Tambah Data Unit Kompetensi" size="xl" @hidden="resetModalUnit" @ok="handleOkUnit">
      <add-unit :data="detil_ukk" :form="form" :loading_form="loading_form" :jumlah_form="jumlah_form"></add-unit>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="danger" class="d-inline-block">
          <b-button variant="danger" @click="addForm">Tambah Form</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
    <b-modal ref="detil-modal" title="Detil Data UKK" size="xl" ok-only ok-title="Tutup" ok-variant="secondary">
      <detil-ukk :data="detil_ukk"></detil-ukk>
    </b-modal>
    <b-modal ref="edit-modal" title="Perbaharui Data" size="lg" ok-title="Perbaharui" cancel-title="Tutup" @ok="handleUpdate">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="danger" class="d-inline-block">
        <edit-ukk :data="detil_ukk" :form="form" :loading_form="loading_form" @deleteUnit="deleteUnit"></edit-ukk>
      </b-overlay>
    </b-modal>
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
      //form
      form: {
        user_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        paket_ukk_id: '',
        jurusan_id: '',
        kurikulum_id: '',
        nomor_paket: {},
        nama_paket_id: {},
        nama_paket_en: {},
        status: {},
        kode_unit: {},
        nama_unit: {},
      },
      state: {
        jurusan_id_feedback: '',
        jurusan_id_state: null,
        kurikulum_id_feedback: '',
        kurikulum_id_state: null,
      },
      data_jurusan: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('add-ukk', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('loading', true);
      this.$http.post('/ukk/get-jurusan', this.form).then(response => {
        let getData = response.data
        this.data_jurusan = getData.data_jurusan
        for (var i = 1; i < this.jumlah_form + 1; i++) {
          this.form.status[i] = null
        }
        eventBus.$emit('loading', false);
        this.$refs['add-ukk'].show()
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
    addForm(){
      this.jumlah_form = this.jumlah_form + 1
      this.form.status[this.jumlah_form] = null
      console.log('addForm');
    },
    resetModal(){
      this.form.jurusan_id = ''
      this.state.jurusan_id_feedback = ''
      this.state.jurusan_id_state = null
      this.form.kurikulum_id = ''
      this.state.kurikulum_id_feedback = ''
      this.state.kurikulum_id_state = null
      this.form.nomor_paket = {}
      this.form.nama_paket_id = {}
      this.form.nama_paket_en = {}
      this.form.status = {}
      this.jumlah_form = 5
      this.data_jurusan = []
      this.data_kurikulum = []
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/ukk/simpan-ukk', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.jurusan_id_feedback = (getData.errors.jurusan_id) ? getData.errors.jurusan_id.join(', ') : ''
          this.state.jurusan_id_state = (getData.errors.jurusan_id) ? false : null
          this.state.kurikulum_id_feedback = (getData.errors.kurikulum_id) ? getData.errors.kurikulum_id.join(', ') : ''
          this.state.kurikulum_id_state = (getData.errors.kurikulum_id) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$refs['add-ukk'].hide()
            this.resetModal()
            this.loadPostsData()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    resetModalUnit(){
      console.log('resetModalUnit');
      this.jumlah_form = 5
    },
    handleOkUnit(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmitUnit()
    },
    handleSubmitUnit(){
      this.loading_form = true
      this.$http.post('/ukk/add-unit-ukk', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.$refs['add-unit'].hide()
          this.loadPostsData()
        })
      })
    },
    handleUpdate(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmitUpdate()
    },
    handleSubmitUpdate(){
      this.loading_form = true
      this.$http.post('/ukk/update-ukk', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.$refs['edit-modal'].hide()
          this.loadPostsData()
        })
      })
    },
    handeleAksi(val){
      this.loading = true
      if(val.aksi === 'add_unit'){
        this.form.paket_ukk_id = val.id
        this.$http.post('/ukk/detil-ukk', {
          paket_ukk_id: val.id,
        }).then(response => {
          this.loading = false
          let getData = response.data
          this.detil_ukk = getData
          this.$refs['add-unit'].show()
        })
      }
      if(val.aksi === 'detil'){
        this.$http.post('/ukk/detil-ukk', {
          paket_ukk_id: val.id,
        }).then(response => {
          this.loading = false
          let getData = response.data
          this.detil_ukk = getData
          this.$refs['detil-modal'].show()
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
          this.loading_form = false
          let getData = response.data
          this.detil_ukk = getData
          this.form.paket_ukk_id = getData.paket_ukk_id
          this.form.nomor_paket = getData.nomor_paket
          this.form.nama_paket_id = getData.nama_paket_id
          this.form.nama_paket_en = getData.nama_paket_en
          this.form.status = getData.status
          var kode_unit = {}
          var nama_unit = {}
          this.detil_ukk.unit_ukk.forEach(function(value, key) {
            kode_unit[value.unit_ukk_id] = value.kode_unit
            nama_unit[value.unit_ukk_id] = value.nama_unit
          })
          this.form.kode_unit = kode_unit
          this.form.nama_unit = nama_unit
          this.$refs['edit-modal'].show()
        })
      }
      console.log(val);
    },
    deleteUnit(unit_ukk_id){
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
            this.loading_form = true
            this.$http.post('/ukk/delete-unit-ukk', {
              unit_ukk_id: unit_ukk_id
            }).then(response => {
              this.loading_form = false
              let getData = response.data
              this.$swal({
                icon: getData.icon,
                title: getData.title,
                text: getData.text,
                customClass: {
                  confirmButton: 'btn btn-success',
                },
              }).then(result => {
                this.handeleAksi({aksi: 'edit', id: this.form.paket_ukk_id})
              })
            });
          }
        })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>