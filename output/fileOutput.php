<?php

/**
 * Prints the result of the tool in a csv file.
 * @param array $tableData The array which holds the data in the table.
 * @param array $initialTableData The array which holds the data before any filter is applied.
 * @param string $fileName The name of the file to be written(and created if necessary);
 */
function printFile(array $tableData, array $headers, string $fileName)
{
    if ($fileName === '') {
        return;
    }
    $fileHandler = fopen($fileName, "w");

    //Print columns
    fputcsv($fileHandler, $headers);

    //Print rows
    foreach ($tableData as $row) {
        fputcsv($fileHandler, $row);
    }

    fclose($fileHandler);
}