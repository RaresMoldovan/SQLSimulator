<?php
include 'constants.php';

const OPTIONS_ARRAY = [OPTION_HELP, OPTION_SELECT . ':',
    OPTION_FROM . ':',
    OPTION_OUTPUT . ":",
    OPTION_OUTPUT_FILE . ':',
    OPTION_SORT_COLUMN . ':',
    OPTION_SORT_MODE . ':',
    OPTION_SORT_DIRECTION . ':',
    OPTION_MULTISORT . ':',
    OPTION_MULTISORT_DIRECTION . ':',
    OPTION_UNIQUE . ':',
    OPTION_WHERE . ':',
    OPTION_AGGREGATE_SUM . ':',
    OPTION_AGGREGATE_PRODUCT . ':',
    OPTION_AGGREGATE_LIST . ':',
    OPTION_AGGREGATE_LIST_GLUE . ':',
    OPTION_UPPERCASE . ':',
    OPTION_LOWERCASE . ':',
    OPTION_TITLECASE . ':',
    OPTION_POWER_VALUES . ':',
    OPTION_MAP_FUNCTION . ':',
    OPTION_MAP_FUNCTION_COLUMN . ':',
    OPTION_COLUMN_SORT . ':'];


/**
 * Gets the command line arguments and returns the final list of options.
 * @return array The final list of options.
 */
function getOptions(): array
{
    //Create a default options array with empty string on each key
    $defaultOptions = array();
    foreach (OPTIONS_ARRAY as $option) {
        $defaultOptions[str_replace(':', "", $option)] = "";
    }

    //Get the options of the user from the command line
    if (php_sapi_name() === 'cli') {
        $userOptions = getopt('', OPTIONS_ARRAY);
    } else {
        $userOptions = getURLParameters(OPTIONS_ARRAY);
    }

    //Merge the two arrays into the final result
    $finalOptions = array_merge($defaultOptions, $userOptions);

    //Update final options with some default dependencies
    $finalOptions = updateDefaultOptions($finalOptions);

    return $finalOptions;
}

/**
 * Updates the options with default setting (select all in case of aggregation) and keep only help option if help found.
 * @param array $options The array of options.
 * @return array The updated option array.
 */
function updateDefaultOptions(array $options): array
{
    //Select option becomes redundant when aggregate found
    if ($options[OPTION_AGGREGATE_SUM] !== '' ||
        $options[OPTION_AGGREGATE_PRODUCT] !== '' ||
        $options[OPTION_AGGREGATE_LIST] !== ''
    ) {
        $options[OPTION_SELECT] = '*';
    }

    //Every option becomes redundant if help option is provided
    if ($options[OPTION_HELP] === false) {
        foreach ($options as $key => $option) {
            if ($key !== OPTION_HELP) {
                $options[$key] = '';
            }
        }
    }
    return $options;
}

/**
 * Gets the column options in a string form and returns them in array form.
 * @param string $columnOption The selected columns in string form.
 * @param array $tableData The data structure
 * @return array The headers of the table in array format.
 */
function getHeaders(string $columnOption, array $tableData): array
{
    if ($columnOption == '*') {
        return array_keys($tableData[0]);
    }
    $columns = preg_split('/[ ,]/', $columnOption);
    return $columns;
}

/**
 * Gets the URL parameters and stores them in an option array.
 * @param array $optionsArray The array of options with all the values set to empty string.
 * @return array The option array.
 */
function getURLParameters(array $optionsArray): array
{
    $urlParameters = array();
    foreach ($optionsArray as $optionName) {
        $name = str_replace(':', "", $optionName);
        if (isset($_GET[$name])) {
            $urlParameters[$name] = $_GET[$name];
            continue;
        }
        $urlParameters[$name] = '';
    }
    $urlParameters[OPTION_OUTPUT] = 'screen';
    return $urlParameters;
}