<template>
  <b-modal v-model="showModal" title="Tambah Paket Uji Kompetensi Keahlian" size="xl" @hidden="resetModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Kompetensi Keahlian" label-for="jurusan_id" label-cols-md="3" :invalid-feedback="state.jurusan_id_feedback" :state="state.jurusan_id_state">
              <v-select id="jurusan_id" v-model="form.jurusan_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_id" label="nama_jurusan_sp" :options="data_jurusan" placeholder="== Pilih Kompetensi Keahlian ==" :searchable="false" :state="state.jurusan_id_state" @input="changeJurusan">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-for="kurikulum_id" label-cols-md="3" :invalid-feedback="state.kurikulum_id_feedback" :state="state.kurikulum_id_state">
              <b-overlay :show="loading_kurikulum" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="kurikulum_id" v-model="form.kurikulum_id" :reduce="nama_kurikulum => nama_kurikulum.kurikulum_id" label="nama_kurikulum" :options="data_kurikulum" placeholder="== Pilih Kurikulum ==" :searchable="false" :state="state.kurikulum_id_state" @input="changeKurikulum">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-table-simple hover bordered responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">Nomor Paket</b-th>
                  <b-th class="text-center">Judul Paket (ID)</b-th>
                  <b-th class="text-center">Judul Paket (EN)</b-th>
                  <b-th class="text-center">Status</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="formulir in jumlah_form">
                  <b-tr>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nomor_paket[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_paket_id[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_paket_en[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-select v-model="form.status[formulir]" :options="data_status"></b-form-select>
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
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
    vSelect,
  },
  data() {
    return {
      showModal: false,
      loading_form: false,
      loading_kurikulum: false,
      data_jurusan: [],
      data_kurikulum: [],
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
      form: {
        user_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        paket_ukk_id: '',
        jurusan_id: '',
        kurikulum_id: '',
        nomor_paket: {},
        nama_paket_id: {},
        nama_paket_en: {},
        status: {},
        kode_unit: {},
        nama_unit: {},
      },
      state: {
        jurusan_id_feedback: '',
        jurusan_id_state: null,
        kurikulum_id_feedback: '',
        kurikulum_id_state: null,
      },
      jumlah_form: 5,
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.showModal = true
      this.data_jurusan = data.data_jurusan
    },
    addForm(){
      this.jumlah_form = this.jumlah_form + 1
      this.form.status[this.jumlah_form] = null
    },
    changeJurusan(val){
      this.form.kurikulum_id = ''
      if(val){
        this.state.jurusan_id_feedback = ''
        this.state.jurusan_id_state = true
        this.loading_kurikulum = true
        this.$http.post('/ukk/get-kurikulum', this.form).then(response => {
          this.loading_kurikulum = false
          let getData = response.data
          this.data_kurikulum = getData.kurikulum
        });
      }
    },
    changeKurikulum(val){
      if(val){
        this.state.kurikulum_id_feedback = ''
        this.state.kurikulum_id_state = true
      }
    },
    resetModal(){
      this.form.jurusan_id = ''
      this.state.jurusan_id_feedback = ''
      this.state.jurusan_id_state = null
      this.form.kurikulum_id = ''
      this.state.kurikulum_id_feedback = ''
      this.state.kurikulum_id_state = null
      this.form.nomor_paket = {}
      this.form.nama_paket_id = {}
      this.form.nama_paket_en = {}
      this.form.status = {}
      this.jumlah_form = 5
      this.data_jurusan = []
      this.data_kurikulum = []
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/ukk/simpan-ukk', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.jurusan_id_feedback = (getData.errors.jurusan_id) ? getData.errors.jurusan_id.join(', ') : ''
          this.state.jurusan_id_state = (getData.errors.jurusan_id) ? false : null
          this.state.kurikulum_id_feedback = (getData.errors.kurikulum_id) ? getData.errors.kurikulum_id.join(', ') : ''
          this.state.kurikulum_id_state = (getData.errors.kurikulum_id) ? false : null
        } else {
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
        }
      }).catch(error => {
        console.log(error);
      })
    },
  },
}
</script>