<template>
  <b-table-simple bordered responsive>
    <b-thead>
      <b-tr>
        <b-th class="text-center">No</b-th>
        <b-th class="text-center">Nama</b-th>
        <b-th class="text-center">NISN</b-th>
        <b-th class="text-center">Agama</b-th>
        <b-th class="text-center">Nilai Akhir</b-th>
        <b-th class="text-center">Capaian Kompetensi</b-th>
      </b-tr>
    </b-thead>
    <b-tbody>
      <template v-if="data_siswa.length">
        <template v-for="(pd, index) in data_siswa">
          <b-tr>
            <b-td class="text-center">{{index + 1}}</b-td>
            <b-td>{{pd.nama}}</b-td>
            <b-td class="text-center">{{pd.nisn}}</b-td>
            <b-td class="text-center">{{pd.agama.nama}}</b-td>
            <b-td class="text-center" v-if="merdeka">
              {{(pd.nilai_akhir_kurmer) ? pd.nilai_akhir_kurmer.nilai : '-'}}
            </b-td>
            <b-td class="text-center" v-else>
              {{(pd.nilai_akhir_pengetahuan) ? pd.nilai_akhir_pengetahuan.nilai : '-'}}
            </b-td>
            <b-td v-if="pd.deskripsi_mapel">
              <template v-if="pd.deskripsi_mapel.deskripsi_pengetahuan && pd.deskripsi_mapel.deskripsi_keterampilan">
                {{pd.deskripsi_mapel.deskripsi_pengetahuan}}
                <hr>
                {{pd.deskripsi_mapel.deskripsi_keterampilan}}
              </template>
              <template v-if="pd.deskripsi_mapel.deskripsi_pengetahuan && !pd.deskripsi_mapel.deskripsi_keterampilan">
                {{pd.deskripsi_mapel.deskripsi_pengetahuan}}
              </template>
              <template v-if="!pd.deskripsi_mapel.deskripsi_pengetahuan && pd.deskripsi_mapel.deskripsi_keterampilan">
                {{pd.deskripsi_mapel.deskripsi_keterampilan}}
              </template>
            </b-td>
            <b-td v-else>-</b-td>
          </b-tr>
        </template>
      </template>
    </b-tbody>
  </b-table-simple>
</template>

<script>
import { BTableSimple, BTbody, BThead, BTr, BTd, BTh } from 'bootstrap-vue'
export default {
  components: {
    BTableSimple, BTbody, BThead, BTr, BTd, BTh
  },
  props: {
    data_siswa: {
      type: Array,
      required: true
    },
    merdeka: {
      type: Boolean,
      default: () => false,
    }
  },
}
</script>