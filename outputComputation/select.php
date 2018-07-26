<?php

/**
 * Selects only the columns specified in the columnSpecification parameter.
 * @param array $tableData The array which holds the data in the table.
 * @param string $columnSpecification Specifies the columns, separated by commas or '*' if all are needed.
 * @return array The same table data containing only the required columns.
 */
function applySelect(array $tableData, string $columnSpecification)
{
    if ($columnSpecification === '*') {
        return $tableData;
    }

    $columns         = preg_split('/[, ]/', $columnSpecification);
    $slicedTableData = array();
    foreach ($tableData as $key => $row) {
        $slicedTableData[$key] = array();
        foreach ($columns as $column) {
            $slicedTableData[$key][$column] = $tableData[$key][$column];
        }
    }
    return $slicedTableData;
}