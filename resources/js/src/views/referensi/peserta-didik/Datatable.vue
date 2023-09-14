<template>
  <div>
    <b-row v-if="status === 'aktif'">
      <b-col md="4" class="mb-2">
        <v-select id="tingkat" v-model="filter.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Filter Tingkat Kelas ==" :searchable="false" @input="changeTingkat">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
      <b-col md="4">
        <v-select id="tingkat" v-model="filter.jurusan_sp_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_sp_id" label="nama_jurusan_sp" :options="data_jurusan" placeholder="== Filter Jurusan ==" @input="changeJurusan">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
      <b-col md="4">
        <v-select id="tingkat" v-model="filter.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Filter Rombel ==" @input="changeRombel">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
    </b-row>
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
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(ttl)="row">
          {{row.item.tempat_lahir}}, {{row.item.tanggal_lahir_indo}}
        </template>
        <template v-slot:cell(agama)="row">
          {{(row.item.agama) ? row.item.agama.nama : '-'}}
        </template>
        <template v-slot:cell(anggota_rombel)="row">
          {{(row.item.anggota_rombel && row.item.anggota_rombel.rombongan_belajar) ? row.item.anggota_rombel.rombongan_belajar.nama : '-'}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-button variant="success" size="sm" @click="getDetil(row.item.peserta_didik_id)">Detil</b-button>
        </template>
        <template v-slot:cell(user)="row">
          <template v-if="row.item.user">
            <template v-if="cekPass(row.item.user.password, row.item.user.default_password)">
              {{row.item.user.default_password}}
            </template>
            <template v-else>
              <b-badge variant="success">Custom</b-badge>
            </template>
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
    <b-modal ref="detil-modal" size="lg" :title="title" @ok="handleOk" ok-title="Perbaharui" cancel-title="Tutup">
      <detil-pd :data="data" :loading_modal="loading_modal" :form="form" :pekerjaan="pekerjaan"></detil-pd>
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
import DetilPd from './../modal/DetilPd.vue'
import vSelect from 'vue-select'
import bcrypt from 'bcryptjs';
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
    DetilPd,
    vSelect,
  },
  props: {
    status: {
      type: String,
      required: true
    },
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
    loading_modal: {
      type: Boolean,
      default: () => false,
    },
    filter: {
      type: Object,
      required: true
    },
    data_jurusan: {
      type: Array,
    },
    data_rombel: {
      type: Array,
    },
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
      title: '',
      data: null,
      pekerjaan: [],
      form: {
        peserta_didik_id: '',
        status: '',
        anak_ke: '',
        diterima_kelas: '',
        email: '',
        nama_wali: '',
        alamat_wali: '',
        telp_wali: '',
        kerja_wali: '',
      },
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
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
    cekPass(password, default_password){
      return bcrypt.compareSync(default_password, password)
    },
    getDetil(peserta_didik_id){
      this.$emit('loadingTable', true)
      this.$emit('loadingModal', true)
      this.form.peserta_didik_id = peserta_didik_id
      this.$http.post('/peserta-didik/detil', this.form).then(response => {
        this.$emit('loadingTable', false)
        this.$emit('loadingModal', false)
        var getData = response.data
        this.data = getData.data
        this.pekerjaan = getData.pekerjaan
        this.form.status = this.data.status
        this.form.anak_ke = this.data.anak_ke
        this.form.diterima_kelas = this.data.diterima_kelas
        this.form.email = this.data.email
        this.form.nama_wali = this.data.nama_wali
        this.form.alamat_wali = this.data.alamat_wali
        this.form.telp_wali = this.data.telp_wali
        this.form.kerja_wali = this.data.kerja_wali
        this.title = 'Detil '+getData.data.nama
        this.$refs['detil-modal'].show()
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
    changeTingkat(val) {
      this.$emit('tingkat', val)
    },
    changeJurusan(val) {
      this.$emit('jurusan', val)
    },
    changeRombel(val) {
      this.$emit('rombel', val)
    },
    search: _.debounce(function (e) {
      this.$emit('search', e)
    }, 500),
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.$emit('loadingModal', true)
      this.$http.post('/peserta-didik/update', this.form).then(response => {
        let getData = response.data
        this.$emit('loadingModal', false)
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.$refs['detil-modal'].hide()
          this.loadPerPage(this.meta.per_page)
        })
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>