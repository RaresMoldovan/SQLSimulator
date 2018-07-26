<?php
require 'input/inputReader.php';
require 'validator/inputValidator.php';
require 'parser/fileParser.php';
require 'validator/parsingValidator.php';
require 'dataReducing/whereFilter.php';
require 'dataReducing/uniqueFilter.php';
require 'dataTransformation/sorting.php';
require 'dataTransformation/multisorting.php';
require 'dataTransformation/functionMapping.php';
require 'aggregation/aggregates.php';
require 'outputComputation/select.php';
require 'outputComputation/columnSort.php';
require 'output/consoleOutput.php';
require 'output/fileOutput.php';

const ERROR_HEADERS = ['MESSAGE', 'SUBJECT'];

function main()
{

    //Get options from the arguments list
    $options = getOptions();

    //Get errors before parsing the file
    $errors = validateInput($options);

    if (errorsPresent($errors)) {
        printStandard($errors, ERROR_HEADERS);
        return;
    }

    //Construct the table data structure
    $tableData = getTableData($options[OPTION_FROM]);

    //Get errors after parsing the file
    $errors = validateInputAfterParsing($options, $tableData);
    if (errorsPresent($errors)) {
        printStandard($errors, ERROR_HEADERS);
        return;
    }

    //Maintain headers
    $headers = getHeaders($options[OPTION_SELECT], $tableData);

    //Apply where filter
    $tableData = applyWhereFilter($tableData, $options[OPTION_WHERE]);

    //Apply unique filter
    $tableData = applyUniqueFilter($tableData, $options[OPTION_UNIQUE]);

    //Apply sorting transformation
    $tableData = applySortingTransformation($tableData, $options[OPTION_SORT_COLUMN], $options[OPTION_SORT_DIRECTION], $options[OPTION_SORT_MODE]);

    //Apply multi-sorting transformation
    $tableData = applyMultiSortingTransformation($tableData, $options[OPTION_MULTISORT], $options[OPTION_MULTISORT_DIRECTION]);

    //Apply function mapping
    $tableData = applyFunctionMapping($tableData, $options[OPTION_MAP_FUNCTION], $options[OPTION_MAP_FUNCTION_COLUMN]);

    //Apply case mapping
    $tableData = applyCaseMapping($tableData, $options[OPTION_LOWERCASE], OPTION_LOWERCASE);
    $tableData = applyCaseMapping($tableData, $options[OPTION_UPPERCASE], OPTION_UPPERCASE);
    $tableData = applyCaseMapping($tableData, $options[OPTION_TITLECASE], OPTION_TITLECASE);

    //Apply power mapping
    $tableData = applyPowerMapping($tableData, $options[OPTION_POWER_VALUES]);

    //Apply aggregates
    $tableData = applyAggregateFunction($tableData, $options[OPTION_AGGREGATE_SUM], [OPTION_AGGREGATE_SUM]);
    $tableData = applyAggregateFunction($tableData, $options[OPTION_AGGREGATE_PRODUCT], [OPTION_AGGREGATE_PRODUCT]);
    $tableData = applyAggregateFunction($tableData, $options[OPTION_AGGREGATE_LIST], [OPTION_AGGREGATE_LIST, $options[OPTION_AGGREGATE_LIST_GLUE]]);

    //Apply select
    $tableData = applySelect($tableData, $options[OPTION_SELECT]);

    //Apply column sorting
    $tableData = applyColumnSorting($tableData, $options[OPTION_COLUMN_SORT]);
    //var_dump($tableData);

    //Output
    if ($options[OPTION_OUTPUT] == OUTPUT_SCREEN) {
        printStandard($tableData, $headers);
    } else {
        printFile($tableData, $headers, $options[OPTION_OUTPUT_FILE]);
    }
}


main();