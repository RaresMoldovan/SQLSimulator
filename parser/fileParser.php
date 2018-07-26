<?php

/**
 * Reads the lines of CSV file and retrieves an array containing them.
 * @param string $fileName The name of the file which is to be read.
 * @return array|bool The array of lines in case of success or boolean false in case of error.
 */
function readLinesCSV(string $fileName)
{

    $lines  = array();
    $handle = fopen($fileName, "r");
    if ($handle != false) {

        while (($line = fgetcsv($handle)) !== false) {

            array_push($lines, $line);
        }
        fclose($handle);
        return $lines;
    }
    return false;
}

/**
 * Constructs the table data in format array(array) where each inner array represents a table row with the column names
 * as keys and the actual data as values.
 * TODO: (INFO) Rethink this once you learn about Exceptions.
 * @param array $csvArray The array of rows.
 * @return array|int The final data structure in case of success, number where an error was found in case of error.
 */
function constructTableStructure(array $csvArray)
{
    $tableData       = array();
    $numberOfEntries = count($csvArray);
    $rowSize         = count($csvArray[0]);
    for ($i = 1; $i < $numberOfEntries; $i++) {
        $tableData[$i - 1] = array();
        if (count($csvArray[$i]) !== $rowSize) {
            return $i;
        }
        for ($j = 0; $j < $rowSize; $j++) {
            $tableData[$i - 1][$csvArray[0][$j]] = $csvArray[$i][$j];
        }
    }
    return $tableData;
}

/**
 * @param string $fileName The name of the file which is the resource of the data structure.
 * @return array|int The final data structure in case of success, number where an error was found in case of error.
 * TODO: (INFO) You are loading the entire file into memory, and then constructing another array into memory, You're sailing in strange waters.
 */
function getTableData(string $fileName)
{
    return constructTableStructure(readLinesCSV($fileName));
}