<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateNilaiAkhir implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    use Exportable;
    public function __construct(array $data){
        $this->data_siswa = $data['data_siswa'];
        $this->data_tp = $data['data_tp'];
        $this->pembelajaran = $data['pembelajaran'];
        $header = 12;
        $this->baris_akhir = $header + ($this->data_siswa->count() * $this->data_tp->count());
        return $this;
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(false);
        $sheet->getColumnDimension('B')->setVisible(false);
        $sheet->getColumnDimension('F')->setVisible(false);
        $sheet->getStyle('A1:H7')->getFont()->setBold(true);
        $sheet->getStyle('A12:H12')->getFont()->setBold(true);
        $sheet->getStyle('A8')->getAlignment()->setWrapText(true);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A12:H12')->getAlignment()->setWrapText(true);
        //$sheet->getProtection()->setSheet(true);
        //$sheet->getStyle('G13:G'.$this->baris_akhir)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        //$sheet->getStyle('E13:E'.$this->baris_akhir)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
    }
    public function columnWidths(): array
    {
        $default = [
            'A' => 5,
            'C' => 30,
            'D' => 15,
            'G' => 15,
            'H' => 75,
        ];
        return $default;
    }
    public function title(): string
    {
        return 'NILAI AKHIR';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A8:H10')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                
                $event->sheet->getStyle('A12:H'.$this->baris_akhir)->applyFromArray([
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
	public function view(): View
    {
        $params = [
			'data_siswa' => $this->data_siswa,
            'data_tp' => $this->data_tp,
            'pembelajaran' => $this->pembelajaran,
        ];
        return view('unduhan.template_nilai_akhir_angka', $params);
    }
}
