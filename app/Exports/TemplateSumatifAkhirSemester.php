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

class TemplateSumatifAkhirSemester implements FromView, WithStyles, WithColumnWidths, WithEvents
{
    use Exportable;
    public function __construct(array $data){
        $this->data = $data;
        $this->jumlah_siswa = $data['data_siswa']->count();
        $arr = [];
        for($x = 'A'; $x < 'ZZ'; $x++){
            $arr[] = $x;
        }
        $this->arr = $arr;
    }
    public function view(): View
    {
        return view('unduhan.template_sumatif_akhir_semester', $this->data);
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(false);
        $sheet->getColumnDimension('B')->setVisible(false);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A7')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A9:F9')->getFont()->setBold(true);
        $sheet->getProtection()->setSheet(true);
        $sheet->getStyle('E10:F'.$this->jumlah_siswa+9)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
    }
    public function columnWidths(): array
    {
        $default = [
            'A' => 5,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 15,
        ];
        return $default;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A9:F'.$this->jumlah_siswa+9)->applyFromArray([
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
