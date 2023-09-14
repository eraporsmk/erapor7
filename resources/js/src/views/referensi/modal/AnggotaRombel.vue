<template>
  <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-table-simple hover bordered responsive>
      <b-thead>
        <b-tr>
          <b-th class="text-center">No</b-th>
          <b-th class="text-center">Nama</b-th>
          <b-th class="text-center">NISN</b-th>
          <b-th class="text-center">L/P</b-th>
          <b-th class="text-center">Tempat, Tanggal Lahir</b-th>
          <b-th class="text-center">Agama</b-th>
          <b-th class="text-center">Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="(anggota, index) in list_anggota" :key="anggota.peserta_didik_id">
          <b-td class="text-center">{{index + 1}}</b-td>
          <b-td>{{anggota.nama}}</b-td>
          <b-td class="text-center">{{anggota.nisn}}</b-td>
          <b-td class="text-center">{{anggota.jenis_kelamin}}</b-td>
          <b-td>{{anggota.tempat_lahir}}, {{anggota.tanggal_lahir_indo}}</b-td>
          <b-td>{{(anggota.agama) ? anggota.agama.nama : ''}}</b-td>
          <b-td class="text-center">
            <b-button variant="danger" size="sm" @click="keluarkan(anggota.anggota_rombel.anggota_rombel_id)">Keluarkan</b-button>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-overlay>
</template>

<script>
import { BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'

export default {
  components: {
    BOverlay,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    BButton,
  },
  props: {
    list_anggota: {
      type: Array,
      required: true
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    }
  },
  methods: {
    keluarkan(anggota_rombel_id){
      this.$emit('keluarkan', anggota_rombel_id)
    },
  },
}
</script>