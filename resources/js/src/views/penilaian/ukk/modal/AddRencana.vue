<template>
  <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-row>
      <b-col cols="12">
        <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
          <b-form-input id="tahun_pelajaran" :value="form.periode_aktif" disabled />
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
        <b-form-group label="Penguji Internal" label-for="penguji_internal" label-cols-md="3" :invalid-feedback="feedback.penguji_internal" :state="state.penguji_internal">
          <b-overlay :show="loading_guru" opacity="0.6" size="md" spinner-variant="secondary">
            <v-select id="penguji_internal" v-model="form.penguji_internal" :reduce="nama_lengkap => nama_lengkap.guru_id" label="nama_lengkap" :options="data_internal" placeholder="== Pilih Penguji Internal ==" :state="state.penguji_internal">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-overlay>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Penguji Eksternal" label-for="penguji_eksternal" label-cols-md="3" :invalid-feedback="feedback.penguji_eksternal" :state="state.penguji_eksternal">
          <b-overlay :show="loading_guru" opacity="0.6" size="md" spinner-variant="secondary">
            <v-select id="penguji_eksternal" v-model="form.penguji_eksternal" :reduce="nama_lengkap => nama_lengkap.guru_id" label="nama_lengkap" :options="data_eksternal" placeholder="== Pilih Penguji Eksternal ==" @input="changeEksternal" :state="state.penguji_eksternal">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-overlay>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Paket Kompetensi" label-for="paket_ukk_id" label-cols-md="3" :invalid-feedback="feedback.paket_ukk_id" :state="state.paket_ukk_id">
          <b-overlay :show="loading_paket" opacity="0.6" size="md" spinner-variant="secondary">
            <v-select id="paket_ukk_id" v-model="form.paket_ukk_id" :reduce="nama_paket_id => nama_paket_id.paket_ukk_id" label="nama_paket_id" :options="data_paket" placeholder="== Pilih Paket Kompetensi ==" :state="state.paket_ukk_id" @input="changePaket">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-overlay>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Tanggal Sertifikat" label-for="tanggal" label-cols-md="3"  :invalid-feedback="feedback.tanggal" :state="state.tanggal">
          <b-form-datepicker v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal" @context="onContext" placeholder="== Pilih Tanggal ==" />
        </b-form-group>
      </b-col>
    </b-row>
    <b-overlay :show="loading_siswa" opacity="0.6" size="md" spinner-variant="danger">
      <b-row>
        <b-col cols="12" v-if="show_table">
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center" width="5%"></b-th>
                <b-th class="text-center" width="55%"></b-th>
                <b-th class="text-center" width="45%"></b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="(item, index) in data_siswa">
                <b-tr>
                  <b-td class="text-center">
                    <b-form-checkbox :id="`checkbox-${index}`" v-model="form.siswa_dipilih[`${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`]" :name="`checkbox-${index}`" :value="`${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`" stacked class="mx-auto"></b-form-checkbox>
                  </b-td>
                  <b-td>{{item.nama}}</b-td>
                  <b-td>{{(rencana_ukk) ? rencana_ukk.paket_ukk.nama_paket_id : '-'}}</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-col>
      </b-row>
    </b-overlay>
  </b-overlay>
</template>

<script>
import { BOverlay, BRow, BCol, BFormGroup, BFormInput, BFormDatepicker, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormCheckbox } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay, BRow, BCol, BFormGroup, BFormInput, BFormDatepicker, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormCheckbox,
    vSelect,
  },
  props: {
    loading_form: {
      type: Boolean,
      default: () => false,
    },
    form: {
      type: Object,
      required: true
    },
    state: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      loading_guru: false,
      loading_paket: false,
      loading_siswa: false,
      loading_rombel: false,
      loading_mapel: false,
      loading_table: false,
      show_table: false,
      data_rombel: [],
      data_internal: [],
      data_eksternal: [],
      data_paket: [],
      data_siswa: [],
      rencana_ukk: '',
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
    }
  },
  methods: {
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      this.form.jurusan_id = '',
      this.form.penguji_internal = ''
      this.form.penguji_eksternal = ''
      this.form.paket_ukk_id = ''
      this.form.tanggal = ''
      if(val){
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
      this.form.penguji_internal = ''
      this.form.penguji_eksternal = ''
      this.form.paket_ukk_id = ''
      this.form.tanggal = ''
      if(val){
        this.loading_guru = true
        this.$http.post('/ukk/get-penguji-ukk', this.form).then(response => {
          this.loading_guru = false
          let getData = response.data
          this.data_internal = getData.data_internal
          this.data_eksternal = getData.data_eksternal
          this.form.jurusan_id = getData.rombel.jurusan_sp.jurusan_id
          console.log(getData);
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeEksternal(val){
      if(val){
        this.loading_paket = true
        this.$http.post('/ukk/get-paket-ukk', this.form).then(response => {
          this.loading_paket = false
          let getData = response.data
          this.data_paket = getData.paket_ukk
          console.log(getData);
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changePaket(val){
      if(val){
        this.show_table = false
        this.loading_siswa = true
        this.$http.post('/ukk/get-siswa', this.form).then(response => {
          this.show_table = true
          this.loading_siswa = false
          let getData = response.data
          this.rencana_ukk = getData.rencana_ukk
          this.data_siswa = getData.data_siswa
          var siswa_dipilih = {}
          this.data_siswa.forEach(function(item, key) {
            if(item.nilai_ukk && getData.rencana_ukk){
              siswa_dipilih[`${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`] = `${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`
            }
          })
          this.form.siswa_dipilih = siswa_dipilih
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