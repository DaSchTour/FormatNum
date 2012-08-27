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
	
	$points = substr_count($number_raw, '.');
	$comma = substr_count($number_raw, ',');
		
	$params = array();
	foreach ($args as $key => $value) {
		if (substr_count($value, '=') == 1) {
			$element = explode( '=', $value);
			unset($args[$key]);
			$params[$element[0]] = $element[1];
		}
	}
	$format= $params['format'];
	if (isset($args[1])) {
		$decs = intval($args[1]);
	}
	elseif (isset($params['decs'])) {
		$decs = intval($params['decs']);
	}
	if (isset($args[2])) {
		$dsep = $args[2];
	}
	elseif (isset($params['dsep'])) {
		$dsep = $params['dsep'];
	}
	if (isset($args[3])) {
		$tsep = $args[3];
	}
	elseif (isset($params['tsep'])) {
		$tsep = $params['tsep'];
	}
	if (isset($args[4])) {
		$otsep = $args[4];
	}
	elseif (isset($params['otsep'])) {
		$otsep = $params['otsep'];
	}
	elseif ($comma > 1 && $point == 1) {
		$otsep = ',';
	}
	elseif ($point > 1 && $comma == 1) {
		$otsep = '.';
	}
	else {
		$otsep='';
	}
	if (isset($params['mint'])) {
		$mint = intval($params['mint']);
	}
	switch ($format) {
		case 'DIN':
			$dsep = ",";
			$tsep = "t";
			$mint = 4;
			break;
		case 'ISO':
			$dsep = ",";
			$tsep = "t";
			$mint = 3;
			break;
		default:
			if (!isset($desc)) $decs=2;
			if (!isset($tsep)) $tsep='t';
			if (!isset($dsep)) $dsep=',';
			if (!isset($mint)) $mint=0;
	}
	if ( $tsep == '_' ){
		$tsep = ' ';
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
	if (count($num_array) > 1) {
		$number = $num_array[0] . "." . $num_array[1];
	}
	else {
		$number = $num_array[0];
	}
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