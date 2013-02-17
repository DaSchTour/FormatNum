<?php
/**
 * Javascript Slideshow
 * Javascript Slideshow Hooks
 *
 * @author 		@See $wgExtensionCredits
 * @license		GPL
 * @package		FormatNum
 * @addtogroup  Extensions
 * @link		http://www.mediawiki.org/wiki/Extension:FormatNum
 *
**/

// Check environment
if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
	die( -1 );
}

/* Configuration */

// Credits
$wgExtensionCredits['parserhook'][] = array (
	'path'=> __FILE__ ,
	'name'=>'FormatNum',
	'url'=>'http://www.mediawiki.org/wiki/Extension:FormatNum',
	'descriptionmsg' => 'formatnum-desc',
	'author'=>'[http://www.dasch-tour.de DaSch]',
	'version'=>'0.6.1',
);
$dir = __DIR__.'/';

// Internationalization
$wgExtensionMessagesFiles['FormatNum'] = $dir . 'FormatNum.i18n.php';
$wgExtensionMessagesFiles['FormatNumMagic'] = $dir . 'FormatNum.i18n.magic.php';

// Define a setup function
$wgAutoloadClasses['FormatNumHooks'] = $dir . 'FormatNum.hooks.php';
$wgHooks['ParserFirstCallInit'][] = 'FormatNumHooks::efFormatNumParserFunction_Setup';