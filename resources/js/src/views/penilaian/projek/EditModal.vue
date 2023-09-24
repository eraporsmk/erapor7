<template>
  <b-modal v-model="editModalShow" title="Edit Perencanaan Projek Pancasila" scrollable size="xl" @ok="handleOk">
    <b-table-simple bordered responsive v-if="data">
      <b-tr>
        <b-td>Kelas</b-td>
        <b-td>{{data.rombongan_belajar.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Tema</b-td>
        <b-td>{{data.pembelajaran.nama_mata_pelajaran}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Nama Projek</b-td>
        <b-td>
          <b-form-group label-for="nama_projek" :invalid-feedback="feedback.nama_projek" :state="state.nama_projek">
            <b-form-input id="nama_projek" v-model="form.nama_projek" :state="state.nama_projek" />
          </b-form-group>
        </b-td>
      </b-tr>
      <b-tr>
        <b-td>Deskripsi Projek</b-td>
        <b-td>
          <b-form-group label-for="deskripsi" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
            <b-form-textarea id="deskripsi" v-model="form.deskripsi" :state="state.deskripsi"></b-form-textarea>
          </b-form-group>
        </b-td>
      </b-tr>
      <b-tr>
        <b-td>Nomor Urut Projek</b-td>
        <b-td>
          <b-form-group label-for="deskripsi" :invalid-feedback="feedback.no_urut" :state="state.no_urut">
            <b-form-input id="deskripsi" v-model="form.no_urut" :state="state.no_urut"></b-form-input>
          </b-form-group>
        </b-td>
      </b-tr>
      <b-tr>
        <b-td>Dimensi/Elemen/Sub Elemen</b-td>
        <b-td>
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">Dimensi</b-th>
                <b-th class="text-center">Elemen</b-th>
                <b-th class="text-center">Sub Elemen</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="aspek_budaya_kerja in data.aspek_budaya_kerja">
                <b-tr>
                  <b-td>{{aspek_budaya_kerja.budaya_kerja.aspek}}</b-td>
                  <b-td>{{aspek_budaya_kerja.elemen_budaya_kerja.elemen}}</b-td>
                  <b-td>{{aspek_budaya_kerja.elemen_budaya_kerja.deskripsi}}</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-td>
      </b-tr>
    </b-table-simple>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="primary">
        <b-button variant="primary" @click="ok">Perbaharui</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="secondary">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { 
  BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BFormTextarea, BOverlay, BButton
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BFormTextarea, BOverlay, BButton
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    state: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      data: null,
      loading_form: false,
      editModalShow: false,
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-edit-projek', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data = data
      this.form.rencana_budaya_kerja_id = data.rencana_budaya_kerja_id
      this.form.nama_projek = data.nama
      this.form.deskripsi = data.deskripsi
      this.form.no_urut = data.no_urut
      this.editModalShow = true
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/penilaian/update-projek', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.nama_projek = (getData.errors.nama_projek) ? getData.errors.nama_projek.join(', ') : ''
          this.state.nama_projek = (getData.errors.nama_projek) ? false : null
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.feedback.no_urut = (getData.errors.no_urut) ? getData.errors.no_urut.join(', ') : ''
          this.state.no_urut = (getData.errors.no_urut) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.editModalShow = false
            this.$emit('reload');
            this.resetForm()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    resetForm(){
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      this.form.nama_projek = ''
      this.form.deskripsi = ''
      this.form.sub_elemen = {}
    },
  }
}
</script>