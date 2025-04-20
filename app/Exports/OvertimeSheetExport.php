<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OvertimeSheetExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return array_merge(
                ['full_name' => $item['full_name']],
                $item['overtime_data']
            );
        });
    }

    public function headings(): array
    {
        $daysInMonth = now()->daysInMonth;
        $days = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        return array_merge([__('overtime.name')], $days);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->getDelegate()->getColumnDimension('A')->setWidth(30);

                $daysInMonth = now()->daysInMonth;
                $columnIndex = 2;

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                    $sheet->getDelegate()->getColumnDimension($columnLetter)->setWidth(6);

                    $sheet->getDelegate()->getStyle($columnLetter.'2:'.$columnLetter.(count($this->data) + 1))
                    ->getNumberFormat()
                    ->setFormatCode('0.00');

                    $columnIndex++;
                }

                $sheet->getDelegate()->getStyle('A1:' . $columnLetter . '1')->getFont()->setBold(true);

                $sheet->getDelegate()->getStyle('B2:' . $columnLetter . (count($this->data) + 1))
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}