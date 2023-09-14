<?php

namespace App\Http\Requests;

use App\Rules\NomorStartRule;
use App\Rules\NomorEndRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class NomorIjazahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_3' => 
                [
                    'required',
                    'integer',
                    new NomorStartRule($this->kode_wilayah, request()->end_3, 3),
                ],
                'end_3' => 
                [
                    'required',
                    'integer',
                    new NomorEndRule($this->kode_wilayah, request()->start_3, 3),
                    'gte:start_3',
                ],
                'jumlah_3' => 
                [
                    'required',
                    'integer',
                ],
                'start_4' => 
                [
                    'nullable',
                    'integer',
                    new NomorStartRule($this->kode_wilayah, request()->end_4, 4),
                ],
                'end_4' => 
                [
                    'nullable',
                    'integer',
                    new NomorStartRule($this->kode_wilayah, request()->start_4, 4),
                    'gte:start_4',
                ],
                'jumlah_4' => 
                [
                    'nullable',
                    'integer',
                ]
        ];
    }
    public function messages()
    {
        return [
            'jumlah_3.required' => 'Jumlah Blangko Ijazah Diterima (SMK Program 3 Tahun) tidak boleh kosong',
            'jumlah_3.integer' => 'Jumlah Blangko Ijazah Diterima (SMK Program 3 Tahun) harus berupa angka',
            'start_3.required' => 'Nomor Seri (SMK Program 3 Tahun) tidak boleh kosong',
            'start_3.integer' => 'Nomor Seri (SMK Program 3 Tahun) harus berupa angka',
            'end_3.required' => 'Nomor Seri (SMK Program 3 Tahun) tidak boleh kosong',
            'end_3.integer' => 'Nomor Seri (SMK Program 3 Tahun) harus berupa angka',
            'start_3.unique' => 'Nomor Seri (SMK Program 3 Tahun) sudah terdaftar milik Daerah lain',
            'end_3.unique' => 'Nomor Seri (SMK Program 3 Tahun) sudah terdaftar milik Daerah lain',
            'end_3.gte' => 'Nomor Urut Akhir harus sama dengan atau lebih besar dari Nomor Urut Awal',
            'jumlah_4.integer' => 'Jumlah Blangko Ijazah Diterima (SMK Program 4 Tahun) harus berupa angka',
            'start_4.integer' => 'Nomor Seri (SMK Program 4 Tahun) harus berupa angka',
            'end_4.integer' => 'Nomor Seri (SMK Program 4 Tahun) harus berupa angka',
            'start_4.unique' => 'Nomor Seri (SMK Program 4 Tahun) sudah terdaftar milik Daerah lain',
            'end_4.unique' => 'Nomor Seri (SMK Program 4 Tahun) sudah terdaftar milik Daerah lain',
            'start_4.required' => 'Nomor Seri (SMK Program 4 Tahun) tidak boleh kosong',
            'end_4.required' => 'Nomor Seri (SMK Program 4 Tahun) tidak boleh kosong',
            'end_4.gte' => 'Nomor Urut Akhir harus sama dengan atau lebih besar dari Nomor Urut Awal',
        ];
    }
}
