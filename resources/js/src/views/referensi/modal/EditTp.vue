<template>
  <b-modal v-model="editTp" size="xl" title="Ubah Ringkasan Kompetensi Dasar" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form-group :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
        <b-form-textarea id="deskripsi" v-model="form.deskripsi" placeholder="Deskripsi Tujuan Pembelajaran" rows="3" max-rows="6" :state="state.deskripsi_state"></b-form-textarea>
      </b-form-group>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BFormInput, BFormGroup, BButton, BFormTextarea} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, 
    BFormInput, 
    BFormGroup, 
    BButton, 
    BFormTextarea,
  },
  data() {
    return {
      editTp: false,
      loading_form: false,
      form: {
        tp_id: '',
        deskripsi: '',
      },
      state: {
        deskripsi: null,
      },
      feedback: {
        deskripsi: '',
      },
    }
  },
  created() {
    eventBus.$on('open-modal-edit-tp', this.handleEvent);
  },
  methods: {
    handleEvent(val){
      this.editTp = true
      this.form.tp_id = val.tp_id
      this.form.deskripsi = val.deskripsi
    },
    hideModal(){
      this.editTp = false
      this.resetModal()
    },
    resetModal(){
      this.form.tp_id = ''
      this.form.deskripsi = ''
      this.state.deskripsi = null
      this.feedback.deskripsi = ''
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_form = true
      this.$http.post('/referensi/update-tp', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>