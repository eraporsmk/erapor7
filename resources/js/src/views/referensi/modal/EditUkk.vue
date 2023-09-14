<template>
  <div>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmitUnit">
        <b-row v-if="data">
          <b-col cols="12">
            <b-form-group label="Kompetensi Keahlian" label-for="jurusan_id" label-cols-md="3">
              <b-form-input id="jurusan_id" :value="data.jurusan.nama_jurusan" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kode Kompetensi" label-for="kurikulum_id" label-cols-md="3">
              <b-form-input id="kurikulum_id" :value="data.kurikulum_id" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nomor Paket" label-for="nomor_paket" label-cols-md="3">
              <b-form-input id="nomor_paket" v-model="form.nomor_paket"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket (ID)" label-for="nama_paket_id" label-cols-md="3">
              <b-form-input id="nama_paket_id" v-model="form.nama_paket_id"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket (EN)" label-for="nama_paket_en" label-cols-md="3">
              <b-form-input id="nama_paket_en" v-model="form.nama_paket_en"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Status" label-for="status" label-cols-md="3">
              <b-form-select id="status" v-model="form.status" :options="data_status"></b-form-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-table-simple hover bordered responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center" width="30%">Kode Unit</b-th>
                  <b-th class="text-center" width="65%">Nama Unit Kompetensi</b-th>
                  <b-th class="text-center" width="5%">#</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(unit_ukk, index) in data.unit_ukk">
                  <b-tr>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.kode_unit[unit_ukk.unit_ukk_id]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_unit[unit_ukk.unit_ukk_id]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td class="text-center">
                      <a class="text-danger" @click="deleteUnit(unit_ukk.unit_ukk_id)"><font-awesome-icon :icon="`fa-solid fa-trash`" /></a>
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
import { BOverlay, BForm, BFormInput, BFormSelect, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
export default {
  components: {
    BOverlay, BForm, BFormInput, BFormSelect, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd,
  },
  props: {
    form: {
      type: Object,
      required: true,
    },
    data: {
      required: true
    },
    jumlah_form: {
      type: Number,
      default: () => 5,
    },
    loading_form: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
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
    deleteUnit(unit_ukk_id){
      this.$emit('deleteUnit', unit_ukk_id)
    }
  },
}
</script>