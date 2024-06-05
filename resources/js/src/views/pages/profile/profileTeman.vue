<template>
  <b-card no-body>
    <b-card-body>
      <template v-if="isBusy">
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
      <template v-else>
        <b-tabs fill v-model="tabIndex" @input="inputTab" content-class="mt-0">
          <template v-for="(item, index) in semester">
            <b-tab :title="item.nama">
              <template v-if="isBusy">
                  <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                  </div>
                </template>
                <template v-else>
                  <b-table-simple bordered responsive class="mb-2">
                    <b-thead>
                      <b-tr>
                        <b-th class="text-center">No</b-th>
                        <b-th class="text-center">Nama</b-th>
                        <b-th class="text-center">Tempat, Tanggal Lahir</b-th>
                        <b-th class="text-center">Email</b-th>
                        <b-th class="text-center">Handphone</b-th>
                      </b-tr>
                    </b-thead>
                    <b-tbody>
                      <template v-if="items.length">
                        <b-tr v-for="(item, index) in items" :key="item.peserta_didik_id">
                          <b-td class="text-center">{{ index + 1 }}</b-td>
                          <b-td>{{ item.nama }}</b-td>
                          <b-td>{{ item.tempat_lahir }}, {{ item.tanggal_lahir_indo }}</b-td>
                          <b-td>{{ item.email }}</b-td>
                          <b-td>{{ item.no_telp }}</b-td>
                        </b-tr>
                      </template>
                      <template v-else>
                        <b-tr>
                          <b-td colspan="5" class="text-center">Tidak ada data untuk ditampilkan</b-td>
                        </b-tr>
                      </template>
                    </b-tbody>
                  </b-table-simple>
                </template>
            </b-tab>
          </template>
        </b-tabs>
      </template>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTabs, BTab, BTableSimple, BThead, BTbody, BTr, BTh, BTd} from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTabs, 
    BTab,
    BTableSimple, 
    BThead, 
    BTbody, 
    BTr, 
    BTh, 
    BTd
  },
  props: {
    pd: {
      type: Object,
      default: () => {},
    },
    semester: {
      type: Array,
      default: () => [],
    }
  },
  data() {
    return {
      isBusy: true,
      tabIndex: 0,
      items: [],
    }
  },
  created() {
    this.inputTab(this.tabIndex)
  },
  methods: {
    inputTab(idx){
      if(idx >= 0){
        let smt = this.semester[idx]
        this.isBusy = true
        this.$http.post('/users/teman-sekelas', {
          semester_id: smt.semester_id
        }).then(response => {
          this.isBusy = false
          this.items = response.data
        })
      }
    },
  },
}
</script>