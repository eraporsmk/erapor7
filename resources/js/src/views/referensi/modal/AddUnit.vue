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
              <b-form-input id="nomor_paket" :value="data.nomor_paket" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket" label-for="nama_paket_id" label-cols-md="3">
              <b-form-input id="nama_paket_id" :value="data.nama_paket_id" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-table-simple hover bordered responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center" width="30%">Kode Unit</b-th>
                  <b-th class="text-center" width="70%">Nama Unit Kompetensi</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="formulir in jumlah_form">
                  <b-tr>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.kode_unit[formulir]"></b-form-input>
                      </b-form-group>
                    </b-td>
                    <b-td>
                      <b-form-group>
                        <b-form-input v-model="form.nama_unit[formulir]"></b-form-input>
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
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd,
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
}
</script>