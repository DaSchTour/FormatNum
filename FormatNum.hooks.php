<?php
class FormatNumHooks {
	/*
	@param parser from mediawiki
	@return must return true for next prasers
	*/
	public function efFormatNumParserFunction_Setup( $parser ) {
	$parser->setFunctionHook( 'formatnum', 'FormatNumHooks::efFormatNumParserFunction_Render' );
	return true;
	}

	/*
	@param parser from mediawiki
	@return string from number_format
	*/
	function efFormatNumParserFunction_Render( &$parser ) {
	/* number | decimals | dec sep | thousend sep | orig thousend sep | min thousend	
	 The parser function itself
	 The input parameters are wikitext with templates expanded
	 The output should be wikitext too */
	global $wgLanguageCode;
	/* parse arguments */
	$args = func_get_args();
	array_shift( $args );
	/* first arg is always the number */
	$number_raw = $args[0];
		
	$params = array();
	foreach ($args as $key => $value) {
		if (substr_count($value, '=') == 1) {
			$element = explode( '=', $value);
			unset($args[$key]);
			$params[$element[0]] = $element[1];
		}
	}
	if(array_key_exists('format',$params)) {
		$format = $params['format'];
	}
	else {
		$format = NULL;
	}
	
	/* second arg or 'decs' may be number of decimals */
	if (isset($args[1])) {
		$decs = intval($args[1]);
	}
	elseif (isset($params['decs'])) {
		$decs = intval($params['decs']);
	}
	
	/* third arg or 'dsep' may be decimal seperator */
	if (isset($args[2])) {
		$dsep = $args[2];
	}
	elseif (isset($params['dsep'])) {
		$dsep = $params['dsep'];
	}
	
	/* fourth arg or 'tsep' may be thousand seperator */
	if (isset($args[3])) {
		$tsep = $args[3];
	}
	elseif (isset($params['tsep'])) {
		$tsep = $params['tsep'];
	}
	
	/* fifth arg or 'otsep' may be original/old thousand seperator */
	if (isset($args[4])) {
		$otsep = $args[4];
	}
	elseif (isset($params['otsep'])) {
		$otsep = $params['otsep'];
	}
	
	/* count number of points and commas to guess original thousand seperator if not set */
	if (!isset($otsep)) {
		$points = substr_count($number_raw, '.');
		$commas = substr_count($number_raw, ',');
		if ($commas > 1 && $points <= 1) {
			$otsep = ',';
		}
		elseif ($points > 1 && $commas <= 1) {
			$otsep = '.';
		}
		else {
			$otsep='';
		}
	}
	
	/* 'mint' is the minimum number to seperated thousands */
	if (isset($params['mint'])) {
		$mint = intval($params['mint']);
	}
	
	/* use language code as default format when nothing is set */
	if (!isset($desp) && !isset($tsep) && !isset($format)) {
		/* ignoring language variants */
		$lang = explode('-',$wgLanguageCode);
		$format = strtoupper($lang[0]);
	}
	
	/* predefined format, the short way */
	switch ($format) {
		case 'DE':
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
		case 'EN':
			$dsep = ".";
			$tsep = ",";
			$mint = 3;
			break;
		default:
			if (!isset($decs)) $decs=2;
			if (!isset($tsep)) $tsep='t';
			if (!isset($dsep)) $dsep=',';
			if (!isset($mint)) $mint=0;
	}
	if ( $tsep == '_' ){
		$tsep = ' ';
	}
	
	/* Cleanup */
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
	
	/* length of the number to check if seperation of thousands applies */
	$numlength = strlen($num_array[0]);
	$number = floatval($number);
	if ($mint >= $numlength) {
		$tsep = "";
	}
	/* format number and generate output */
	$output = number_format( $number, $decs, $dsep, $tsep );
	
	/* for non-breaking space replace string placeholder with the HTML-thing */
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