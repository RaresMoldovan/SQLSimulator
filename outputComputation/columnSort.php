<?php

const SORTING_TYPE_TO_FUNCTION = [DIRECTION_ASCENDING => 'ksort', DIRECTION_DESCENDING => 'krsort'];
/**
 * Sorts the columns of the table data in ascending/descending order.
 * @param array $tableData The array which holds the data in the table.
 * @param string $direction Can be either asc or desc.
 * @return array The same table data with the columns sorted.
 */
function applyColumnSorting(array $tableData, string $direction): array
{
    $sortingTypeToFunction = SORTING_TYPE_TO_FUNCTION;
    if ($direction === '') {
        return $tableData;
    }
    foreach ($tableData as $key => $row) {
        $sortingTypeToFunction[$direction]($row);
        $tableData[$key] = $row;
    }
    return $tableData;
}

