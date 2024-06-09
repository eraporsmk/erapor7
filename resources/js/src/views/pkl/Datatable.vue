<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="4">
        <v-select v-model="rombongan_belajar_id" :options="meta.data_rombel" @input="changeRombel" :reduce="nama => nama.rombongan_belajar_id" label="nama" placeholder="== Filter Rombel =="></v-select>
      </b-col>
      <b-col md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #empty="scope">
          <p class="text-center">Tidak ada data untuk ditampilkan</p>
        </template>
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(kelas)="row">
          {{row.item.rombongan_belajar.nama}}
        </template>
        <template v-slot:cell(dudi)="row">
          {{row.item.akt_pd.dudi.nama}}
        </template>
        <template v-slot:cell(pks)="row">
          {{row.item.akt_pd.judul_akt_pd}}
        </template>
        <template v-slot:cell(pd_pkl_count)="row">
          {{row.item.rombongan_belajar.anggota_rombel_count}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="success" size="sm">
            <b-dropdown-item href="javascript:" @click="aksi(row.item.pkl_id, 'detil')"><font-awesome-icon icon="fa-solid fa-eye" /> Detil</b-dropdown-item>
            <b-dropdown-item href="javascript:" @click="aksi(row.item.pkl_id, 'edit')"><font-awesome-icon icon="fa-solid fa-pencil" /> Ubah Data</b-dropdown-item>
            <b-dropdown-item href="javascript:" @click="aksi(row.item.pkl_id, 'hapus')"><font-awesome-icon icon="fa-solid fa-trash" /> Hapus Data</b-dropdown-item>
          </b-dropdown>
        </template>
        <template v-slot:cell(rombel)="row">
          {{row.item.kelas.nama}}
        </template>
        <template v-slot:cell(cetak)="row">
          <b-dropdown id="dropdown-dropleft" dropleft text="Daftar Nilai" variant="success" size="sm">
            <template v-for="(pkl, index) in row.item.all_pd_pkl">
              <b-dropdown-item href="javascript:" @click="cetakPkl(row.item.peserta_didik_id, pkl.pkl_id)"><font-awesome-icon icon="fa-solid fa-print" /> {{ pkl.praktik_kerja_lapangan.nama_dudi }}</b-dropdown-item>
            </template>
          </b-dropdown>
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
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BButton, BOverlay, BDropdown, BDropdownItem } from 'bootstrap-vue'
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
    BDropdown, 
    BDropdownItem,
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
    },
    loading: {
      type: Boolean,
      default: () => false,
    }
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
      rombongan_belajar_id: '',
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
    aksi(val, aksi){
      this.$emit('aksi', {
        id: val,
        aksi: aksi,
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
    cetakPkl(peserta_didik_id, pkl_id){
      window.open(`/cetak/rapor-pkl/${peserta_didik_id}/${pkl_id}/${this.user.guru_id}/${this.user.semester.semester_id}`, `_blank`);
    },
    changeRombel(val){
      this.rombongan_belajar_id = val
      this.$emit('rombel', val)
    }
  },
}
</script>