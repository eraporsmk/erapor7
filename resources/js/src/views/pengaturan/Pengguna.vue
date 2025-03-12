<template>
  <b-card>
    <datatable :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @role_id="handleRole" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" />
  </b-card>
</template>

<script>
import { BCard } from 'bootstrap-vue'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BCard,
    Datatable
  },
  data() {
    return {
      isBusy: true,
      fields: [
        {
          key: 'name',
          label: 'Nama',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'email',
          label: 'Email',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'roles',
          label: 'Jenis Pengguna',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'login_terakhir',
          label: 'Terakhir Login',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'password',
          label: 'Password',
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
      role_id: null,
      search: '',
      sortBy: 'name', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false, //ASCEDING
    }
  },
  created() {
    this.loadPostsData()
    eventBus.$on('generatePengguna', this.handleEvent);
  },
  methods: {
    handleEvent(){
      //this.loadPostsData()
      this.$swal({
          title: 'Apakah Anda yakin?',
          text: 'Tindakan ini tidak dapat dikembalikan!',
          icon: 'question',
          showConfirmButton: true,
          confirmButtonText: 'Akun PTK',
          showLoaderOnConfirm: true,
          showDenyButton: true,
          denyButtonText: 'Akun PD',
          showLoaderOnDeny: true,
          showCancelButton: true,
          cancelButtonText: 'Batal',
          customClass: {
            confirmButton: 'btn btn-primary',
            denyButton:'btn btn-danger ml-1',
            cancelButton: 'btn btn-secondary ml-1',
          },
          buttonsStyling: false,
          allowOutsideClick: () => !this.$swal.isLoading(),
        }).then(result => {
          eventBus.$emit('loading', true);
          var aksi;
          if(result.isConfirmed){
            aksi = 'ptk'
          } else if(result.isDenied){
            aksi = 'pd'
          }
          if (aksi) {
            this.$http.post('/users/generate', {
              aksi: aksi,
              sekolah_id: this.user.sekolah_id,
              semester_id: this.user.semester.semester_id,
              periode_aktif: this.user.semester.nama,
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
                //eventBus.$emit('my-event');
                eventBus.$emit('loading', false);
                this.loadPostsData()
              })
            });
          }
        })
    },
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/users', {
        params: {
          user_id: this.user.user_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          role_id: this.role_id,
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
    //JIKA ADA EMIT SORT
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>