<?php

/**
 * Prints the table data in pretty Json format or in table format in case of browser access.
 * @param array $tableData The array which holds the data in the table.
 * @param array $headers The headers of the table in array format.
 */
function printStandard(array $tableData, array $headers)
{
    if (isset($tableData[OPTION_HELP])) {
        require 'helpOption.php';
        return;
    }
    if (php_sapi_name() === 'cli') {
        echo json_encode($tableData, JSON_PRETTY_PRINT) . PHP_EOL;
        return;
    }
    renderTable($tableData, $headers);

}

/**
 * Renders the table containing the data in html format.
 * @param array $tableData he array which holds the data in the table.
 * @param array $headers The headers of the table in array format.
 */
function renderTable(array $tableData, array $headers)
{

    echo "<table border = '1'>";
    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th> $header </th>";
    }
    echo "</tr>";
    foreach ($tableData as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td> $cell </td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}