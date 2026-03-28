<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = 'C:\Users\Aleja\Desktop\sistemas y analisis\SAUPA\Proyecto-Planificacion\1_4987824578307819177.xlsx';

try {
    $spreadsheet = IOFactory::load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    echo "Report Name: " . $worksheet->getTitle() . "\n";
    echo "Max Row: $highestRow, Max Col: $highestColumn\n\n";

    for ($row = 1; $row <= 30; $row++) { // Check first 30 rows
        $found = false;
        for ($col = 'A'; $col <= 'L'; $col++) { // Check cols A to L
            $value = $worksheet->getCell($col . $row)->getValue();
            if ($value) {
                echo "[$col$row]: $value | ";
                $found = true;
            }
        }
        if ($found) echo "\n";
    }

} catch (Exception $e) {
    echo 'Error loading file: ' . $e->getMessage();
}
