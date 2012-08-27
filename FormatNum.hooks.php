<?php
class FormatNumHooks {
	public function efFormatNumParserFunction_Setup( $parser ) {
	# Set a function hook associating the "example" magic word with our function
	$parser->setFunctionHook( 'formatnum', 'FormatNumHooks::efFormatNumParserFunction_Render' );
	return true;
	}

	function efFormatNumParserFunction_Render( $parser, $param1 = 0, $param2 = 2, $param3 = '.', $param4 = ',', $param5 = '.' ) {
	# number | decimals | dec sep | thousend sep | orig thousend sep 	
	# The parser function itself
	# The input parameters are wikitext with templates expanded
	# The output should be wikitext too
	if ( $param4 == '_' ){
		$param4 = ' ';
	}
	$param1 = str_replace ( $param5, '', $param1 );
	if ( substr_count($param1, '.') == 1 ) {
		$num_array = explode('.', $param1);
	}
	elseif ( substr_count($param1, ',') == 1 ) {
		$num_array = explode(',', $param1);
	}
	else {
		$num_array[0] = $param1;
	}
	$number_raw = $num_array[0] . "." . $num_array[1];
	$numlength = strlen($num_array[0]);
	$number = floatval($number_raw);
	$decs = intval($param2);
	switch ($param3) {
		case 'DIN':
			$dec_point = ",";
			$thousend_sep = "t";
			$min_th_sep = 4;
			break;
		case 'ISO':
			$dec_point = ",";
			$thousend_sep = "t";
			$min_th_sep = 3;			
			break;
		default:
			$dec_point = $param3;
			$thousend_sep = $param4;
			$min_th_sep = 3;
	}
	if ($min_th_sep >= $numlength) {
		$thousend_sep = "";
	}
	$output = number_format( $number, $decs, $dec_point, $thousend_sep );
	switch ($thousend_sep) {
		case 't':
			$output = str_replace ( 't', '&thinsp;', $output );
			break;
		case 'n':
			$output = str_replace ( 'n', '&nbsp;', $output );
			break;
	}
	return $output;
	}	
}
