<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OvertimeSheetExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
    protected $month;
    protected $year;
    protected $totalOvertimeData;

    public function __construct($data, $month, $year, $totalOvertimeData)
    {
        $this->data = $data;
        $this->month = $month;
        $this->year = $year;
        $this->totalOvertimeData = $totalOvertimeData;
    }

    public function collection()
    {   
        $transformedData = collect();
        $index = 1;

        foreach ($this->data as $item) {
            $rowData = [
                'sl' => $index,                              
                'full_name' => $item['full_name'],           
            ];
            
            foreach ($item['overtime_data'] as $day => $value) {
                $rowData[$day] = $value;
            }
            
            $rowData['total_per_month'] = $item['total_overtime_by_month'];

            $transformedData->push($rowData);
            $index++;
        }
        
        $totalRow = [
            'sl' => '', 
            'full_name' => strtoupper(__('overtime.total_all'))
        ];

        for ($d = 1; $d <= now()->daysInMonth; $d++) {
            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
            $totalRow[$day] = '';
        }

        $totalRow['total_per_month'] = $this->totalOvertimeData;
        
        $transformedData->push($totalRow);

        return $transformedData;
    }

    public function headings(): array
    {
        $daysInMonth = now()->daysInMonth;
        $days = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $days[] = $i; 
        }

        
        return array_merge(
            [strtoupper(__('overtime.sl')), strtoupper(__('overtime.name'))], 
            $days, 
            [strtoupper(__('overtime.total_per_month'))]
        );
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $daysInMonth = now()->daysInMonth;
                
                $sheet->getDelegate()->getColumnDimension('A')->setWidth(8);  
                $sheet->getDelegate()->getColumnDimension('B')->setWidth(30); 

                $columnIndex = 3; 
                
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                    $sheet->getDelegate()->getColumnDimension($columnLetter)->setWidth(6);
                    
                    $sheet->getDelegate()->getStyle($columnLetter.'2:'.$columnLetter.(count($this->data) + 1))
                        ->getNumberFormat()
                        ->setFormatCode('0.00');
                    
                    $columnIndex++;
                }
                
                $totalColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->getDelegate()->getColumnDimension($totalColumnLetter)->setWidth(10);
                $sheet->getDelegate()->getStyle($totalColumnLetter.'2:'.$totalColumnLetter.(count($this->data) + 1))
                    ->getNumberFormat()
                    ->setFormatCode('0.00');
                
                $sheet->getDelegate()->getStyle('A1:' . $totalColumnLetter . '1')->getFont()->setBold(true);
                
                $sheet->getDelegate()->getStyle('A2:' . $totalColumnLetter . (count($this->data) + 2))
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                $sheet->getDelegate()->getStyle('B2:B' . (count($this->data) + 2))
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
                $sheet->setCellValue('A1', strtoupper(__('overtime.sl')));
                
                $lastRow = count($this->data) + 2;
                $sheet->getDelegate()->getStyle($totalColumnLetter . $lastRow)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('FFFF00'); 
                
                $sheet->getDelegate()->getStyle('A' . $lastRow . ':' . $totalColumnLetter . $lastRow)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('F0F0F0'); 
                
                $sheet->getDelegate()->getStyle('A' . $lastRow . ':' . $totalColumnLetter . $lastRow)
                    ->getFont()->setBold(true);
            },
        ];
    }
}