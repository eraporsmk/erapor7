<template>
  <b-modal v-model="detilModalShow" title="Detil Perencanaan Projek Pancasila" scrollable ok-only ok-variant="secondary" size="xl">
    <b-table-simple bordered responsive v-if="data">
      <b-tr>
        <b-td>Kelas</b-td>
        <b-td>{{data.rombongan_belajar.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Tema</b-td>
        <b-td>{{data.pembelajaran.nama_mata_pelajaran}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Nama Projek</b-td>
        <b-td>{{data.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Deskripsi Projek</b-td>
        <b-td>{{data.deskripsi}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Dimensi/Elemen/Sub Elemen</b-td>
        <b-td>
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">Dimensi</b-th>
                <b-th class="text-center">Elemen</b-th>
                <b-th class="text-center">Sub Elemen</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="aspek_budaya_kerja in data.aspek_budaya_kerja">
                <b-tr>
                  <b-td>{{aspek_budaya_kerja.budaya_kerja.aspek}}</b-td>
                  <b-td>{{aspek_budaya_kerja.elemen_budaya_kerja.elemen}}</b-td>
                  <b-td>{{aspek_budaya_kerja.elemen_budaya_kerja.deskripsi}}</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-td>
      </b-tr>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { 
  BTableSimple, BThead, BTbody, BTh, BTr, BTd
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTr, BTd
  },
  data() {
    return {
      data: null,
      detilModalShow: false,
    }
  },
  created() {
    eventBus.$on('open-modal-detil-projek', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data = data
      this.detilModalShow = true
    },
  }
}
</script>