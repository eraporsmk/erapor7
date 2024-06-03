<template>
  <b-card>
    <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @getAnggota="handleAnggota" @getPembelajaran="handlePembelajaran"  />
    <anggota-rombel :title="title":loading_modal="loading_modal" :list_anggota="list_anggota" @keluarkan="keluarkan"></anggota-rombel>
    <pembelajaran :title="title" :loading_modal="loading_modal" :list_pembelajaran="list_pembelajaran" :form="form" :data_guru="data_guru" :data_kelompok="data_kelompok" @hapus="handleHapus" @getPembelajaran="handlePembelajaran"></pembelajaran>
  </b-card>
</template>

<script>
//, BRow, BCol, BFormFile, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BSpinner
import { BCard, BOverlay, BButton } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import Pembelajaran from './../modal/Pembelajaran.vue'
import AnggotaRombel from './../modal/AnggotaRombel.vue'
import eventBus from '@core/utils/eventBus';
//import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
export default {
  components: {
    BCard,
    BOverlay,
    BButton,
    Datatable,
    Pembelajaran,
    AnggotaRombel
  },
  data() {
    return {
      loading: false,
      loading_modal: false,
      isBusy: true,
      fields: [
        {
          key: 'nama',
          label: 'Nama',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'wali_kelas',
          label: 'Wali Kelas',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'tingkat',
          label: 'Tingkat',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'jurusan_sp',
          label: 'Program/Kompetensi Keahlian',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'kurikulum',
          label: 'Kurikulum',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'anggota_rombel',
          label: 'Anggota Rombel',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'pembelajaran',
          label: 'Pembelajaran',
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
      sortBy: 'tingkat',
      sortByDesc: false,
      title: '',
      rombongan_belajar_id: '',
      list_anggota: [],
      list_pembelajaran: [],
      data_kelompok: [],
      data_guru: [],
      form: {
        nama: {},
        guru_pengajar_id : {},
        kelompok_id: {},
        no_urut: {},
      },
      placement: 'top',
      jml_data: 0,
      jml_simpan: 0,
    }
  },
  watch: {
    jml_data(val){
      this.hitung()
    },
  },
  created() {
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/rombongan-belajar', {
        params: {
          jenis_rombel: 16,
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
        this.items = getData.data
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
    handleAnggota(val){
      this.loading = true
      this.rombongan_belajar_id = val
      this.$http.post('/rombongan-belajar/anggota-rombel', {
        rombongan_belajar_id: val,
      }).then(response => {
        this.loading = false
        this.loading_modal = false
        var getData = response.data
        this.list_anggota = getData.data
        this.title = 'Anggota Rombel Kelas '+getData.rombel.nama
        eventBus.$emit('open-modal-anggota-rombel')
      }).catch(error => {
        console.log(error);
      })
    },
    handlePembelajaran(val){
      this.loading = true
      this.rombongan_belajar_id = val
      this.$http.post('/rombongan-belajar/pembelajaran', {
        rombongan_belajar_id: val,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading = false
        this.loading_modal = false
        var getData = response.data
        this.list_pembelajaran = getData.data
        this.jml_data = this.list_pembelajaran.length
        var nama = {}
        var guru_pengajar_id = {}
        var kelompok_id = {}
        var no_urut = {}
        this.list_pembelajaran.forEach(function(value, key) {
          nama[value.pembelajaran_id] = value.nama_mata_pelajaran
          guru_pengajar_id[value.pembelajaran_id] = value.guru_pengajar_id
          kelompok_id[value.pembelajaran_id] = value.kelompok_id
          no_urut[value.pembelajaran_id] = value.no_urut
        })
        this.form.nama = nama
        this.form.guru_pengajar_id = guru_pengajar_id
        this.form.kelompok_id = kelompok_id
        this.form.no_urut = no_urut
        this.data_guru = getData.guru
        this.data_kelompok = getData.Kelompok
        this.title = 'Pembelajaran Kelas '+getData.rombel.nama
        eventBus.$emit('open-modal-pembelajaran', val)
      }).catch(error => {
        console.log(error);
      })
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_modal = true
      this.$http.post('/rombongan-belajar/simpan-pembelajaran', this.form).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.loading_modal = true
          this.handlePembelajaran(this.rombongan_belajar_id)
        })
      });
    },
    hitung(aksi = null){
      if(aksi == 'start'){
        this.jml_simpan = this.jml_simpan + 1
        if(this.jml_simpan === this.jml_data){
          this.jml_simpan = 0;
          this.handlePembelajaran(this.rombongan_belajar_id)
        }
      }

    },
    swalConfirm(text, aksi, params, after){
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
        allowOutsideClick: () => !this.$swal.isLoading(),
      }).then(result => {
        if (result.value) {
          this.loading_modal = true
          this.$http.post(aksi, params).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              if(after == 'keluar'){
                this.handleKeluarkan()
              }
              if(after == 'hapus-pembelajaran'){
                this.handlePembelajaran(this.rombongan_belajar_id)
              }
            })
          });
        }
      })
    },
    keluarkan(id){
      var text = 'Tindakan ini tidak dapat dikembalikan!'
      var aksi = '/rombongan-belajar/keluarkan'
      var params = {anggota_rombel_id: id}
      this.swalConfirm(text, aksi, params, 'keluar')
    },
    handleKeluarkan(){
      this.handleAnggota(this.rombongan_belajar_id)
    },
    handleHapus(val){
      this.rombongan_belajar_id = val.rombongan_belajar_id
      var text = 'Aksi ini akan menghapus Guru Pengajar dan Kelompok!'
      var aksi = '/rombongan-belajar/hapus-pembelajaran'
      var params = {pembelajaran_id: val.pembelajaran_id, rombongan_belajar_id: val.rombongan_belajar_id}
      this.swalConfirm(text, aksi, params, 'hapus-pembelajaran')
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>