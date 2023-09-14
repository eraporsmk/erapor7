<template>
  <b-modal v-model="editKd" size="xl" title="Ubah Ringkasan Kompetensi Dasar" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
      <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-alert show variant="danger" v-if="message">
          <div class="alert-body text-center">
            {{message}}
          </div>
        </b-alert>
        <b-form-group label="Deskripsi Kompetensi Dasar Lama" label-for="kompetensi_dasar">
          <b-form-textarea id="kompetensi_dasar" v-model="form.kompetensi_dasar" placeholder="Deskripsi Kompetensi Dasar Lama" rows="3" max-rows="6" disabled></b-form-textarea>
        </b-form-group>
        <b-form-group label="Deskripsi Kompetensi Dasar Baru" label-for="kompetensi_dasar_alias-aktif" :invalid-feedback="feedback.kompetensi_dasar_alias" :state="state.kompetensi_dasar_alias">
          <b-form-textarea id="kompetensi_dasar_alias" v-model="form.kompetensi_dasar_alias" placeholder="Deskripsi Kompetensi Dasar Baru" rows="3" max-rows="6" :state="state.kompetensi_dasar_alias"></b-form-textarea>
        </b-form-group>
      </b-overlay>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
          <b-button variant="primary" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
</template>

<script>
import { BOverlay, BFormInput, BRow, BCol, BFormGroup, BButton, BFormTextarea, BAlert} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay, 
    BFormInput, 
    BRow, 
    BCol, 
    BFormGroup, 
    BButton, 
    BFormTextarea,
    BAlert,
    vSelect
  },
  data() {
    return {
      editKd: false,
      loading: false,
      loading_rombel: false,
      loading_mapel: false,
      loading_kompetensi: false,
      form: {
        kompetensi_dasar_id: '',
        kompetensi_dasar: '',
        kompetensi_dasar_alias: '',
      },
      state: {
        kompetensi_dasar: null,
        kompetensi_dasar_alias: null,
      },
      feedback: {
        kompetensi_dasar: '',
        kompetensi_dasar_alias: '',
      },
      message: null,
    }
  },
  created() {
    eventBus.$on('open-modal-edit-kd', this.handleEvent);
  },
  methods: {
    handleEvent(val){
      this.editKd = true
      this.form.kompetensi_dasar_id = val.kompetensi_dasar_id
      this.form.kompetensi_dasar = val.kompetensi_dasar
      this.form.kompetensi_dasar_alias = val.kompetensi_dasar_alias
    },
    hideModal(){
      this.editKd = false
      this.resetModal()
    },
    resetModal(){
      this.message = null
      this.form.kompetensi_dasar_id = ''
      this.form.kompetensi_dasar = ''
      this.form.kompetensi_dasar_alias = ''
      this.state.kompetensi_dasar = null
      this.state.kompetensi_dasar_alias = null
      this.feedback.kompetensi_dasar = ''
      this.feedback.kompetensi_dasar_alias = ''
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading = true
      this.$http.post('/referensi/update-kd', this.form).then(response => {
        this.loading = false
        let getData = response.data
        if(getData.errors){
          this.message = (getData.errors.kompetensi_dasar_id) ? getData.errors.kompetensi_dasar_id.join(', ') : ''
          this.state.kompetensi_dasar = (getData.errors.kompetensi_dasar) ? false : null
          this.state.kompetensi_dasar_alias = (getData.errors.kompetensi_dasar_alias) ? false : null
          this.feedback.kompetensi_dasar = (getData.errors.kompetensi_dasar) ? getData.errors.kompetensi_dasar.join(', ') : ''
          this.feedback.kompetensi_dasar_alias = (getData.errors.kompetensi_dasar_alias) ? getData.errors.kompetensi_dasar_alias.join(', ') : ''
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