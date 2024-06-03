<template>
  <b-modal v-model="showPembelajaranModal" size="xl" :title="title" :body-class="table_class" @ok="handleOk">
    <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-table-simple hover bordered :class="table_class">
        <b-thead>
          <b-tr>
            <b-th class="text-center">No</b-th>
            <b-th class="text-center">Mata Pelajaran</b-th>
            <b-th class="text-center">ID Mapel</b-th>
            <b-th class="text-center">Guru Mapel (Dapodik)</b-th>
            <b-th class="text-center">Guru Pengajar</b-th>
            <b-th class="text-center">Kelompok</b-th>
            <b-th class="text-center">No Urut</b-th>
            <b-th class="text-center">Reset</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(pembelajaran, index) in list_pembelajaran">
            <b-tr v-bind:class="{'bg-warning': pembelajaran.induk_pembelajaran_id}">
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>
                <b-form-input v-model="form.nama[pembelajaran.pembelajaran_id]"></b-form-input>
              </b-td>
              <b-td class="text-center">
                <b-form-input :value="pembelajaran.mata_pelajaran_id" disabled />
              </b-td>
              <b-td class="text-center">
                <b-form-input :value="pembelajaran.guru.nama_lengkap" disabled />
              </b-td>
              <b-td>
                <v-select v-model="form.guru_pengajar_id[pembelajaran.pembelajaran_id]" :options="data_guru" :reduce="nama_lengkap => nama_lengkap.guru_id" label="nama_lengkap" placeholder="== Pilih Guru Pengajar ==" @open="handleOpen" @close="handleClose">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-td>
              <b-td>
                <v-select v-model="form.kelompok_id[pembelajaran.pembelajaran_id]" :options="data_kelompok" :reduce="nama_kelompok => nama_kelompok.kelompok_id" label="nama_kelompok" placeholder="== Pilih Kelompok ==">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-td>
              <b-td>
                <b-form-input v-model="form.no_urut[pembelajaran.pembelajaran_id]"></b-form-input>
              </b-td>
              <b-td class="text-center">
                <template v-if="pembelajaran.kelompok_id && pembelajaran.no_urut">
                  <a @click="hapus(pembelajaran.pembelajaran_id, pembelajaran.rombongan_belajar_id)" class="text-danger"><font-awesome-icon icon="fa-solid fa-trash" /></a>
                </template>
                <template v-else>
                  -
                </template>
              </b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="secodary">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="primary">
          <b-button variant="primary" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-overlay>
  </b-modal>
</template>

<script>
import { BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormInput, VBModal } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import vSelect from 'vue-select'
import Ripple from 'vue-ripple-directive'
export default {
  components: {
    BOverlay,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    BFormInput,
    vSelect,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  props: {
    form: {
      type: Object,
      required: true,
    },
    /*state: {
      type: Object,
      required: true,
    },*/
    list_pembelajaran: {
      type: Array,
      required: true
    },
    data_guru: {
      type: Array,
      required: true
    },
    data_kelompok: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      default: () => '',
    },
  },
  data(){
    return {
      showPembelajaranModal: false,
      table_class: 'pb-2',
      loading_modal: false,
      rombongan_belajar_id: null,
    }
  },
  created() {
    eventBus.$on('open-modal-pembelajaran', this.handleEvent);
  },
  methods: {
    handleEvent(rombongan_belajar_id){
      this.loading_modal = false
      this.rombongan_belajar_id = rombongan_belajar_id
      this.showPembelajaranModal = true
    },
    hapus(pembelajaran_id, rombongan_belajar_id){
      this.$emit('hapus', {
        pembelajaran_id: pembelajaran_id,
        rombongan_belajar_id: rombongan_belajar_id,
      })
    },
    handleOpen(){
      this.table_class = 'pb-5'
    },
    handleClose(){
      this.table_class = 'pb-2'
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_modal = true
      this.$http.post('/rombongan-belajar/simpan-pembelajaran', this.form).then(response => {
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
          this.loading_modal = true
          this.$emit('getPembelajaran', this.rombongan_belajar_id)
        })
      });
    },
  },
}
</script>