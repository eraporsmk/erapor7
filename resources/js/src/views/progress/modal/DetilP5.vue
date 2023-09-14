<template>
  <b-table-simple bordered responsive>
    <b-thead>
      <b-tr>
        <b-th class="text-center">No</b-th>
        <b-th class="text-center">Tema</b-th>
        <b-th class="text-center">Projek</b-th>
        <b-th class="text-center">Jumlah Sub Elemen</b-th>
        <b-th class="text-center">Detil</b-th>
      </b-tr>
    </b-thead>
    <b-tbody>
      <template v-if="data_projek.length">
        <template v-for="(item, index) in data_projek">
          <b-tr>
            <b-td class="text-center">{{index + 1}}</b-td>
            <b-td>{{item.nama_mata_pelajaran}}</b-td>
            <b-td>{{(item.projek) ? item.projek.nama : '-'}}</b-td>
            <b-td class="text-center">{{(item.projek) ? item.projek.aspek_budaya_kerja_count : '-'}}</b-td>
            <b-td class="text-center">
              <b-button variant="success" size="sm" @click="detilProjek(item)" v-if="item.projek">Detil</b-button>
            </b-td>
          </b-tr>
        </template>
      </template>
    </b-tbody>
  </b-table-simple>
</template>

<script>
import { BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton } from 'bootstrap-vue'
export default {
  components: {
    BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton
  },
  props: {
    data_projek: {
      type: Array,
      required: true
    },
  },
  methods: {
    detilProjek(item){
      this.$emit('detil_projek', item)
    },
  },
}
</script>