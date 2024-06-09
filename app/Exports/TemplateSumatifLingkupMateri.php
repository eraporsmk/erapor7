<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class TemplateSumatifLingkupMateri implements FromView, WithStyles, WithColumnWidths, WithEvents
{
    use Exportable;
    public function __construct(array $data){
        $this->data = $data;
        $this->jumlah_siswa = $data['data_siswa']->count();
        $this->jumlah_tp = $data['data_tp']->count();
        $arr = [];
        for($x = 'A'; $x < 'ZZ'; $x++){
            $arr[] = $x;
        }
        $this->arr = $arr;
        $huruf_akhir = $this->arr[$this->jumlah_tp + 3];
        $angka_akhir = $this->jumlah_siswa + 9;
        $this->last = $huruf_akhir.$angka_akhir;
    }
    public function view(): View
    {
        return view('unduhan.template_sumatif_lingkup_materi', $this->data);
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(false);
        $sheet->getColumnDimension('B')->setVisible(false);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A9:'.$this->arr[$this->jumlah_tp + 3].'9')->getFont()->setBold(true);
        $sheet->getProtection()->setSheet(true);
        $sheet->getStyle('E10:'.$this->last)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        foreach($this->data['data_tp'] as $index => $tp){
            $sheet->getColumnDimension($this->arr[$index + $this->jumlah_tp + 4])->setVisible(false);
            $sheet->getComment($this->arr[$index + 4].'9')->getText()->createTextRun($tp->deskripsi);
            foreach($this->data['data_siswa'] as $key => $val){
                $sheet->getComment($this->arr[$index + 4].$key + 10)->getText()->createTextRun($tp->deskripsi);
            }
        }
    }
    public function columnWidths(): array
    {
        $default = [
            'A' => 5,
            'C' => 30,
            'D' => 15,
        ];
        return $default;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A9:'.$this->last)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
