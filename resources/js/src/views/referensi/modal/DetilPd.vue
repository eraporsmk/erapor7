<template>
  <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-form @submit.prevent="handleSubmit">
      <b-row v-if="data">
        <b-col cols="12">
          <b-form-group label="Nama Lengkap" label-cols-md="3">
            <b-form-input :value="data.nama" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIS" label-cols-md="3">
            <b-form-input :value="data.nis" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NISN" label-cols-md="3">
            <b-form-input :value="data.nisn" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIK" label-cols-md="3">
            <b-form-input :value="data.nik" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Jenis Kelamin" label-cols-md="3">
            <b-form-input :value="(data.jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan'" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tempat Lahir" label-cols-md="3">
            <b-form-input :value="data.tempat_lahir" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tanggal Lahir" label-cols-md="3">
            <b-form-input :value="data.tanggal_lahir_indo" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Agama" label-cols-md="3">
            <b-form-input :value="(data.agama_id) ? data.agama.nama : ''" disabled />
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
            <b-form-input :value="data.alamat" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="RT" label-cols-md="3">
            <b-form-input :value="data.rt" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="RW" label-cols-md="3">
            <b-form-input :value="data.rw" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Desa/Kelurahan" label-cols-md="3">
            <b-form-input :value="data.desa_kelurahan" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kecamatan" label-cols-md="3">
            <b-form-input :value="data.kecamatan" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kodepos" label-cols-md="3">
            <b-form-input :value="data.kode_pos" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Telp/HP" label-cols-md="3">
            <b-form-input :value="data.no_telp" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Asal Sekolah" label-cols-md="3">
            <b-form-input :value="data.sekolah_asal" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Diterima dikelas" label-cols-md="3">
            <b-form-input v-model="form.diterima_kelas" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Diterima pada tanggal" label-cols-md="3">
            <b-form-input :value="data.diterima" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Email" label-cols-md="3">
            <b-form-input v-model="form.email" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Ayah" label-cols-md="3">
            <b-form-input :value="data.nama_ayah" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Ayah" label-cols-md="3">
            <b-form-input :value="(data.pekerjaan_ayah) ? data.pekerjaan_ayah.nama : ''" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Ibu Kandung" label-cols-md="3">
            <b-form-input :value="data.nama_ibu" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Ibu" label-cols-md="3">
            <b-form-input :value="(data.pekerjaan_ibu) ? data.pekerjaan_ibu.nama : ''" disabled />
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
            <!--b-form-select v-model="kerja_wali" :options="pekerjaan" text-field="nama" value-field="pekerjaan_id">
              <template #first>
                <b-form-select-option value="" disabled>== Pilih Pekerjaan Wali ==</b-form-select-option>
              </template>
            </b-form-select-->
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
  </b-overlay>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BFormGroup, BFormInput } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BRow, BCol, BForm, BFormGroup, BFormInput,
    vSelect
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    data: {
      required: true
    },
    pekerjaan: {
      type: Array,
      required: true
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    }
  },
  data() {
    return {
      data_status: ['Anak Kandung', 'Anak Tiri', 'Anak Angkat'],
    }
  },
}
</script>