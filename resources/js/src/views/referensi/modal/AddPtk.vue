<template>
  <b-modal v-model="addPtk" size="xl" :title="title" @show="resetModal" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" cancel-title="Batal">
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="8">
          <b-form-file v-model="file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange" :state="fileState" />
          <p v-show="feedback_file" class="text-danger">{{feedback_file}}</p>
        </b-col>
          <b-col cols="4">
          <b-button block variant="warning" :href="link_excel" target="_blank">UNDUH TEMPLATE</b-button>
        </b-col>
      </b-row>
      <b-row class="mt-2" v-if="imported_data.length">
        <b-col cols="12">
          <b-table-simple hover bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Nama</b-th>
                <b-th class="text-center">NUPTK</b-th>
                <b-th class="text-center">NIP</b-th>
                <b-th class="text-center">NIK</b-th>
                <b-th class="text-center">Jenis Kelamin</b-th>
                <b-th class="text-center">Tempat Lahir</b-th>
                <b-th class="text-center">Tanggal Lahir</b-th>
                <b-th class="text-center">Agama</b-th>
                <b-th class="text-center">Alamat Jalan</b-th>
                <b-th class="text-center">RT</b-th>
                <b-th class="text-center">RW</b-th>
                <b-th class="text-center">Desa/Kelurahan</b-th>
                <b-th class="text-center">Kecamatan</b-th>
                <b-th class="text-center">Kodepos</b-th>
                <b-th class="text-center">Telp/HP</b-th>
                <b-th class="text-center">Email</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="(item, index) in imported_data" :key="item.no">
                <b-td class="text-center">{{item.no}}</b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.nama[item.no]" :state="state.nama[item.no]">
                    <b-form-input v-model="form.nama[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.nuptk[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.nip[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.nik[item.no]" :state="state.nik[item.no]">
                    <b-form-input v-model="form.nik[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.jenis_kelamin[item.no]" :state="state.jenis_kelamin[item.no]">
                    <b-form-input v-model="form.jenis_kelamin[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.tempat_lahir[item.no]" :state="state.tempat_lahir[item.no]">
                    <b-form-input v-model="form.tempat_lahir[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.tanggal_lahir[item.no]" :state="state.tanggal_lahir[item.no]">
                    <b-form-input v-model="form.tanggal_lahir[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.agama[item.no]" :state="state.agama[item.no]">
                    <b-form-input v-model="form.agama[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.alamat_jalan[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.rt[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.rw[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.desa_kelurahan[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.kecamatan[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.kodepos[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group>
                    <b-form-input v-model="form.telp_hp[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
                <b-td>
                  <b-form-group :invalid-feedback="feedback.email[item.no]" :state="state.email[item.no]">
                    <b-form-input v-model="form.email[item.no]"></b-form-input>
                  </b-form-group>
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading" rounded opacity="0.6" size="sm" spinner-variant="secondary">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading" rounded opacity="0.6" size="sm" spinner-variant="primary" v-if="simpan">
        <b-button variant="primary" @click="ok()" v-if="simpan">Simpan</b-button>
      </b-overlay>
    </template>
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
    link_excel: {
      required: true,
    },
    jenis_gtk: {
      required: true,
    },
  },
  data() {
    return {
      simpan: false,
      addPtk: false,
      loading: false,
      form: {
        sekolah_id: '',
        nama: {},
        nuptk: {},
        nip: {},
        nik: {},
        jenis_kelamin: {},
        tempat_lahir: {},
        tanggal_lahir: {},
        agama: {},
        alamat_jalan: {},
        rt: {},
        rw: {},
        desa_kelurahan: {},
        kecamatan: {},
        kodepos: {},
        telp_hp: {},
        email: {},
      },
      state: {
        nama: null,
        nik: null,
        tanggal_lahir: null,
        agama: null,
        email: null,
        jenis_kelamin: null
      },
      feedback: {
        nama: '',
        nik: '',
        tanggal_lahir: '',
        agama: '',
        email: '',
        jenis_kelamin: ''
      },
      file: null,
      fileState: null,
      feedback_file: '',
      imported_data: [],
      data_status: [
        { value: null, text: '== Pilih Status ==' },
        { value: '1', text: 'Aktif' },
        { value: '0', text: 'Tidak Aktif' },
      ],
    }
  },
  created() {
    this.form.tahun_pelajaran = this.user.semester.nama
    this.form.jenis_gtk = this.jenis_gtk
    this.form.sekolah_id = this.user.sekolah_id
    eventBus.$on('open-modal-ptk', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.addPtk = true
    },
    hideModal(){
      this.addPtk = false
      this.resetModal()
    },
    resetModal(){
      /*this.form.sekolah_id = ''
      this.form.nama = {}
      this.form.nuptk = {}
      this.form.nip = {}
      this.form.nik = {}
      this.form.jenis_kelamin = {}
      this.form.tempat_lahir = {}
      this.form.tanggal_lahir = {}
      this.form.agama = {}
      this.form.alamat_jalan = {}
      this.form.rt = {}
      this.form.rw = {}
      this.form.desa_kelurahan = {}
      this.form.kecamatan = {}
      this.form.kodepos = {}
      this.form.telp_hp = {}
      this.form.email = {}
      this.state.nama = null
      this.state.nik = null
      this.state.tanggal_lahir = null
      this.state.agama = null
      this.state.email = null
      this.state.jenis_kelamin = null
      this.feedback.nama = ''
      this.feedback.nik = ''
      this.feedback.tanggal_lahir = ''
      this.feedback.agama = ''
      this.feedback.email = ''
      this.feedback.jenis_kelamin = ''*/
    },
    onFileChange(e) {
      //this.$emit('loading', true)
      //this.$emit('simpan', false)
      this.loading = true
      this.simpan = false
      this.file = e.target.files[0];
      const data = new FormData();
      data.append('file_excel', (this.file) ? this.file : '');
      this.$http.post('/guru/upload', data).then(response => {
        //this.$emit('loading', false)
        this.loading = false
        let data = response.data
        this.fileState = null
        this.feedback_file = ''
        if(data.errors){
          this.fileState = (data.errors.file_excel) ? false : null
          this.feedback_file = (data.errors.file_excel) ? data.errors.file_excel.join(', ') : ''
        } else {
          //this.$emit('simpan', true)
          this.simpan = true
          this.imported_data = data.imported_data
          var nama = {}
          var nuptk = {}
          var nip = {}
          var nik = {}
          var jenis_kelamin = {}
          var tempat_lahir = {}
          var tanggal_lahir = {}
          var agama = {}
          var alamat_jalan = {}
          var rt = {}
          var rw = {}
          var desa_kelurahan = {}
          var kecamatan = {}
          var kodepos = {}
          var telp_hp = {}
          var email = {}
          var nama_state = {}
          var nik_state = {}
          var jenis_kelamin_state = {}
          var tempat_lahir_state = {}
          var tanggal_lahir_state = {}
          var agama_state = {}
          var email_state = {}
          var nama_feedback = {}
          var nik_feedback = {}
          var jenis_kelamin_feedback = {}
          var tempat_lahir_feedback = {}
          var tanggal_lahir_feedback = {}
          var agama_feedback = {}
          var email_feedback = {}
          this.imported_data.forEach(function(value, key) {
            nama[value.no] = value.nama
            nuptk[value.no] = value.nuptk
            nip[value.no] = value.nip
            nik[value.no] = value.nik
            jenis_kelamin[value.no] = value.jenis_kelamin
            tempat_lahir[value.no] = value.tempat_lahir
            tanggal_lahir[value.no] = value.tanggal_lahir
            agama[value.no] = value.agama
            alamat_jalan[value.no] = value.alamat_jalan
            rt[value.no] = value.rt
            rw[value.no] = value.rw
            desa_kelurahan[value.no] = value.desa_kelurahan
            kecamatan[value.no] = value.kecamatan
            kodepos[value.no] = value.kodepos
            telp_hp[value.no] = value.telp_hp
            email[value.no] = value.email
            nama_state[value.no] = null
            nik_state[value.no] = null
            jenis_kelamin_state[value.no] = null
            tempat_lahir_state[value.no] = null
            tanggal_lahir_state[value.no] = null
            agama_state[value.no] = null
            email_state[value.no] = null
            nama_feedback[value.no] = ''
            nik_feedback[value.no] = ''
            jenis_kelamin_feedback[value.no] = ''
            tanggal_lahir_feedback[value.no] = ''
            tanggal_lahir_feedback[value.no] = ''
            agama_feedback[value.no] = ''
            email_feedback[value.no] = ''
          });
          this.form.nama = nama
          this.form.nuptk = nuptk
          this.form.nip = nip
          this.form.nik = nik
          this.form.jenis_kelamin = jenis_kelamin
          this.form.tempat_lahir = tempat_lahir
          this.form.tanggal_lahir = tanggal_lahir
          this.form.agama = agama
          this.form.alamat_jalan = alamat_jalan
          this.form.rt = rt
          this.form.rw = rw
          this.form.desa_kelurahan = desa_kelurahan
          this.form.kecamatan = kecamatan
          this.form.kodepos = kodepos
          this.form.telp_hp = telp_hp
          this.form.email = email
          this.state.nama = nama_state
          this.state.nik = nik_state
          this.state.jenis_kelamin = jenis_kelamin_state
          this.state.tanggal_lahir = tanggal_lahir_state
          this.state.tempat_lahir = tempat_lahir_state
          this.state.agama = agama_state
          this.state.email = email_state
          this.feedback.nama = nama_feedback
          this.feedback.nik = nik_feedback
          this.feedback.jenis_kelamin = jenis_kelamin_feedback
          this.feedback.tempat_lahir = tempat_lahir_feedback
          this.feedback.tanggal_lahir = tanggal_lahir_feedback
          this.feedback.agama = agama_feedback
          this.feedback.email = email_feedback
        }
      }).catch(error => {
        console.log(error);
        this.fileState = false
        this.feedback_file = 'Isian salah. Silahkan periksa kembali!!!'
      })
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading = true
      this.$http.post('/guru/simpan', this.form).then(response => {
        this.loading = false
        let data = response.data
        if(data.errors){
          var nama_state = {}
          var nik_state = {}
          var tanggal_lahir_state = {}
          var agama_state = {}
          var email_state = {}
          var jenis_kelamin_state = {}
          var tempat_lahir_state = {}
          var nama_feedback = {}
          var nik_feedback = {}
          var jenis_kelamin_feedback = {}
          var tempat_lahir_feedback = {}
          var tanggal_lahir_feedback = {}
          var agama_feedback = {}
          var email_feedback = {}
          this.imported_data.forEach(function(value, key) {
            nama_state[value.no] = (data.errors['nama.'+value.no]) ? false : null
            nik_state[value.no] = (data.errors['nik.'+value.no]) ? false : null
            jenis_kelamin_state[value.no] = (data.errors['jenis_kelamin.'+value.no]) ? false : null
            tempat_lahir_state[value.no] = (data.errors['tempat_lahir.'+value.no]) ? false : null
            tanggal_lahir_state[value.no] = (data.errors['tanggal_lahir.'+value.no]) ? false : null
            agama_state[value.no] = (data.errors['agama.'+value.no]) ? false : null
            email_state[value.no] = (data.errors['email.'+value.no]) ? false : null
            nama_feedback[value.no] = (data.errors['nama.'+value.no]) ? data.errors['nama.'+value.no].join(', ') : ''
            nik_feedback[value.no] = (data.errors['nik.'+value.no]) ? data.errors['nik.'+value.no].join(', ') : ''
            jenis_kelamin_feedback[value.no] = (data.errors['jenis_kelamin.'+value.no]) ? data.errors['jenis_kelamin.'+value.no].join(', ') : ''
            tempat_lahir_feedback[value.no] = (data.errors['tempat_lahir.'+value.no]) ? data.errors['tempat_lahir.'+value.no].join(', ') : ''
            tanggal_lahir_feedback[value.no] = (data.errors['tanggal_lahir.'+value.no]) ? data.errors['tanggal_lahir.'+value.no].join(', ') : ''
            agama_feedback[value.no] = (data.errors['agama.'+value.no]) ? data.errors['agama.'+value.no].join(', ') : ''
            email_feedback[value.no] = (data.errors['email.'+value.no]) ? data.errors['email.'+value.no].join(', ') : ''
          })
          this.state.nama = nama_state
          this.state.nik = nik_state
          this.state.jenis_kelamin = jenis_kelamin_state
          this.state.tanggal_lahir = tanggal_lahir_state
          this.state.tempat_lahir = tempat_lahir_state
          this.state.agama = agama_state
          this.state.email = email_state
          this.feedback.nama = nama_feedback
          this.feedback.nik = nik_feedback
          this.feedback.jenis_kelamin = jenis_kelamin_feedback
          this.feedback.tempat_lahir = tempat_lahir_feedback
          this.feedback.tanggal_lahir = tanggal_lahir_feedback
          this.feedback.agama = agama_feedback
          this.feedback.email = email_feedback
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
      })
    },
  },
}
</script>