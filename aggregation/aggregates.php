<?php

const INITIAL_VALUES = [OPTION_AGGREGATE_SUM => 0, OPTION_AGGREGATE_PRODUCT => 1, OPTION_AGGREGATE_LIST => ''];
/**
 * Applies the aggregation function on the table data.
 * @param array $tableData The array which holds the data in the table.
 * @param string $column The column on which the aggregation is performed.
 * @param array $type It contains on position 0 de the aggregation type and on position 1 the glue (for aggregate-list)
 * @return array The single-row array containing the data first row with aggregation result as an additional column.
 */
function applyAggregateFunction(array $tableData, string $column, array $type)
{
    if ($column === '') {
        return $tableData;
    }
    $reducingFunction                               = getRowAggregationFunction($column, $type);
    $reduced                                        = array_reduce($tableData, $reducingFunction, getInitialValue($tableData, $column, $type));
    $reduced[strtr($type[0], ['aggregate-' => ''])] = $reduced[$column];
    $reduced[$column]                               = $tableData[0][$column];
    return [$reduced];
}

/**
 * Returns the aggregation function for two rows.
 * @param string $column The column on which the aggregation is performed.
 * @param array $type It contains on position 0 de the aggregation type and on position 1 the glue (for aggregate-list)
 * @return Closure The binary operator between 2 rows.
 */
function getRowAggregationFunction(string $column, array $type)
{
    return function (array $firstRow, array $secondRow) use ($column, $type) {
        $firstRow[$column] = call_user_func(getCellAggregationFunction($type), $firstRow[$column], $secondRow[$column]);
        return $firstRow;
    };
}

/**
 * Returns the aggregation function for 2 cells on the same column.
 * @param array $type It contains on position 0 de the aggregation type and on position 1 the glue (for aggregate-list)
 * @return Closure The binary operator between 2 cells.
 */
function getCellAggregationFunction(array $type)
{

    switch ($type[0]) {
        case OPTION_AGGREGATE_SUM:
            return function ($x, $y) {
                return $x + $y;
            };
        case OPTION_AGGREGATE_PRODUCT:
            return function ($x, $y) {
                return $x * $y;
            };
        case OPTION_AGGREGATE_LIST:
            return function ($x, $y) use ($type) {
                return $x === '' ? $y : $x . $type[1] . $y;
            };
    }
}

/**
 * Gets the initial value to be used by array_reduce.
 * @param array $tableData The array which holds the data in the table.
 * @param string $column The column on which the aggregation is performed.
 * @param array $type It contains on position 0 de the aggregation type and on position 1 the glue (for aggregate-list)
 * @return array The initial value(expressed as a row).
 */
function getInitialValue(array $tableData, string $column, array $type)
{

    $tableData[0][$column] = INITIAL_VALUES[$type[0]];
    return $tableData[0];
}