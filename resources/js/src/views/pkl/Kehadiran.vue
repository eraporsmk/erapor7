<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <b-form @submit="onSubmit">
          <b-row>
            <b-col cols="12">
              <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
                <b-form-input id="tahun_pelajaran" :value="form.tahun_pelajaran" disabled />
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
                <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="state.tingkat" @input="changeTingkat">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
                <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                  <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="state.rombongan_belajar_id">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-overlay>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="DUDI" label-for="pkl_id" label-cols-md="3" :invalid-feedback="feedback.pkl_id" :state="state.pkl_id">
                <b-overlay :show="loading_pkl" opacity="0.6" size="md" spinner-variant="secondary">
                  <v-select id="pkl_id" v-model="form.pkl_id" :reduce="nama_dudi => nama_dudi.pkl_id" label="nama_dudi" :options="data_dudi" placeholder="== Pilih Mata Pelajaran ==" :state="state.pkl_id" @input="changePkl">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-overlay>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-overlay :show="loading_siswa" opacity="0.6" size="md" spinner-variant="secondary">
                <template v-if="show && data_siswa.length">
                  <b-table-simple bordered striped responsive>
                    <b-thead>
                      <b-tr>
                        <b-th class="text-center">No</b-th>
                        <b-th class="text-center">Nama Peserta Didik</b-th>
                        <b-th class="text-center">NISN</b-th>
                        <b-th class="text-center">Sakit</b-th>
                        <b-th class="text-center">Izin</b-th>
                        <b-th class="text-center">Alpa</b-th>
                      </b-tr>
                    </b-thead>
                    <b-tbody>
                      <template v-for="(siswa, index) in data_siswa">
                        <b-tr>
                          <b-td class="text-center">{{index + 1}}</b-td>
                          <b-td>{{siswa.nama}}</b-td>
                          <b-td>{{siswa.nisn}}</b-td>
                          <b-td>
                            <b-form-input v-model="form.sakit[siswa.peserta_didik_id]" :state="state.sakit[siswa.peserta_didik_id]" :title="feedback.sakit[siswa.peserta_didik_id]" @keypress="isNumber($event)" />
                          </b-td>
                          <b-td>
                            <b-form-input v-model="form.izin[siswa.peserta_didik_id]" :state="state.izin[siswa.peserta_didik_id]" :title="feedback.izin[siswa.peserta_didik_id]" @keypress="isNumber($event)" />
                          </b-td>
                          <b-td>
                            <b-form-input v-model="form.alpa[siswa.peserta_didik_id]" :state="state.alpa[siswa.peserta_didik_id]" :title="feedback.alpa[siswa.peserta_didik_id]" @keypress="isNumber($event)" />
                          </b-td>
                        </b-tr>
                      </template>
                    </b-tbody>
                  </b-table-simple>
                </template>
              </b-overlay>
            </b-col>
            <b-col cols="12" v-if="show && !data_siswa.length">
              <p class="text-center">Rencana penilaian PKL tidak ditemukan!</p>
            </b-col>
            <b-col cols="12" v-if="data_siswa.length">
              <b-form-group label-cols-md="3">
                <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
              </b-form-group>
            </b-col>
          </b-row>
        </b-form>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BForm, BFormGroup, BFormInput, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    vSelect,
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BButton,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    BRow,
    BCol,
  },
  data() {
    return {
      isBusy: false,
      show: false,
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
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
        pkl_id: '',
        sakit: {},
        izin: {},
        alpa: {},
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        pkl_id: '',
        sakit: {},
        izin: {},
        alpa: {},
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        pkl_id: null,
        sakit: {},
        izin: {},
        alpa: {},
      },
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
      if(val){
        this.loading_siswa = true
        this.$http.post('/praktik-kerja-lapangan/get-siswa', this.form).then(response => {
          this.loading_siswa = false
          this.show = true
          let getData = response.data
          this.data_siswa = getData.siswa
          this.data_siswa.forEach(siswa => {
            this.state.sakit[siswa.peserta_didik_id] = null
            this.state.izin[siswa.peserta_didik_id] = null
            this.state.alpa[siswa.peserta_didik_id] = null
            this.feedback.sakit[siswa.peserta_didik_id] = ''
            this.feedback.izin[siswa.peserta_didik_id] = ''
            this.feedback.alpa[siswa.peserta_didik_id] = ''
            this.form.sakit[siswa.peserta_didik_id] = siswa.absensi_pkl?.sakit
            this.form.izin[siswa.peserta_didik_id] = siswa.absensi_pkl?.izin
            this.form.alpa[siswa.peserta_didik_id] = siswa.absensi_pkl?.alpa
          });
        }).catch(error => {
          console.log(error);
        })
      }
    },
    onSubmit(event) {
      event.preventDefault()
      this.isBusy = true
      /*var error = 0;
      Object.keys(this.form.sakit).forEach(peserta_didik_id => {
        if(this.form.sakit[peserta_didik_id] > 0 && !Number.isInteger(this.form.sakit[peserta_didik_id])){
          this.state.sakit[peserta_didik_id] = false
          this.feedback.sakit[peserta_didik_id] = 'Inputan harus berupa angka'
          error++;
        }
      });
      Object.keys(this.form.izin).forEach(peserta_didik_id => {
        if(this.form.izin[peserta_didik_id] > 0 && !Number.isInteger(this.form.izin[peserta_didik_id])){
          this.state.izin[peserta_didik_id] = false
          this.feedback.izin[peserta_didik_id] = 'Inputan harus berupa angka'
          error++;
        }
      });
      Object.keys(this.form.alpa).forEach(peserta_didik_id => {
        console.log((this.form.alpa[peserta_didik_id] > 0 && !Number.isInteger(this.form.alpa[peserta_didik_id])));
        if(this.form.alpa[peserta_didik_id] > 0 && !Number.isInteger(this.form.alpa[peserta_didik_id])){
          this.state.alpa[peserta_didik_id] = false
          this.feedback.alpa[peserta_didik_id] = 'Inputan harus berupa angka'
          error++;
        }
      });
      if(error){
        this.isBusy = false
        return false
      }*/
      this.$http.post('/praktik-kerja-lapangan/simpan-absensi', this.form).then(response => {
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
      this.form.sakit = {}
      this.form.izin = {}
      this.form.alpa = {}
      this.data_siswa = []
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>