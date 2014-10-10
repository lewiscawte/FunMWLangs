<?php

/**
 * Fun MediaWiki Languages Switcher extension for MediaWiki
 * by Ryan Schmidt
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */


if( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'name'           => 'Fun MediaWiki Languages Switcher',
	'author'         => 'Ryan Schmidt',
	'version'        => '1.1',
	'descriptionmsg' => 'funmwlangs-desc',
);
$wgExtensionCredits['specialpage'][] = array(
        'path' => __FILE__,
	'name' => 'Fun MediaWiki Languages',
	'version' => 0.3,
	'author' => 'Lewis Cawte, Others ([http://funmwlangs.sourceforge.net/wiki/Team List])',
	'url' => 'https://sourceforge.net/projects/funmwlangs',
	'descriptionmsg' => 'funmwlangs-desc2',
);

$dir = dirname(__FILE__) . '/';
$wgAutoloadClasses['funmwlangs'] = $dir . 'funmwlangs_body.php';
$wgSpecialPages['funmwlangs'] = 'funmwlangs';
$wgMessagesDirs['YourExtension'] = __DIR__ . '/i18n';
$wgExtensionAliasesFiles['funmwlangs'] = $dir . 'funmwlangs.alias.php';
$wgSpecialPageGroups['funmwlangs'] = 'wiki';

//rights
$wgAvailableRights[] = 'langconfig';
$wgGroupPermissions['sysop']['langconfig'] = true;

//add the custom langs
$config = explode("\n", file_get_contents($dir . 'funmwlangs.conf'));
$wgfunmwlangs = array();
foreach($config as $line) {
	$l = explode(":", $line, 3);
	//validate input
	if(!preg_match('/^[a-z][a-z-]*[a-z]$/', $l[0])) {
		continue;
	}
	$e = false;
	if($l[1] == 1) {
		$wgExtraLanguageNames[$l[0]] = $l[2];
		$e = true;
	}
	$wgfunmwlangs[$l[0]] = array( $e, $l[2] );
}
