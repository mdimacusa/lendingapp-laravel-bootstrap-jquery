<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class ExportExcel extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromArray,WithHeadings,ShouldAutoSize,WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $excel_header;
    protected $excel_data;

    function __construct($excel_header,$excel_data){
      $this->excel_header = $excel_header;
      $this->excel_data = $excel_data;
    }


    public function headings():array{
        $header = $this->excel_header;
        return $header;
    }
    public function array(): array
    {
        $data = $this->excel_data;
        return $data;
    }
}
