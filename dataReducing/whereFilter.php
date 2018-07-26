<?php

/**
 * Applies the 'where' filter on the table data.
 * @param array $tableData The array which holds the data in the table.
 * @param string $filterString The filter string of type '(column)(operator)(value)'
 * @return array The table data which contains only the rows which comply to the filtering condition.
 */
function applyWhereFilter(array $tableData, string $filterString): array
{
    if ($filterString === '') {
        return $tableData;
    }

    $functionToBeApplied = createFilterFunction($filterString);
    return array_filter($tableData, $functionToBeApplied);

}

const REGULAR_EXPRESSION = '/^([\d\w]+)(<>|<|>|=)([\d\w]+)$/m';

/**
 * Creates a filtering function based on an input filtering string.
 * @param string $filterString he filter string of type '(column)(operator)(value)
 * @return callable The functions which returns a boolean value based on the condition.
 */
function createFilterFunction(string $filterString): callable
{
    preg_match_all(REGULAR_EXPRESSION, $filterString, $matches, PREG_SET_ORDER, 0);
    return function ($value) use ($matches) {
        $checkedValue = $value[$matches[0][1]];
        $queryValue   = $matches[0][3];
        $operator     = $matches[0][2];
        switch ($operator) {
            case "=" :
                return $checkedValue == $queryValue;
            case "<" :
                return $checkedValue < $queryValue;
            case ">" :
                return $checkedValue > $queryValue;
            case "<>" :
                return $checkedValue != $queryValue;
            default:
                return $checkedValue == $queryValue;
        }
    };
}

