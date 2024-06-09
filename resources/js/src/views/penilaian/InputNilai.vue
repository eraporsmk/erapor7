<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <template v-if="status_penilaian">
          <b-form @submit="onSubmit">
            <formulir-nilai ref="formulir" :meta="meta" :form="form" @show_form="handleShowForm" :show_cp="show_cp" :show_sumatif="handleShowSumatif" @hide_form="handleHideForm" @show_cp="handleShowCp"></formulir-nilai>
            <b-row v-if="show">
              <b-col cols="12" class="mb-2" v-if="show_sumatif || data_tp.length">
                <b-row>
                  <b-col cols="6">
                    <b-form-group :invalid-feedback="template_excel_feedback" :state="template_excel_state">
                      <b-form-file v-model="template_excel" :state="template_excel_state" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
                    </b-form-group>
                  </b-col>
                  <b-col cols="6">
                    <b-button block variant="primary" :href="link_template_tp" target="_blank">Unduh Template {{ nama_template }}</b-button>
                  </b-col>
                </b-row>
              </b-col>
              <template v-if="show_sumatif">
                <show-sumatif :data_siswa="data_siswa" :form="form" @setRerata="setRerata"></show-sumatif>
              </template>
              <template v-else>
                <template v-if="data_tp.length">
                  <template v-if="show_cp">
                    <b-col cols="12">
                      <b-table-simple bordered striped responsive>
                        <b-thead>
                          <b-tr>
                            <b-th class="text-center">No</b-th>
                            <b-th class="text-center">Nama Peserta Didik</b-th>
                            <template v-for="(tp, i) in data_tp">
                              <b-th class="text-center"><span v-b-tooltip.hover :title="tp.deskripsi">{{`TP ${i+1}`}}</span></b-th>
                            </template>
                          </b-tr>
                        </b-thead>
                        <b-tbody>
                          <template v-for="(siswa, index) in data_siswa">
                            <b-tr>
                              <b-td class="text-center" style="vertical-align:middle">{{index + 1}}</b-td>
                              <b-td style="vertical-align:middle">{{siswa.nama}}</b-td>
                              <template v-for="(tp, i) in data_tp">
                                <b-td>
                                  <b-form-input v-model="form.nilai_tp[siswa.anggota_rombel.anggota_rombel_id+'#'+tp.tp_id]" />
                                </b-td>
                              </template>
                            </b-tr>
                          </template>
                        </b-tbody>
                      </b-table-simple>
                    </b-col>
                  </template>
                  <template v-else>
                    <b-col cols="12">
                      <b-table-simple bordered striped responsive>
                        <b-thead>
                          <b-tr>
                            <b-th class="text-center">No</b-th>
                            <b-th class="text-center">Nama Peserta Didik</b-th>
                            <b-th class="text-center">Nilai Akhir</b-th>
                            <b-th class="text-center">Ketercapaian Kompetensi</b-th>
                            <b-th class="text-center">Deskripsi Tujuan Pembelajaran</b-th>
                          </b-tr>
                        </b-thead>
                        <b-tbody>
                          <template v-for="(siswa, index) in data_siswa">
                            <b-tr>
                              <b-td class="text-center" style="vertical-align:top" :rowspan="data_tp.length + 1">{{index + 1}}</b-td>
                              <b-td :rowspan="data_tp.length + 1" style="vertical-align:top">{{siswa.nama}}</b-td>
                              <b-td :rowspan="data_tp.length + 1" style="vertical-align:top">
                                <b-form-input v-model="form.nilai[siswa.anggota_rombel.anggota_rombel_id]" />
                              </b-td>
                            </b-tr>
                            <template v-for="(tp, i) in data_tp">
                              <b-tr>
                                <b-td>
                                  <!--[siswa.anggota_rombel.anggota_rombel_id][tp.tp_id]-->
                                  <v-select append-to-body v-model="form.kompeten[siswa.anggota_rombel.anggota_rombel_id+'#'+tp.tp_id]" :reduce="nama => nama.id" label="nama" :options="data_capaian" placeholder="== Pilih Capaian ==" :searchable="false"></v-select>
                                </b-td>
                                <b-td>
                                  {{tp.deskripsi}}
                                </b-td>
                              </b-tr>
                            </template>
                          </template>
                        </b-tbody>
                      </b-table-simple>
                    </b-col>
                  </template>
                </template>
                <template v-else>
                  <b-col cols="12">
                    <b-alert show variant="danger">
                      <div class="alert-body text-center">
                        <h2>Tidak ditemukan data Tujuan Pembelajaran</h2>
                        <p>Silahkan tambah data Tujuan Pembelajaran terlebih dahulu <b-link to="/referensi/tujuan-pembelajaran">disini</b-link></p>
                      </div>
                    </b-alert>
                  </b-col>
                </template>
              </template>
              <b-col cols="12" v-if="show_sumatif || data_tp.length">
                <b-form-group label-cols-md="3">
                  <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </template>
        <template v-else>
          <b-alert show variant="danger">
            <div class="alert-body">
              <p>Penilaian tidak aktif. Silahkan hubungi administrator!</p>
            </div>
          </b-alert>
        </template>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BOverlay, BCardBody, BForm, BFormGroup, BFormInput, BFormFile, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BAlert, BLink, VBTooltip } from 'bootstrap-vue'
import FormulirNilai from './../components/FormulirNilai.vue'
import ShowSumatif from './../components/ShowSumatif.vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BOverlay,
    BCardBody,
    BForm,
    BFormGroup,
    BFormInput,
    BFormFile,
    BRow,
    BCol,
    BButton,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BAlert,
    BLink,
    VBTooltip,
    FormulirNilai,
    ShowSumatif,
    vSelect
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      status_penilaian: true,
      nama_template: '',
      show: false,
      show_cp: false,
      show_sumatif: false,
      isBusy: true,
      form: {
        tahun_pelajaran: '',
        tingkat: '',
        jenis_rombel: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        teknik_penilaian_id: '',
        cp_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        merdeka: false,
        nilai: {},
        nilai_tp: {},
        nilai_sumatif: {},
        kompeten: {},
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        jenis_rombel_feedback: '',
        jenis_rombel_state: null,
        rombongan_belajar_id_state : null,
        rombongan_belajar_id_feedback: '',
        pembelajaran_id_feedback: '',
        pembelajaran_id_state : null,
        teknik_penilaian_id_feedback: '',
        teknik_penilaian_id_state: null,
        cp_id_feedback: '',
        cp_id_state: null,
      },
      data_siswa: [],
      data_tp: [],
      template_excel: null,
      template_excel_feedback: '',
      template_excel_state: null,
      link_template_tp: '',
      data_capaian: [
        {
          id: '0',
          nama: 'Tidak tercapai',
        },
        {
          id: '1',
          nama: 'Tercapai',
        }
      ],
      opsi: '',
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.tahun_pelajaran = this.user.semester.nama
    this.isBusy = true
    this.$http.post(`/penilaian/status`, this.form).then(response => {
      this.status_penilaian = response.data
      this.isBusy = false
    })
  },
  methods: {
    setRerata(val){
      var nilai_nontes = val.non_tes
      var nilai_tes = val.tes
      var calculateAverage = 0;
      if(nilai_nontes && nilai_tes){
        calculateAverage = this.calculateAverage([parseInt(nilai_nontes), parseInt(nilai_tes)])
      } else if(nilai_nontes && !nilai_tes){
        calculateAverage = nilai_nontes
      } else if(!nilai_nontes && nilai_tes){
        calculateAverage = nilai_tes
      }
      this.form.nilai_sumatif[`${val.anggota_rombel_id}#na`] = calculateAverage
    },
    calculateAverage(array) {
      var total = 0;
      var count = 0;
      array.forEach(function(item, index) {
          total += item;
          count++;
      });
      var angka = total / count;
      return angka.toFixed(0);
    },
    onFileChange(e) {
      this.isBusy = true
      this.template_excel = e.target.files[0];
      const data = new FormData();
      data.append('template_excel', (this.template_excel) ? this.template_excel : '');
      data.append('rombongan_belajar_id', this.form.rombongan_belajar_id)
      data.append('pembelajaran_id', this.form.pembelajaran_id)
      data.append('sekolah_id', this.form.sekolah_id)
      data.append('merdeka', this.form.merdeka)
      data.append('opsi', this.opsi)
      this.$http.post('/penilaian/upload-nilai', data).then(response => {
        this.isBusy = false
        let data = response.data
        if(data.errors){
          this.template_excel_state = (data.errors.template_excel) ? false : null
          this.template_excel_feedback = (data.errors.template_excel) ? data.errors.template_excel.join(', ') : ''
        } else {
          this.template_excel = null
          var nilai_tp = {}
          var nilai_sumatif = {}
          var _this = this
          data.data_nilai.forEach(function(item, index) {
            if(_this.opsi == 'sumatif-lingkup-materi'){
              item.nilai.forEach(function(a, b){
                nilai_tp[item.anggota_rombel_id+'#'+a.tp] = a.angka
              })
            } else {
              nilai_sumatif[item.anggota_rombel_id+'#non-tes'] = item.NILAI_NON_TES
              nilai_sumatif[item.anggota_rombel_id+'#tes'] = item.NILAI_TES
              var calculateAverage = 0;
              if(item.NILAI_NON_TES && item.NILAI_TES){
                calculateAverage = _this.calculateAverage([item.NILAI_NON_TES, item.NILAI_TES])
              } else if(item.NILAI_NON_TES && !item.NILAI_TES){
                calculateAverage = item.NILAI_NON_TES
              } else if(!item.NILAI_NON_TES && item.NILAI_TES){
                calculateAverage = item.NILAI_TES
              }
              nilai_sumatif[item.anggota_rombel_id+'#na'] = calculateAverage
            }
          })
          if(this.opsi == 'sumatif-lingkup-materi'){
            this.form.nilai_tp = nilai_tp
          } else {
            this.form.nilai_sumatif = nilai_sumatif
          }
        }
      });
    },
    handleShowCp(val){
      this.show_cp = val
    },
    handleShowSumatif(val){
      this.show_sumatif = val
    },
    handleShowForm(opsi){
      this.opsi = opsi
      this.link_template_tp = `/downloads/template-${opsi}/${this.form.pembelajaran_id}`
      this.isBusy = true
      this.$http.post(`/penilaian/get-${opsi}`, this.form).then(response => {
        this.isBusy = false
        this.show = true
        let getData = response.data
        this.data_siswa = getData.data.data_siswa
        if(opsi == 'sumatif-lingkup-materi'){
          this.data_tp = getData.data.data_tp
          this.show_sumatif = false
          this.nama_template = 'Sumatif Lingkup Materi'
        }
        if(opsi == 'sumatif-akhir-semester'){
          this.show_sumatif = true
          this.nama_template = 'Sumatif Akhir Semester'
        }
        var nilai = {}
        var nilai_tp = {}
        var nilai_sumatif = {}
        var kompeten = {}
        this.data_siswa.forEach(function(value, key) {
          if(opsi == 'nilai-akhir'){
            nilai[value.anggota_rombel.anggota_rombel_id] = (value.anggota_rombel.nilai_akhir_mapel) ? value.anggota_rombel.nilai_akhir_mapel.nilai : ''
            value.anggota_rombel.capaian_kompeten.forEach(function(capaian, index) {
              kompeten[value.anggota_rombel.anggota_rombel_id+'#'+capaian.tp_id] = capaian.kompeten
            })
          }
          if(opsi == 'sumatif-lingkup-materi'){
            value.anggota_rombel.nilai_tp.forEach(function(tp, index) {
              nilai_tp[value.anggota_rombel.anggota_rombel_id+'#'+tp.tp_id] = tp.nilai
            })
          }
          if(opsi == 'sumatif-akhir-semester'){
            value.anggota_rombel.nilai_sumatif.forEach(function(sumatif, index) {
              nilai_sumatif[value.anggota_rombel.anggota_rombel_id+'#'+sumatif.jenis] = sumatif.nilai
            })
          }
        })
        this.form.nilai_sumatif = nilai_sumatif
        this.form.nilai_tp = nilai_tp
        this.form.nilai = nilai
        this.form.kompeten = kompeten
      })
    },
    handleHideForm(){
      this.show = false
    },
    onSubmit(event) {
      event.preventDefault()
      this.isBusy = true
      this.$http.post(`/penilaian/simpan-${this.opsi}`, this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.handleHideForm()
          this.onReset()
        })
      })
    },
    onReset() {
      //event.preventDefault()
      this.handleHideForm()
      this.form.tahun_pelajaran = this.user.semester.nama
      this.form.tingkat = ''
      this.form.jenis_rombel = ''
      this.form.rombongan_belajar_id = ''
      this.form.merdeka = false
      this.form.pembelajaran_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.teknik_penilaian_id = ''
      this.form.cp_id= ''
      this.form.nilai = {}
      this.form.kompeten = {}
      this.data_siswa = []
      this.data_tp = []
      this.form.nilai_tp = {}
      this.meta.tingkat_feedback = ''
      this.meta.tingkat_state = null
      this.meta.jenis_rombel_feedback = ''
      this.meta.jenis_rombel_state = null
      this.meta.rombongan_belajar_id_state = null
      this.meta.pembelajaran_id_state = null
      this.meta.rombongan_belajar_id_feedback = ''
      this.meta.pembelajaran_id_feedback = ''
      this.$refs.formulir.setValue();
      this.show_cp = false
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>