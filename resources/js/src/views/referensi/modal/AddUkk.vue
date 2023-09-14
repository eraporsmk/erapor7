<template>
  <div>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Kompetensi Keahlian" label-for="jurusan_id" label-cols-md="3" :invalid-feedback="state.jurusan_id_feedback" :state="state.jurusan_id_state">
              <v-select id="jurusan_id" v-model="form.jurusan_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_id" label="nama_jurusan_sp" :options="data_jurusan" placeholder="== Pilih Kompetensi Keahlian ==" :searchable="false" :state="state.jurusan_id_state" @input="changeJurusan">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-for="kurikulum_id" label-cols-md="3" :invalid-feedback="state.kurikulum_id_feedback" :state="state.kurikulum_id_state">
              <b-overlay :show="loading_kurikulum" rounded opacity="0.6" size="lg" spinner-variant="secondary">
                <v-select id="kurikulum_id" v-model="form.kurikulum_id" :reduce="nama_kurikulum => nama_kurikulum.kurikulum_id" label="nama_kurikulum" :options="data_kurikulum" placeholder="== Pilih Kurikulum ==" :searchable="false" :state="state.kurikulum_id_state" @input="changeKurikulum">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-table-simple hover bordered responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">Nomor Paket</b-th>
                  <b-th class="text-center">Judul Paket (ID)</b-th>
                  <b-th class="text-center">Judul Paket (EN)</b-th>
                  <b-th class="text-center">Status</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="formulir in jumlah_form">
                  <b-tr>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nomor_paket[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_paket_id[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_paket_en[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-select v-model="form.status[formulir]" :options="data_status"></b-form-select>
                      </b-form-group>
                    </b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
  </div>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
    vSelect,
  },
  props: {
    form: {
      type: Object,
      required: true,
    },
    state: {
      type: Object,
      required: true,
    },
    data_jurusan: {
      type: Array,
      required: true
    },
    jumlah_form: {
      type: Number,
      default: () => 5,
    }
  },
  data() {
    return {
      loading_form: false,
      loading_kurikulum: false,
      data_kurikulum: [],
      data_status: [
        {
          value: null, text: '== Pilih Status =='
        },
        {
          value: '1', text: 'Aktif',
        },
        {
          value: '0', text: 'Tidak Aktif',
        },
      ],
    }
  },
  methods: {
    changeJurusan(val){
      this.form.kurikulum_id = ''
      if(val){
        this.state.jurusan_id_feedback = ''
        this.state.jurusan_id_state = true
        this.loading_kurikulum = true
        this.$http.post('/ukk/get-kurikulum', this.form).then(response => {
          this.loading_kurikulum = false
          let getData = response.data
          this.data_kurikulum = getData.kurikulum
        });
      }
    },
    changeKurikulum(val){
      if(val){
        this.state.kurikulum_id_feedback = ''
        this.state.kurikulum_id_state = true
      }
    },
  },
}
</script>