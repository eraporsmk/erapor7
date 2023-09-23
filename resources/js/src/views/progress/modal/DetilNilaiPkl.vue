<template>
  <div>
    <b-table-simple bordered striped>
      <b-thead>
        <b-tr>
          <b-th class="text-center">NO</b-th>
          <b-th class="text-center">Nama Peserta Didik</b-th>
          <b-th class="text-center">NISN</b-th>
          <b-th class="text-center">Catatan</b-th>
          <b-th class="text-center">Tujuan Pembelajaran</b-th>
          <b-th class="text-center">Nilai</b-th>
          <b-th class="text-center">Deskripsi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(siswa, index) in data_siswa">
          <b-tr>
            <b-td class="text-center" :rowspan="siswa.pd.nilai_pkl.length + 1">{{ index+1 }}</b-td>
            <b-td :rowspan="siswa.pd.nilai_pkl.length + 1">{{ siswa.pd.nama }}</b-td>
            <b-td class="text-center" :rowspan="siswa.pd.nilai_pkl.length + 1">{{ siswa.pd.nisn }}</b-td>
            <template v-if="!siswa.pd.nilai_pkl.length">
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
            </template>
            <template v-else>
              <td :rowspan="siswa.pd.nilai_pkl.length + 1">{{ siswa.catatan }}</td>
            </template>
          </b-tr>
          <template v-if="siswa.pd.nilai_pkl.length">
            <template v-for="(item) in siswa.pd.nilai_pkl">
              <b-tr>
                  <td>{{ item.tp.deskripsi }}</td>
                  <td class="text-center">{{ item.nilai }}</td>
                  <td>{{item.deskripsi}}</td>
              </b-tr>
            </template>
          </template>
        </template>
      </b-tbody>
    </b-table-simple>
  </div>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTr, BTh, BTd
  },
  props: {
    data_siswa: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      sortBy: null,
    }
  },
  methods: {
    predikat(val){
      var template_desk = {
        1: 'Sangat Baik',
        2: 'Baik',
        3: 'Cukup',
        4: 'Kurang',
      }
      return template_desk[val];
    },
  },
}
</script>