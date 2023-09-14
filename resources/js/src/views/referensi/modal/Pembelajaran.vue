<template>
  <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-table-simple hover bordered>
      <b-thead>
        <b-tr>
          <b-th class="text-center">No</b-th>
          <b-th class="text-center">Mata Pelajaran</b-th>
          <b-th class="text-center">ID Mapel</b-th>
          <b-th class="text-center">Guru Mapel (Dapodik)</b-th>
          <b-th class="text-center">Guru Pengajar</b-th>
          <b-th class="text-center">Kelompok</b-th>
          <b-th class="text-center">No Urut</b-th>
          <b-th class="text-center">Reset</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(pembelajaran, index) in list_pembelajaran">
          <b-tr v-bind:class="{'bg-warning': pembelajaran.induk_pembelajaran_id}">
            <b-td class="text-center">{{index + 1}}</b-td>
            <b-td>
              <b-form-input v-model="form.nama[pembelajaran.pembelajaran_id]"></b-form-input>
            </b-td>
            <b-td class="text-center">
              <b-form-input :value="pembelajaran.mata_pelajaran_id" disabled />
            </b-td>
            <b-td class="text-center">
              <b-form-input :value="pembelajaran.guru.nama_lengkap" disabled />
            </b-td>
            <b-td>
              <v-select v-model="form.guru_pengajar_id[pembelajaran.pembelajaran_id]" :options="data_guru" :reduce="nama_lengkap => nama_lengkap.guru_id" label="nama_lengkap" placeholder="== Pilih Guru Pengajar ==">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-td>
            <b-td>
              <v-select v-model="form.kelompok_id[pembelajaran.pembelajaran_id]" :options="data_kelompok" :reduce="nama_kelompok => nama_kelompok.kelompok_id" label="nama_kelompok" placeholder="== Pilih Kelompok ==">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-td>
            <b-td>
              <b-form-input v-model="form.no_urut[pembelajaran.pembelajaran_id]"></b-form-input>
            </b-td>
            <b-td class="text-center">
              <template v-if="pembelajaran.kelompok_id && pembelajaran.no_urut">
                <a @click="hapus(pembelajaran.pembelajaran_id, pembelajaran.rombongan_belajar_id)" class="text-danger"><font-awesome-icon icon="fa-solid fa-trash" /></a>
              </template>
              <template v-else>
                -
              </template>
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-overlay>
</template>

<script>
import { BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormInput } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    BFormInput,
    vSelect,
  },
  props: {
    form: {
      type: Object,
      required: true,
    },
    /*state: {
      type: Object,
      required: true,
    },*/
    list_pembelajaran: {
      type: Array,
      required: true
    },
    data_guru: {
      type: Array,
      required: true
    },
    data_kelompok: {
      type: Array,
      required: true
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    }
  },
  methods: {
    hapus(pembelajaran_id, rombongan_belajar_id){
      this.$emit('hapus', {
        pembelajaran_id: pembelajaran_id,
        rombongan_belajar_id: rombongan_belajar_id,
      })
    },
  },
}
</script>