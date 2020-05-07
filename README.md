# CSV_Parser
Read CSV file and return arrays of rows


 * Write a function which will read CSV file and return arrays of rows (where each row is array of cells) containing
 * requested columns in input only for rows that match input filter
 *
 * Example:
 * $filter  = [ 3 => 'hello', 2 => 'world' ]; -- return only rows which have string 'hello' in column 3 and string
 * 'world' in column 2 (first column has index 1)
 * $columns = [ 1, 3, 5 ]; -- for each row matching $filter return only columns 1, 3 and 5 (first column has index 1)
