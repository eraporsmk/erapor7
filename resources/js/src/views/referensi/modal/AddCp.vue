<template>
  <b-modal v-model="addCp" size="xl" title="Tambah Data Capaian Pembelajaran" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
    <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
            <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
            <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="state.tingkat">
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
          <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
            <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Elemen" label-for="elemen" label-cols-md="3" :invalid-feedback="feedback.elemen" :state="state.elemen">
            <b-form-input id="elemen" v-model="form.elemen" placeholder="Elemen" :state="state.elemen"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Deskripsi Capaian Kompetensi" label-for="capaian_pembelajaran" label-cols-md="3" :invalid-feedback="feedback.capaian_pembelajaran" :state="state.capaian_pembelajaran">
            <b-form-textarea id="capaian_pembelajaran" v-model="form.capaian_pembelajaran" placeholder="Deskripsi Capaian Kompetensi" rows="3" max-rows="6" :state="state.capaian_pembelajaran"></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
  <!--b-modal v-model="addCp" size="xl" title="Tambah Data Kompetensi Dasar" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
            <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
            <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="state.tingkat">
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
          <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
            <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Aspek Penilaian" label-for="kompetensi_id" label-cols-md="3" :invalid-feedback="feedback.kompetensi_id" :state="state.kompetensi_id">
            <b-overlay :show="loading_kompetensi" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="kompetensi_id" v-model="form.kompetensi_id" :reduce="nama => nama.id" label="nama" :options="data_kompetensi" placeholder="== Pilih Aspek Penilaian ==" :searchable="false" :state="state.kompetensi_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kode Kompetensi Dasar" label-for="id_kompetensi" label-cols-md="3" :invalid-feedback="feedback.id_kompetensi" :state="state.id_kompetensi">
            <b-form-input id="id_kompetensi" v-model="form.id_kompetensi" placeholder="3.x untuk pengetahuan, 4.x untuk keterampilan" :state="state.id_kompetensi"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Deskripsi Kompetensi Dasar" label-for="kompetensi_dasar" label-cols-md="3" :invalid-feedback="feedback.kompetensi_dasar" :state="state.kompetensi_dasar">
            <b-form-textarea id="kompetensi_dasar" v-model="form.kompetensi_dasar" placeholder="Deskripsi Kompetensi Dasar" rows="3" max-rows="6" :state="state.kompetensi_dasar"></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal-->
</template>

<script>
import { BOverlay, BFormInput, BRow, BCol, BFormGroup, BButton, BFormTextarea} from 'bootstrap-vue'
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
    vSelect
  },
  data() {
    return {
      addCp: false,
      loading_modal: false,
      loading_rombel: false,
      loading_mapel: false,
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
        mata_pelajaran_id: '',
        elemen: '',
        capaian_pembelajaran: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        mata_pelajaran_id: null,
        elemen: null,
        capaian_pembelajaran: null,
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        mata_pelajaran_id: '',
        elemen: '',
        capaian_pembelajaran: '',
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
      data_mapel: [],
    }
  },
  created() {
    this.tahun_pelajaran = this.user.semester.nama
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.tahun_pelajaran
    eventBus.$on('open-modal-cp', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.addCp = true
    },
    hideModal(){
      this.addCp = false
      this.resetModal()
    },
    resetModal(){
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.elemen = ''
      this.form.capaian_pembelajaran = ''
      this.state.tingkat = null
      this.state.rombongan_belajar_id = null
      this.state.mata_pelajaran_id = null
      this.state.elemen = null
      this.state.capaian_pembelajaran = null
      this.feedback.tingkat = ''
      this.feedback.rombongan_belajar_id = ''
      this.feedback.mata_pelajaran_id = ''
      this.feedback.elemen = ''
      this.feedback.capaian_pembelajaran = ''
    },
    changeTingkat(val){
      this.loading_rombel = true
      this.form.rombongan_belajar_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.elemen = ''
      this.form.capaian_pembelajaran = ''
      if(val){
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.loading_mapel = true
      this.form.mata_pelajaran_id = ''
      this.form.elemen = ''
      this.form.capaian_pembelajaran = ''
      if(val){
        this.$http.post('/referensi/get-mapel', this.form).then(response => {
          this.loading_mapel = false
          let getData = response.data
          this.data_mapel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading = true
      this.$http.post('/referensi/add-cp', this.form).then(response => {
        this.loading = false
        let getData = response.data
        if(getData.errors){
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.state.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? false : null
          this.state.elemen = (getData.errors.elemen) ? false : null
          this.state.capaian_pembelajaran = (getData.errors.capaian_pembelajaran) ? false : null
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.feedback.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
          this.feedback.elemen = (getData.errors.elemen) ? getData.errors.elemen.join(', ') : ''
          this.feedback.capaian_pembelajaran = (getData.errors.capaian_pembelajaran) ? getData.errors.capaian_pembelajaran.join(', ') : ''
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