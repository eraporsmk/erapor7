<template>
    <b-col cols="12">
      <b-table-simple bordered striped responsive>
        <b-thead>
          <b-tr>
            <b-th class="text-center">No</b-th>
            <b-th class="text-center">Nama Peserta Didik</b-th>
            <b-th class="text-center">Non Tes</b-th>
            <b-th class="text-center">Tes</b-th>
            <b-th class="text-center">NA Sumatif Akhir Semester</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(siswa, index) in data_siswa">
            <b-tr>
              <b-td class="text-center" style="vertical-align:middle">{{index + 1}}</b-td>
              <b-td style="vertical-align:middle">{{siswa.nama}}</b-td>
              <b-td><b-form-input v-model="form.nilai_sumatif[siswa.anggota_rombel.anggota_rombel_id+'#non-tes']" @input="setRerata(siswa.anggota_rombel.anggota_rombel_id, '#non-tes')" /></b-td>
              <b-td><b-form-input v-model="form.nilai_sumatif[siswa.anggota_rombel.anggota_rombel_id+'#tes']" @input="setRerata(siswa.anggota_rombel.anggota_rombel_id, '#tes')"/></b-td>
              <b-td><b-form-input v-model="form.nilai_sumatif[siswa.anggota_rombel.anggota_rombel_id+'#na']" disabled /></b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
    </b-col>
</template>

<script>
import { BCol, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormInput, BFormGroup, BButton } from 'bootstrap-vue'

export default {
  components: {
    BCol,
    BFormInput,
    BFormGroup,
    BButton,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
  },
  props: {
    data_siswa: {
      required: true
    },
    form: {
      required: true
    },
  },
  methods: {
    setRerata(anggota_rombel_id, jenis){
      this.$emit('setRerata', {
        anggota_rombel_id: anggota_rombel_id,
        jenis: jenis,
        non_tes: this.form.nilai_sumatif[`${anggota_rombel_id}#non-tes`],
        tes: this.form.nilai_sumatif[`${anggota_rombel_id}#tes`],
      })
    }
  },
}
</script>
