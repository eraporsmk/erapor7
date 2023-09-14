<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <b-form-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @change="loadPerPage"></b-form-select>
      </b-col>
      <b-col md="4" offset-md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(rombongan_belajar)="row">
          {{row.item.pembelajaran.rombongan_belajar.nama}}
        </template>
        <template v-slot:cell(pembelajaran)="row">
          {{row.item.pembelajaran.nama_mata_pelajaran}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="primary" size="sm">
            <b-dropdown-item @click="read(row.item, 'detil')"><font-awesome-icon icon="fa-solid fa-eye" /> Detil</b-dropdown-item>
            <b-dropdown-item @click="read(row.item, 'edit')"><font-awesome-icon icon="fa-solid fa-pencil" /> Edit</b-dropdown-item>
            <b-dropdown-item @click="read(row.item.rencana_budaya_kerja_id, 'hapus')"><font-awesome-icon icon="fa-solid fa-trash" /> Hapus</b-dropdown-item>
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
import _ from 'lodash' //IMPORT LODASH, DIMANA AKAN DIGUNAKAN UNTUK MEMBUAT DELAY KETIKA KOLOM PENCARIAN DIISI
import { BRow, BCol, BFormInput, BFormSelect, BFormSelectOption, BTable, BSpinner, BPagination, BButton, BOverlay, BDropdown, BDropdownItem, BBadge } from 'bootstrap-vue'
export default {
  components: {
    BRow,
    BCol,
    BFormInput, BFormSelect, BFormSelectOption,
    BTable,
    BSpinner,
    BPagination,
    BButton,
    BOverlay,
    BDropdown, BDropdownItem, BBadge,
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
    },
    data_tingkat: {
      type: Array,
      //required: true
    },
    data_jurusan: {
      type: Array,
      //required: true
    },
    data_rombel: {
      type: Array,
      //required: true
    },
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
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
    read(item, aksi){
      this.$emit(aksi, item)
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