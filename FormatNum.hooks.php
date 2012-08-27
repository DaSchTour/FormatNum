<?php
class FormatNumHooks {
	public function efFormatNumParserFunction_Setup( $parser ) {
	# Set a function hook associating the "example" magic word with our function
	$parser->setFunctionHook( 'formatnum', 'FormatNumHooks::efFormatNumParserFunction_Render' );
	return true;
	}

	function efFormatNumParserFunction_Render( &$parser ) {
	# number | decimals | dec sep | thousend sep | orig thousend sep | min thousend	
	# The parser function itself
	# The input parameters are wikitext with templates expanded
	# The output should be wikitext too
	$args = func_get_args();
	array_shift( $args );
	$number_raw = $args[0];
	$params = array();
	foreach ($args as $key => $value) {
		$element = explode( '=', $value);
		unset($args[$key]);
		$params[$element[0]] = $element[1]; 
	}
	$format= $args['format'];
	if (!isset($args[1])) {
		$decs = intval($args[1]);
	}
	elseif (!isset($params['decs'])) {
		$decs = intval($params['decs']);
	}
	if (!isset($args[2])) {
		$dsep = $args[2];
	}
	elseif (!isset($params['dsep'])) {
		$dsep = $params['dsep'];
	}
	if (!isset($args[3])) {
		$tsep = $args[3];
	}
	elseif (!isset($params['tsep'])) {
		$tsep = $params['tsep'];
	}
	if (!isset($args[4])) {
		$otsep = $args[4];
	}
	elseif (!isset($params['otsep'])) {
		$otsep = $params['otsep'];
	}
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
