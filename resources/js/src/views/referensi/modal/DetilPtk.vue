<template>
  <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-form @submit.prevent="handleSubmit">
      <b-row v-if="data">
        <b-col cols="12">
          <b-form-group label="Nama Lengkap" label-cols-md="3">
            <b-form-input :value="data.nama_lengkap" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Gelar Depan" label-cols-md="3">
            <v-select v-model="form.gelar_depan" multiple :options="ref_gelar_depan" :reduce="kode => kode.gelar_akademik_id" label="kode" placeholder="== Pilih Gelar Depan ==">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Gelar Belakang" label-cols-md="3">
            <v-select v-model="form.gelar_belakang" multiple :options="ref_gelar_belakang" :reduce="kode => kode.gelar_akademik_id" label="kode" placeholder="== Pilih Gelar Depan ==">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NUPTK" label-cols-md="3">
            <b-form-input :value="data.nuptk" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIP" label-cols-md="3">
            <b-form-input :value="data.nip" disabled />
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
            <b-form-input :value="data.no_hp" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Email" label-cols-md="3">
            <b-form-input :value="data.email" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12" v-if="isAsesor">
          <b-form-group label="DUDI" label-cols-md="3">
            <v-select v-model="form.dudi_id" :options="ref_dudi" :reduce="nama => nama.dudi_id" label="nama" placeholder="== Pilih DUDI ==">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
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
    ref_gelar_depan: {
      type: Array,
      required: true
    },
    ref_gelar_belakang: {
      type: Array,
      required: true
    },
    ref_dudi: {
      type: Array,
      required: true
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    },
    isAsesor: {
      type: Boolean,
      default: () => false,
    },
  },
}
</script>