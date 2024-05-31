<template>
  <b-modal v-model="showModal" title="Perbaharui Data" size="xl" @hidden="resetModal" ok-title="Perbaharui" cancel-title="Tutup" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="xl" spinner-variant="danger">
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
              <b-form-input id="nomor_paket" v-model="form.nomor_paket"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket (ID)" label-for="nama_paket_id" label-cols-md="3">
              <b-form-input id="nama_paket_id" v-model="form.nama_paket_id"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket (EN)" label-for="nama_paket_en" label-cols-md="3">
              <b-form-input id="nama_paket_en" v-model="form.nama_paket_en"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Status" label-for="status" label-cols-md="3">
              <b-form-select id="status" v-model="form.status" :options="data_status"></b-form-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-table-simple hover bordered responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center align-middle" width="15%" rowspan="2">Kode Unit</b-th>
                  <b-th class="text-center align-middle" width="80%" colspan="2">Nama Unit Kompetensi</b-th>
                  <b-th class="text-center align-middle" width="5%" rowspan=2>#</b-th>
                </b-tr>
                <b-tr>
                  <b-th class="text-center">Bahasa Indonesia</b-th>
                  <b-th class="text-center">Bahasa Inggris</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(unit_ukk, index) in data_unit_ukk">
                  <b-tr>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.kode_unit[unit_ukk.unit_ukk_id]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_unit_id[unit_ukk.unit_ukk_id]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_unit_en[unit_ukk.unit_ukk_id]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td class="text-center">
                      <a class="text-danger" @click="deleteUnit(unit_ukk.unit_ukk_id)"><font-awesome-icon :icon="`fa-solid fa-trash`" /></a>
                    </b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BFormSelect, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormInput, BFormSelect, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
  },
  /*props: {
    form: {
      type: Object,
      required: true,
    },
    data: {
      required: true
    },
    jumlah_form: {
      type: Number,
      default: () => 5,
    },
    loading_form: {
      type: Boolean,
      default: () => false,
    },
  },*/
  data() {
    return {
      showModal: false,
      loading_form: false,
      nama_jurusan: '',
      kurikulum_id: '',
      form: {
        nomor_paket: '',
        nama_paket_id: '',
        nama_paket_en: '',
        status: '',
        kode_unit: {},
        nama_unit_id: {},
        nama_unit_en: {},
      },
      data_status: [
        {
          value: null, text: '== Pilih Status =='
        },
        {
          value: '1', text: 'Aktif',
        },
        {
          value: '0', text: 'Tidak Aktif',
        },
      ],
      data_unit_ukk: [],
    }
  },
  created() {
    eventBus.$on('open-modal-edit-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.nama_jurusan = data.jurusan.nama_jurusan
      this.kurikulum_id = data.kurikulum_id
      this.form.paket_ukk_id = data.paket_ukk_id
      this.form.nomor_paket = data.nomor_paket
      this.form.nama_paket_id = data.nama_paket_id
      this.form.nama_paket_en = data.nama_paket_en
      this.form.status = data.status
      var kode_unit = {}
      var nama_unit_id = {}
      var nama_unit_en = {}
      this.data_unit_ukk = data.unit_ukk
      this.data_unit_ukk.forEach(function(value, key) {
        kode_unit[value.unit_ukk_id] = value.kode_unit
        nama_unit_id[value.unit_ukk_id] = value.nama_unit_id
        nama_unit_en[value.unit_ukk_id] = value.nama_unit_en
      })
      this.form.kode_unit = kode_unit
      this.form.nama_unit_id = nama_unit_id
      this.form.nama_unit_en = nama_unit_en
      /*this.form.paket_ukk_id = data.paket_ukk_id
      
      
      this.nomor_paket = data.nomor_paket
      this.nama_paket_id = data.nama_paket_id*/
      this.showModal = true
    },
    resetModal(){},
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/ukk/update-ukk', this.form).then(response => {
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
          this.$emit('reload')
          this.resetModal()
        })
      })
    },
    deleteUnit(unit_ukk_id){
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
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.loading_form = true
          this.$http.post('/ukk/delete-unit-ukk', {
            unit_ukk_id: unit_ukk_id
          }).then(response => {
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
              this.$emit('reload-unit', this.form.paket_ukk_id)
              //this.handeleAksi({aksi: 'edit', id: this.form.paket_ukk_id})
            })
          });
        }
      })
    }
  },
}
</script>