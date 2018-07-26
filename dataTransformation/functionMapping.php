<?php

/**
 * Applies a function on each cell of a specific column. The function is provided in a file with same name and
 * extension .php.
 * @param array $tableData The array which holds the data in the table.
 * @param string $functionName The name of the function to be mapped on a certain column.
 * @param string $column The name of the column whose values will be altered.
 * @return array The data with the filtered values.
 */
function applyFunctionMapping(array $tableData, string $functionName, string $column): array
{
    if ($functionName === '') {
        return $tableData;
    }
    require "$functionName" . ".php";
    return array_map(function (array $row) use ($column, $functionName) {
        $row[$column] = $functionName($row[$column]);
        return $row;
    }, $tableData);
}


const CASETYPE_NAME_TO_FUNCTION = [OPTION_LOWERCASE => 'strtolower', OPTION_UPPERCASE => 'strtoupper',
    OPTION_TITLECASE => 'ucwords'];
/**
 * Applies a case-altering function on each cell of a specific column.
 * @param array $tableData The array which holds the data in the table.
 * @param string $column The name of the column whose values will be altered.
 * @param string $caseType The type of case mapping: lowercase, uppercase, titlecase.
 * @return array The data with the filtered values.
 */
function applyCaseMapping(array $tableData, string $column, string $caseType): array
{

    if ($column === '') {
        return $tableData;
    }
    return array_map(function (array $row) use ($column, $caseType) {
        $row[$column] = call_user_func(CASETYPE_NAME_TO_FUNCTION[$caseType], $row[$column]);
        return $row;
    }, $tableData);
}

/**
 * Applies the power function on each cell of a specific column.
 * @param array $tableData
 * @param string $columnAndPower Contains the column name and the power sepparated by whitespace character(s).
 * @return array The data with the filtered values.
 */
function applyPowerMapping(array $tableData, string $columnAndPower): array
{
    if ($columnAndPower === '') {
        return $tableData;
    }
    $colAndPow = preg_split('/ /', $columnAndPower);
    return array_map(function (array $row) use ($colAndPow) {
        $row[$colAndPow[0]] = pow($row[$colAndPow[0]], $colAndPow[1]);
        return $row;
    }, $tableData);
}