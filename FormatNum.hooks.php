<?php
class FormatNumHooks {
	function efFormatNumParserFunction_Setup( $parser ) {
	# Set a function hook associating the "example" magic word with our function
	$parser->setFunctionHook( 'formatnum', 'efFormatNumParserFunction_Render' );
	return true;
	}

	function efFormatNumParserFunction_Render( $parser, $param1 = 0, $param2 = 0, $param3 = '.', $param4 = ',' ) {
	# The parser function itself
	# The input parameters are wikitext with templates expanded
	# The output should be wikitext too
	if ( $param4 == '_' ){
		$param4 = ' ';
	}
	$param1 = intval($param1);
	$param2 = intval($param2);
	$output = number_format( $param1, $param2, $param3, $param4 );
	switch ($param4) {
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
