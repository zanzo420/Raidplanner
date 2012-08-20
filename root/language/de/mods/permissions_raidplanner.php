<?php
/**
*
* permissions_calendar [German]
*
* @author alightner
*
* @package phpBB Calendar
* @version CVS/SVN: $Id$
* @copyright (c) 2009 alightner
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @translation killerpommes
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
$lang['permission_cat']['raidplanner'] = 'Raidplaner';

// Adding new permission set
$lang['permission_type']['raidplanner_'] = 'Raidplaner Berechtigungen';

$lang = array_merge($lang, array(
	
	/* Admin Permissions */
	'acl_a_raid_config'		=> array('lang' => 'Kann ACP Raidplaner Einstellungen und Raidplan Typen verwalten', 'cat' => 'raidplanner'),
	
	
	/* moderator permissions */
	'acl_m_raidplanner_edit_other_users_raidplans'	=> array('lang' => 'Kann von anderen Benutzern erstellte Raidpläne bearbeiten', 'cat' => 'raidplanner'),
	'acl_m_raidplanner_delete_other_users_raidplans'	=> array('lang' => 'Kann von anderen Benutzern erstellte Raidpläne löschen', 'cat' => 'raidplanner'),
	'acl_m_raidplanner_edit_other_users_signups'	=> array('lang' => 'Kann von anderen Benutzern erstellte Antworten bearbeiten', 'cat' => 'raidplanner'),
	
	
	/* User Permissions */
	// allows creating raids
	'acl_u_raidplanner_create_raidplans'			=> array('lang' => 'Kann Raidpläne erstellen', 'cat' => 'raidplanner'),
	// allows group raidplans where only usergroups can subscribe
	'acl_u_raidplanner_create_group_raidplans'	=> array('lang' => 'Kann Gruppe Raidpläne erstellen', 'cat' => 'raidplanner'),
	// allows public raidplans where every member can subscribe 
	'acl_u_raidplanner_create_public_raidplans'	=> array('lang' => 'Kann öffentlichen Raidpläne erstellen', 'cat' => 'raidplanner'),
	// allows private raidplans - only for you - eg hairdresser
	'acl_u_raidplanner_create_private_raidplans'	=> array('lang' => 'Kann privaten Raidpläne erstellen', 'cat' => 'raidplanner'),
	// can create raidplans that recur
	'acl_u_raidplanner_create_recurring_raidplans' => array('lang' => 'Kann wiederkehrende Raidpläne erstellen', 'cat' => 'raidplanner'),
	// allows deleting raids
	'acl_u_raidplanner_delete_raidplans'			=> array('lang' => 'Kann Raidpläne löschen', 'cat' => 'raidplanner'),
	// allows editing raids that you created
	'acl_u_raidplanner_edit_raidplans'			=> array('lang' => 'Kann Raidpläne bearbeiten', 'cat' => 'raidplanner'),
	// allows signing up to raids
	'acl_u_raidplanner_signup_raidplans'			=> array('lang' => 'Kann sich für Raidpläne anmelden', 'cat' => 'raidplanner'),
	// allows viewing raids
	'acl_u_raidplanner_view_raidplans'			=> array('lang' => 'Kann Raidpläne sehen', 'cat' => 'raidplanner'),
	// view raid participation
	'acl_u_raidplanner_view_headcount'			=> array('lang' => 'Kann die Kopfzahl für von anderen Benutzern erstellte Raidpläne sehen', 'cat' => 'raidplanner'),
	// push plans
	'acl_u_raidplanner_push' => array('lang' => 'Kann Raidpläne in BBDKP einfügen(Push)', 'cat' => 'raidplanner'),

));

?>
