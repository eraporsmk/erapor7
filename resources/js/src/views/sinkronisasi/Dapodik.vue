<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <template v-if="!lengkap">
          <b-alert show variant="info">
            <div class="alert-body text-center">
              <h2>Pengaturan Web Services Dapodik</h2>
              <p>Untuk melakukan pengiriman nilai e-Rapor ke Dapodik, silahkan isi form dibawah ini:</p>
            </div>
          </b-alert>
          <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
            <b-form @submit.prevent="handleSubmit">
              <b-row>
                <b-col cols="12">
                  <b-form-group label="URL Dapodik" label-for="url_dapodik" label-cols-md="3" :invalid-feedback="url_dapodik_feedback" :state="url_dapodik_state">
                    <b-form-input id="url_dapodik" v-model="form.url_dapodik" :state="url_dapodik_state" placeholder="Contoh: http://localhost:5774 (tanpa garis miring di akhir!)"/>
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <b-form-group label="Token Dapodik" label-for="token_dapodik" label-cols-md="3" :invalid-feedback="token_dapodik_feedback" :state="token_dapodik_state">
                    <b-form-input id="token_dapodik" v-model="form.token_dapodik" :state="token_dapodik_state"/>
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="12">
                  <b-button type="submit" variant="primary">Cek Koneksi</b-button>
                </b-col>
              </b-row>
            </b-form>
          </b-overlay>
        </template>
        <template v-else>
          <b-tabs justified>
            <b-tab title="Rombongan Belajar">
              <b-card-text>
                <datatable-rombel :form="form" />
              </b-card-text>
            </b-tab>
            <b-tab title="Mata Evaluasi Rapor" @click="getMatev">
              <b-card-text>Tab contents 2</b-card-text>
            </b-tab>
            <b-tab title="Rekapitulasi" @click="getRekap">
              <b-card-text>
                <b-table-simple bordered responsive>
                  <b-thead>
                    <b-tr variant="light">
                      <b-th class="text-center">No</b-th>
                      <b-th class="text-center">Data</b-th>
                      <b-th class="text-center">Jml Data e-Rapor</b-th>
                      <b-th class="text-center">Jml Data Dapodik</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr>
                      <b-td class="text-center">1</b-td>
                      <b-td>Rombongan Belajar</b-td>
                      <b-td class="text-center">2</b-td>
                      <b-td class="text-center">3</b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </b-card-text>
            </b-tab>
          </b-tabs>
        </template>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BCardText, BSpinner, BRow, BCol, BOverlay, BForm, BFormGroup, BFormInput, BButton, BAlert, BTabs, BTab, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd } from 'bootstrap-vue'
import DatatableRombel from './datatables/Rombel.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BRow, 
    BCol, 
    BOverlay,
    BForm,
    BFormGroup, 
    BFormInput,
    BButton,
    BAlert,
    BTabs, 
    BTab,
    BCardText,
    BTableSimple, 
    BThead, 
    BTbody, 
    BTfoot, 
    BTr, 
    BTh, 
    BTd,
    DatatableRombel,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      lengkap: false,
      form: {
        user_id: '',
        sekolah_id: '',
        npsn: '',
        semester_id: '',
        periode_aktif: '',
        url_dapodik: '',
        token_dapodik: '',
      },
      url_dapodik_feedback: '',
      url_dapodik_state: null,
      token_dapodik_feedback: '',
      token_dapodik_state: null,
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.npsn = this.user.sekolah.npsn
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.isBusy = true
      this.$http.post('/sinkronisasi/nilai-dapodik', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.lengkap = (getData.url_dapodik) ? true : false
        this.form.url_dapodik = getData.url_dapodik
        this.form.token_dapodik = getData.token_dapodik
      })
    },
    handleSubmit(){
      this.loading = true
      this.$http.post('/sinkronisasi/cek-koneksi', this.form).then(response => {
        this.loading = false
        let getData = response.data
        if(getData.errors){
          this.url_dapodik_feedback = (getData.errors.url_dapodik) ? getData.errors.url_dapodik.join(', ') : ''
          this.url_dapodik_state = (getData.errors.url_dapodik) ? false : null
          this.token_dapodik_feedback = (getData.errors.token_dapodik) ? getData.errors.token_dapodik.join(', ') : ''
          this.token_dapodik_state = (getData.errors.token_dapodik) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostsData()
          })
        }
        console.log(getData);
      })
    },
    getMatev(){
      console.log('getMatev');
    },
    getRekap(){
      console.log('getRekap');
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>