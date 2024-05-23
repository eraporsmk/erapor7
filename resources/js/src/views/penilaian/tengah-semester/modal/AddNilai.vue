<template>
  <b-modal v-model="openModalAddPts" :title="title" @hidden="hideModal" ok-only ok-title="Batal" ok-variant="secondary">
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="12" class="mb-2">
          <b-button block variant="warning" :href="link_excel" target="_blank">UNDUH TEMPLATE</b-button>
        </b-col>
        <b-col cols="12">
          <b-form-file v-model="file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange" :state="fileState" />
          <p v-show="feedback_file" class="text-danger">{{feedback_file}}</p>
        </b-col>
      </b-row>
    </b-overlay>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormFile, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton, } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormFile, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
  },
  props: {
    title: {
      required: true,
    },
  },
  data() {
    return {
      link_excel: '',
      simpan: false,
      openModalAddPts: false,
      loading: false,
      pembelajaran_id: '',
      file: null,
      fileState: null,
      feedback_file: '',
    }
  },
  created() {
    eventBus.$on('open-modal-add-nilai-pts', this.handleEvent);
  },
  methods: {
    handleEvent(pembelajaran_id){
      this.pembelajaran_id = pembelajaran_id
      this.link_excel = `/downloads/template-nilai-pts/${pembelajaran_id}`
      this.openModalAddPts = true
    },
    hideModal(){
      this.resetModal()
    },
    resetModal(){
      this.link_excel = ''
      this.simpan = false
      this.openModalAddPts = false
      this.loading = false
      this.pembelajaran_id = ''
      this.file = null
      this.fileState = null
      this.feedback_file = ''
    },
    onFileChange(e) {
      this.loading = true
      this.simpan = false
      this.file = e.target.files[0];
      const data = new FormData();
      data.append('file_excel', (this.file) ? this.file : '');
      data.append('pembelajaran_id', (this.pembelajaran_id) ? this.pembelajaran_id : '');
      this.$http.post('/penilaian/upload-nilai-pts', data).then(response => {
        //this.$emit('loading', false)
        this.loading = false
        let data = response.data
        this.fileState = null
        this.feedback_file = ''
        if(data.errors){
          this.fileState = (data.errors.file_excel) ? false : null
          this.feedback_file = (data.errors.file_excel) ? data.errors.file_excel.join(', ') : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            text: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      }).catch(error => {
        console.log(error);
        this.fileState = false
        this.feedback_file = 'Isian salah. Silahkan periksa kembali!!!'
      })
    },
  },
}
</script>