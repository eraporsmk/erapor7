<template>
  <b-table-simple bordered responsive v-if="data">
    <b-tr>
      <b-td width="30%">Kompetensi Keahlian</b-td>
      <b-td width="70%">{{data.jurusan.nama_jurusan}}</b-td>
    </b-tr>
    <b-tr>
      <b-td>Kurikulum</b-td>
      <b-td>{{data.kurikulum.nama_kurikulum}}</b-td>
    </b-tr>
    <b-tr>
      <b-td>Nomor Paket</b-td>
      <b-td>{{data.nomor_paket}}</b-td>
    </b-tr>
    <b-tr>
      <b-td>Judul Paket (ID)</b-td>
      <b-td>{{data.nama_paket_id}}</b-td>
    </b-tr>
    <b-tr>
      <b-td>Judul Paket (EN)</b-td>
      <b-td>{{data.nama_paket_en}}</b-td>
    </b-tr>
    <b-tr>
      <b-td>Status</b-td>
      <b-td>
        <b-badge variant="success" v-if="data.status">Aktif</b-badge>
        <b-badge variant="danger" v-else>Tidak Aktif</b-badge>
      </b-td>
    </b-tr>
    <b-tr>
      <b-td>Unit UKK {{data.unit_ukk.length}}</b-td>
      <b-td>
        <b-table-simple bordered responsive>
          <b-thead>
            <b-tr>
              <b-th>Kode Unit</b-th>
              <b-th>Nama Unit Kompetensi</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(unit_ukk, index) in data.unit_ukk" v-if="data.unit_ukk.length">
              <b-tr>
                <b-td>{{unit_ukk.kode_unit}}</b-td>
                <b-td>{{unit_ukk.nama_unit}}</b-td>
              </b-tr>
            </template>
            <template v-else>
              <b-tr>
                <b-td class="text-center" colspan="2">Tidak ada data untuk ditampilkan</b-td>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </b-td>
    </b-tr>
  </b-table-simple>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTh, BTr, BTd, BBadge } from 'bootstrap-vue'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BBadge
  },
  props: {
    data: {
      required: true,
    },
  },
}
</script>