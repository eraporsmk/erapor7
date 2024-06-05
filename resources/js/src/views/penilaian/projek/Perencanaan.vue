<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @detil="handleDetil" @edit="handleEdit" @hapus="handleHapus" />
      </div>
    </b-card-body>
    <add-modal :title="title" :loading_form="loading_form" :form="form" :state="state" :feedback="feedback" @reload="handlePerPage"></add-modal>
    <detil-modal></detil-modal>
    <edit-modal :data="data" :form="form" :state="state" :feedback="feedback" @reload="handlePerPage"></edit-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BModal, BButton, BOverlay} from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import AddModal from './AddModal.vue'
import DetilModal from './DetilModal.vue'
import EditModal from './EditModal.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BModal,
    BButton,
    BOverlay,
    //BRow, BCol, BFormGroup, BFormInput, BFormTextarea,
    //BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormCheckbox,
    //vSelect,
    Datatable,
    DetilModal,
    EditModal,
    AddModal
  },
  data() {
    return {
      data: null,
      title: 'Tambah Perencanaan P5',
      show_table: false,
      loading_form: false,
      isBusy: true,
      fields: [
        {
          key: 'rombongan_belajar',
          label: 'Kelas',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'pembelajaran',
          label: 'Tema',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nama',
          label: 'Nama Projek',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'deskripsi',
          label: 'Deskripsi',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'no_urut',
          label: 'No. Urut',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'aspek_budaya_kerja_count',
          label: 'Jml Sub Elemen',
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
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
      search: '',
      sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: true, //ASCEDING
      form: {
        aksi: 'rencana',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        rencana_budaya_kerja_id: '',
        nama_projek: '',
        deskripsi: '',
        no_urut: '',
        sub_elemen: {},
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        nama_projek: '',
        deskripsi: '',
        no_urut: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        pembelajaran_id: null,
        nama_projek: null,
        deskripsi: null,
        no_urut: null,
      },
      /*tingkat: '',
      tingkat: null,
      rombongan_belajar_id: '',
      rombongan_belajar_id: null,
      pembelajaran_id: '',
      pembelajaran_id: null,
      nama_projek: '',
      nama_projek: null,
      deskripsi: '',
      deskripsi: null,*/
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('add-projek', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-modal-add-projek');
    },
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/penilaian/nilai-projek', {
        params: {
          user_id: this.user.user_id,
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
          role_id: this.role_id,
          roles: response.data.roles,
        }
      })
    },
    //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
    handlePerPage(val) {
      this.per_page = val //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
      this.loadPostsData() //DAN REQUEST DATA BARU KE SERVER
    },
    handleRole(val){
      this.role_id = val
      this.loadPostsData()
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
    handleDetil(val){
      this.$http.post('/penilaian/detil-projek', {
        rencana_budaya_kerja_id: val.rencana_budaya_kerja_id
      }).then(response => {
        eventBus.$emit('open-modal-detil-projek', response.data)
      }).catch(error => {
        console.log(error);
      })
    },
    handleEdit(val){
      this.$http.post('/penilaian/detil-projek', {
        rencana_budaya_kerja_id: val.rencana_budaya_kerja_id
      }).then(response => {
        eventBus.$emit('open-modal-edit-projek', response.data)
      }).catch(error => {
        console.log(error);
      })
    },
    handleHapus(val){
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
          this.$http.post('/penilaian/hapus-projek', {
            rencana_budaya_kerja_id: val,
          }).then(response => {
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
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>