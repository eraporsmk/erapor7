<template>
  <b-modal v-model="showModal" title="Tambah Data Unit Kompetensi" size="xl" @hidden="resetModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Kompetensi Keahlian" label-for="jurusan_id" label-cols-md="3">
              <b-form-input id="jurusan_id" :value="nama_jurusan" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kode Kompetensi" label-for="kurikulum_id" label-cols-md="3">
              <b-form-input id="kurikulum_id" :value="kurikulum_id" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nomor Paket" label-for="nomor_paket" label-cols-md="3">
              <b-form-input id="nomor_paket" :value="nomor_paket" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket" label-for="nama_paket_id" label-cols-md="3">
              <b-form-input id="nama_paket_id" :value="nama_paket_id" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-table-simple hover bordered responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center align-middle" width="20%" rowspan="2">Kode Unit</b-th>
                  <b-th class="text-center" width="80%" colspan="2">Nama Unit Kompetensi</b-th>
                </b-tr>
                <b-tr>
                  <b-th class="text-center">Bahasa Indonesia</b-th>
                  <b-th class="text-center">Bahasa Inggris</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="formulir in jumlah_form">
                  <b-tr>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.kode_unit[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_unit_id[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_unit_en[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay rounded opacity="0.6" spinner-small spinner-variant="danger" class="d-inline-block">
        <b-button variant="danger" @click="addForm">Tambah Form</b-button>
      </b-overlay>
      <b-overlay rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="success" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
  },
  data() {
    return {
      showModal: false,
      loading_form: false,
      nama_jurusan: '',
      kurikulum_id: '',
      nomor_paket: '',
      nama_paket_id: '',
      jumlah_form: 5,
      form: {
        paket_ukk_id: '',
        kode_unit: {},
        nama_unit_id: {},
        nama_unit_en: {},
      },
    }
  },
  created() {
    eventBus.$on('open-modal-add-unit-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.form.paket_ukk_id = data.paket_ukk_id
      this.nama_jurusan = data.jurusan.nama_jurusan
      this.kurikulum_id = data.kurikulum_id
      this.nomor_paket = data.nomor_paket
      this.nama_paket_id = data.nama_paket_id
      this.showModal = true
    },
    resetModal(){
      this.nama_jurusan = ''
      this.kurikulum_id = ''
      this.nomor_paket = ''
      this.nama_paket_id = ''
      this.jumlah_form = 5
      this.form = {
        kode_unit: {},
        nama_unit_id: {},
        nama_unit_en: {},
      }
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/ukk/add-unit-ukk', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.showModal = false
          this.resetModal()
          this.$emit('reload')
        })
      })
    },
    addForm(){
      this.jumlah_form = this.jumlah_form + 1
    },
  },
}
</script>