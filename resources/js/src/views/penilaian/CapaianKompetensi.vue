<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <template v-if="status_penilaian">
          <b-form @submit="onSubmit">
            <formulir ref="formulir" :meta="meta" :form="form" @show_form="handleShowForm" @hide_form="handleHideForm"></formulir>
            <b-row v-if="show">
              <b-col cols="12" class="mb-2" v-if="show_reset">
                <b-form-group label="Reset Capaian Kompetensi" label-for="tingkat" label-cols-md="3">
                  <b-button variant="danger" @click="resetData">Reset Capaian Kompetensi</b-button>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-table-simple bordered striped responsive>
                  <b-thead>
                    <b-tr>
                      <b-th class="text-center" rowspan="2" style="vertical-align:middle">No</b-th>
                      <b-th class="text-center" rowspan="2" style="vertical-align:middle">Nama Peserta Didik</b-th>
                      <b-th class="text-center" rowspan="2" style="vertical-align:middle">Nilai Akhir</b-th>
                      <b-th class="text-center" colspan="2">Capaian Kompetensi</b-th>
                    </b-tr>
                    <b-tr>
                      <b-th class="text-center">Kompetensi yang sudah dicapai</b-th>
                      <b-th class="text-center">Kompetensi yang perlu ditingkatkan</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <template v-for="(siswa, index) in data_siswa">
                      <b-tr>
                        <b-td class="text-center">{{index + 1}}</b-td>
                        <b-td>{{siswa.nama}}</b-td>
                        <b-td class="text-center">
                          {{form.nilai[siswa.anggota_rombel.anggota_rombel_id]}}
                        </b-td>
                        <b-td class="text-center">
                          <b-form-textarea v-model="form.kompeten[siswa.anggota_rombel.anggota_rombel_id]" rows="5"></b-form-textarea>
                        </b-td>
                        <b-td class="text-center">
                          <b-form-textarea v-model="form.inkompeten[siswa.anggota_rombel.anggota_rombel_id]" rows="5"></b-form-textarea>
                        </b-td>
                      </b-tr>
                    </template>
                  </b-tbody>
                </b-table-simple>
              </b-col>
              <b-col cols="12">
                <b-form-group label-cols-md="3">
                  <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
                </b-form-group>
              </b-col>
            </b-row>
            <template v-if="nonAktif">
              <b-alert show variant="danger">
                <div class="alert-body">
                  <p>Penilaian tidak aktif. Silahkan hubungi administrator!</p>
                </div>
              </b-alert>
            </template>
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
import { BCard, BOverlay, BCardBody, BForm, BFormGroup, BFormInput, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BAlert } from 'bootstrap-vue'
import Formulir from './../components/Formulir.vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BOverlay,
    BCardBody,
    BForm,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BRow,
    BCol,
    BButton,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BAlert,
    Formulir,
    vSelect
  },
  data() {
    return {
      status_penilaian: true,
      show_reset: 0,
      show: false,
      isBusy: true,
      form: {
        tahun_pelajaran: '',
        tingkat: '',
        jenis_rombel: '',
        rombongan_belajar_id: '',
        mata_pelajaran_id: '',
        pembelajaran_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        merdeka: false,
        nilai: {},
        kompeten: {},
        inkompeten: {},
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        jenis_rombel_feedback: '',
        jenis_rombel_state: null,
        rombongan_belajar_id_state : null,
        pembelajaran_id_state : null,
        rombongan_belajar_id_feedback: '',
        pembelajaran_id_feedback: '',
      },
      data_siswa: [],
      template_excel: null,
      template_excel_feedback: '',
      template_excel_state: null,
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
      nonAktif: false,
    }
  },
  created() {
    //this.tahun_pelajaran = this.user.semester.nama
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
    handleShowForm(val){
      if(!val){
        this.isBusy = true
        this.$http.post('/penilaian/get-capaian-kompetensi', this.form).then(response => {
          this.isBusy = false
          let getData = response.data
          this.show_reset = getData.data.show_reset
          this.data_siswa = getData.data.data_siswa
          if(this.data_siswa.length){
            this.show = true
          }
          var nilai = {}
          var kompeten = {}
          var inkompeten = {}
          var _this = this
          this.data_siswa.forEach(function(value, key) {
            nilai[value.anggota_rombel.anggota_rombel_id] = (value.anggota_rombel.nilai_akhir_mapel) ? value.anggota_rombel.nilai_akhir_mapel.nilai : ''
            if(value.anggota_rombel.single_deskripsi_mata_pelajaran){
              kompeten[value.anggota_rombel.anggota_rombel_id] = value.anggota_rombel.single_deskripsi_mata_pelajaran.deskripsi_pengetahuan
              inkompeten[value.anggota_rombel.anggota_rombel_id] = value.anggota_rombel.single_deskripsi_mata_pelajaran.deskripsi_keterampilan
            } else {
              var tempTpKompeten = []
              var tempTpInKompeten = []
              if(value.anggota_rombel.tp_kompeten.length){
                value.anggota_rombel.tp_kompeten.forEach(function(tp_kompeten, index_kompeten) {
                  tempTpKompeten.push(_this.convertUTF7toUTF8(_this.lcfirst(tp_kompeten.tp.deskripsi)))
                })
                kompeten[value.anggota_rombel.anggota_rombel_id] = 'Menunjukkan penguasaan yang baik dalam '+tempTpKompeten.join(', ')
              } else {
                kompeten[value.anggota_rombel.anggota_rombel_id] = null
              }
              if(value.anggota_rombel.tp_inkompeten.length){
                value.anggota_rombel.tp_inkompeten.forEach(function(tp_inkompeten, index_inkompeten) {
                  tempTpInKompeten.push(_this.convertUTF7toUTF8(_this.lcfirst(tp_inkompeten.tp.deskripsi)))
                })
                inkompeten[value.anggota_rombel.anggota_rombel_id] = 'Perlu ditingkatkan dalam '+tempTpInKompeten.join(', ')
              } else {
                inkompeten[value.anggota_rombel.anggota_rombel_id] = null
              }
            }
          })
          this.form.nilai = nilai
          this.form.kompeten = kompeten
          this.form.inkompeten = inkompeten
        })
      } else {
        this.nonAktif = true
      }
    },
    lcfirst(string){
      return string.charAt(0).toLowerCase() + string.slice(1);
    },
    convertUTF7toUTF8(string) {
      var b64Token = /\+([a-z\d\/+]*\-?)/gi,
          hex, len, replace, i;

      return string.replace(b64Token, function(match, grp) {
          hex = Buffer(grp, 'base64');
          len = hex.length >> 1 << 1;
          replace = '';
          i = 1;

          for(i; i < len; i = i + 2) {
              replace += String.fromCharCode(hex.readUInt16BE(i - 1));
          }
          return replace;
      });
    },
    handleHideForm(){
      this.show = false
    },
    onSubmit(event) {
      event.preventDefault()
      this.isBusy = true
      this.$http.post('/penilaian/simpan-capaian-kompetensi', this.form).then(response => {
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
      this.form.nilai = {}
      this.form.kompeten = {}
      this.form.inkompeten = {}
      this.data_siswa = []
      this.meta.tingkat_feedback = ''
      this.meta.tingkat_state = null
      this.meta.jenis_rombel_feedback = ''
      this.meta.jenis_rombel_state = null
      this.meta.rombongan_belajar_id_state = null
      this.meta.pembelajaran_id_state = null
      this.meta.rombongan_belajar_id_feedback = ''
      this.meta.pembelajaran_id_feedback = ''
      this.$refs.formulir.setValue();
    },
    resetData(){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.isBusy = true
          this.$http.post('/penilaian/reset-capaian-kompetensi', this.form).then(response => {
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
              this.handleHideForm()
            this.onReset()
            })
          });
        }
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>