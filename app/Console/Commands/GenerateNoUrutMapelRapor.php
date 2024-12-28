<?php

namespace App\Console\Commands;

use App\Models\Pembelajaran;
use Illuminate\Console\Command;

class GenerateNoUrutMapelRapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:noUrutRapor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatisasi set no urut rapor pada pembelajaran berdasarkan panduan rapor';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         /**
          * 16 - A. Kelompok Mata Pelajaran Umum
          * 17 - B . Kelompok Mata Pelajaran Kejuruan
          */

        $noUrut = [
            //Agama
            [
                'mata_pembelajaran_id' => [100011070, 100012050, 100013010, 100014140, 100015010],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 1
                ]
            ],
            //Pendidikan Pancasila
            [
                'mata_pembelajaran_id' => [200010300],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 2
                ]
            ],
            //Bahasa Indonesia
            [
                'mata_pembelajaran_id' => [300110000],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 3
                ]
            ],
            //Penjas
            [
                'mata_pembelajaran_id' => [500010000],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 4
                ]
            ],
            //Sejarah
            [
                'mata_pembelajaran_id' => [401230000],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 5
                ]
            ],
            //Seni Budaya
            [
                'mata_pembelajaran_id' => [700111000,700110010,700111000, 700114000, 700122000, 700121000, 700110000,700109000],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 6
                ]
            ],
            // Mulok Bahasa Daerah
            [
                'mata_pembelajaran_id' => [300311900],
                'data'            => [
                    'kelompok_id' => 16,
                    'no_urut'     => 7
                ]
            ],
            //Kelompok mapel kejuruan
            //Matematika
            [
                'mata_pembelajaran_id' => [401000000],
                'data'            => [
                    'kelompok_id' => 17,
                    'no_urut'     => 1
                ]
            ],
            //Bahasa Inggris
            [
                'mata_pembelajaran_id' => [300210000],
                'data'            => [
                    'kelompok_id' => 17,
                    'no_urut'     => 2
                ]
            ],
            //Informatika
            [
                'mata_pembelajaran_id' => [700106100],
                'data'            => [
                    'kelompok_id' => 17,
                    'no_urut'     => 3
                ]
            ],
            //IPAS
            [
                'mata_pembelajaran_id' => [401901000, 600070200],
                'data'            => [
                    'kelompok_id' => 17,
                    'no_urut'     => 4
                ]
            ],
            
            /**
             * Dasar-Dasar Program keahlian dan Kompetensi Keahlian 
             * Tambahkan id mapel pada array value mata_pelajaran_id
             * sesuaikan dengan kode mapel kejuruan dasar keahlian dan konsentrasi keahlian
             * pada sekolah anda
             * 
             */
            [
                'mata_pembelajaran_id' => [800000216,800000213,800000166,
                                            800000159,800000171,800000145,
                                            800000106,800000150,800000139,
                                            800000121, 800000122, 800000107,
                                            800000252,800000261, 800000278],
                'data'            => [
                    'kelompok_id' => 17,
                    'no_urut'     => 5
                ]
            ],
        ];
        //Perintah Update no urut agama

        //Pembelajaran
        foreach ($noUrut as $mapel):
            Pembelajaran::wherein('mata_pelajaran_id', $mapel['mata_pembelajaran_id'])->update($mapel['data']);
        endforeach;

        return Command::SUCCESS;
    }
}
