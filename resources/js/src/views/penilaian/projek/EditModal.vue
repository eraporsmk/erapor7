<template>
  <b-table-simple bordered responsive>
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
      <b-td>
        <b-form-group label-for="nama_projek" :invalid-feedback="feedback.nama_projek" :state="state.nama_projek">
          <b-form-input id="nama_projek" v-model="form.nama_projek" :state="state.nama_projek" />
        </b-form-group>
      </b-td>
    </b-tr>
    <b-tr>
      <b-td>Deskripsi Projek</b-td>
      <b-td>
        <b-form-group label-for="deskripsi" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
          <b-form-textarea id="deskripsi" v-model="form.deskripsi" :state="state.deskripsi"></b-form-textarea>
        </b-form-group>
      </b-td>
    </b-tr>
    <b-tr>
      <b-td>Nomor Urut Projek</b-td>
      <b-td>
        <b-form-group label-for="deskripsi" :invalid-feedback="feedback.no_urut" :state="state.no_urut">
          <b-form-input id="deskripsi" v-model="form.no_urut" :state="state.no_urut"></b-form-input>
        </b-form-group>
      </b-td>
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
</template>

<script>
import { 
  BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BFormTextarea
} from 'bootstrap-vue'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BFormTextarea
  },
  props: {
    data: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    state: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
  },
}
</script>