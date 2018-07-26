<?php

//OPTION ARGUMENTS
const OPTION_HELP                = 'help';
const OPTION_SELECT              = 'select';
const OPTION_FROM                = 'from';
const OPTION_OUTPUT              = 'output';
const OPTION_OUTPUT_FILE         = 'output-file';
const OPTION_SORT_COLUMN         = 'sort-column';
const OPTION_SORT_MODE           = 'sort-mode';
const OPTION_SORT_DIRECTION      = 'sort-direction';
const OPTION_MULTISORT           = 'multi-sort';
const OPTION_MULTISORT_DIRECTION = 'multi-sort-dir';
const OPTION_UNIQUE              = 'unique';
const OPTION_WHERE               = 'where';
const OPTION_AGGREGATE_SUM       = 'aggregate-sum';
const OPTION_AGGREGATE_PRODUCT   = 'aggregate-product';
const OPTION_AGGREGATE_LIST      = 'aggregate-list';
const OPTION_AGGREGATE_LIST_GLUE = 'aggregate-list-glue';
const OPTION_UPPERCASE           = 'uppercase-column';
const OPTION_LOWERCASE           = 'lowercase-column';
const OPTION_TITLECASE           = 'titlecase-column';
const OPTION_POWER_VALUES        = 'power-values';
const OPTION_MAP_FUNCTION        = 'map-function';
const OPTION_MAP_FUNCTION_COLUMN = 'map-function-column';
const OPTION_COLUMN_SORT         = 'column-sort';

//SORTING STANDARD DIRECTION OPTIONS
const DIRECTION_ASCENDING  = 'asc';
const DIRECTION_DESCENDING = 'desc';

//SORTING STANDARD MODE OPTION
const MODE_NATURAL = 'natural';
const MODE_ALPHA   = 'alpha';
const MODE_NUMERIC = 'numeric';

//OUTPUT STANDARD OPTIONS
const OUTPUT_SCREEN = 'screen';
const OUTPUT_CSV    = 'csv';

//PHP-FRIENDLY SORTING CONSTANTS
const DIRECTION_CONSTANTS_MAP = [DIRECTION_ASCENDING => SORT_ASC, DIRECTION_DESCENDING => SORT_DESC];