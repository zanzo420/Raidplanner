<?php
/**
*
* calendarpost [正體中文]
*
* @author alightner alightner@hotmail.com
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2008 phpBB-TW 心靈捕手 http://phpbb-tw.net/
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

$lang = array_merge($lang, array(
	'ALL_DAY'					=> '持續一整天',
	'ALLOW_GUESTS'				=> '允許會員讓訪客看見這個事件。',
	'ALLOW_GUESTS_ON'			=> '會員被允許讓訪客看見這個事件。',
	'ALLOW_GUESTS_OFF'			=> '會員不被允許讓訪客看見這個事件。',
	'AM'						=> 'AM',
	'CALENDAR_POST_EVENT'		=> '新增事件',
	'CALENDAR_EDIT_EVENT'		=> '編輯事件',
	'CALENDAR_TITLE'			=> '行事曆',
	'DELETE_EVENT'				=> '刪除事件',
	'DELETE_ALL_EVENTS'			=> '刪除這個事件的所有出現率',
	'EMPTY_EVENT_MESSAGE'		=> '請輸入內容。',
	'EMPTY_EVENT_SUBJECT'		=> '請輸入標題。',
	'END_DATE'					=> '結束日期',
	'END_RECURRING_EVENT_DATE'	=> '這個事件將在何時結束？',
	'END_TIME'					=> '結束時間',
	'EVENT_ACCESS_LEVEL'			=> '誰可以檢視事件',
	'EVENT_ACCESS_LEVEL_GROUP'		=> '群組',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> '私人',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> '公開',
	'EVENT_CREATED_BY'			=> '發表人',
	'EVENT_DELETED'				=> '事件刪除成功。',
	'EVENT_EDITED'				=> '事件編輯成功。',
	'EVENT_GROUP'				=> '哪個群組可以檢視事件',
	'EVENT_STORED'				=> '事件新增成功。',
	'EVENT_TYPE'				=> '事件類型',
	'EVERYONE'					=> '任何人',
	'FROM_TIME'					=> '從',
	'INVITE_INFO'				=> '可檢視人員',
	'LOGIN_EXPLAIN_POST_EVENT'	=> '您必須登入後才能新增/編輯/刪除事件。',
	'MESSAGE_BODY_EXPLAIN'		=> '在此編輯訊息，不可以小於 <strong>%d</strong> 字元。',
	'NEGATIVE_LENGTH_EVENT'		=> '事件的結束時間不可以早於開始時間。',
	'NEVER'						=> '從不',
	'NEW_EVENT'					=> '新的事件',
	'NO_EVENT'					=> '要求的事件不存在.',
	'NO_EVENT_TYPES'			=> '管理員尚未設定事件類型，無法新增事件。',
	'NO_POST_EVENT_MODE'		=> '沒有指定的發文模式。',
	'PM'						=> 'PM',
	'RECURRING_EVENT'			=> '循環的事件',
	'RECURRING_EVENT_TYPE'		=> '下個事件將要如何計畫？',
	'RECURRING_EVENT_TYPE_EXPLAIN'	=> '選擇一個字母表示它們頻率：A - 每年、M - 每月、W - 每週、D - 每天',
	'RECURRING_EVENT_FREQ'		=> '這個事件多久發生一次？',
	'RECURRING_EVENT_FREQ_EXPLAIN'	=> '在上頭選擇的這個值表示 [Y]',
	'RECURRING_EVENT_CASE_1'    => 'A: 每 [Y] 年 [月名] 的第 [X] 天',
	'RECURRING_EVENT_CASE_2'    => 'A: 每 [Y] 年 [月名] 的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_3'    => 'A: 每 [Y] 年在 [月名] 整週的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_4'    => 'A: 從每 [Y] 年 [月名] 最後的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_5'    => 'A: 從每 [Y] 年在 [月名] 最後整週的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_6'    => 'M: 每 [Y] 月的第 [X] 天',
	'RECURRING_EVENT_CASE_7'    => 'M: 每 [Y] 月的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_8'    => 'M: 每 [Y] 月整週的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_9'    => 'M: 從每 [Y] 月最後的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_10'    => 'M: 從每 [Y] 月最後整週的第 [X] [平日名]',
	'RECURRING_EVENT_CASE_11'    => 'W: 每 [Y] 週 [平日名]',
	'RECURRING_EVENT_CASE_12'    => 'D: 每 [Y] 天',

	'RETURN_CALENDAR'			=> '%s返回行事曆%s',
	'START_DATE'				=> '開始日期',
	'START_TIME'				=> '開始時間',
	'TO_TIME'					=> '至',
	'TRACK_RSVPS'				=> '追蹤出席的人數',
	'TRACK_RSVPS_ON'			=> '啟用追蹤出席的人數之功能。',
	'TRACK_RSVPS_OFF'			=> '停用追蹤出席的人數之功能。',
	'USER_CANNOT_DELETE_EVENT'	=> '您沒有刪除事件的權限。',
	'USER_CANNOT_EDIT_EVENT'	=> '您沒有編輯事件的權限。',
	'USER_CANNOT_POST_EVENT'	=> '您沒有新增事件的權限。',
	'USER_CANNOT_VIEW_EVENT'	=> '您沒有檢視事件的權限。',
	'VIEW_EVENT'				=> '%s檢視發佈的事件%s',
	'WEEK'						=> '週',
	'ZERO_LENGTH_EVENT'			=> '事件的結束時間不可以等於開始時間。',
));

?>