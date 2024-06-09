<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <validation-observer ref="simpleRules">
          <b-form>
            <b-row>
              <b-col cols="12">
                <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
                  <b-form-input id="tahun_pelajaran" :value="form.tahun_pelajaran" disabled />
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="meta.tingkat">
                  <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="meta.tingkat" @input="changeTingkat">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="meta.rombongan_belajar_id">
                  <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="meta.rombongan_belajar_id">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="DUDI" label-for="pkl_id" label-cols-md="3" :invalid-feedback="feedback.pkl_id" :state="meta.pkl_id">
                  <b-overlay :show="loading_pkl" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="pkl_id" v-model="form.pkl_id" :reduce="nama_dudi => nama_dudi.pkl_id" label="nama_dudi" :options="data_dudi" placeholder="== Pilih DUDI ==" :state="meta.pkl_id" @input="changePkl">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-overlay :show="loading_siswa" opacity="0.6" size="md" spinner-variant="secondary">
                  <template v-if="show && data_siswa.length  && data_tp.length">
                    <b-table-simple bordered striped responsive>
                      <b-thead>
                        <b-tr>
                          <b-th class="text-center">No</b-th>
                          <b-th class="text-center">Nama Peserta Didik</b-th>
                          <b-th class="text-center">NISN</b-th>
                          <b-th class="text-center">Tujuan Pembelajaran</b-th>
                          <b-th class="text-center">Nilai</b-th>
                          <b-th class="text-center">Deskripsi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <template v-for="(siswa, index) in data_siswa">
                          <b-tr>
                            <b-td class="text-center" :rowspan="data_tp.length + 2" style="vertical-align: top;">{{index + 1}}</b-td>
                            <b-td :rowspan="data_tp.length + 2" style="vertical-align: top;">{{siswa.nama}}</b-td>
                            <b-td :rowspan="data_tp.length + 2" class="text-center" style="vertical-align: top;">{{siswa.nisn}}</b-td>
                          </b-tr>
                          <template v-for="(tp, i) in data_tp">
                            <b-tr>
                              <b-td>{{ tp.deskripsi }}</b-td>
                              <b-td width="100">
                                <validation-provider #default="{ errors }" rules="integer|between:0,100" :custom-messages="{integer: 'Masukkan Angka', between: 'Angka 0-100'}">
                                  <b-form-input v-model="form.nilai[`${tp.tp_id}#${siswa.peserta_didik_id}`]" :state="errors.length > 0 ? false:null" placeholder="0-100" />
                                  <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                              </b-td>
                              <b-td>
                                <b-form-textarea title="Deskripsi ..." placeholder="Deskripsi ..." v-model="form.deskripsi[`${tp.tp_id}#${siswa.peserta_didik_id}`]"></b-form-textarea>
                              </b-td>
                            </b-tr>
                          </template>
                          <b-tr>
                            <b-td colspan="3">
                              <b-form-textarea title="Catatan PKL..." placeholder="Catatan PKL..." v-model="form.catatan[siswa.peserta_didik_id]"></b-form-textarea>
                            </b-td>
                          </b-tr>
                        </template>
                      </b-tbody>
                    </b-table-simple>
                  </template>
                </b-overlay>
              </b-col>
              <b-col cols="12" v-if="show && !data_siswa.length || show && !data_tp.length">
                <b-alert show variant="danger">
                  <div class="alert-body text-center">
                    <h2>Rencana penilaian PKL tidak ditemukan!</h2>
                    <p>Silahkan tambah Rencana penilaian PKL terlebih dahulu <b-link to="/pkl/perencanaan">disini</b-link></p>
                  </div>
                </b-alert>
              </b-col>
              <b-col cols="12" v-if="data_siswa.length && data_tp.length">
                <b-form-group label-cols-md="3">
                  <b-button type="submit" variant="primary" class="float-right ml-1" @click.prevent="validationForm">Simpan</b-button>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </validation-observer>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { BCard, BCardBody, BOverlay, BForm, BFormGroup, BFormInput, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, VBTooltip, BAlert, BLink} from 'bootstrap-vue'
import vSelect from 'vue-select'
import { integer, between } from '@validations'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
    BCard,
    BCardBody,
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BRow,
    BCol,
    BButton,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    VBTooltip,
    BAlert, 
    BLink,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      show: false,
      isBusy: false,
      loading_rombel: false,
      loading_pkl: false,
      loading_siswa: false,
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
        },
      ],
      data_rombel: [],
      data_dudi: [],
      data_siswa: [],
      data_tp: [],
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
        pkl_id: '',
        nilai: {},
        deskripsi: {},
        catatan: {},
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        pkl_id: '',
        nilai: {},
      },
      meta: {
        tingkat: null,
        rombongan_belajar_id: null,
        pkl_id: null,
        nilai: {},
      },
      integer, 
      between,
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.tahun_pelajaran = this.user.semester.nama
  },
  methods: {
    changeTingkat(val){
      this.show = false
      this.data_siswa = []
      this.data_tp = []
      this.form.rombongan_belajar_id = ''
      this.form.pkl_id = ''
      if(val){
        this.loading_rombel = true
        this.$http.post('/praktik-kerja-lapangan/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.show = false
      this.data_siswa = []
      this.data_tp = []
      this.form.pkl_id = ''
      if(val){
        this.loading_pkl = true
        this.$http.post('/praktik-kerja-lapangan/get-pkl', this.form).then(response => {
          this.loading_pkl = false
          let getData = response.data
          this.data_dudi = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changePkl(val){
      this.show = false
      this.data_siswa = []
      this.data_tp = []
      if(val){
        this.loading_siswa = true
        this.$http.post('/praktik-kerja-lapangan/get-siswa', this.form).then(response => {
          this.loading_siswa = false
          let getData = response.data
          this.data_siswa = getData.siswa
          this.data_tp = getData.tp
          var _this = this
          this.data_siswa.forEach(siswa => {
            siswa.nilai_pkl.forEach(nilai => {
              this.form.nilai[`${nilai.tp_id}#${siswa.peserta_didik_id}`] = nilai.nilai
              this.form.deskripsi[`${nilai.tp_id}#${siswa.peserta_didik_id}`] = nilai.deskripsi
            })
            this.form.catatan[siswa.peserta_didik_id] = siswa.pd_pkl.catatan
          });
          this.show = true
        }).catch(error => {
          console.log(error);
        })
      }
    },
    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          this.onSubmit()
        }
      })
    },
    onSubmit() {
      this.isBusy = true
      this.$http.post('/praktik-kerja-lapangan/simpan-nilai', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.onReset()
        })
      })
    },
    onReset() {
      this.show = false
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.pkl_id = ''
      this.form.nilai = {}
      this.form.deskripsi = {}
      this.form.catatan = {}
      this.data_siswa = []
      this.data_tp = []
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>