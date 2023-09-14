<template>
  <b-row>
    <b-col cols="12">
      <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
        <b-form-input id="tahun_pelajaran" :value="form.tahun_pelajaran" disabled />
      </b-form-group>
    </b-col>
    <b-col cols="12">
      <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="meta.tingkat_feedback" :state="meta.tingkat_state">
        <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="meta.tingkat_state" @input="changeTingkat">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-form-group>
    </b-col>
    <b-col cols="12">
      <b-form-group label="Jenis Rombongan Belajar" label-for="jenis_rombel" label-cols-md="3" :invalid-feedback="meta.jenis_rombel_feedback" :state="meta.jenis_rombel_state">
        <v-select id="jenis_rombel" v-model="form.jenis_rombel" :reduce="nama => nama.id" label="nama" :options="data_jenis" placeholder="== Pilih Jenis Rombongan Belajar ==" @input="changeJenis" :searchable="false" :state="meta.jenis_rombel_state">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-form-group>
    </b-col>
    <b-col cols="12">
      <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="meta.rombongan_belajar_id_feedback" :state="meta.rombongan_belajar_id_state">
        <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="meta.rombongan_belajar_id_state">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col>
    <b-col cols="12">
      <b-form-group label="Mata Pelajaran" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="meta.pembelajaran_id_feedback" :state="meta.pembelajaran_id_state">
        <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.pembelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="meta.pembelajaran_id_state" @input="changeMapel">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col>
    <b-col cols="12">
      <b-form-group label="Jenis Asesmen" label-for="teknik_penilaian_id" label-cols-md="3" :invalid-feedback="meta.teknik_penilaian_id_feedback" :state="meta.teknik_penilaian_id_state">
        <b-overlay :show="loading_teknik" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="teknik_penilaian_id" v-model="form.teknik_penilaian_id" :reduce="nama => nama.teknik_penilaian_id" label="nama" :options="data_teknik" placeholder="== Pilih Jenis Asesmen ==" :state="meta.teknik_penilaian_id_state" @input="changeTeknik">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col>
    <!--b-col cols="12" v-if="show_cp">
      <b-form-group label="Capaian Pembelajaran" label-for="cp_id" label-cols-md="3" :invalid-feedback="meta.cp_id_feedback" :state="meta.cp_id_state">
        <b-overlay :show="loading_tp" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="cp_id" v-model="form.cp_id" :reduce="deskripsi => deskripsi.cp_id" label="deskripsi" :options="data_cp" placeholder="== Pilih Capaian Pembelajaran ==" :state="meta.cp_id_state" @input="changeTp">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
            <template #option="{ elemen, deskripsi }">
              <b>Elemen: {{elemen}}</b><br/>
              Deskripsi: {{deskripsi}}
            </template>
            <template #selected-option="{ elemen, deskripsi }">
              <p><b>{{elemen}}</b></p>
              {{deskripsi}}
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col-->
  </b-row>
</template>

<script>
import { BRow, BCol, BOverlay, BFormGroup, BFormInput } from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
  components: {
    BRow,
    BCol,
    BOverlay, 
    BFormGroup, 
    BFormInput,
    vSelect
  },
  props: {
    meta: {
      required: true
    },
    form: {
      required: true
    },
    show_cp: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
      loading_rombel: false,
      loading_mapel: false,
      loading_teknik: false,
      loading_tp: false,
      tahun_pelajaran: '',
      pembelajaran: '',
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
      data_jenis: [
        {
          id: 1,
          nama: 'Reguler',
        },
        {
          id: 16,
          nama: 'Matpel Pilihan',
        },
      ],
      data_rombel: [],
      data_mapel: [],
      data_teknik: [],
      data_cp: [],
    }
  },
  /*created() {
    this.tahun_pelajaran = this.user.semester.nama
  },*/
  methods: {
    setValue(){
        this.pembelajaran = '';
        this.data_rombel = []
        this.data_mapel = []
    },
    changeTingkat(val){
      this.$emit('hide_form')
      this.$emit('show_cp', false)
      this.$emit('show_sumatif', false)
      this.form.jenis_rombel = ''
      this.form.rombongan_belajar_id = ''
      this.pembelajaran = '';
      this.form.pembelajaran_id = ''
      this.form.teknik_penilaian_id = ''
      this.form.cp_id = ''
      this.form.merdeka = false
    },
    changeJenis(val){
      this.$emit('hide_form')
      this.$emit('show_cp', false)
      this.$emit('show_sumatif', false)
      this.form.rombongan_belajar_id = ''
      this.pembelajaran = '';
      this.form.pembelajaran_id = ''
      this.form.teknik_penilaian_id = ''
      this.form.cp_id = ''
      this.form.merdeka = false
      if(val){
        this.loading_rombel = true
        this.$http.post('/penilaian/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.$emit('hide_form')
      this.$emit('show_cp', false)
      this.$emit('show_sumatif', false)
      this.pembelajaran = '';
      this.form.pembelajaran_id = ''
      this.form.teknik_penilaian_id = ''
      this.form.cp_id = ''
      this.form.merdeka = false
      if(val){
        this.loading_mapel = true
        this.$http.post('/penilaian/get-mapel', this.form).then(response => {
          this.loading_mapel = false
          let getData = response.data
          this.data_mapel = getData.data
          this.form.merdeka = getData.merdeka
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeMapel(val){
      this.$emit('hide_form')
      this.$emit('show_cp', false)
      this.$emit('show_sumatif', false)
      this.form.teknik_penilaian_id = ''
      this.form.cp_id = ''
      if(val){
        this.loading_teknik = true
        this.$http.post('/penilaian/get-teknik-penilaian', this.form).then(response => {
          this.loading_teknik = false
          let getData = response.data
          this.data_teknik = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeTeknik(val){
      this.$emit('hide_form')
      this.$emit('show_cp', false)
      this.$emit('show_sumatif', false)
      this.form.cp_id = ''
      if(val){
        this.loading_teknik = true
        this.$http.post('/penilaian/get-cp', this.form).then(response => {
          this.loading_teknik = false
          let getData = response.data
          this.$emit('show_cp', getData.show_cp)
          var opsi;
          if(!getData.show_cp){
            opsi = 'nilai-akhir-sumatif'
            this.$emit('show_sumatif', true)
          } else {
            opsi = 'nilai-tp'
          }
          this.$emit('show_form', opsi)
          this.data_cp = getData.data_cp
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeTp(val){
      this.$emit('show_form', 'nilai-tp')
    }
  },
}
</script>
