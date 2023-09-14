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
          <v-select id="pembelajaran_id" v-model="pembelajaran" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="meta.pembelajaran_id_state" @input="changeMapel">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col>
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
  },
  data() {
    return {
      loading_rombel: false,
      loading_mapel: false,
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
      this.form.jenis_rombel = ''
      this.form.rombongan_belajar_id = ''
      this.pembelajaran = '';
      this.form.pembelajaran_id = ''
      this.form.mata_pelajaran_id = ''
      this.form.merdeka = false
    },
    changeJenis(val){
      this.$emit('hide_form')
      this.form.rombongan_belajar_id = ''
      this.pembelajaran = '';
      this.form.pembelajaran_id = ''
      this.form.mata_pelajaran_id = ''
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
      this.pembelajaran = '';
      this.form.pembelajaran_id = ''
      this.form.mata_pelajaran_id = ''
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
      if(val){
        this.form.pembelajaran_id = val.pembelajaran_id
        this.form.mata_pelajaran_id = val.mata_pelajaran_id
        this.$emit('show_form')
      }
    },
  },
}
</script>
