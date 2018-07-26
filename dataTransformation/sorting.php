<?php

/**
 * @param array $tableData The array which holds the data in the table.
 * @param string $column The column which will be taken into consideration in the sorting process.
 * @param string $direction The direction of the sorting (asc or desc).
 * @param string $mode The mode of the sorting(alpha,numeric or natural).
 * @return array The sorted array.
 */
function applySortingTransformation(array $tableData, string $column, string $direction, string $mode): array
{
    if ($column === "" || $direction === "" || $mode === "") {
        return $tableData;
    }
    $comparisonFunction = getComparisonFunction($column, $direction, $mode);
    usort($tableData, $comparisonFunction);
    return $tableData;

}

const SORTING_MODE_MAP = [MODE_NATURAL => 'strnatcmp', MODE_ALPHA => 'strcmp', MODE_NUMERIC => 'numericCompare'];
/**
 * @param string $column The column which will be taken into consideration in the sorting process.
 * @param string $direction The direction of the sorting (asc or desc).
 * @param string $mode The mode of the sorting(alpha,numeric or natural).
 * @return Closure The function which compares two rows based on the selected criteria.
 */
function getComparisonFunction(string $column, string $direction, string $mode): callable
{
    return function ($x, $y) use ($mode, $direction, $column) {
        if ($direction === DIRECTION_ASCENDING) {
            return call_user_func(SORTING_MODE_MAP[$mode], $x[$column], $y[$column]);
        }
        return -call_user_func(SORTING_MODE_MAP[$mode], $x[$column], $y[$column]);
    };
}

/**
 * Performs the comparison of 2 numeric values and complies to the standard return format for usort.
 * @param $x mixed First value.
 * @param $y mixed Second value.
 * @return int <0 for less, =0 for equal and >0 for greater.
 */
function numericCompare($x, $y): int
{
    if ($x === $y) {
        return 0;
    }
    if ($x < $y) {
        return -1;
    }
    return 1;
}

