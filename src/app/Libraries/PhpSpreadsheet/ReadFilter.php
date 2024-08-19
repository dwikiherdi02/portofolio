<?php

namespace App\Libraries\PhpSpreadsheet;

class ReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{
    private $startRow = 0;
    private $columns  = [];

    /**  Get the list of rows and columns to read  */
    public function __construct($startRow, $columns) {
        $this->startRow = $startRow;
        $this->columns  = $columns;
    }

    public function readCell($columnAddress, $row, $worksheetName = '') {
        //  Only read the rows and columns that were configured
        if ($row >= $this->startRow) {
            if (in_array($columnAddress,$this->columns)) {
                return true;
            }
        }
        return false;
    }
}
