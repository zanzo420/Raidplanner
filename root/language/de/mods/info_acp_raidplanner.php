<?php
/**
 * bbdkp acp language file for raidplanner module [German]
 * 
 * @package bbDkp
 * @copyright 2010 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * @translation killerpommes
 * 
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(
	'ACP_CAT_RAIDPLANNER' 				=> 'Raidplaner', //main tab 
	'ACP_RAIDPLANNER' 					=> 'Raidplaner', //category
	'ACP_RAIDPLANNER_SETTINGS'  		=> 'Raidplanereinstellungen', 	//module
	'ACP_RAIDPLANNER_SETTINGS_EXPLAIN' 	=> 'Hier konfigurierst du die Raid Planer Einstellungen',
	'ACP_RAIDPLANNER_EVENTSETTINGS'		=> 'Ereigniseinstellungen', //module
));

?>