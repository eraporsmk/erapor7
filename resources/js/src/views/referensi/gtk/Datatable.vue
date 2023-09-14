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
        <template v-slot:cell(ttl)="row">
          {{row.item.tempat_lahir}}, {{row.item.tanggal_lahir_indo}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-button variant="warning" size="sm" @click="detil(row.item)">Detil</b-button>
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
    <b-modal ref="detil-modal" size="lg" :title="judul" @ok="handleOk" ok-title="Simpan" cancel-title="Tutup">
      <detil-ptk :data="data" :loading_modal="loading_modal" :ref_gelar_depan="ref_gelar_depan" :ref_gelar_belakang="ref_gelar_belakang" :form="form" :isAsesor="isAsesor" :ref_dudi="ref_dudi"></detil-ptk>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="secondary">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="primary">
          <b-button variant="primary" @click="ok()">Perbaharui</b-button>
        </b-overlay>
      </template>
    </b-modal>
  </div>
</template>

<script>
import _ from 'lodash'
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BButton, BOverlay } from 'bootstrap-vue'
import DetilPtk from './../modal/DetilPtk.vue'
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
    DetilPtk,
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
    isAsesor: {
      type: Boolean,
      default: () => false,
    }
  },
  data() {
    return {
      loading: false,
      loading_modal: false,
      sortBy: null,
      sortDesc: false,
      judul: null,
      guru_id: '',
      data: null,
      form: {
        gelar_depan: [],
        gelar_belakang: [],
        dudi_id: '',
      },
      ref_gelar_depan: [],
      ref_gelar_belakang: [],
      ref_dudi: [],
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
    detil(item){
      this.loading = true
      this.guru_id = item.guru_id
      this.$http.post('/guru/detil', {
        guru_id: this.guru_id,
        sekolah_id: item.sekolah_id,
        asesor: this.isAsesor,
      }).then(response => {
        this.loading = false
        let getData = response.data
        console.log(getData);
        this.data = getData.guru
        var gelar_depan = []
        getData.guru.gelar_depan.forEach(function(value, key) {
          gelar_depan.push(value.gelar_akademik_id)
        });
        var gelar_belakang = []
        getData.guru.gelar_belakang.forEach(function(value, key) {
          gelar_belakang.push(value.gelar_akademik_id)
        });
        this.form.gelar_depan = gelar_depan
        this.form.gelar_belakang = gelar_belakang
        this.ref_gelar_depan = getData.ref_gelar_depan
        this.ref_gelar_belakang = getData.ref_gelar_belakang
        this.ref_dudi = getData.ref_dudi
        this.form.dudi_id = getData.dudi_id
        this.judul = 'DETIL '+getData.guru.nama_lengkap
        this.$refs['detil-modal'].show()
      });
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_modal = true
      this.$http.post('/guru/update', {
        guru_id: this.guru_id,
        gelar_depan: this.form.gelar_depan,
        gelar_belakang: this.form.gelar_belakang,
        sekolah_id: this.user.sekolah_id,
        dudi_id: this.form.dudi_id,
        asesor: this.isAsesor,
      }).then(response => {
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
          this.$refs['detil-modal'].hide()
          this.loadPerPage(this.meta.per_page);
        })
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
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>