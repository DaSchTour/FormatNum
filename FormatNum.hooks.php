<?php
class FormatNumHooks {
	public function efFormatNumParserFunction_Setup( $parser ) {
	# Set a function hook associating the "example" magic word with our function
	$parser->setFunctionHook( 'formatnum', 'FormatNumHooks::efFormatNumParserFunction_Render' );
	return true;
	}

	function efFormatNumParserFunction_Render( &$parser ) {
	# number | decimals | dec sep | thousend sep | orig thousend sep 	
	# The parser function itself
	# The input parameters are wikitext with templates expanded
	# The output should be wikitext too
	$args = func_get_args();
	$number_raw = $args[0];
	$format= $args['format'];
	$tsep = $args['tsep'];
	$dsep = $args['dsep'];
	$otsep = $args['otsep'];
	$decs = intval($args['decs']);
	$mint = intval($args['mint']);
	if ( $tsep == '_' ){
		$tsep = ' ';
	}
	switch ($format) {
		case 'DIN':
			$dsep = ",";
			$tsep = "t";
			$mint = 4;
		case 'ISO':
			$dsep = ",";
			$tsep = "t";
			$mint = 3;			
		default:
			if (!isset($desc)) $decs=2;
			if (!isset($tsep)) $tsep='t';
			if (!isset($dsep)) $dsep=',';
			if (!isset($mint)) $mint=0;
	}
	$number_clean = str_replace ( $otsep, '', $number_raw );
	if ( substr_count($number_clean, '.') == 1 ) {
		$num_array = explode('.', $number_clean);
	}
	elseif ( substr_count($number_clean, ',') == 1 ) {
		$num_array = explode(',', $number_clean);
	}
	else {
		$num_array[0] = $number_clean;
	}
	$number = $num_array[0] . "." . $num_array[1];
	$numlength = strlen($num_array[0]);
	$number = floatval($number);
	if ($mint >= $numlength) {
		$tsep = "";
	}
	$output = number_format( $number, $decs, $dsep, $tsep );
	switch ($tsep) {
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
