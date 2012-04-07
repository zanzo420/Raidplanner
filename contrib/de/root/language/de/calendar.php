<?php
/**
*
* calendar [Deutsch] german_translation Roman.S passat3233@gmx.de
*
* @author alightner
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2009 alightner
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
	'ALL_DAY'				=> 'Ganztägiger Termin',
	'AM'					=> 'Vormittags',
	'CALENDAR_TITLE'		=> 'Kalender',
	'CALENDAR_NUMBER_ATTEND'=> 'Die Anzahl von Leuten, die du zu diesem Termin einlädst',
	'CALENDAR_NUMBER_ATTEND_EXPLAIN'=> '(gebe für dich selbst eine 1 ein)',
	'CALENDAR_RESPOND'		=> 'Bitte registriere dich hier für den Termin',
	'CALENDAR_WILL_ATTEND'	=> 'Wirst du an diesem Termin teilnehmen?',
	'COL_HEADCOUNT'			=> 'Anzahl',
	'COL_WILL_ATTEND'		=> 'wird teilnehmen?',
	'COMMENTS'				=> 'Kommentare',
	'DAY'					=> 'Tag',
	'DAY_OF'				=> 'Tag ',
	'DELETE_ALL_EVENTS'		=> 'Lösche alle Vorkommen dieses Termins.',
	'DETAILS'				=> 'Details',
	'EDIT'					=> 'Bearbeiten',
	'EDIT_ALL_EVENTS'		=> 'Bearbeite alle Vorkommen dieses Termins.',
	'EVENT_CREATED_BY'		=> 'Termin angelegt von',
	'EVERYONE'				=> 'Alle',
	'FROM_TIME'				=> 'Von',
	'HOW_MANY_PEOPLE'		=> 'Anzahl Teilnehmer',
	'INVALID_EVENT'			=> 'Der Termin, den du ansehen möchtest, exisiert nicht.',
	'INVITE_INFO'			=> 'Eingeladen',
	'OCCURS_EVERY'			=> 'Wiederholt sich',
	'RECURRING_EVENT_CASE_1_STR'    => '%1$s Tag im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_2_STR'    => '%3$s %2$s im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_3_STR'    => '%3$s %2$s von vollen Wochen im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_3b_STR'    => '%2$s der ersten Teilwoche im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_4_STR'    => '%3$s vom letzten %2$s im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_5_STR'    => '%3$s vom letzten %2$s von vollen Wochen im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_5b_STR'    => '%2$s der letzten Teilwoche im %4$s - jedes %5$s. Jahr',
	'RECURRING_EVENT_CASE_6_STR'    => '%1$s Tag des Monats - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_7_STR'    => '%3$s %2$s des Monats - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_8_STR'    => '%3$s %2$s von vollen Wochen im Monat - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_8b_STR'    => '%2$s der ersten Teilwoche im Monat - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_9_STR'    => '%3$s des letzten %2$s des Monats - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_10_STR'    => '%3$s des letzten %2$s von vollen Wochen im Monat - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_10b_STR'    => '%2$s der letzten Teilwoche im Monat - jeden %5$s. Monat',
	'RECURRING_EVENT_CASE_11_STR'    => '%2$s - jede %5$s Woche',
	'RECURRING_EVENT_CASE_12_STR'    => 'Jeden %5$s Tag',
	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'MAYBE'					=> 'Evtl.',
	'MONTH'					=> 'Monat',
	'MONTH_OF'				=> 'Monat ',
	'MY_EVENTS'				=> 'Meine Termine',
	'NEW_EVENT'				=> 'Neuer Termin',
	'NO_EVENTS_TODAY'		=> 'Im Kalender stehen für heute keine Termine.',
	'PAGE_TITLE'			=> 'Kalender',
	'PM'					=> 'Nachmittags',
	'PRIVATE_EVENT'			=> 'Dieser Termin ist privat. Du mußt eingeladen und angemeldet sein, um ihn anzusehen.',
	'TO_TIME'				=> 'bis',
	'UPCOMING_EVENTS'		=> 'Anstehende Termine',
	'USER_CANNOT_VIEW_EVENT'=> 'Du hast leider keine Berechtigung, diesen Termin anzusehen.',
	'WATCH_CALENDAR_TURN_ON'	=> 'Kalender ansehen',
	'WATCH_CALENDAR_TURN_OFF'	=> 'Kalender ansehen beenden',
	'WATCH_EVENT_TURN_ON'	=> 'Termin ansehen',
	'WATCH_EVENT_TURN_OFF'	=> 'Termin ansehen beenden',
	'WEEK'					=> 'Woche',
	'WEEK_OF'				=> 'Woche ',
	'ZEROTH_FROM'			=> '0. von ',
	'numbertext'			=> array(
		'0'		=> '0.',
		'1'		=> '1.',
		'2'		=> '2.',
		'3'		=> '3.',
		'4'		=> '4.',
		'5'		=> '5.',
		'6'		=> '6.',
		'7'		=> '7.',
		'8'		=> '8.',
		'9'		=> '9.',
		'10'	=> '10.',
		'11'	=> '11.',
		'12'	=> '12.',
		'13'	=> '13.',
		'14'	=> '14.',
		'15'	=> '15.',
		'16'	=> '16.',
		'17'	=> '17.',
		'18'	=> '18.',
		'19'	=> '19.',
		'20'	=> '20.',
		'21'	=> '21.',
		'22'	=> '22.',
		'23'	=> '23.',
		'24'	=> '24.',
		'25'	=> '25.',
		'26'	=> '26.',
		'27'	=> '27.',
		'28'	=> '28.',
		'29'	=> '29.',
		'30'	=> '30.',
		'31'	=> '31.',
		'n'		=> 'n.' ),
));

?>
