<?php


const SINGLE_COLUMN_OPTION = [
    OPTION_SORT_COLUMN,
    OPTION_UNIQUE,
    OPTION_AGGREGATE_SUM,
    OPTION_AGGREGATE_LIST,
    OPTION_AGGREGATE_PRODUCT,
    OPTION_MAP_FUNCTION_COLUMN,
    OPTION_UPPERCASE,
    OPTION_LOWERCASE,
    OPTION_TITLECASE
];
const MULTI_COLUMN_OPTION  = [OPTION_MULTISORT, OPTION_SELECT];

/**
 * Validates the values provided to options which require column names.
 * @param array $options The array of options.
 * @param array $columnNames The array containing all the column names.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function validateSingleColumnOptions(array $options, array $columnNames): array
{
    $errors = array();
    foreach (SINGLE_COLUMN_OPTION as $optionName) {
        if ($options[$optionName] === '') {
            continue;
        }
        if (!in_array($options[$optionName], $columnNames)) {
            $errors[] = ['ERROR: ' . $optionName, $options[$optionName]];
        }
    }

    return $errors;
}

const SPECIFICATION_SEPARATORS = "[ ,]";
/**
 * Validates the values provided to options which require multiple column names.
 * @param array $options The array of options.
 * @param array $columnNames The array containing all the column names.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function validateMultiColumnOptions(array $options, array $columnNames): array
{
    $columnNames[]      = '*';
    $multiColumnOptions = MULTI_COLUMN_OPTION;
    $errors             = array();
    foreach ($multiColumnOptions as $optionName) {
        if ($options[$optionName] === '') {
            continue;
        }
        $columns = preg_split('/' . SPECIFICATION_SEPARATORS . '/', $options[$optionName]);
        foreach ($columns as $column) {
            if (!in_array($column, $columnNames)) {
                $errors[] = ['ERROR: ' . $optionName, $column];
            }
        }
    }
    return $errors;
}

const REGEX_ALLOWED_OPERATORS = '<>|=|<|>';
/**
 * Validates the expression passed to the where option.
 * @param $expression The expression passed to the where option.
 * @param $columnNames The array containing all the column names.
 * @return int Returns 1/0 if the expression matches/doesn't match the standard.
 */
function validateWhereExpression($expression, $columnNames): int
{
    $columnAlternativesString = implode("|", $columnNames);
    $regularExpression        = '/^(' . $columnAlternativesString . ")(" . REGEX_ALLOWED_OPERATORS . ')/';
    return preg_match($regularExpression, $expression);
}

/**
 * Performs the entire validation flow after parsing the file and obtaining the column names.
 * @param array $options The array of options.
 * @param mixed $tableData he array which holds the data in the table.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function validateInputAfterParsing(array $options, $tableData): array
{
    $errors = array();
    if (is_int($tableData)) {
        $errors[] = ['ERROR: ' . OPTION_FROM,'In '. $options[OPTION_FROM] . 'at line' . $tableData+1];
        return $errors;
    }
    $columnNames = array_keys($tableData[0]);

    $errors = validateSingleColumnOptions($options, $columnNames);

    $errors = array_merge($errors, validateMultiColumnOptions($options, $columnNames));

    if ($options[OPTION_WHERE] !== '' && validateWhereExpression($options[OPTION_WHERE], $columnNames) == false) {
       $errors[] = ['ERROR: ' . OPTION_WHERE, $options[OPTION_WHERE]];
    }
    return $errors;
}