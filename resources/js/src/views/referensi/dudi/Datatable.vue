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
        <template v-slot:cell(aktifitas)="row">
          {{row.item.akt_pd_count}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-button variant="primary" size="sm" @click="detil(row.item.dudi_id)">Detil</b-button>
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
    <b-modal ref="mou-modal" size="xl" :title="title" ok-only ok-title="Tutup" ok-variant="secondary">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <template v-if="data">
          <b-table-simple hover bordered responsive>
            <b-tr>
              <b-td>Nama</b-td>
              <b-td>{{data.nama}}</b-td>
            </b-tr>
            <b-tr>
              <b-td>Bidang Usaha</b-td>
              <b-td>{{data.nama_bidang_usaha}}</b-td>
            </b-tr>
            <b-tr>
              <b-td>Alamat</b-td>
              <b-td>{{data.alamat_jalan}}</b-td>
            </b-tr>
          </b-table-simple>
          <h4 class="card-title">MoU</h4>
          <b-table-simple hover bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">Nomor MoU</b-th>
                <b-th class="text-center">Judul MoU</b-th>
                <b-th class="text-center">Periode Kerja Sama</b-th>
                <b-th class="text-center">Narahubung</b-th>
                <b-th class="text-center">Telp. Narahubung</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="(mou, index) in data.mou" :key="mou.mou_id">
                <b-td>{{mou.nomor_mou}}</b-td>
                <b-td>{{mou.judul_mou}}</b-td>
                <b-td>{{mou.tanggal_mulai}} s/d {{mou.tanggal_selesai}}</b-td>
                <b-td>{{mou.contact_person}}</b-td>
                <b-td>{{mou.telp_cp}}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <h4 class="card-title">Aktifitas Peserta Didik</h4>
          <b-table-simple hover bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">Nama Kegiatan</b-th>
                <b-th class="text-center">SK Tugas</b-th>
                <b-th class="text-center">Guru Pembimbing</b-th>
                <b-th class="text-center">Jml PD</b-th>
                <b-th class="text-center">Aksi</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="(mou, index) in data.mou">
                <b-tr v-for="(akt_pd, index) in mou.akt_pd" :key="akt_pd.akt_pd_id">
                  <b-td>{{akt_pd.judul_akt_pd}}</b-td>
                  <b-td>{{akt_pd.sk_tugas}}</b-td>
                  <b-td>
                    <ul>
                      <li v-for="(bimbing_pd, index) in akt_pd.bimbing_pd" :key="bimbing_pd.bimbing_pd_id">{{bimbing_pd.guru.nama_lengkap}}</li>
                    </ul>
                  </b-td>
                  <b-td class="text-center">{{akt_pd.anggota_akt_pd_count}}</b-td>
                  <b-td class="text-center">
                    <b-button variant="danger" size="sm" @click="anggota(akt_pd.akt_pd_id)">Detil Anggota</b-button>
                  </b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </template>
      </b-overlay>
    </b-modal>
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
              <b-th class="text-center">Kelas</b-th>
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
              <b-td>{{(anggota.kelas) ? anggota.kelas.nama : ''}}</b-td>
              <b-td class="text-center">
                <b-button variant="danger" size="sm" @click="keluarkan(anggota.anggota_akt_pd.anggota_akt_pd_id, anggota.anggota_akt_pd.akt_pd_id)">Keluarkan</b-button>
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
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BButton, BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
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
      dudi_id: '',
      akt_pd_id: '',
      data: null,
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
    detil(dudi_id){
      this.loading_modal = true
      this.dudi_id = dudi_id
      this.$http.post('/referensi/detil-dudi', {
        dudi_id: dudi_id,
      }).then(response => {
        this.loading_modal = false
        var getData = response.data
        this.title = `Informasi Detil ${getData.nama}`
        this.data = getData
        this.$refs['mou-modal'].show()
      }).catch(error => {
        console.log(error);
      })
    },
    anggota(akt_pd_id){
      this.loading_modal = true
      this.akt_pd_id = akt_pd_id
      this.$http.post('/referensi/anggota-prakerin', {
        akt_pd_id: akt_pd_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        var getData = response.data
        this.title = `Anggota Aktifitas Prakerin`
        this.list_anggota = getData
        this.$refs['anggota-modal'].show()
      }).catch(error => {
        console.log(error);
      })
    },
    keluarkan(anggota_akt_pd_id, akt_pd_id){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: false,
      }).then(result => {
        if (result.value) {
          this.$http.delete(`/referensi/keluarkan-anggota-prakerin/${anggota_akt_pd_id}`).then(response => {
            let data = response.data
            this.$swal({
              icon: data.icon,
              title: data.title,
              text: data.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(re => {
              //this.$emit('per_page', this.meta.per_page)
              this.anggota(akt_pd_id)
            })
          });
        }
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