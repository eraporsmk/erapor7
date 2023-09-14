<?php

namespace App\Exports;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Capaian_pembelajaran;
use App\Models\Kompetensi_dasar;

class TemplateTp implements FromView, WithColumnWidths, WithEvents
{
    use Exportable;
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 45,
            'C' => 70            
        ];
    }
    public function registerEvents(): array
    {

        $cellRange      = 'A7:C17';

        return [
            AfterSheet::class    => function(AfterSheet $event) use($cellRange) {
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setHorizontal('left')->setWrapText(true);
                $event->sheet->getStyle('A7:B7')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A8:A17')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('C3')->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('C4')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('C5')->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('B8:B17')->getAlignment()->setHorizontal('left');
            },
        ];
    }

    public function query($id)
    {
        $this->id = $id;
        return $this;
    }
	public function view(): View
    {
        $cp = NULL;
        $kd = NULL;
        if(Str::isUuid($this->id)){
            $kd = Kompetensi_dasar::with(['pembelajaran'])->find($this->id);
        } else {
            $cp = Capaian_pembelajaran::with(['pembelajaran.rombongan_belajar'])->find($this->id);
        }
        $params = [
			'cp' => $cp,
            'kd' => $kd
        ];
        return view('unduhan.template_tp', $params);
    }
}
