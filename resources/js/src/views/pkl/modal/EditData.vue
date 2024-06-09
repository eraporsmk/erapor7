<template>
  <b-modal v-model="editRencana" title="Edit Perencanaan Penilaian PKL" size="xl">
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
        <b-form-group label="DUDI" label-for="dudi_id" label-cols-md="3" :invalid-feedback="feedback.dudi_id" :state="state.dudi_id">
          <b-overlay :show="loading_dudi" opacity="0.6" size="md" spinner-variant="secondary">
            <v-select id="dudi_id" v-model="form.dudi_id" :reduce="nama => nama.dudi_id" label="nama" :options="data_dudi" placeholder="== Pilih DUDI ==" @input="changeDudi" :state="state.dudi_id">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-overlay>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Perjanjian Kerja Sama (PKS)" label-for="akt_pd_id" label-cols-md="3" :invalid-feedback="feedback.akt_pd_id" :state="state.akt_pd_id">
          <b-overlay :show="loading_akt_pd" opacity="0.6" size="md" spinner-variant="secondary">
            <v-select id="akt_pd_id" v-model="form.akt_pd_id" :reduce="judul_akt_pd => judul_akt_pd.akt_pd_id" label="judul_akt_pd" :options="data_akt_pd" placeholder="== Pilih Perjanjian Kerja Sama (PKS) ==" @input="changeAktPd" :state="state.akt_pd_id">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-overlay>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Tanggal Mulai" label-for="tanggal_mulai" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai" :state="state.tanggal_mulai">
          <b-form-datepicker v-model="form.tanggal_mulai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai" @context="onContext" placeholder="== Pilih Tanggal Mulai ==" />
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Tanggal Selesai" label-for="tanggal_selesai" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai" :state="state.tanggal_selesai">
          <b-form-datepicker v-model="form.tanggal_selesai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai" @context="onContext" placeholder="== Pilih Tanggal Selesai ==" />
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Instruktur" label-for="instruktur" label-cols-md="3" :invalid-feedback="feedback.instruktur" :state="state.instruktur">
          <b-form-input id="instruktur" v-model="form.instruktur" placeholder="Nama Lengkap Instruktur" :state="state.instruktur" />
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="NIP" label-for="nip" label-cols-md="3">
          <b-form-input id="nip" v-model="form.nip" placeholder="NIP Instruktur (Jika ada)" />
        </b-form-group>
      </b-col>
      <b-col cols="12" v-if="show_tp">
        <b-form-group label="Tujuan Pembelajaran" label-cols-md="3" v-slot="{ ariaDescribedby }">
          <template v-if="data_tp.length">
            <b-form-checkbox-group v-model="form.tp_id" :options="data_tp" :aria-describedby="ariaDescribedby" name="data-tp" text-field ="deskripsi" value-field="tp_id" class="mb-1" stacked></b-form-checkbox-group>
          </template>
          <template v-else>
            <b-alert show variant="danger">
              <div class="alert-body text-center">
                <h2>Tidak ditemukan data Tujuan Pembelajaran</h2>
                <p>Silahkan tambah data Tujuan Pembelajaran terlebih dahulu <b-link to="/referensi/tujuan-pembelajaran">disini</b-link></p>
              </div>
            </b-alert>
          </template>
        </b-form-group>
      </b-col>
    </b-row>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="secodary">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="sm" spinner-variant="primary">
        <b-button variant="primary" @click="handleSubmit()">Perbaharui</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BRow, BCol, BSpinner, BButton, BOverlay, BFormGroup, BFormInput, BFormDatepicker, BFormCheckboxGroup, BAlert, BLink } from 'bootstrap-vue'
import vSelect from 'vue-select'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BRow, BCol, BSpinner, BButton, BOverlay, BFormGroup, BFormInput, BFormDatepicker, BFormCheckboxGroup, BAlert, BLink,
    vSelect,
  },
  data() {
    return {
      editRencana: false,
      loading_modal: false,
      loading_rombel: false,
      loading_dudi: false,
      loading_akt_pd: false,
      loading_tp: false,
      show_tp: false,
      form: {
        tahun_pelajaran: '',
        semester_id: '',
        sekolah_id: '',
        guru_id: '',
        tingkat: '',
        rombongan_belajar_id: '',
        dudi_id: '',
        akt_pd_id: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        instruktur: '',
        nip: '',
        tp_id: [],
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        dudi_id: null,
        akt_pd_id: null,
        tanggal_mulai: null,
        tanggal_selesai: null,
        instruktur: null,
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        dudi_id: '',
        akt_pd_id: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        instruktur: '',
      },
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
      data_akt_pd: [],
      data_tp: [],
    }
  },
  created() {
    this.form.tahun_pelajaran = this.user.semester.nama
    this.form.semester_id = this.user.semester.semester_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.guru_id = this.user.guru_id
    eventBus.$on('open-edit-pkl', this.handleEvent);
  },
  methods: {
    handleEvent(val){
      var data = val.pkl
      this.editRencana = true
      this.form.pkl_id = data.pkl_id
      this.form.tingkat = data.rombongan_belajar.tingkat
      this.form.rombongan_belajar_id = data.rombongan_belajar_id
      this.form.dudi_id = data.akt_pd.dudi.dudi_id
      this.form.akt_pd_id = data.akt_pd_id
      this.form.tanggal_mulai = data.tanggal_mulai
      this.form.tanggal_selesai = data.tanggal_selesai
      this.form.instruktur = data.instruktur
      this.form.nip = data.nip
      var tp_id = []
      data.tp_pkl.forEach(function(value, key) {
        tp_id.push(value.tp_id)
      })
      this.form.tp_id = tp_id
      this.changeTingkat(this.form.tingkat)
      this.changeRombel(this.form.rombongan_belajar_id)
      this.changeDudi(this.form.dudi_id)
      this.changeAktPd(this.form.akt_pd_id)
    },
    hideModal(){
      this.editRencana = false
      this.resetForm()
    },
    resetForm(){
      this.form.pkl_id = ''
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.dudi_id = ''
      this.form.akt_pd_id = ''
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''
      this.form.instruktur = ''
      this.form.nip = ''
      this.state.tingkat = null
      this.state.rombongan_belajar_id = null
      this.state.dudi_id = null
      this.state.akt_pd_id = null
      this.state.tanggal_mulai = null
      this.state.tanggal_selesai = null
      this.state.instruktur = null
      this.feedback.tingkat = ''
      this.feedback.rombongan_belajar_id = ''
      this.feedback.dudi_id = ''
      this.feedback.akt_pd_id = ''
      this.feedback.tanggal_mulai = ''
      this.feedback.tanggal_selesai = ''
      this.feedback.instruktur = ''
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_modal = true
      this.$http.post('/praktik-kerja-lapangan/update', this.form).then(response => {
        this.loading_modal = false
        var data = response.data
        if(data.errors){
          this.state.tingkat = (data.errors.tingkat) ? false : null
          this.state.rombongan_belajar_id = (data.errors.rombongan_belajar_id) ? false : null
          this.state.dudi_id = (data.errors.dudi_id) ? false : null
          this.state.akt_pd_id = (data.errors.akt_pd_id) ? false : null
          this.state.tanggal_mulai = (data.errors.tanggal_mulai) ? false : null
          this.state.tanggal_selesai = (data.errors.tanggal_selesai) ? false : null
          this.state.instruktur = (data.errors.instruktur) ? false : null
          this.feedback.tingkat = (data.errors.tingkat) ? data.errors.tingkat.join(', ') : ''
          this.feedback.rombongan_belajar_id = (data.errors.rombongan_belajar_id) ? data.errors.rombongan_belajar_id.join(', ') : ''
          this.feedback.dudi_id = (data.errors.dudi_id) ? data.errors.dudi_id.join(', ') : ''
          this.feedback.akt_pd_id = (data.errors.akt_pd_id) ? data.errors.akt_pd_id.join(', ') : ''
          this.feedback.tanggal_mulai = (data.errors.tanggal_mulai) ? data.errors.tanggal_mulai.join(', ') : ''
          this.feedback.tanggal_selesai = (data.errors.tanggal_selesai) ? data.errors.tanggal_selesai.join(', ') : ''
          this.feedback.instruktur = (data.errors.instruktur) ? data.errors.instruktur.join(', ') : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            text: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            if(data.icon !== 'error'){
              this.hideModal()
              this.$emit('reload')
            }
          });
        }
      })
    },
    changeTingkat(val){
      /*this.show_tp = false
      this.data_tp = []
      this.form.tp_id = []
      this.form.rombongan_belajar_id = ''
      this.form.dudi_id = ''
      this.form.akt_pd_id = ''
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''*/
      if(val){
        this.state.tingkat = null
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
      /*this.show_tp = false
      this.data_tp = []
      this.form.tp_id = []
      this.form.dudi_id = ''
      this.form.akt_pd_id = ''
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''*/
      if(val){
        this.state.rombongan_belajar_id = null
        this.loading_dudi = true
        this.$http.post('/praktik-kerja-lapangan/get-dudi', this.form).then(response => {
          this.loading_dudi = false
          let getData = response.data
          this.data_dudi = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeDudi(val){
      /*this.show_tp = false
      this.data_tp = []
      this.form.tp_id = []
      this.form.akt_pd_id = ''
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''*/
      if(val){
        this.state.dudi_id = null
        this.loading_akt_pd = true
        this.$http.post('/praktik-kerja-lapangan/get-akt-pd', this.form).then(response => {
          this.loading_akt_pd = false
          let getData = response.data
          this.data_akt_pd = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeAktPd(val){
      /*this.show_tp = false
      this.data_tp = []
      this.form.tp_id = []
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''*/
      if(val){
        this.state.akt_pd_id = null
        this.loading_tp = true
        this.$http.post('/praktik-kerja-lapangan/get-tp', this.form).then(response => {
          this.show_tp = true
          this.loading_tp = false
          let getData = response.data
          this.data_tp = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.custom-checkbox {
  margin-bottom: 5px !important;
}
</style>