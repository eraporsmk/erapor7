<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" :searchable="false" :clearable="false" @input="loadPerPage" />
      </b-col>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.role_id" :reduce="display_name => display_name.name" label="display_name" :options="meta.roles" placeholder="== Filter Hak Akses ==" :searchable="false" @input="getRoles">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-col>
      <b-col md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(roles)="row">
          {{allRoles(row.item.roles)}}
        </template>
        <template v-slot:cell(password)="row">
          <template v-if="cekPass(row.item.password, row.item.default_password)">
            {{row.item.default_password}}
          </template>
          <template v-else>
            <b-badge variant="success">Custom</b-badge>
          </template>
        </template>
        <template v-slot:cell(actions)="row">
          <b-button variant="warning" size="sm" @click="detil(row.item.user_id)">Detil</b-button>
        </template>
      </b-table>
    </b-overlay>
    <b-row class="mt-2">
      <b-col md="6">
        <p>Menampilkan {{ (meta.from) ? meta.from : 0 }} sampai {{ meta.to }} dari {{ meta.total }} entri</p>
      </b-col>
      <b-col md="6">
        <b-pagination v-model="meta.current_page" :total-rows="meta.total" :per-page="meta.per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
      </b-col>
    </b-row>
    <b-modal ref="detil-modal" size="lg" :title="judul" @ok="handleOk" ok-title="Simpan" cancel-title="Tutup">
      <b-table-simple bordered responsive>
        <b-tr>
          <b-td>Nama</b-td>
          <b-td>{{(data) ? data.name : ''}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Email</b-td>
          <b-td>{{(data) ? data.email : ''}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Password</b-td>
          <b-td>
            <template v-if="data && cekPass(data.password, data.default_password)">
              {{data.default_password}}
            </template>
            <template v-else>
              <b-badge variant="success">Custom</b-badge>
              <b-button variant="danger" size="sm" @click="resetPassword()">Reset Password</b-button>
            </template>
          </b-td>
        </b-tr>
        <b-tr>
          <b-td>Terakhir Login</b-td>
          <b-td>{{(data) ? data.login_terakhir : ''}}</b-td>
        </b-tr>
      </b-table-simple>
      <h4>Hak Akses yang Dimiliki</h4>
      <b-table-simple bordered responsive>
        <b-thead>
          <b-tr>
            <b-th class="text-center">Tahun Pelajaran</b-th>
            <b-th class="text-center">Hak Akses</b-th>
            <b-th class="text-center">Aksi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-if="data">
            <b-tr v-for="(item, index) in data.roles_teams" :key="item.role_id">
              <b-td>{{item.display_name}}</b-td>
              <b-td class="text-center">{{data.roles[index].display_name}}</b-td>
              <b-td class="text-center">
                <b-button variant="danger" size="sm" @click="hapusAkses(data.roles[index].name)" v-if="role_guru.includes(data.roles[index].id)">
                  <font-awesome-icon icon="fa-solid fa-trash" v-b-tooltip.hover.html="'Hapus Hak Akses'" />
                </b-button>
              </b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
      <template v-if="options.length">
        <h4>Tambah Hak Akses di Tahun Pelajaran {{periode_aktif}}</h4>
        <b-form-group>
          <b-form-checkbox-group v-model="selected" name="hak-akses" stacked>
            <b-form-checkbox v-for="option in options" :key="option.value" :value="option.value" class="mb-1">
              {{ option.text }}
            </b-form-checkbox>
          </b-form-checkbox-group>
        </b-form-group>
      </template>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" @click="cancel()">Tutup</b-button>
        <b-button variant="danger" size="sm" @click="resetPassword()">Reset Password</b-button>
        <b-button variant="primary" size="sm" @click="ok()">Simpan</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import _ from 'lodash' //IMPORT LODASH, DIMANA AKAN DIGUNAKAN UNTUK MEMBUAT DELAY KETIKA KOLOM PENCARIAN DIISI
import bcrypt from 'bcryptjs';
import vSelect from 'vue-select'
import { BRow, BCol, BTable, BSpinner, BPagination, BButton, BFormInput, BBadge, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormCheckboxGroup, BFormCheckbox, VBTooltip, BOverlay } from 'bootstrap-vue'
export default {
  components: {
    BRow,
    BCol,
    BTable,
    BSpinner,
    BPagination,
    BButton,
    BFormInput,
    BBadge,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    BFormGroup, 
    BFormCheckboxGroup,
    BFormCheckbox,
    VBTooltip,
    BOverlay,
    vSelect,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  props: {
    items: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isBusy: {
      type: Boolean,
      default: () => true,
    }
  },
  data() {
    return {
      loading: false,
      sortBy: null,
      sortDesc: false,
      judul: null,
      user_id: '',
      data: null,
      roles: [],
      periode_aktif: null,
      selected: [],
      options: [],
      role_guru: [7,8,9],
    }
  },
  watch: {
    sortBy(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },
  methods: {
    cekPass(password, default_password){
      return bcrypt.compareSync(default_password, password)
      /*bcrypt.compare(default_password, password, (err, res) => {
        if(res)
          return 'sama'
        return 'tidak sama'
        // res == true or res == false
      });*/
    },
    allRoles(roles){
      var names = roles.map((a) => a.display_name);
      return names.join(", ")
    },
    detil(user_id){
      this.loading = true
      this.user_id = user_id
      this.periode_aktif = this.user.semester.nama
      this.$http.post('/users/detil', {
        user_id: this.user_id,
        periode_aktif: this.user.semester.nama,
      }).then(response => {
        this.loading = false
        let getData = response.data
        this.data = getData.user
        this.options = getData.roles
        this.judul = 'DETIL '+getData.user.name
        console.log(getData);
        this.$refs['detil-modal'].show()
      });
    },
    swalConfirm(text, aksi, params){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => !this.$swal.isLoading(),
      }).then(result => {
        if (result.value) {
          this.$http.post(aksi, params).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              this.$refs['detil-modal'].hide()
              this.loadPerPage(this.meta.per_page);
            })
          });
        }
      })
    },
    hapusAkses(role) {
      var text = 'Tindakan ini tidak dapat dikembalikan!'
      var aksi = '/users/hapus-akses'
      var params = {user_id: this.user_id, role:role, periode_aktif: this.user.semester.nama}
      this.swalConfirm(text, aksi, params)
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.$http.post('/users/update', {
        user_id: this.user_id,
        akses: this.selected,
        periode_aktif: this.user.semester.nama,
      }).then(response => {
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.$refs['detil-modal'].hide()
          this.loadPerPage(this.meta.per_page);
          this.selected = []
        })
      })
    },
    resetPassword(){
      var text = 'Tindakan ini tidak dapat dikembalikan!'
      var aksi = '/users/reset-password'
      var params = {user_id: this.user_id}
      this.swalConfirm(text, aksi, params)
    },
    loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page)
    },
    getRoles(val){
      this.$emit('role_id', this.meta.role_id)
    },
    changePage(val) {
      this.$emit('pagination', val)
    },
    search: _.debounce(function (e) {
      this.$emit('search', e)
    }, 500),
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>