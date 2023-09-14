<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isBusy="isBusy" :loading="loading" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @detil="handleDetil" @hapus="handleHapus" />
      </div>
    </b-card-body>
    <b-modal ref="add-rencana" :title="title" size="xl">
      <add-rencana :loading_form="loading_form" :form="form" :state="state" :feedback="feedback"></add-rencana>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="primary">
          <b-button variant="primary" @click="handleSubmit">Simpan</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="secondary">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
      </template>
    </b-modal>
    <b-modal ref="detil-rencana" :title="title" size="xl" ok-only ok-variant="secondary" ok-title="Tutup">
      <detil-rencana :rencana="rencana" :data_siswa="data_siswa"></detil-rencana>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BModal, BButton, BOverlay} from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import AddRencana from './modal/AddRencana.vue'
import DetilRencana from './modal/DetilRencana.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard, BCardBody, BSpinner, BModal, BButton, BOverlay,
    Datatable,
    AddRencana,
    DetilRencana
  },
  data() {
    return {
      title: 'Tambah Perencanaan Penilaian UKK',
      isBusy: true,
      loading: false,
      fields: [
        {
          key: 'paket_ukk',
          label: 'Paket Kompetensi',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'guru_internal',
          label: 'Penguji Internal',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'guru_eksternal',
          label: 'Penguji Eksternal',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'pd_count',
          label: 'Jml PD',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
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
        aksi: 'rencana-ukk',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        tingkat: '',
        rombongan_belajar_id: '',
        jurusan_id: '',
        penguji_internal: '',
        penguji_eksternal: '',
        paket_ukk_id: '',
        tanggal: '',
        rencana_ukk_id: '',
        siswa_dipilih: {},
      },
      state: {
        /*tingkat: '',
        tingkat: null,
        rombongan_belajar_id: '',
        rombongan_belajar_id: null,
        tanggal: '',
        tanggal: null,
        penguji_internal: '',
        penguji_internal: null,
        penguji_eksternal: '',
        penguji_eksternal: null,
        paket_ukk_id: '',
        paket_ukk_id: null,*/
        tingkat: null,
        rombongan_belajar_id: null,
        jurusan_id: null,
        penguji_internal: null,
        penguji_eksternal: null,
        paket_ukk_id: null,
        tanggal: null,
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        jurusan_id: '',
        penguji_internal: '',
        penguji_eksternal: '',
        paket_ukk_id: '',
        tanggal: '',
      },
      loading_form: false,
      rencana: null,
      data_siswa: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('add-rencana', (val) => {
      this.$refs['add-rencana'].show()
      //this.$router.push({ name: 'penilaian-input-sikap' })
    })
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/ukk/get-rencana-ukk', {
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
      this.loading = true
      this.$http.post('/ukk/detil-rencana', {
        rencana_ukk_id: val,
      }).then(response => {
        this.loading = false
        let getData = response.data
        this.rencana = getData.rencana
        this.data_siswa = getData.data_siswa
        this.$refs['detil-rencana'].show()
      }).catch(error => {
        console.log(error);
      })
    },
    handleHapus(val){
      var params = {rencana_ukk_id: val}
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
          this.loading_modal = true
          this.$http.post('/ukk/hapus-rencana-ukk', params).then(response => {
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
    handleSubmit(){
      console.log(this.form);
      this.loading_form = true
      this.$http.post('/ukk/simpan-rencana-ukk', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.feedback.penguji_internal = (getData.errors.penguji_internal) ? getData.errors.penguji_internal.join(', ') : ''
          this.state.penguji_internal = (getData.errors.penguji_internal) ? false : null
          this.feedback.penguji_eksternal = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.state.penguji_eksternal = (getData.errors.deskripsi) ? false : null
          this.feedback.tanggal = (getData.errors.tanggal) ? getData.errors.tanggal.join(', ') : ''
          this.state.tanggal = (getData.errors.tanggal) ? false : null
          this.feedback.paket_ukk_id = (getData.errors.paket_ukk_id) ? getData.errors.paket_ukk_id.join(', ') : ''
          this.state.paket_ukk_id = (getData.errors.paket_ukk_id) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$refs['add-rencana'].hide()
            this.resetForm()
            this.loadPostsData()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    resetForm(){
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.jurusan_id = ''
      this.form.penguji_internal = ''
      this.form.penguji_eksternal = ''
      this.form.paket_ukk_id = ''
      this.form.tanggal = ''
      this.form.rencana_ukk_id = ''
      this.form.siswa_dipilih = {}
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>