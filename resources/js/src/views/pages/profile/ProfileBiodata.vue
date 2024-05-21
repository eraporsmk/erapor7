<template>
  <b-card>
    <b-card-title>Biodata</b-card-title>
    <b-overlay :show="loading_biodata" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-form @submit.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Lengkap" label-cols-md="3">
              <b-form-input :value="bioData.nama" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="NIS" label-cols-md="3">
              <b-form-input :value="bioData.no_induk" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="NISN" label-cols-md="3">
              <b-form-input :value="bioData.nisn" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="NIK" label-cols-md="3">
              <b-form-input :value="bioData.nik" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Jenis Kelamin" label-cols-md="3">
              <b-form-input :value="(bioData.jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan'" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tempat Lahir" label-cols-md="3">
              <b-form-input :value="bioData.tempat_lahir" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tanggal Lahir" label-cols-md="3">
              <b-form-input :value="bioData.tanggal_lahir_indo" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Agama" label-cols-md="3">
              <b-form-input :value="(bioData.agama_id) ? bioData.agama.nama : ''" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Status dalam keluarga" label-cols-md="3">
              <v-select id="status" v-model="form.status" :options="data_status" placeholder="== Pilih Status dalam keluarga ==" :searchable="false">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Anak Ke" label-cols-md="3">
              <b-form-input v-model="form.anak_ke" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Alamat" label-cols-md="3">
              <b-form-input :value="bioData.alamat" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="RT" label-cols-md="3">
              <b-form-input :value="bioData.rt" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="RW" label-cols-md="3">
              <b-form-input :value="bioData.rw" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Desa/Kelurahan" label-cols-md="3">
              <b-form-input :value="bioData.desa_kelurahan" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kecamatan" label-cols-md="3">
              <b-form-input :value="bioData.kecamatan" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kodepos" label-cols-md="3">
              <b-form-input :value="bioData.kode_pos" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Telp/HP" label-cols-md="3">
              <b-form-input :value="bioData.no_telp" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Asal Sekolah" label-cols-md="3">
              <b-form-input :value="bioData.sekolah_asal" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Diterima dikelas" label-cols-md="3">
              <b-form-input v-model="form.diterima_kelas" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Diterima pada tanggal" label-cols-md="3">
              <b-form-input :value="bioData.diterima" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Email" label-cols-md="3">
              <b-form-input v-model="form.email" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Ayah" label-cols-md="3">
              <b-form-input :value="bioData.nama_ayah" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Pekerjaan Ayah" label-cols-md="3">
              <b-form-input :value="(bioData.pekerjaan_ayah) ? bioData.pekerjaan_ayah.nama : ''" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Ibu Kandung" label-cols-md="3">
              <b-form-input :value="bioData.nama_ibu" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Pekerjaan Ibu" label-cols-md="3">
              <b-form-input :value="(bioData.pekerjaan_ibu) ? bioData.pekerjaan_ibu.nama : ''" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Wali" label-cols-md="3">
              <b-form-input v-model="form.nama_wali" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Alamat Wali" label-cols-md="3">
              <b-form-input v-model="form.alamat_wali" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Telp/HP Wali" label-cols-md="3">
              <b-form-input v-model="form.telp_wali" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Pekerjaan Wali" label-cols-md="3">
              <v-select id="kerja_wali" v-model="form.kerja_wali" :reduce="nama => nama.pekerjaan_id" label="nama" :options="pekerjaan" placeholder="== Pilih Pekerjaan Wali ==">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col offset-md="3">
            <b-button type="submit" variant="primary" class="mr-1">Perbaharui</b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
  </b-card>
</template>

<script>
import {
  BCard, BCardTitle, BForm, BRow, BCol, BFormGroup, BFormInput, BButton, BOverlay,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardTitle,
    BForm,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BButton,
    BOverlay,
    vSelect,
  },
  props: {
    bioData: {
      type: Object,
      default: () => {},
    },
    form: {
      type: Object,
      required: true
    },
    pekerjaan: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      data_status: ['Anak Kandung', 'Anak Tiri', 'Anak Angkat'],
      loading_biodata: false,
    }
  },
  methods: {
    handleSubmit() {
      this.loading_biodata = true
      this.$http.post('/peserta-didik/update', this.form).then(response => {
        let getData = response.data
        this.loading_biodata = false
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.$emit('reload')
        })
      })
    },
  },
}
</script>
