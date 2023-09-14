<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Blangko;

class NomorStartRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $kode_wilayah;
    protected $end;
    protected $tahun;
    public function __construct($kode_wilayah, $end, $tahun)
    {
        $this->kode_wilayah = $kode_wilayah;
        $this->end = $end;
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
        /*$collection = collect([
            ['product' => 'Desk', 'price' => 1],
            ['product' => 'Chair', 'price' => 2],
            ['product' => 'Bookcase', 'price' => 3],
            ['product' => 'Pencil', 'price' => 4],
            ['product' => 'Door', 'price' => 5],
        ]);
         
        $filtered = $collection->whereBetween('price', [1, 4]);
         
        dd($filtered->all());
        dump([$value, $this->end]);*/
        //$find = Blangko::whereBetween('start', [$value, $this->end])->whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->first();
        //$value = 2;
        //$this->end = 101;
        $terkecil = Blangko::whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->min('start');
        $terbesar = Blangko::whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->max('end');
        return $value < $terkecil || $value > $terbesar;
        return $value >= $terkecil && $value <= $terbesar;
        //dump($terkecil);
        //dump($terbesar);
        //$find = Blangko::whereBetween('start', [$terkecil, $terbesar])->whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->first();
        //$value = 100; //<= 100: ditolak
        $find = Blangko::where('end', '<=', $value)->whereNotNull('kode_wilayah')->where('tahun', $this->tahun)->where('kode_wilayah', '<>', $this->kode_wilayah)->first();
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
