<template>
  <b-row>
    <b-col md="4" class="mb-2">
      <v-select v-model="meta.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" @input="changeTingkat" placeholder="== Filter Tingkat ==">
        <template slot="no-options">
          Tidak ada data untuk ditampilkan
        </template>
      </v-select>
    </b-col>
    <b-col md="4" class="mb-2">
      <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
        <v-select v-model="meta.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" @input="changeRombel" placeholder="== Filter Rombel ==">
          <template slot="no-options">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-overlay>
    </b-col>
    <b-col md="4" class="mb-2">
      <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
        <v-select v-model="meta.pembelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.pembelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" @input="changeMapel" placeholder="== Filter Mapel ==">
          <template slot="no-options">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-overlay>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BOverlay } from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
  components: {
    vSelect,
    BRow,
    BCol,
    BOverlay
  },
  props: {
    meta: {
      required: true
    },
  },
  data() {
    return {
      loading_rombel: false,
      loading_mapel: false,
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
  methods: {
    changeTingkat(val){
      this.$emit('tingkat', val)
      this.meta.rombongan_belajar_id = ''
      this.meta.pembelajaran_id = ''
      this.data_rombel = []
      this.data_mapel = []
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.meta).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.$emit('rombel', val)
      this.meta.pembelajaran_id = ''
      this.data_mapel = []
      if(val){
        this.loading_mapel = true
        this.$http.post('/referensi/get-mapel', this.meta).then(response => {
          this.loading_mapel = false
          let getData = response.data
          this.data_mapel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeMapel(val){
      this.$emit('mapel', val)
    },
  },
}
</script>
