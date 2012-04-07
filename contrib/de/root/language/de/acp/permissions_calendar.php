<?php
/**
*
* permissions_calendar [Deutsch] german_translation Roman.S passat3233@gmx.de
*
* @author alightner
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2009 alightner
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

/**
*	MODDERS PLEASE NOTE
*
*	You are able to put your permission sets into a separate file too by
*	prefixing the new file with permissions_ and putting it into the acp
*	language folder.
*
*	An example of how the file could look like:
*
*	<code>
*
*	if (empty($lang) || !is_array($lang))
*	{
*		$lang = array();
*	}
*
*	// Adding new category
*	$lang['permission_cat']['bugs'] = 'Bugs';
*
*	// Adding new permission set
*	$lang['permission_type']['bug_'] = 'Bug Permissions';
*
*	// Adding the permissions
*	$lang = array_merge($lang, array(
*		'acl_bug_view'		=> array('lang' => 'Can view bug reports', 'cat' => 'bugs'),
*		'acl_bug_post'		=> array('lang' => 'Can post bugs', 'cat' => 'post'), // Using a phpBB category here
*	));
*
*	</code>
*/

// Adding new category
$lang['permission_cat']['calendar'] = 'Kalender';

// Adding new permission set
$lang['permission_type']['calendar_'] = 'Kalender - Rechte';


// User Permissions
$lang = array_merge($lang, array(
	'acl_u_calendar_view_events'	=> array('lang' => 'Darf Termine ansehen', 'cat' => 'calendar'),
	'acl_u_calendar_create_events'	=> array('lang' => 'Darf Termine anlegen', 'cat' => 'calendar'),
	'acl_u_calendar_edit_events'	=> array('lang' => 'Darf Termine bearbeiten', 'cat' => 'calendar'),
	'acl_u_calendar_delete_events'	=> array('lang' => 'Darf Termine löschen', 'cat' => 'calendar'),
	'acl_u_calendar_create_public_events'	=> array('lang' => 'Darf öffentliche Termine anlegen', 'cat' => 'calendar'),
	'acl_u_calendar_create_group_events'	=> array('lang' => 'Darf Gruppentermine anlegen', 'cat' => 'calendar'),
	'acl_u_calendar_create_private_events'	=> array('lang' => 'Darf private Termine anlegen', 'cat' => 'calendar'),
	'acl_u_calendar_nonmember_groups'	=> array('lang' => 'Kann Termine für Gruppen ohne eigene Mitgliedschaft anlegen/bearbeiten', 'cat' => 'calendar'),
	'acl_u_calendar_track_rsvps'	=> array('lang' => 'Darf Termine mit Teilnehmerliste anlegen', 'cat' => 'calendar'),
	'acl_u_calendar_allow_guests'	=> array('lang' => 'Darf Termine mit der Möglichkeit das Benutzer Gäste zum Termin einladen können, anlegen', 'cat' => 'calendar'),
	'acl_u_calendar_view_headcount'	=> array('lang' => 'Darf den Teilnehmerlistenzähler in Terminen, die von anderen Benutzern angelegt wurden, ansehen', 'cat' => 'calendar'),
	'acl_u_calendar_view_detailed_rsvps'	=> array('lang' => 'Darf die Teilnehmerliste in Terminen, die von anderen Benutzern angelegt wurden, ansehen', 'cat' => 'calendar'),
	'acl_u_calendar_create_recurring_events'	=> array('lang' => 'Darf wiederkehrende Termine anlegen', 'cat' => 'calendar'),

));

// Moderator Permissions
$lang = array_merge($lang, array(
	'acl_m_calendar_edit_other_users_events'	=> array('lang' => 'Darf Termine bearbeiten, die von anderen angelegt wurden', 'cat' => 'calendar'),
	'acl_m_calendar_delete_other_users_events'	=> array('lang' => 'Darf Termine löschen, die von anderen angelegt wurden', 'cat' => 'calendar'),
	'acl_m_calendar_edit_other_users_rsvps'	=> array('lang' => 'Darf Antworten bearbeiten, die von anderen angelegt wurden', 'cat' => 'calendar'),
));

// Admin Permissions
$lang = array_merge($lang, array(
	'acl_a_calendar'				=> array('lang' => 'Darf Kalender-Einstellungen und Termin-Typen verwalten', 'cat' => 'calendar'),
));

?>
