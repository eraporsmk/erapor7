<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="4" offset-md="4">
        <b-form-input @input="cari" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #empty="scope">
          <p class="text-center">Tidak ada data untuk ditampilkan</p>
        </template>
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(wali_kelas)="row">
          {{row.item.wali_kelas.nama_lengkap}}
        </template>
        <template v-slot:cell(generate_matev)="row">
          <template v-if="row.item.pembelajaran_count">
            <b-button variant="warning" size="sm" @click="handleMatev(row.item.rombongan_belajar_id, row.item.nama)"><font-awesome-icon icon="fa-solid fa-bolt-lightning" size="xs" /> Generate</b-button>
          </template>
          <template v-else>
            <font-awesome-icon icon="fa-solid fa-circle-question" v-b-tooltip.hover.html="`Kelas ${row.item.nama} belum memiliki Pembelajaran termapping!`" />
          </template>
        </template>
        <template v-slot:cell(nilai)="row">
          <template v-if="row.item.matev">
            <b-button variant="primary" size="sm" @click="kirimData(row.item.rombongan_belajar_id, row.item.nama)"><font-awesome-icon icon="fa-solid fa-database" size="xs" /> Kirim Data</b-button>
          </template>
          <template v-else>
            <font-awesome-icon icon="fa-solid fa-circle-question" v-b-tooltip.hover.html="`Kelas ${row.item.nama} belum memiliki Mata Evaluasi!`" />
          </template>
        </template>
      </b-table>
    </b-overlay>
    <b-row class="mt-2">
      <b-col md="6">
        <p>Menampilkan {{ (meta.from) ? meta.from : 0 }} sampai {{ meta.to }} dari {{ meta.total }} entri</p>
      </b-col>
      <b-col md="6">
        <b-pagination v-model="meta.current_page" :total-rows="meta.total" :per-page="meta.per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import _ from 'lodash'
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BButton, BOverlay, VBTooltip } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BFormInput,
    BTable,
    BSpinner,
    BPagination,
    BButton,
    BOverlay,
    vSelect,
    VBTooltip,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  props: {
    form: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      items: [],
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
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'matev',
          label: 'Mata Evaluasi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'matev_terkirim',
          label: 'Mata Evaluasi Terkirim',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'generate_matev',
          label: 'Generate Matev',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'nilai',
          label: 'Kirim Nilai',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      isBusy: true,
      loading: false,
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'nama',
      sortByDesc: false,
      sortDesc: false,
    }
  },
  watch: {
    sortBy(val) {
      this.handleSort({
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val) {
      this.handleSort({
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },
  created() {
    this.loadPostsData()
  },
  methods: {
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData()
      }
    },
    loadPostsData(){
      this.isBusy = true
      let current_page = this.current_page
      this.$http.get('/sinkronisasi/rombongan-belajar', {
        params: {
          user_id: this.form.user_id,
          sekolah_id: this.form.sekolah_id,
          semester_id: this.form.semester_id,
          periode_aktif: this.form.periode_aktif,
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
    swalConfirm(text, aksi, params){
      this.$swal({
        title: 'Konfirmasi',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Lanjutkan!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.loading = true
          this.$http.post(aksi, params).then(response => {
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
    },
    handleMatev(rombongan_belajar_id, nama_kelas){
      this.swalConfirm(`Aksi ini akan menggenerate Mata Evaluasi seluruh Mata Pelajaran di kelas ${nama_kelas}`, '/sinkronisasi/matev-rapor', {
        rombongan_belajar_id: rombongan_belajar_id,
        nama_kelas: nama_kelas,
      })
    },
    kirimData(rombongan_belajar_id, nama_kelas){
      this.swalConfirm(`Aksi ini akan mengirim nilai akhir seluruh Mata Pelajaran di kelas ${nama_kelas}`, '/sinkronisasi/kirim-nilai', {
        rombongan_belajar_id: rombongan_belajar_id,
        nama_kelas: nama_kelas,
        npsn: this.form.npsn,
        semester_id: this.form.semester_id,
        url_dapodik: this.form.url_dapodik,
        token_dapodik: this.form.token_dapodik,
      })
    },
    loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val) {
      this.$emit('pagination', val)
    },
    cari: _.debounce(function (e) {
      this.$emit('search', e)
    }, 500),
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>