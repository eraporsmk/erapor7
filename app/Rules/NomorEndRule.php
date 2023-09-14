<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Blangko;

class NomorEndRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $kode_wilayah;
    protected $start;
    protected $tahun;
    public function __construct($kode_wilayah, $start, $tahun)
    {
        $this->kode_wilayah = $kode_wilayah;
        $this->start = $start;
        $this->tahun = $tahun;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $find = Blangko::where('end', '>=', $value)->whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->first();
        //$terkecil = Blangko::whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->min('start');
        //$terbesar = Blangko::whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->max('end');
        //$find = Blangko::whereBetween('end', [$terkecil, $terbesar])->whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->first();
        if($find){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nomor Ijazah (SMK Program '.$this->tahun.' Tahun) sudah terdaftar milik Daerah lain';
    }
}
