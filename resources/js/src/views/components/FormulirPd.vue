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
    <b-col cols="12" v-if="show_mapel">
      <b-form-group label="Mata Pelajaran" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="meta.pembelajaran_id_feedback" :state="meta.pembelajaran_id_state">
        <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="meta.pembelajaran_id_state" @input="changeMapel">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col>
    <b-col cols="12">
      <b-form-group label="Nama Peserta Didik" label-for="anggota_rombel_id" label-cols-md="3" :invalid-feedback="meta.anggota_rombel_id_feedback" :state="meta.anggota_rombel_id_state">
        <b-overlay :show="loading_pd" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="anggota_rombel_id" v-model="form.anggota_rombel_id" :reduce="nama => nama.anggota_rombel.anggota_rombel_id" label="nama" :options="data_siswa" placeholder="== Pilih Nama Peserta Didik ==" :searchable="true" :state="meta.anggota_rombel_id_state">
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
    show_mapel: {
      type: Boolean,
      default: () => false,
    }
  },
  data() {
    return {
      loading_rombel: false,
      loading_mapel: false,
      loading_pd: false,
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
      data_siswa: [],
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
      if(val){
        this.$emit('hide_form')
        this.form.anggota_rombel_id = ''
        this.form.rombongan_belajar_id = ''
        this.form.pembelajaran_id = ''
        this.form.mata_pelajaran_id = ''
        this.form.merdeka = false
        this.loading_rombel = true
        this.$http.post('/rombongan-belajar/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      if(val){
        var aksi = (this.show_mapel) ? '/penilaian/get-mapel' : '/peserta-didik/get-pd'
        this.$emit('hide_form')
        this.form.pembelajaran_id = ''
        this.form.mata_pelajaran_id = ''
        this.form.merdeka = false
        this.loading_mapel = true
        this.loading_pd = true
        this.$http.post(aksi, this.form).then(response => {
          this.loading_mapel = false
          this.loading_pd = false
          let getData = response.data
          this.data_mapel = getData.data
          this.form.merdeka = getData.merdeka
          this.data_siswa = getData.data_siswa
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeMapel(val){
      if(val){
        this.form.pembelajaran_id = val.pembelajaran_id
        this.form.mata_pelajaran_id = val.mata_pelajaran_id
        this.$emit('show_form')
      }
    },
  },
}
</script>
