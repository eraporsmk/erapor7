<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-table-simple bordered>
          <b-thead>
            <b-tr>
              <b-th class="text-center">Mata Pelajaran</b-th>
              <b-th class="text-center">Kelas</b-th>
              <b-th class="text-center">Jml PD</b-th>
              <b-th class="text-center">PD Dinilai</b-th>
              <b-th class="text-center">Aksi</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-if="data.length">
              <b-tr v-for="item in data" :key="item.pembelajaran_id">
                <b-td>{{ item.nama_mata_pelajaran }}</b-td>
                <b-td class="text-center">{{ item.rombongan_belajar.nama }}</b-td>
                <b-td class="text-center">{{ item.anggota_rombel_count }}</b-td>
                <b-td class="text-center">{{ item.nilai_pts_count }}</b-td>
                <b-td class="text-center">
                  <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="primary" size="sm">
                    <b-dropdown-item href="javascript:" @click="aksi('add', item.pembelajaran_id)"><font-awesome-icon icon="fa-solid fa-upload" /> Import Nilai</b-dropdown-item>
                    <b-dropdown-item href="javascript:" @click="aksi('detil', item.pembelajaran_id)"><font-awesome-icon icon="fa-solid fa-check" /> Detil Nilai</b-dropdown-item>
                  </b-dropdown>
                </b-td>
              </b-tr>
            </template>
            <template v-else>
              <b-tr>
                <b-td class="text-center" colspan="5">Tidak ada data untuk ditampilkan</b-td>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </div>
    </b-card-body>
    <add-nilai @reload="handleReload" :title="`Impor Nilai Tengah Semester`" :link_excel="link_excel"></add-nilai>
    <detil-nilai></detil-nilai>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BThead, BTr, BTh, BTbody, BTd, BButton, BDropdown, BDropdownItem } from 'bootstrap-vue'
import AddNilai from './modal/AddNilai.vue'
import DetilNilai from './modal/DetilNilai.vue'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple,
    BThead,
    BTr,
    BTh,
    BTbody,
    BTd,
    BButton,
    BDropdown,
    BDropdownItem,
    AddNilai,
    DetilNilai,
  },
  data() {
    return {
      //`/excel/format_excel_instruktur.xlsx`
      isBusy: true,
      link_excel: '',
      data: [],
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.isBusy = true
      this.$http.get('/penilaian/all-pembelajaran', {
        params: {
          user_id: this.user.user_id,
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
        }
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data = getData.data
      })
    },
    handleReload(){
      this.loadPostData()
    },
    aksi(aksi, pembelajaran_id){
      console.log(aksi);
      eventBus.$emit(`open-modal-${aksi}-nilai-pts`, pembelajaran_id);
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>