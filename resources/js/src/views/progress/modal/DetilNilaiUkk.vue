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
      <b-table responsive bordered striped :items="data_siswa" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :loading="loading">
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(no)="row">
          {{meta.from + row.index}}
        </template>
        <template v-slot:cell(kelas)="row">
          {{row.item.kelas.nama}}
        </template>
        <template v-slot:cell(predikat)="row">
          {{(row.item.anggota_ekskul.single_nilai_ekstrakurikuler) ? predikat(row.item.anggota_ekskul.single_nilai_ekstrakurikuler.nilai) : ''}}
        </template>
        <template v-slot:cell(deskripsi)="row">
          {{(row.item.anggota_ekskul.single_nilai_ekstrakurikuler) ? row.item.anggota_ekskul.single_nilai_ekstrakurikuler.deskripsi_ekskul : ''}}
        </template>
      </b-table>
      <b-row class="mt-2">
        <b-col md="6">
          <p>Menampilkan {{ (meta.from) ? meta.from : 0 }} sampai {{ meta.to }} dari {{ meta.total }} entri</p>
        </b-col>
        <b-col md="6">
          <b-pagination v-model="meta.current_page" :total-rows="meta.total" :per-page="meta.per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
        </b-col>
      </b-row>
    </b-overlay>
  </div>
</template>

<script>
import _ from 'lodash'
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BOverlay } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BOverlay, vSelect
  },
  props: {
    data_siswa: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: () => false,
    },
    meta: {
      required: true
    },
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
      fields: [
        {
          key: 'no',
          label: 'NO',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'nama',
          label: 'Nama',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'NISN',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'kelas',
          label: 'Kelas',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'predikat',
          label: 'Predikat',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'deskripsi',
          label: 'Deskripsi',
          sortable: false,
          thClass: 'text-center',
        },
      ],
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
    loadPerPage(val) {
      this.$emit('per_page_nilai', this.meta.per_page)
    },
    changePage(val) {
      this.$emit('pagination_nilai', val)
    },
    search: _.debounce(function (e) {
      this.$emit('search_nilai', e)
    }, 500),
    predikat(val){
      var template_desk = {
        1: 'Sangat Baik',
        2: 'Baik',
        3: 'Cukup',
        4: 'Kurang',
      }
      return template_desk[val];
    },
  },
}
</script>