<template>
  <b-row>
    <b-col cols="12">
      <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
        <b-form-input id="tahun_pelajaran" :value="form.periode_aktif" disabled />
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
    loading: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
      loading_rombel: false,
      tahun_pelajaran: '',
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
    }
  },
  methods: {
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      if(val){
        this.$emit('hide_form')
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
        this.$emit('hide_form')
        this.$emit('rombel', val)
      }
    },
  },
}
</script>
