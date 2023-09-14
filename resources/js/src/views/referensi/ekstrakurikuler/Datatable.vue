<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="4" offset-md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(guru)="row">
          {{row.item.guru.nama_lengkap}}
        </template>
        <template v-slot:cell(anggota)="row">
          <b-button variant="primary" size="sm" @click="anggota(row.item.rombongan_belajar_id)">Detil <b-badge>{{row.item.rombongan_belajar.anggota_rombel_count}}</b-badge></b-button>
        </template>
        <template v-slot:cell(sinkron)="row">
          <b-button variant="danger" size="sm" @click="sinkron(row.item.rombongan_belajar_id)"><font-awesome-icon icon="fa-solid fa-arrows-rotate" /> Sinkron Anggota</b-button>
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
    <b-modal ref="anggota-modal" size="xl" :title="title" ok-only ok-title="Tutup" ok-variant="secondary">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-table-simple hover bordered responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Nama</b-th>
              <b-th class="text-center">NISN</b-th>
              <b-th class="text-center">L/P</b-th>
              <b-th class="text-center">Tempat, Tanggal Lahir</b-th>
              <b-th class="text-center">Agama</b-th>
              <b-th class="text-center">Aksi</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(anggota, index) in list_anggota" :key="anggota.peserta_didik_id">
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>{{anggota.nama}}</b-td>
              <b-td class="text-center">{{anggota.nisn}}</b-td>
              <b-td class="text-center">{{anggota.jenis_kelamin}}</b-td>
              <b-td>{{anggota.tempat_lahir}}, {{anggota.tanggal_lahir_indo}}</b-td>
              <b-td>{{(anggota.agama) ? anggota.agama.nama : ''}}</b-td>
              <b-td class="text-center">
                <b-button variant="danger" size="sm" @click="keluarkan(anggota.anggota_rombel.anggota_rombel_id)">Keluarkan</b-button>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </b-overlay>
    </b-modal>
  </div>
</template>

<script>
import _ from 'lodash' //IMPORT LODASH, DIMANA AKAN DIGUNAKAN UNTUK MEMBUAT DELAY KETIKA KOLOM PENCARIAN DIISI
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BButton, BOverlay, BBadge, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
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
    BBadge,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    vSelect,
  },
  props: {
    items: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isBusy: {
      type: Boolean,
      default: () => true,
    }
  },
  data() {
    return {
      loading_modal: false,
      sortBy: null,
      sortDesc: false,
      title: '',
      list_anggota: [],
    }
  },
  watch: {
    sortBy(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },
  methods: {
    anggota(val){
      this.loading_modal = true
      this.rombongan_belajar_id = val
      this.$http.post('/rombongan-belajar/anggota-rombel', {
        rombongan_belajar_id: val,
      }).then(response => {
        this.loading_modal = false
        var getData = response.data
        this.list_anggota = getData.data
        this.title = 'Anggota Ekstrakurikuler Kelas '+getData.rombel.nama
        this.$refs['anggota-modal'].show()
      }).catch(error => {
        console.log(error);
      })
    },
    sinkron(val){
      this.loading_modal = true
      this.rombongan_belajar_id = val
      this.$http.post('/referensi/sinkron', {
        user_id: this.user.user_id,
        rombongan_belajar_id: val,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        var getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.loadPerPage(this.meta.per_page)
        })
      }).catch(error => {
        console.log(error);
      })
    },
    loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val) {
      this.$emit('pagination', val)
    },
    search: _.debounce(function (e) {
      this.$emit('search', e)
    }, 500),
  },
}
</script>