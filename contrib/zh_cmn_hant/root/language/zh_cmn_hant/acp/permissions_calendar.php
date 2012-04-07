<?php
/**
* calendar [正體中文]
*
* @author alightner alightner@hotmail.com
* @author SpaceDoG spacedog@hypermutt.com
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2008 phpBB-TW 心靈捕手 http://phpbb-tw.net/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
$lang['permission_cat']['calendar'] = '行事曆';

// Adding new permission set
$lang['permission_type']['calendar_'] = '行事曆權限';


// User Permissions
$lang = array_merge($lang, array(
	'acl_u_calendar_view_events'	=> array('lang' => '可檢視行事曆事件', 'cat' => 'calendar'),
	'acl_u_calendar_create_events'	=> array('lang' => '可新增行事曆事件', 'cat' => 'calendar'),
	'acl_u_calendar_edit_events'	=> array('lang' => '可編輯行事曆事件', 'cat' => 'calendar'),
	'acl_u_calendar_delete_events'	=> array('lang' => '可刪除行事曆事件', 'cat' => 'calendar'),
	'acl_u_calendar_create_public_events'	=> array('lang' => '可建立公開的事件', 'cat' => 'calendar'),
	'acl_u_calendar_create_group_events'	=> array('lang' => '可建立群組的事件', 'cat' => 'calendar'),
	'acl_u_calendar_create_private_events'	=> array('lang' => '可建立私人的事件', 'cat' => 'calendar'),
	'acl_u_calendar_nonmember_groups'	=> array('lang' => '可建立/編輯他們不屬於之群組的事件', 'cat' => 'calendar'),
	'acl_u_calendar_track_rsvps'	=> array('lang' => '可建立含有追蹤出席人數的事件', 'cat' => 'calendar'),
	'acl_u_calendar_allow_guests'	=> array('lang' => '可建立允許外面訪客瀏覽的事件', 'cat' => 'calendar'),
	'acl_u_calendar_view_headcount'	=> array('lang' => '可檢視被其他會員所建立事件的點閱人數', 'cat' => 'calendar'),
	'acl_u_calendar_view_detailed_rsvps'	=> array('lang' => '可檢視被其他會員所建立事件的詳細回覆', 'cat' => 'calendar'),
	'acl_u_calendar_create_recurring_events'	=> array('lang' => '可建立循環的事件', 'cat' => 'calendar'),

));

// Moderator Permissions
$lang = array_merge($lang, array(
	'acl_m_calendar_edit_other_users_events'	=> array('lang' => '可編輯他人新增的行事曆事件', 'cat' => 'calendar'),
	'acl_m_calendar_delete_other_users_events'	=> array('lang' => '可刪除他人新增的行事曆事件', 'cat' => 'calendar'),
	'acl_m_calendar_edit_other_users_rsvps'	=> array('lang' => '可編輯被其他會員所建立的回覆', 'cat' => 'calendar'),
));

// Admin Permissions
$lang = array_merge($lang, array(
	'acl_a_calendar'				=> array('lang' => '可設定行事曆及事件類型', 'cat' => 'calendar'),
));

?>