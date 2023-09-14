<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" :searchable="false" :clearable="false" @input="loadPerPage" />
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
        <template v-slot:cell(detil)="row">
          <b-button variant="success" size="sm" @click="getDetil(row.item.pembelajaran_id)">Detil</b-button>
        </template>
        <template v-slot:cell(detil_p5)="row">
          <b-button variant="success" size="sm" @click="getDetil(row.item.projek.pembelajaran_id)">Detil</b-button>
        </template>
        <template v-slot:cell(rombel_p5)="row">
          {{row.item.nama}}
        </template>
        <template v-slot:cell(koordinator)="row">
          {{row.item.projek.guru.nama_lengkap}}
        </template>
        <template v-slot:cell(tema_count)="row">
          {{row.item.projek.tema_count}}
        </template>
        <template v-slot:cell(rencana_projek_count)="row">
          {{row.item.projek.rencana_projek.length}}
        </template>
        <template v-slot:cell(aspek_projek_count)="row">
          {{jumlahAspek(row.item.projek.rencana_projek)}}
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
import { BRow, BCol, BFormInput, BFormSelect, BTable, BSpinner, BPagination, BButton, BOverlay } from 'bootstrap-vue'
import vSelect from 'vue-select'
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
      loading: false,
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
    getDetil(val){
      this.$emit('detil', val)
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
    jumlahAspek(array){
      var Jumlah = 0;
      array.forEach(function(value, key) {
        Jumlah =+ value.aspek_budaya_kerja_count
      })
      return Jumlah
    }
  },
}
</script>