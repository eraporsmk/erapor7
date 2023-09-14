<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Kompetensi_dasar;
use App\Models\Kelompok;
use App\Models\Budaya_kerja;
use App\Models\Elemen_budaya_kerja;
use App\Models\Opsi_budaya_kerja;
use App\Models\Teknik_penilaian;
use App\Models\Mata_pelajaran;
use App\Models\Capaian_pembelajaran;
use App\Models\Role;
use App\Models\Gelar;
use Carbon\Carbon;
use File;

class RefCP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ref:cp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = File::get(database_path('data/gelar_akademik.json'));
        $data = json_decode($data);
        foreach($data as $d){
            Gelar::updateOrCreate(
                [
                    'gelar_akademik_id' => $d->gelar_akademik_id,
                ],
                [
                    'kode' => $d->kode,
                    'nama' => $d->nama,
                    'posisi_gelar' => $d->posisi_gelar,
                    'created_at' => $d->create_date,
                    'updated_at' => $d->last_update,
                    'deleted_at' => $d->expired_date,
                    'last_sync' => $d->last_sync,
                ]
            );
        }
        $data = [
            [
                'name' => 'guru-p5',
                'display_name' => 'Koord P5',
                'description' => 'Koord P5',
            ]
        ];
        foreach($data as $d){
            Role::updateOrCreate(
                [
                    'name' => $d['name'],
                ],
                [
                    'display_name' => $d['display_name'],
                    'description' => $d['description'],
                ]
            );
        }
        $data = [
            [
                'id' => 1,
                    'nama' => 'Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan Berakhlak Mulia',
                    'elemen' => [
                        [
                            'id' => 1,
                            'nama' => 'Akhlak beragama',
                            'deskripsi' => 'Melakukan perbuatan baik sesuai tuntunan ajaran agama secara sadar dan berulang'
                        ],
                        [
                            'id' => 2,
                            'nama' => 'Akhlak pribadi',
                            'deskripsi' => 'Berkata yang baik dan jujur, menjaga amanah dengan baik, konsisten, serta menjauhkan diri dari perbuatan yang kurang baik'
                        ],
                        [
                            'id' => 3,
                            'nama' => 'Akhlak kepada manusia',
                            'deskripsi' => 'Melakukan perbuatan baik kepada orang lain'
                        ],
                        [
                            'id' => 4,
                            'nama' => 'Akhlak kepada alam',
                            'deskripsi' => 'Memelihara alam'
                        ],
                        [
                            'id' => 5,
                            'nama' => 'Akhlak bernegara',
                            'deskripsi' => 'Mematuhi peraturan perundangan yang berlaku'
                        ],
                        [
                            'id' => 21,
                            'nama' => 'Akhlak beragama',
                            'deskripsi' => 'Menerapkan pemahamannya tentang kualitas atau sifat-sifat Tuhan dalam ritual ibadahnya baik ibadah yang bersifat personal maupun sosial.'
                        ],
                        [
                            'id' => 22,
                            'nama' => 'Akhlak beragama',
                            'deskripsi' => 'Memahami struktur organisasi, unsur-unsur utama agama/kepercayaan dalam konteks Indonesia, memahami kontribusi agama/kepercayaan terhadap peradaban dunia.'
                        ],
                        [
                            'id' => 23,
                            'nama' => 'Akhlak beragama',
                            'deskripsi' => 'Melaksanakan ibadah secara rutin dan mandiri serta menyadari arti penting ibadah tersebut dan berpartisipasi aktif pada kegiatan keagamaan atau kepercayaan'
                        ],
                        [
                            'id' => 24,
                            'nama' => 'Akhlak pribadi',
                            'deskripsi' => 'Menyadari bahwa aturan agama dan sosial merupakan aturan yang baik dan menjadi bagian dari diri sehingga bisa menerapkannya secara bijak dan kontekstual'
                        ],
                        [
                            'id' => 25,
                            'nama' => 'Akhlak pribadi',
                            'deskripsi' => 'Melakukan aktivitas fisik, sosial, dan ibadah secara seimbang.'
                        ],
                        [
                            'id' => 26,
                            'nama' => 'Akhlak kepada manusia',
                            'deskripsi' => 'Mengidentifikasi hal yang menjadi permasalahan bersama, memberikan alternatif solusi untuk menjembatani perbedaan dengan mengutamakan kemanusiaan.'
                        ],
                        [
                            'id' => 27,
                            'nama' => 'Akhlak kepada manusia',
                            'deskripsi' => 'Memahami dan menghargai perasaan dan sudut pandang orang dan/atau kelompok lain.'
                        ],
                        [
                            'id' => 28,
                            'nama' => 'Akhlak kepada alam',
                            'deskripsi' => 'Mengidentifikasi masalah lingkungan hidup di tempat ia tinggal dan melakukan langkah-langkah konkret yang bisa dilakukan untuk menghindari kerusakan dan menjaga keharmonisan ekosistem yang ada di lingkungannya.'
                        ],
                        [
                            'id' => 29,
                            'nama' => 'Akhlak kepada alam',
                            'deskripsi' => 'Mewujudkan rasa syukur dengan membangun kesadaran peduli lingkungan alam dengan menciptakan dan mengimplementasikan solusi dari permasalahan lingkungan yang ada.'
                        ],
                        [
                            'id' => 30,
                            'nama' => 'Akhlak bernegara',
                            'deskripsi' => 'Menggunakan hak dan melaksanakan kewajiban kewarganegaraan dan terbiasa mendahulukan kepentingan umum di atas kepentingan pribadi sebagai wujud dari keimanannya kepada Tuhan YME.'
                        ],
                    ],
            ],
            [
                'id' => 2,
                    'nama' => 'Bernalar Kritis',
                    'elemen' => [
                        [
                            'id' => 6,
                            'nama' => 'Mengidentifikasi, mengklarifikasi, dan mengolah informasi dan gagasan',
                            'deskripsi' => 'Secara kritis mengklarifikasi serta menganalisis gagasan dan informasi yang kompleks dan abstrak dari berbagai sumber. Memprioritaskan suatu gagasan yang paling relevan dari hasil klarifikasi dan analisis'
                        ],
                        [
                            'id' => 7,
                            'nama' => 'Menganalisis dan mengevaluasi penalaran',
                            'deskripsi' => 'Menganalisis dan mengevaluasi penalaran yang digunakannya dalam menemukan dan mencari solusi serta mengambil keputusan'
                        ],
                        [
                            'id' => 8,
                            'nama' => 'Merefleksi dan mengevaluasi pemilirannya sendiri',
                            'deskripsi' => 'Menjelaskan alasan untuk mendukung pemikirannya dan memikirkan pandangan yang mungkin berlawanan dengan pemikirannya dan mengubah pemikirannya jika diperlukan'
                        ],
                        [
                            'id' => 56,
                            'nama' => 'Memperoleh dan memproses informasi dan gagasan',
                            'deskripsi' => 'Mengajukan pertanyaan untuk menganalisis secara kritis permasalahan yang kompleks dan abstrak.'
                        ],
                        [
                            'id' => 57,
                            'nama' => 'Memperoleh dan memproses informasi dan gagasan',
                            'deskripsi' => 'Secara kritis mengklarifikasi serta menganalisis gagasan dan informasi yang kompleks dan abstrak dari berbagai sumber. Memprioritaskan suatu gagasan yang paling relevan dari hasil klarifikasi dan analisis.'
                        ],
                        [
                            'id' => 58,
                            'nama' => 'Menganalisis dan mengevaluasi penalaran dan prosedurnya',
                            'deskripsi' => 'Menganalisis dan mengevaluasi penalaran yang digunakannyadalam menemukan dan mencari solusi serta mengambil keputusan.'
                        ],
                        [
                            'id' => 59,
                            'nama' => 'Refleksi pemikiran dan proses berpikir',
                            'deskripsi' => 'Menjelaskan alasan untuk mendukung pemikirannya dan memikirkan pandangan yang mungkin berlawanan dengan pemikirannya dan mengubah pemikirannya jika diperlukan.'
                        ],
                    ],
            ],
            [
                'id' => 3,
                    'nama' => 'Mandiri',
                    'elemen' => [
                        [
                            'id' => 9,
                            'nama' => 'Pemahaman diri dan situasi',
                            'deskripsi' => 'Mempunyai kemampuan dalam membaca keadaan diri dalam menghadapi tantangan yang ada serta mencari pemecahan tantangan  berdasarkan situasi yang ada.'
                        ],
                        [
                            'id' => 10,
                            'nama' => 'Regulasi diri',
                            'deskripsi' => 'Mempunyai standar dalam mengatur diri sendiri dan menjalankan kewajiban diri dengan tetap menghormati hak-hak orang lain.'
                        ],
                        [
                            'id' => 49,
                            'nama' => 'Pemahaman diri dan situasi',
                            'deskripsi' => 'Mengidentifikasi kekuatan dan tantangan-tantangan yang akan dihadapi pada konteks pembelajaran, sosial dan pekerjaan yang akan dipilihnya dimasa depan.'
                        ],
                        [
                            'id' => 50,
                            'nama' => 'Pemahaman diri dan situasi',
                            'deskripsi' => 'Melakukan refleksi terhadap umpan balik dari teman, guru, dan orang dewasa lainnya, serta informasi-informasi karir yang akan dipilihnya untuk menganalisis karakteristik dan keterampilan yang dibutuhkan dalam menunjang atau menghambat karirnya di masa depan.'
                        ],
                        [
                            'id' => 51,
                            'nama' => 'Regulasi diri',
                            'deskripsi' => 'Mengendalikan dan menyesuaikan emosi yang dirasakannya secara tepat ketika menghadapi situasi yang menantang dan menekan pada konteks belajar, relasi, dan pekerjaan.'
                        ],
                        [
                            'id' => 52,
                            'nama' => 'Regulasi diri',
                            'deskripsi' => 'Mengevaluasi efektivitas strategi pembelajaran digunakannya, serta menetapkan tujuan belajar, prestasi, dan pengembangan diri secara spesifik dan merancang strategi yang sesuai untuk menghadapi tantangan-tantangan yang akan dihadapi pada konteks pembelajaran, sosial dan pekerjaan yang akan dipilihnya di masa depan.'
                        ],
                        [
                            'id' => 53,
                            'nama' => 'Regulasi diri',
                            'deskripsi' => 'Menentukan prioritas pribadi, berinisiatif mencari dan mengembangkan pengetahuan dan keterampilan yang spesifik sesuai tujuan di masa depan.'
                        ],
                        [
                            'id' => 54,
                            'nama' => 'Regulasi diri',
                            'deskripsi' => 'Melakukan tindakan-tindakan secara konsisten guna mencapai tujuan karirdan pengembangan dirinya di masa depan, serta berusaha mencari dan melakukan alternatif tindakan lain yang dapat dilakukan ketika menemui hambatan.'
                        ],
                        [
                            'id' => 55,
                            'nama' => 'Regulasi diri',
                            'deskripsi' => 'Menyesuaikan dan mulai menjalankan rencana dan strategi pengembangan dirinya dengan mempertimbangkan minat dan tuntutan pada konteks belajar maupun pekerjaan yang akan dijalaninya di masa depan, serta berusaha untuk mengatasi tantangan-tantangan yang ditemui.'
                        ],
                    ],
            ],
            [
                'id' => 4,
                    'nama' => 'Berkebinekaan Global',
                    'elemen' => [
                        [
                            'id' => 11,
                            'nama' => 'Mengenal dan benghargai budaya',
                            'deskripsi' => 'Keinginan untuk mengetahui budaya lain dan membangun rasa menghargai terhadap kebudayaan yang berbeda tersebut'
                        ],
                        [
                            'id' => 12,
                            'nama' => 'Komunikasi dan interaksi antar budaya',
                            'deskripsi' => 'Kemampuan dalam menjalin hubungan melalui berbagai macam bentuk komunikasi dan interaksi dengan orang lain yang mempunyai budaya dan latar belakang yang berbeda'
                        ],
                        [
                            'id' => 13,
                            'nama' => 'Refleksi dan tanggungjawab terhadap kebinekaan',
                            'deskripsi' => 'Keinginan untuk menjadikan pengalaman bertemu orang yang berbeda sebagai satu cara untuk membangun persahabatan dan pertemanan yang berdasarkan pada nilai-nilai kemanusiaan'
                        ],
                        [
                            'id' => 14,
                            'nama' => 'Berkeadilan sosial',
                            'deskripsi' => 'Kemampuan dalam bersiap adil terhadap orang-orang yang berbeda latar belakang'
                        ],
                        [
                            'id' => 31,
                            'nama' => 'Mengenal dan benghargai budaya',
                            'deskripsi' => 'Menganalisis pengaruh keanggotaan kelompok lokal, regional, nasional, dan global terhadap pembentukan identitas, termasuk identitas dirinya. Mulai menginternalisasi identitas diri sebagai bagian dari budaya bangsa.'
                        ],
                        [
                            'id' => 32,
                            'nama' => 'Mengenal dan benghargai budaya',
                            'deskripsi' => 'Menganalisis dinamika budaya yang mencakup pemahaman, kepercayaan, dan praktik keseharian dalam rentang waktu yang panjang dan konteks yang luas.'
                        ],
                        [
                            'id' => 33,
                            'nama' => 'Mengenal dan benghargai budaya',
                            'deskripsi' => 'Mempromosikan pertukaran budaya dan kolaborasi dalam dunia yang saling terhubung serta menunjukkannya dalam perilaku.'
                        ],
                        [
                            'id' => 34,
                            'nama' => 'Komunikasi dan interaksi antar budaya',
                            'deskripsi' => 'Menganalisis hubungan antara bahasa, pikiran, dan konteks untukmemahami dan meningkatkan komunikasi antarbudaya yangberbeda-beda.'
                        ],
                        [
                            'id' => 35,
                            'nama' => 'Komunikasi dan interaksi antar budaya',
                            'deskripsi' => 'Menyajikan pandangan yang seimbang mengenai permasalahan yang dapat menimbulkan pertentangan pendapat, memosisikan orang lain dan budaya yang berbeda darinya secara setara, serta bersedia memberikan pertolongan ketika orang lain berada dalam situasi sulit.'
                        ],
                        [
                            'id' => 36,
                            'nama' => 'Refleksi dan tanggungjawab terhadap kebinekaan',
                            'deskripsi' => 'Merefleksikan secara kritis dampak dari pengalaman hidup di lingkungan yang beragam terkait dengan perilaku, kepercayaan serta tindakannya terhadap orang lain.'
                        ],
                        [
                            'id' => 37,
                            'nama' => 'Refleksi dan tanggungjawab terhadap kebinekaan',
                            'deskripsi' => 'Mengkritik dan menolak stereotip serta prasangka tentang gambaran identitas kelompok dan suku bangsa serta berinisiatif mengajak orang lain untuk menolak stereotip dan prasangka.'
                        ],
                        [
                            'id' => 38,
                            'nama' => 'Refleksi dan tanggungjawab terhadap kebinekaan',
                            'deskripsi' => 'Mengetahui tantangan dan keuntungan hidup dalam lingkungan dengan budaya yang beragam, serta memahami pentingnya kerukunan antar budaya dalam kehidupan bersama yang harmonis.'
                        ],
                        [
                            'id' => 39,
                            'nama' => 'Berkeadilan sosial',
                            'deskripsi' => 'Berinisiatif melakukan suatu tindakan berdasarkan identifikasi masalah untuk mempromosikan keadilan, keamanan ekonomi, menopang ekologi dan demokrasi sambil menghindari kerugian jangka panjang terhadap manusia, alam ataupun masyarakat.'
                        ],
                        [
                            'id' => 40,
                            'nama' => 'Berkeadilan sosial',
                            'deskripsi' => 'Berpartisipasi menentukan pilihan dan keputusan untuk kepentingan bersama melalui proses bertukar pikiran secara cermat dan terbuka secara mandiri.'
                        ],
                        [
                            'id' => 41,
                            'nama' => 'Berkeadilan sosial',
                            'deskripsi' => 'Memahami konsep hak dan kewajiban, serta implikasinya terhadap ekspresi dan perilakunya. Mulai mencari solusi untuk dilema terkait konsep hak dan kewajibannya.'
                        ],
                    ],
            ],
            [
                'id' => 5,
                    'nama' => 'Perkembangan Dimensi Kreatif',
                    'elemen' => [
                        [
                            'id' => 15,
                            'nama' => 'Menghasilkan gagasan yang orisinal',
                            'deskripsi' => 'Melahirkan gagasan berdasarkan pemikiran sendiri atau tim dengan mempertimbangkan berbagai macam informasi yang sesuai dengan gagasan tersebut.'
                        ],
                        [
                            'id' => 16,
                            'nama' => 'Melahirkan karya dan tindakan yang orisinal',
                            'deskripsi' => 'Melahirkan sesuatu yang asli serta bisa meningkatkan kualitas hidup diri sendiri serta orang banyak.'
                        ],
                        [
                            'id' => 17,
                            'nama' => 'Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan',
                            'deskripsi' => 'Mampu mencari alternatif-alternatif penyelesaian suatu masalah dengan mempertimbangkan baik atau buruknya solusi tersebut termasuk keluar dalam tekanan'
                        ],
                        [
                            'id' => 60,
                            'nama' => 'Menghasilkan gagasan yang orisinal',
                            'deskripsi' => 'Menghasilkan gagasan yang beragam untuk mengekspresikan pikiran dan/atau perasaannya, menilai gagasannya, serta memikirkan segala risikonya dengan mempertimbangkan banyak perspektif seperti etika dan nilai kemanusiaan ketika gagasannya direalisasikan.'
                        ],
                        [
                            'id' => 61,
                            'nama' => 'Melahirkan karya dan tindakan yang orisinal',
                            'deskripsi' => 'Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya dalam bentuk karya dan/atau tindakan, serta mengevaluasinya dan mempertimbangkan dampak dan risikonya bagi diri dan lingkungannya dengan menggunakan berbagai perspektif.'
                        ],
                        [
                            'id' => 62,
                            'nama' => 'Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan',
                            'deskripsi' => 'Bereksperimen dengan berbagai pilihan secara kreatif untuk memodifikasi gagasan sesuai dengan perubahan situasi.'
                        ],
                    ],
            ],
            [
                'id' => 6,
                    'nama' => 'Bergotong royong',
                    'elemen' => [
                        [
                            'id' => 18,
                            'nama' => 'Kolaborasi',
                            'deskripsi' => 'Menjalin kerjasama dan bersinergi untuk mencapai tujuan dan kebaikan bersama dengan mengesampingkan kepentingan pribadi.'
                        ],
                        [
                            'id' => 19,
                            'nama' => 'Kepedulian',
                            'deskripsi' => 'Mengekspresikan kepedulian pada sesama dan makhluk hidup lainnya'
                        ],
                        [
                            'id' => 20,
                            'nama' => 'Berbagi',
                            'deskripsi' => 'Berbagi setiap sumber daya yang dimiliki, termasuk ilmu dan pengetahuan  dengan tetap berpegang teguh pada nilai-nilai kebenaran dan kemajuan bersama'
                        ],
                        [
                            'id' => 42,
                            'nama' => 'Kolaborasi',
                            'deskripsi' => 'Membangun tim dan mengelola kerjasama untuk mencapai tujuan bersama sesuai dengan target yang sudah ditentukan.'
                        ],
                        [
                            'id' => 43,
                            'nama' => 'Kolaborasi',
                            'deskripsi' => 'Aktif menyimak untuk memahami dan menganalisis informasi, gagasan, emosi, keterampilan dan keprihatinan yang disampaikan oleh orang lain dan kelompok menggunakan berbagai simbol dan media secara efektif, serta menggunakan berbagai strategi komunikasi untuk menyelesaikan masalah guna mencapai berbagai tujuan bersama.'
                        ],
                        [
                            'id' => 44,
                            'nama' => 'Kolaborasi',
                            'deskripsi' => 'Menyelaraskan kapasitas kelompok agar para anggota kelompok dapat saling membantu satu sama lain memenuhi kebutuhan mereka baik secara individual maupun kolektif.'
                        ],
                        [
                            'id' => 45,
                            'nama' => 'Kolaborasi',
                            'deskripsi' => 'Menyelaraskan dan menjaga tindakan diri dan anggota kelompok agar sesuai antara satu dengan lainnya serta menerima konsekuensi tindakannya dalam rangka mencapai tujuan bersama.'
                        ],
                        [
                            'id' => 46,
                            'nama' => 'Kepedulian',
                            'deskripsi' => 'Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan berkontribusi sesuai dengan kebutuhan masyarakat untuk menghasilkan keadaan yang lebih baik.'
                        ],
                        [
                            'id' => 47,
                            'nama' => 'Kepedulian',
                            'deskripsi' => 'Melakukan tindakan yang tepat agar orang lain merespon sesuai dengan yang diharapkan dalam rangka penyelesaian pekerjaan dan pencapaian tujuan.'
                        ],
                        [
                            'id' => 48,
                            'nama' => 'Berbagi',
                            'deskripsi' => 'Mengupayakan memberi hal yang dianggap penting dan berharga kepada orang-orang yang membutuhkan di masyarakat yang lebih luas (negara, dunia).'
                        ],
                    ],
            ],
        ];
        foreach($data as $d){
            Budaya_kerja::updateOrCreate(
                [
                    'budaya_kerja_id' => $d['id'],
                ],
                [
                    'aspek' => $d['nama'],
                    'last_sync' => now(),
                ]
            );
            foreach($d['elemen'] as $elemen){
                Elemen_budaya_kerja::updateOrCreate(
                    [
                        'elemen_id' => $elemen['id'],
                    ],
                    [
                        'budaya_kerja_id' => $d['id'],
                        'elemen' => $elemen['nama'],
                        'deskripsi' => $elemen['deskripsi'],
                        'last_sync' => now(),
                    ]
                );
            }
        }
        $data = [
            [
                'id' => 1,
                'kode' => 'BB',
                'nama' => 'Belum Berkembang',
                'deskripsi' => 'Peserta Didik masih membutuhkan bimbingan dalam mengembangkan kemampuan',
                'warna' => 'yellow',
            ],
            [
                'id' => 2,
                'kode' => 'MB',
                'nama' => 'Mulai Berkembang',
                'deskripsi' => 'Peserta Didik mulai mengembangkan kemampuan namun masih belum ajek',
                'warna' => 'yellow',
            ],
            [
                'id' => 5,
                'kode' => 'SB',
                'nama' => 'Sedang Berkembang',
                'deskripsi' => 'Peserta Didik mulai mulai mengembangkan kemampuan',
                'warna' => 'blue',
            ],
            [
                'id' => 3,
                'kode' => 'BSH',
                'nama' => 'Berkembang Sesuai Harapan',
                'deskripsi' => 'Peserta Didik telah mengembangkan kemampuan hingga berada dalam tahap ajek',
                'warna' => 'red',
            ],
            [
                'id' => 4,
                'kode' => 'SAB',
                'nama' => 'Sangat Berkembang',
                'deskripsi' => 'Peserta Didik mengembangkan kemampuannya melampaui harapan',
                'warna' => 'green',
            ],
        ];
        foreach($data as $d){
            Opsi_budaya_kerja::updateOrCreate(
                [
                    'opsi_id' => $d['id'],
                ],
                [
                    'kode' => $d['kode'],
                    'nama' => $d['nama'],
                    'deskripsi' => $d['deskripsi'],
                    'warna' => $d['warna'],
                    'last_sync' => now(),
                ]
            );
        }
        $data = [
            [
                'kompetensi_id' => 3,
                'nama' => 'Sumatif (SMK PK)',
                'bobot' => 1,
            ],
            [
                'kompetensi_id' => 3,
                'nama' => 'Formatif (SMK PK)',
                'bobot' => 0,
            ],
            [
                'kompetensi_id' => 4,
                'nama' => 'Sumatif Lingkup Materi',
                'bobot' => NULL,
            ],
            [
                'kompetensi_id' => 4,
                'nama' => 'Sumatif Akhir Semester',
                'bobot' => NULL,
            ],
        ];
        foreach($data as $d){
            Teknik_penilaian::updateOrCreate(
                [
                    'kompetensi_id' => $d['kompetensi_id'],
                    'nama' => $d['nama'],
                    'bobot' => $d['bobot'],
                ],
                [
                    'last_sync' => now(),
                ]
            );
        }
        $data = (new FastExcel)->import(public_path('templates/kkm.xlsx'), function ($line) {
            Kelompok::updateOrCreate(
                [
                    'kelompok_id' => $line['kelompok_id'],
                ],
                [
                    'nama_kelompok' => $line['nama_kelompok'],
                    'kurikulum' => $line['kurikulum'],
                    'kkm' => ($line['kkm']) ? $line['kkm'] : NULL,
                    'last_sync' => now(),
                ]
            );
        });
        $data = (new FastExcel)->import(public_path('templates/ref_cp.xlsx'), function ($line) {
            $mapel = Mata_pelajaran::find($line['mata_pelajaran_id']);
            if($mapel){
                $find = Kompetensi_dasar::where(function($query) use ($line){
                    $query->where('id_kompetensi', $line['elemen']);
                    $query->where('kompetensi_id', $line['kompetensi_id']);
                    $query->where('mata_pelajaran_id', $line['mata_pelajaran_id']);
                    if($line['fase'] == 'E'){
                        $query->where('kelas_10', 1);
                        $query->where('kelas_11', 0);
                        $query->where('kelas_12', 0);
                        $query->where('kelas_13', 0);
                    } else {
                        $query->where('kelas_10', 0);
                        $query->where('kelas_11', 1);
                        $query->where('kelas_12', 1);
                        $query->where('kelas_13', 1);
                    }
                })->first();
                if($find){
                    $find->id_kompetensi = $line['elemen'];
                    $find->kompetensi_id = $line['kompetensi_id'];
                    $find->mata_pelajaran_id = $line['mata_pelajaran_id'];
                    if($line['fase'] == 'E'){
                        $find->kelas_10 = 1;
                        $find->kelas_11 = 0;
                        $find->kelas_12 = 0;
                        $find->kelas_13 = 0;
                    } else {
                        $find->kelas_10 = 0;
                        $find->kelas_11 = 1;
                        $find->kelas_12 = 1;
                        $find->kelas_13 = 1;
                    }
                    $find->kurikulum = $line['kurikulum'];
                    $find->kompetensi_dasar = $line['deskripsi'];
                    $find->last_sync = now();
                    $find->save();
                } else {
                    if($line['fase'] == 'E'){
                        $kelas_10 = 1;
                        $kelas_11 = 0;
                        $kelas_12 = 0;
                        $kelas_13 = 0;
                    } else {
                        $kelas_10 = 0;
                        $kelas_11 = 1;
                        $kelas_12 = 1;
                        $kelas_13 = 1;
                    }
                    Kompetensi_dasar::create(
                        [
                            'id_kompetensi' => $line['elemen'],
                            'kompetensi_id' => 3,
                            'mata_pelajaran_id' => $line['mata_pelajaran_id'],
                            'kelas_10' => $kelas_10,
                            'kelas_11' => $kelas_11,
                            'kelas_12' => $kelas_12,
                            'kelas_13' => $kelas_13,
                            'kurikulum' => $line['kurikulum'],
                            'kompetensi_dasar_id'	=> Str::uuid(),
                            'kompetensi_dasar' => $line['deskripsi'],
                            'last_sync' => now(),
                        ]
                    );
                }
            }
        });
        Kompetensi_dasar::where('kurikulum', 2022)->forceDelete();
        $data = (new FastExcel)->import(public_path('templates/ref_cp_2.xlsx'), function ($line) {
            $mapel = NULL;
            if($line['no'] && $line['mata_pelajaran_id']){
                $mapel = Mata_pelajaran::find($line['mata_pelajaran_id']);
                if($mapel){
                    Capaian_pembelajaran::updateOrCreate(
                        [
                            'cp_id' => $line['no']
                        ],
                        [
                            'mata_pelajaran_id' => $line['mata_pelajaran_id'],
                            'fase' => $line['fase'],
                            'elemen' => $line['elemen'],
                            'deskripsi' => $line['deskripsi'],
                            'created_at' => Carbon::create('2022', '07', '01', '00', '00', '01'),
                            'updated_at' => now(),
                            'last_sync' => now(),
                            'is_dir' => 1,
                        ]
                    );
                } else {
                    $this->info($line['mata_pelajaran_id'] . ' belum tersedia');
                }
            } else {
                if(Capaian_pembelajaran::where('cp_id', $line['no'])->delete()){
                    $this->error($line['no'] . ' terhapus');
                }
            }
        });
    }
}
