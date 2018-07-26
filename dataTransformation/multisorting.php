<?php

const SEPARATOR = "[ ,]";

/**
 * Performs the multi-sort operation on the table data based on column and direction specifications.
 * @param array $tableData The array which holds the data in the table.
 * @param string $columnSpecification A string which specifies the columns the sorting must consider, sepparated
 * by commas and whitespaces.
 * @param string $directionSpecification A string which specifies the directions the sorting must consider, sepparated
 * by commas and whitespaces.
 * @return array The table data with the values sorted.
 */
function applyMultiSortingTransformation(array $tableData, string $columnSpecification, string $directionSpecification)
{
    if ($columnSpecification === '') {
        return $tableData;
    }
    $columns        = preg_split('/' . SEPARATOR . '/', $columnSpecification);
    $directions     = preg_split('/' . SEPARATOR . '/', $directionSpecification);
    $optionsArray   = createOptionsArray($tableData, $columns, $directions);
    $optionsArray[] = &$tableData;
    call_user_func_array('array_multisort', $optionsArray);
    return $tableData;

}

/**
 * Creates the options array to be passed as argument to array_multisort based on columns and directions.
 * @param array $tableData The array which holds the data in the table.
 * @param array $columns The array which holds the column names relevant in the sorting process.
 * @param array $directions The array which holds the directions associated to the columns.
 * @return array The array of parameters for array_multisort.
 */
function createOptionsArray(array $tableData, array $columns, array $directions)
{
    $arraySize      = count($columns);
    $sortingOptions = array();
    for ($i = 0; $i < $arraySize; $i++) {
        $sortingOptions[] = array_column($tableData, $columns[$i]);
        $sortingOptions[] = DIRECTION_CONSTANTS_MAP[$directions[$i]];
    }
    return $sortingOptions;
}