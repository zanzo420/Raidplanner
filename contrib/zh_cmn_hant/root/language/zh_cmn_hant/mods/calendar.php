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
    '12_HOURS'								=> '12 小時',
    '24_HOURS'								=> '24 小時',
    'AUTO_POPULATE_EVENT_FREQUENCY'			=> '自動填充循環的事件',
    'AUTO_POPULATE_EVENT_FREQUENCY_EXPLAIN'	=> '在行事曆上循環的事件多久（幾天）將被填寫一次？注意：假如您選擇 0，循環的事件永遠不會被增加到行事曆。',
    'AUTO_POPULATE_EVENT_LIMIT'				=> '自動填充限制',
    'AUTO_POPULATE_EVENT_LIMIT_EXPLAIN'		=> '您要在多少天以前填入循環的事件？換句話說，您想要在行事曆上，此事件發生的 30、45、或更多天以前，看見循環的事件？',
    'AUTO_PRUNE_EVENT_FREQUENCY'         => '自動刪除頻率',
    'AUTO_PRUNE_EVENT_FREQUENCY_EXPLAIN'   => '每隔多少天，自動刪除過期的事件一次？設定 0，全部過期的事件將不自動刪除，必須手動刪除。',
    'AUTO_PRUNE_EVENT_LIMIT'            => '自動刪除期限',
    'AUTO_PRUNE_EVENT_LIMIT_EXPLAIN'      => '事件過期多少天後，加入下一次自動刪除列表？換句話說，您想要保留已過期的事件之期限為 0、30、45天？',
    'CALENDAR_ETYPE_NAME'					=> '事件類型名稱',
    'CALENDAR_ETYPE_COLOR'					=> '事件類型顏色',
    'CALENDAR_ETYPE_ICON'					=> '事件類型圖片位址',
    'CALENDAR_SETTINGS_EXPLAIN'				=> '改變行事曆設定。',
    'CHANGE_EVENTS_TO'						=> '更改全部事件為此類型',
    'CLICK_PLUS_HOUR'						=> '移動所有的事件一個小時。',
    'CLICK_PLUS_HOUR_EXPLAIN'				=> '當您重新設定論壇的時區時，能夠移動行事曆上所有的事件 +/- 一個小時。注意：點選移動事件的連結，將遺失您在上頭所做的改變。在移動事件 +/- 一個小時之前，請送出表單以儲存您的工作。',
    'COLOR'									=> '顏色',
    'CREATE_EVENT_TYPE'						=> '新增事件類型',
    'DATE_FORMAT'							=> '日期格式',
    'DATE_FORMAT_EXPLAIN'					=> '試試 &quot;M d, Y&quot;',
    'DATE_TIME_FORMAT'						=> '日期與時間格式',
    'DATE_TIME_FORMAT_EXPLAIN'				=> '試試 &quot;M d, Y h:i a&quot; 或 &quot;M d, Y H:i&quot;',
    'DELETE'								=> '刪除',
    'DELETE_ALL_EVENTS'						=> '刪除已存在的此類型之任何事件',
    'DELETE_ETYPE'							=> '刪除事件類型',
    'DELETE_ETYPE_EXPLAIN'					=> '您確定刪除此類型事件？',
    'DELETE_LAST_EVENT_TYPE'				=> '警告：此為最後一個事件類型。',
    'DELETE_LAST_EVENT_TYPE_EXPLAIN'		=> '刪除此事件類型將會刪除所有包含的事件，除非新增事件類型，否則無法新增事件。',
    'DISPLAY_12_OR_24_HOURS'				=> '顯示時間格式',
    'DISPLAY_12_OR_24_HOURS_EXPLAIN'		=> '時間格式為 AM/PM 12 小時模式或 24 小時模式？此設定將不影響使用者在會員控制台設定的時間格式。影響範圍：1、新增及編輯事件的時間格式之下拉式選單。2、日曆的時間標題。',
    'DISPLAY_HIDDEN_GROUPS'					=> '顯示隱藏的群組',
    'DISPLAY_HIDDEN_GROUPS_EXPLAIN'			=> '您想要讓使用者能夠看見以及邀請隱藏的群組會員嗎？如果設定為否，那麼將只有群組組長能夠看見以及邀請隱藏的群組會員。',
    'DISPLAY_NAME'							=> '名稱描述 (可為空白)',
    'DISPLAY_EVENTS_ONLY_1_DAY'				=> '顯示一整天的事件',
    'DISPLAY_EVENTS_ONLY_1_DAY_EXPLAIN'		=> '只有顯示當天事件開始的時間 (忽略結束的日期/時間)。',
    'DISPLAY_FIRST_WEEK'					=> '顯示未來一週事件',
    'DISPLAY_FIRST_WEEK_EXPLAIN'			=> '要將未來一週事件顯示於論壇首頁？',
    'DISPLAY_NEXT_EVENTS'					=> '條列式顯示未來事件的數目',
    'DISPLAY_NEXT_EVENTS_EXPLAIN'			=> '設定論壇首頁條列式顯示未來事件的數目。注意：當開啟目前週曆顯示於論壇首頁的功能，此選項將被忽略。',
    'DISPLAY_TRUNCATED_SUBJECT'				=> '縮短標題名稱',
    'DISPLAY_TRUNCATED_SUBJECT_EXPLAIN'		=> '過長標題名稱將浪費行事曆空間。您要將標題名稱的字元數設定為？(輸入 0，為不縮短標題名稱)',
    'EDIT'									=> '編輯',
    'EDIT_ETYPE'							=> '編輯事件類型',
    'EDIT_ETYPE_EXPLAIN'					=> '設定事件類型顯示的方式。',
    'FIRST_DAY'								=> '第一天',
    'FIRST_DAY_EXPLAIN'						=> '顯示週曆的第一天為？',
    'FULL_NAME'								=> '事件類型',
    'FRIDAY'								=> '星期五',
    'ICON_URL'								=> '圖片 (完整) 位址',
    'MANAGE_ETYPES'							=> '管理事件類型',
    'MANAGE_ETYPES_EXPLAIN'					=> '事件類型可用來幫助管理行事曆，您可以在此新增、編輯、刪除及記錄事件類型。',
    'MINUS_HOUR'							=> '將所有的事件減少一個小時',
    'MONDAY'								=> '星期一',
    'PLUS_HOUR'								=> '將所有的事件增加一個小時',
    'PLUS_HOUR_CONFIRM'						=> '您確認要將所有的事件移動 %1$s 個小時？',
    'PLUS_HOUR_SUCCESS'						=> '已成功地移動所有的事件 %1$s 個小時。',
    'NO_EVENT_TYPE_ERROR'					=> '無法搜尋指定的事件類型。',
    'SATURDAY'								=> '星期六',
    'SUNDAY'								=> '星期日',
    'TIME_FORMAT'							=> '時間格式',
    'TIME_FORMAT_EXPLAIN'					=> '試試 &quot;h:i a&quot; 或 &quot;H:i&quot;',
    'THURSDAY'								=> '星期四',
    'TUESDAY'								=> '星期二',
    'USER_CANNOT_MANAGE_CALENDAR'			=> '您沒有權限設定行事曆及事件類型。',
    'WEDNESDAY'								=> '星期三',

));

?>