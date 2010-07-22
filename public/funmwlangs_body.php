<?php

//class definition for the special page

if( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

class funmwlangs extends SpecialPage {

	function __construct() {
		parent::SpecialPage( 'funmwlangs', 'langconfig' );
	}
	
	function execute( $par ) {
		global $wgRequest, $wgUser, $wgOut, $wgExtraLanguageNames, $wgfunmwlangs;
		
		if( !$wgUser->isAllowed( 'langconfig' ) ) {
			$wgOut->permissionRequired( 'langconfig' );
			return;
		}
		wfLoadExtensionMessages( 'funmwlangs' );

		$this->setHeaders();
		$wgOut->addWikiMsg( 'funmwlangs-header' );
		
			//get values
			foreach( $wgfunmwlangs as $key => $stuff ) {
				$wgfunmwlangs[$key][0] = $wgRequest->getCheck( 'wpLang-' . $key );
				if( $wgRequest->getVal( 'wpLangDesc-' . $key, '' ) != '' ) {
					$wgfunmwlangs[$key][1] = $wgRequest->getVal( 'wpLangDesc-' . $key );
				}
			}
			//write values
			ksort( $wgfunmwlangs );
			$out = '';
			foreach( $wgfunmwlangs as $key => $stuff ) {
				$out .= $key . ':' . $stuff[0] . ':' . $stuff[1] . "\n";
			}
			if( file_put_contents( dirname( __FILE__ ) . '/funmwlangs.conf', $out ) === false ) {
				$wgOut->addWikiMsg( 'funmwlangs-nowrite' );
				$wgfunmwlangs = $clOld;
			} else {
				$wgOut->addWikiMsg( 'funmwlangs-success' );
			}
		}
		
		//build form
		$thisTitle = Title::makeTitle( NS_SPECIAL, $this->getName() );
		$wgOut->addHTML( '<form method="post" action="' . $thisTitle->getLocalUrl() . '">
<table cellspacing="10">
<tr><th>' . wfMsg( 'funmwlangs-langcode' ) . '</th><th>' . wfMsg( 'funmwlangs-enabled' ) . '</th><th>' . wfMsg( 'funmwlangs-langname' ) . '</th></tr>' );
		foreach( $wgfunmwlangs as $key => $stuff ) {
			$wgOut->addHTML( '<tr><td>' . $key . '</td><td><input type="checkbox" name="wpLang-' . $key . '" id="wpLang-' . $key . '" value="1"' . ($stuff[0] ? ' checked="checked"' : '') . ' /> <label for="wpLang-' . $key . '">' . wfMsg( 'funmwlangs-enabled' ) . '</label></td><td><input type="text" name="wpLangDesc-' . $key . '" value="' . str_replace( '"', '\"', $stuff[1] ) . '" /></td></tr>' . "\n" );
		}
		$wgOut->addHTML( '<tr><td colspan="3"><input type="submit" value="' . wfMsg( 'funmwlangs-submit' ) . '" /> </td></tr></table></form>' );
	}
}
