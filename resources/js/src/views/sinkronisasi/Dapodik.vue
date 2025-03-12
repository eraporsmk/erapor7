<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <template v-if="!lengkap">
          <b-alert show variant="danger">
            <div class="alert-body">
              <h1>Informasi Penting</h1>
              <p>Prioritaskan pengiriman nilai semester pada tingkat akhir, nilai rapor 5 semester untuk siswa kelas 12
                dan 7 semester untuk siswa kelas 13 ke Dapodik</p>
            </div>
          </b-alert>
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
                  <b-form-group label="URL Dapodik" label-for="url_dapodik" label-cols-md="3"
                    :invalid-feedback="url_dapodik_feedback" :state="url_dapodik_state">
                    <b-form-input id="url_dapodik" v-model="form.url_dapodik" :state="url_dapodik_state"
                      placeholder="Contoh: http://localhost:5774 (tanpa garis miring di akhir!)" />
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <b-form-group label="Token Dapodik" label-for="token_dapodik" label-cols-md="3"
                    :invalid-feedback="token_dapodik_feedback" :state="token_dapodik_state">
                    <b-form-input id="token_dapodik" v-model="form.token_dapodik" :state="token_dapodik_state" />
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
          <template v-if="dapodik">
            <template v-if="dapodik.results">
              <b-alert show variant="danger">
                <div class="alert-body">
                  <h1>Informasi Penting</h1>
                  <p>Prioritaskan pengiriman nilai semester pada tingkat akhir, nilai rapor 5 semester untuk siswa kelas
                    12 dan 7 semester untuk siswa kelas 13 ke Dapodik</p>
                </div>
              </b-alert>
              <b-tabs justified>
                <b-tab title="Rombongan Belajar">
                  <b-card-text>
                    <rombel-dapodik :form="form" />
                  </b-card-text>
                </b-tab>
                <b-tab title="Mata Evaluasi Rapor" @click="getMatev">
                  <matev-rapor :form="form" :loading="loading" :isBusy="false" :items="items" :fields="fields"
                    :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch"
                    @sort="handleSort" />
                </b-tab>
              </b-tabs>
            </template>
            <template v-else>
              <b-alert show variant="danger">
                <div class="alert-body text-center">
                  <h2>Koneksi ke Dapodik Gagal!</h2>
                  <p>{{ dapodik.message }}</p>
                  <p>Silahkan periksa kembali pengaturan Web Service Dapodik</p>
                </div>
              </b-alert>
            </template>
          </template>
        </template>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BCardText, BSpinner, BRow, BCol, BOverlay, BForm, BFormGroup, BFormInput, BButton, BAlert, BTabs, BTab, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd } from 'bootstrap-vue'
import RombelDapodik from './datatables/RombelDapodik.vue'
import MatevRapor from './datatables/MatevRapor.vue'
export default {
  components: {
    RombelDapodik,
    MatevRapor,
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
  },
  data() {
    return {
      isBusy: true,
      isBusyMatev: true,
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
      items: [],
      fields: [
        {
          key: 'nm_mata_evaluasi',
          label: 'Mata Pelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'guru',
          label: 'Guru Mapel',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'no_urut',
          label: 'Nomor Urut',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'status',
          label: 'Status',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      isBusy: true,
      loading: false,
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'mata_pelajaran_id',
      sortByDesc: false,
      sortDesc: false,
      dapodik: null,
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
    loadPostsData() {
      this.isBusy = true
      this.$http.post('/sinkronisasi/nilai-dapodik', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.lengkap = (getData.url_dapodik) ? true : false
        this.form.url_dapodik = getData.url_dapodik
        this.form.token_dapodik = getData.token_dapodik
        this.dapodik = getData.dapodik
      })
    },
    getMatevRapor() {
      this.loading = true
      let current_page = this.current_page
      this.$http.get('/sinkronisasi/get-matev-rapor', {
        params: {
          user_id: this.form.user_id,
          sekolah_id: this.form.sekolah_id,
          semester_id: this.form.semester_id,
          periode_aktif: this.form.periode_aktif,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.isBusyMatev = false
        this.loading = false
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
          role_id: this.role_id,
          roles: response.data.roles,
        }
      })
    },
    handleSubmit() {
      this.loading = true
      this.$http.post('/sinkronisasi/cek-koneksi', this.form).then(response => {
        this.loading = false
        let getData = response.data
        if (getData.errors) {
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
      })
    },
    getMatev() {
      this.getMatevRapor()
    },
    handlePerPage(val) {
      this.per_page = val
      this.getMatevRapor()
    },
    handlePagination(val) {
      this.current_page = val
      this.getMatevRapor()
    },
    handleSearch(val) {
      this.search = val
      this.getMatevRapor()
    },
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.getMatevRapor()
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>