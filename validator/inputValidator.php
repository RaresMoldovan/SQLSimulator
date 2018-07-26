<?php

const MANDATORY_INPUT = [OPTION_SELECT, OPTION_FROM, OPTION_OUTPUT];

const OPTION_DEPENDECIES = [
    'sorting' => [OPTION_SORT_COLUMN, OPTION_SORT_MODE, OPTION_SORT_DIRECTION],
    'multisorting' => [OPTION_MULTISORT, OPTION_MULTISORT_DIRECTION],
    'aggregate list' => [OPTION_AGGREGATE_LIST, OPTION_AGGREGATE_LIST_GLUE],
    'mapping' => [OPTION_MAP_FUNCTION, OPTION_MAP_FUNCTION_COLUMN]
];

const OPTION_ALLOWED_VALUES = [
    OPTION_OUTPUT => [OUTPUT_CSV, OUTPUT_SCREEN],
    OPTION_SORT_MODE => [MODE_ALPHA, MODE_NATURAL, MODE_NUMERIC],
    OPTION_SORT_DIRECTION => [DIRECTION_ASCENDING, DIRECTION_DESCENDING]
];

/**
 * Validates the dependencies between options and signals missing options in the returned error array.
 * @param array $options The array of options.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function validateDependencies(array $options): array
{
    $optionDependencies = OPTION_DEPENDECIES;

    $errors = array();
    foreach ($optionDependencies as $key => $dependency) {

        $missingOptions  = array();
        $numberOfOptions = count($dependency);
        foreach ($dependency as $dependencyMember) {
            if ($options[$dependencyMember] === '') {
                $missingOptions[] = $dependencyMember;
            }
        }
        if (count($missingOptions) !== $numberOfOptions && count($missingOptions) > 0) {
            $errors[] = [$key,'ERROR: missing ' . implode(",", $missingOptions)];
        }
    }
    $errors = array_merge($errors, validateOutputDependency($options));
    return $errors;
}

/**
 * Validates the conditional output dependency(if output is csv, then we need a file name).
 * @param array $options The array of options.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function validateOutputDependency(array $options): array
{
    if ($options[OPTION_OUTPUT] === OUTPUT_CSV && $options[OPTION_OUTPUT_FILE] === '') {
        return ['ERROR: ' .OPTION_OUTPUT_FILE, 'missing'];
    }
    return [];
}

/**
 * Validates the presence of minimal options: help or select,from,output.
 * @param array $options The array of options.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function checkForMinimalInput(array $options): array
{
    $errors = array();

    if ($options[OPTION_HELP] !== '') {
        return $errors;
    }

    $mandatoryInput = MANDATORY_INPUT;
    foreach ($mandatoryInput as $mandatoryOption) {
        if ($options[$mandatoryOption] === '') {
            $errors[] = ['ERROR: '. $mandatoryOption, 'missing'];
        }
    }
    return $errors;
}

const STANDARD_OPTION_SEPARATOR = "[ ,]";
/**
 * Validates that the options have the correct values (the standard ones such as asc/desc for sorting).
 * @param array $options The array of options.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function checkForCorrectInputValues(array $options): array
{
    $errors              = array();
    $optionAllowedValues = OPTION_ALLOWED_VALUES;
    foreach ($optionAllowedValues as $optionName => $allowedValues) {
        if ($options[$optionName] !== '' && !in_array($options[$optionName], $allowedValues)) {
            $errors[] = ['ERROR: ' . $optionName, $options[$optionName]];
        }
    }
    //Special check for the multi-sort function which requires a list of options
    if ($options[OPTION_MULTISORT_DIRECTION] !== '') {
        $multisortOptions = preg_split('/' . STANDARD_OPTION_SEPARATOR . '/', $options[OPTION_MULTISORT_DIRECTION]);
        $multisortColumns = preg_split('/' . STANDARD_OPTION_SEPARATOR . '/', $options[OPTION_MULTISORT]);
        if(count($multisortOptions)!=count($multisortColumns)) {
            $errors[] = ['ERROR: ' . OPTION_MULTISORT_DIRECTION . ' and ' . OPTION_MULTISORT, 'Different nr of arguments'];
        }
        foreach ($multisortOptions as $option) {
            if (!in_array($option, OPTION_ALLOWED_VALUES[OPTION_SORT_DIRECTION])) {
                $errors[] = ['ERROR: '.OPTION_MULTISORT_DIRECTION, $option];
            }
        }
    }
    return $errors;
}

/**
 * Checks if an input file exists.
 * @param string $option The name of the option which needs a file to be valid.
 * @param string $fileName The name of the file to be looked for.
 * @return array Empty if the file exists, contains error standard message if it does not exist.
 */
function checkInputFile(string $option, string $fileName): array
{
    if (!file_exists($fileName)) {
        return [['ERROR: ' . $option, $fileName]];
    }
    return [];
}

/**
 * Performs the entire validation of the input options.
 * @param array $options The array of options.
 * @return array The array with discovered errors, key is the option name and value is the actual error.
 */
function validateInput(array $options): array
{
    $errors = array();

    //Special check for option help
    if ($options[OPTION_HELP] === false) {
        $errors[OPTION_HELP] = 'set';
        return $errors;
    }
    $errors = checkForCorrectInputValues($options);
    if (count($errors) !== 0) {
        return $errors;
    }
    $errors = checkForMinimalInput($options);
    if (count($errors) !== 0) {
        return $errors;
    }
    $errors = checkInputFile(OPTION_FROM, $options[OPTION_FROM]);
    if (count($errors) !== 0) {
        return $errors;
    }
    if($options[OPTION_MAP_FUNCTION]!=='') {
        $errors = checkInputFile(OPTION_MAP_FUNCTION, $options[OPTION_MAP_FUNCTION] . '.php');
        if (count($errors) !== 0) {
            return $errors;
        }
    }
    $errors = validateDependencies($options);

    return $errors;
}

/**
 * Checks if any errors are present.
 * @param array $errorArray The array of errors.
 * @return bool True if any errors are found.
 */
function errorsPresent(array $errorArray)
{
    return empty($errorArray) === false;
}
