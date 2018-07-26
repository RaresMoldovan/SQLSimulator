<?php

/**
 * @param array $tableData The array which holds the data in the table.
 * @param string $column The name of the column which needs to have unique values,
 * @return array he array with same outlook but with unique values on provided column.
 */
function applyUniqueFilter(array $tableData, string $column): array
{
    if ($column === '') {
        return $tableData;
    }
    return customArrayUnique($tableData, $column);

}

/**
 * Keeps only the first occurrence of multiple rows with the same value on the provided column.
 * @param array $tableData  The array which holds the data in the table.
 * @param string $column The name of the column which needs to have unique values,
 * @return array The array with same outlook but with unique values on provided column.
 */
function customArrayUnique(array $tableData, string $column): array
{
    $tableSize = count($tableData);
    for ($i = 0; $i < $tableSize - 1; $i++) {

        for ($j = $i + 1; $j < $tableSize; $j++) {
            if ($tableData[$i][$column] === $tableData[$j][$column]) {
                array_splice($tableData, $j, 1);
                $tableSize--;
            }
        }
    }
    return $tableData;
}