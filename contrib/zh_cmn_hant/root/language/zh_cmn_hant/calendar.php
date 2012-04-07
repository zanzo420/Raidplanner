<?php
/**
*
* calendar [正體中文]
*
* @author alightner alightner@hotmail.com
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2008 phpBB-TW 心靈捕手 http://phpbb-tw.net/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*** DO NOT CHANGE*/
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

$lang = array_merge($lang, array(
	'ALL_DAY'				=> '持續一整天',
	'AM'					=> 'AM',
	'CALENDAR_TITLE'		=> '行事曆',
	'CALENDAR_NUMBER_ATTEND'=> '您所設定參加這個事件的人數',
	'CALENDAR_NUMBER_ATTEND_EXPLAIN'=> '(為自己輸入 1)',
	'CALENDAR_RESPOND'		=> '請在這裡註冊',
	'CALENDAR_WILL_ATTEND'	=> '您將要參加這個事件嗎？',
	'COL_HEADCOUNT'			=> '總數',
	'COL_WILL_ATTEND'		=> '將要出席嗎？',
	'COMMENTS'				=> '評論',
	'DAY'					=> '以日曆顯示',
	'DAY_OF'				=> '日曆：',
	'DELETE_ALL_EVENTS'		=> '刪除這個事件的所有出現率。',
	'DETAILS'				=> '細節',
	'EDIT'					=> '編輯',
	'EDIT_ALL_EVENTS'		=> '編輯這個事件的所有出現率。',
	'EVENT_CREATED_BY'		=> '發表人',
	'EVERYONE'				=> '任何人',
	'FROM_TIME'				=> '從',
	'HOW_MANY_PEOPLE'		=> '快速地清點人數',
	'INVALID_EVENT'			=> '嘗試檢視的事件不存在.',
	'INVITE_INFO'			=> '可檢視人員',
	'OCCURS_EVERY'			=> '發生的一切',
	'RECURRING_EVENT_CASE_1_STR'    => '%4$s 的 %1$s 天 - 每 %5$s 年',
	'RECURRING_EVENT_CASE_2_STR'    => '%4$s 的 %3$s %2$s - 每 %5$s 年',
	'RECURRING_EVENT_CASE_3_STR'    => '在 %4$s 整週的 %3$s %2$s - 每 %5$s 年',
	'RECURRING_EVENT_CASE_3b_STR'    => '在 %4$s 第一部份週的 %2$s - 每 %5$s 年',
	'RECURRING_EVENT_CASE_4_STR'    => '%3$s 從 %4$s 之最後的 %2$s - 每 %5$s 年',
	'RECURRING_EVENT_CASE_5_STR'    => '%3$s 從在 %4$s 整週之最後的 %2$s - 每 %5$s 年',
	'RECURRING_EVENT_CASE_5b_STR'    => '在 %4$s 第一部份週的 %2$s - 每 %5$s 年',
	'RECURRING_EVENT_CASE_6_STR'    => '月的 %1$s 天 - 每 %5$s 月',
	'RECURRING_EVENT_CASE_7_STR'    => '月的 %3$s %2$s - 每 %5$s 月',
	'RECURRING_EVENT_CASE_8_STR'    => '在月整週的 %3$s %2$s - 每 %5$s 月',
	'RECURRING_EVENT_CASE_8b_STR'    => '在月第一部份週的  - 每 %5$s 月',
	'RECURRING_EVENT_CASE_9_STR'    => '%3$s 從月之最後的 %2$s - 每 %5$s 月',
	'RECURRING_EVENT_CASE_10_STR'    => '%3$s 從在月整週之最後的 %2$s - 每 %5$s 月',
	'RECURRING_EVENT_CASE_10b_STR'    => '在月最後部份週的 %2$s - 每 %5$s 月',
	'RECURRING_EVENT_CASE_11_STR'    => '%2$s - 每 %5$s 週',
	'RECURRING_EVENT_CASE_12_STR'    => '每 %5$s 天',
	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'MAYBE'					=> '也許',
	'MONTH'					=> '以月曆顯示',
	'MONTH_OF'				=> '月曆：',
	'MY_EVENTS'				=> '我的事件',
	'NEW_EVENT'				=> '新的事件',
	'NO_EVENTS_TODAY'		=> '這天沒有行事計畫。',
	'PAGE_TITLE'			=> '行事曆',
	'PM'					=> 'PM',
	'PRIVATE_EVENT'			=> '私人事件，必須被授權以及登入後才可檢視。',
	'TO_TIME'				=> '到',
	'UPCOMING_EVENTS'		=> '即將到來事件',
	'USER_CANNOT_VIEW_EVENT'=> '您沒有權限檢視事件。',
	'WATCH_CALENDAR_TURN_ON'	=> '訂閱行事曆',
	'WATCH_CALENDAR_TURN_OFF'	=> '停止訂閱行事曆',
	'WATCH_EVENT_TURN_ON'	=> '訂閱此事件',
	'WATCH_EVENT_TURN_OFF'	=> '停止訂閱此事件',
	'WEEK'					=> '以週曆顯示',
	'WEEK_OF'				=> '週曆：',
	'ZEROTH_FROM'			=> '從第 0 個起 ',
	'numbertext'			=> array(
		'0'		=> '第 0 個',
		'1'		=> '第 1 個',
		'2'		=> '第 2 個',
		'3'		=> '第 3 個',
		'4'		=> '第 4 個',
		'5'		=> '第 5 個',
		'6'		=> '第 6 個',
		'7'		=> '第 7 個',
		'8'		=> '第 8 個',
		'9'		=> '第 9 個',
		'10'	=> '第 10 個',
		'11'	=> '第 11 個',
		'12'	=> '第 12 個',
		'13'	=> '第 13 個',
		'14'	=> '第 14 個',
		'15'	=> '第 15 個',
		'16'	=> '第 16 個',
		'17'	=> '第 17 個',
		'18'	=> '第 18 個',
		'19'	=> '第 19 個',
		'20'	=> '第 20 個',
		'21'	=> '第 21 個',
		'22'	=> '第 22 個',
		'23'	=> '第 23 個',
		'24'	=> '第 24 個',
		'25'	=> '第 25 個',
		'26'	=> '第 26 個',
		'27'	=> '第 27 個',
		'28'	=> '第 28 個',
		'29'	=> '第 29 個',
		'30'	=> '第 30 個',
		'31'	=> '第 31 個',
		'n'		=> '第 n 個' ),
));

?>