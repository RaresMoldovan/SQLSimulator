# SQL SIMULATOR
The SQL SIMULATOR enables you to query a CVS database file and obtain results in a tabular manner. <br/> 
The script can be run in both the terminal and the browser. Terminal options given as '--option-name', browser options are given as a url query.

## 1. MANDATORY OPTIONS
* FROM 'file' <br/> 
~ file must be a csv file which contains the column names on the first line and the rows on the following lines
* SELECT col1,col2,...,coln\
~ col1,...,coln must be column names provided in the csv file.\
~ The select option is redundant in case of an aggregate option required.
* OUTPUT screen|csv\
~ screen: displays the results at the standard output in JSON format.\
~ csv: displays the results in a csv file provided with option OUTPUT-FILE.\
## 2.VOLUNTARY OPTIONS
2.1. OUTPUT RELATED
* OUTPUT-FILE 'file'\
~ necessary if the OUTPUT option is set to csv, provides the file where the results should be stored.
* COLUMN-SORT asc|desc\
~ asc: sorts the columns in ascending alphabetical order\
~ desc: sorts the columns in descending alphabetical order\
2.2. FILTERING
* WHERE 'expression'\
~ expression: can be one of the 4 types
    - column=value ~ equal with
    - column<>value ~ different than
    - column>value ~ greater than
    - column<value ~ smaller than
 where column is a valid column name and value is any string.
 * UNIQUE 'column'\
 ~ column: must be a valid column name, the result will keep only the first occurrence of any column value.\
2.3. TRANSFORMATIONS
* SORT-COLUMN 'column'\
~ column: must be a valid column name, specifies the column whose values will be compared in the sorting process.
* SORT-MODE natural|alpha|numeric\
~ natural: sorts the data based on natural ordering (2 comes before 10)\
~ alpha: sorts the data based on alphabetical ordering (2 comes after 10)\
~ numeric: sorts the data based on numeric comparison.\
* SORT-DIR asc|desc\
~ asc: sorts the rows in ascending order.\
~ desc: sorts the rows in descending order.\
!ATTENTION: All the 3 sorting options must be provided in case you need to sort the data.
* MULTI-SORT 'col1,col2,...,coln'\
~ col1,...,coln must be column names provided in the csv file. 
* MULTI-SORT-DIR 'dir1,dir2,...,dirn'\
~ dir1,...,dirn must be available directions in the set {asc,desc}, each column must have a durection association.
!ATTENTION: Both multi-sort options must be provided.
* LOWERCASE-COLUMN | UPPERCASE-COLUMN | TITLECASE-COLUMN 'column'\
~ column: must be an available column, the corresponding column values will be changed based on the case option \
* POWER-VALUES'column power'\
~ column: must be an available column, the corresponding column values will be changed by the exponential function\
~ power: must be a numeric value, corresponds to the power argument of the exponential function
* MAP-FUNCTION 'file-name'\
~ file-name : must be a valid file-name with extension .php which contains a function with the same name as the file
* MAP-FUNCTION-COL 'column'\
~ column: must be an available column, the corresponding column values will be changed by the mapping function\
2.4. AGGREGATES
* AGGREGATE-SUM | AGGREGATE-PRODUCT | AGGREGATE-LIST 'column'\
~ column: must be an available column, the summation/product/concatenation' will be applied on it.
* AGGREGATE-LIST-GLUE 'glue'\
~ glue: must be a valid character/set of characters which will be used to concatenate the column values, mandatory 
for option AGGREGATE-LIST
!ATTENTION: The SELECT option becomes redundant as the aggregate option will output a single row with all columns and
an additional operation column.
