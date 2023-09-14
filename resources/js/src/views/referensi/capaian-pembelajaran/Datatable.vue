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
        <template v-slot:cell(mata_pelajaran_id)="row">
          {{row.item.mata_pelajaran.nama}}
        </template>
        <template v-slot:cell(status)="row">
          <b-badge variant="success" v-if="row.item.aktif">Aktif</b-badge>
          <b-badge variant="danger" v-else>Non Aktif</b-badge>
        </template>
        <template v-slot:cell(actions)="row">
          <b-button variant="danger" size="sm" @click="nonAktifkan(row.item.cp_id)" v-if="row.item.aktif">Non Aktifkan</b-button>
          <b-button variant="success" size="sm" @click="aktifkan(row.item.cp_id)" v-else>Aktifkan</b-button>
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
import { BRow, BCol, BFormInput, BFormSelect, BTable, BSpinner, BPagination, BButton, BOverlay, BBadge } from 'bootstrap-vue'

export default {
  components: {
    BRow,
    BCol,
    BFormInput, BFormSelect,
    BTable,
    BSpinner,
    BPagination,
    BButton,
    BOverlay,
    BBadge
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
    nonAktifkan(val) {
      this.$emit('nonAktifkan', val)
    },
    aktifkan(val) {
      this.$emit('aktifkan', val)
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