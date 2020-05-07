<?php declare( strict_types = 1 );

/*
 * Write a function which will read CSV file and return arrays of rows (where each row is array of cells) containing
 * requested columns in input only for rows that match input filter
 *
 * Example:
 * $filter  = [ 3 => 'hello', 2 => 'world' ]; -- return only rows which have string 'hello' in column 3 and string
 * 'world' in column 2 (first column has index 1)
 * $columns = [ 1, 3, 5 ]; -- for each row matching $filter return only columns 1, 3 and 5 (first column has index 1)
 */


class CsvParser
{
	
	function __construct( string $filename, array $filter, array $columns )
	{
		$this->filename = trim( $filename );
		$this->filter   = $filter;
		$this->columns  = $columns;
		$this->errors   = [];
		$this->results  = [];
	}
	
	/**
	 * CSV file validation.
	 *
	 * @param string $file
	 *
	 * @return boolean
	 */
	public function isCSV( $file )
	{
		$csv_mime_types = [
			'text/csv',
			'text/plain',
			'application/csv',
			'text/comma-separated-values',
			'application/excel',
			'application/vnd.ms-excel',
			'application/vnd.msexcel',
			'text/anytext',
			'application/octet-stream',
			'application/txt',
		];
		$finfo          = finfo_open( FILEINFO_MIME_TYPE );
		$mime_type      = finfo_file( $finfo, $file );
		
		return in_array( $mime_type, $csv_mime_types );
	}
	
	/**
	 * Validate data.
	 *
	 * @return boolean
	 */
	private function validate_data()
	{
		// Check if file exists and is csv type.
		if (
			empty( $this->filename ) ||
			! is_file( $this->filename ) ||
			! $this->isCSV( $this->filename )
		) {
			$this->errors[] = 'File is invalid!';
			
			return false;
		}
		
		// Check if columns or filter arrays are valid.
		foreach ( $this->columns as $item ) {
			if ( ! is_numeric( $item ) ) {
				$this->errors[] = '$columns data is invalid!';
				
				return false;
			}
		}
		
		// Check if filter or filter arrays are valid.
		if ( empty( $this->filter ) || empty( $this->columns ) ) {
			$this->errors[] = '$columns and $filters must not be empty!';
			
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * Validate $filter condition.
	 *
	 * @param array $row
	 *
	 * @return integer
	 */
	private function validate_row_condition( $row )
	{
		$matches = count( $this->filter );
		foreach ( $this->filter as $column => $value ) {
			if (
				isset( $row[ $column ] ) &&
				strpos( strtolower( $row[ $column ] ), trim( strtolower( $value ) ) ) !== false
			) {
				$matches--;
			}
		}
		
		return $matches;
	}
	
	/**
	 * Return results.
	 *
	 * @return array on succes or string on failure
	 */
	public function get_results()
	{
		if ( ! $this->validate_data() ) {
			if ( ! empty( $this->errors ) ) {
				return implode( "\n", $this->errors );
			}
		}
		
		$csv_data = array_map( 'str_getcsv', file( $this->filename ) );
		
		foreach ( $csv_data as $row ) {
			if ( $this->validate_row_condition( $row ) == 0 ) {
				$newrow = [];
				foreach ( $this->columns as $column ) {
					$newrow[] = $row[ $column ] ?? '';
				}
				$this->results[] = $newrow;
			}
		}
		
		return $this->results;
		
	}
	
}

$filename = 'biostats.csv';
$filter   = [ 0 => 'ivan', 2 => '53' ];
$columns  = [ 0, 1, 2 ];

$newfile = new CsvParser( $filename, $filter, $columns );
$results = $newfile->get_results();

print_r( $results );
