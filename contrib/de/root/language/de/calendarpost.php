<?php
/**
*
* calendarpost [Deutsch] german_translation Roman.S passat3233@gmx.de
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
	'ALL_DAY'					=> 'Ganztägiger Termin',
	'ALLOW_GUESTS'				=> 'Erlaube Mitgliedern, Gäste zu diesem Termin einzuladen',
	'ALLOW_GUESTS_ON'			=> 'Mitgliedern ist es erlaubt, Gäste zu diesem Termin einzuladen.',
	'ALLOW_GUESTS_OFF'			=> 'Mitgliedern ist es nicht erlaubt, Gäste zu diesem Termin einzuladen.',
	'AM'						=> 'Vormittags',
	'CALENDAR_POST_EVENT'		=> 'Neuer Termin',
	'CALENDAR_EDIT_EVENT'		=> 'Termin bearbeiten',
	'CALENDAR_TITLE'			=> 'Kalender',
	'DELETE_EVENT'				=> 'Termin löschen',
	'DELETE_ALL_EVENTS'			=> 'Lösche alle Vorkommen dieses Termins.',
	'EMPTY_EVENT_MESSAGE'		=> 'Du mußt einen Text eingeben, wenn du einen Termin anlegst.',
	'EMPTY_EVENT_SUBJECT'		=> 'Du mußt einen Betreff eingeben, wenn du einen Termin anlegst.',
	'END_DATE'					=> 'Ende Datum',
	'END_RECURRING_EVENT_DATE'	=> 'Wann wird dieser Termin enden?',
	'END_TIME'					=> 'Terminende',
	'EVENT_ACCESS_LEVEL'			=> 'Wer darf diesen Termin sehen?',
	'EVENT_ACCESS_LEVEL_GROUP'		=> 'Gruppe',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> 'Privat',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> 'Öffentlich',
	'EVENT_CREATED_BY'			=> 'Termin angelegt von',
	'EVENT_DELETED'				=> 'Der Termin wurde gelöscht.',
	'EVENT_EDITED'				=> 'Der Termin wurde geändert.',
	'EVENT_GROUP'				=> 'Welche Gruppe darf diesen Termin sehen?',
	'EVENT_STORED'				=> 'Der Termin wurde angelegt.',
	'EVENT_TYPE'				=> 'Termin-Typ',
	'EVERYONE'					=> 'Alle',
	'FREQUENCEY_LESS_THAN_1'	=> 'Wiederkehrende Termine müssen ein Termin-Intervall größer oder gleich 1 haben.',
	'FROM_TIME'					=> 'Von',
	'INVITE_INFO'				=> 'Eingeladen',
	'LOGIN_EXPLAIN_POST_EVENT'	=> 'Du mußt angemeldet sein, um Termine hinzuzufügen, zu bearbeiten oder zu löschen.',
	'MESSAGE_BODY_EXPLAIN'		=> 'Text hier eingeben, nicht mehr als <strong>%d</strong> Zeichen.',
	'NEGATIVE_LENGTH_EVENT'		=> 'Der Termin kann nicht enden bevor er begonnen hat!',
	'NEVER'						=> 'Nie',
	'NEW_EVENT'					=> 'Neuer Termin',
	'NO_EVENT'					=> 'Der Termin existiert nicht!',
	'NO_EVENT_TYPES'			=> 'Der Admin hat noch keine Termin-Typen erstellt, es können noch keine Termine angelegt werden.',
	'NO_GROUP_SELECTED'			=> 'Du hast keine Gruppe für diesen Gruppentermin ausgewählt.',
	'NO_POST_EVENT_MODE'		=> 'Keine Post-Methode angelegt.',
	'PM'						=> 'Nachmittags',
	'RECURRING_EVENT'			=> 'Wiederkehrender Termin',
	'RECURRING_EVENT_TYPE'		=> 'Wie soll der nächste Termin berechnet werden?',
	'RECURRING_EVENT_TYPE_EXPLAIN'	=> 'Hinweis: Die Wahlmöglichkeiten beginnen mit einem Buchstaben, die die Frequenz kennzeichen: J - Jährlich, M - Monatlich, W - Wöchentlich, T - Täglich',
	'RECURRING_EVENT_FREQ'		=> 'Termin-Intervall',
	'RECURRING_EVENT_FREQ_EXPLAIN'	=> 'Dieser Wert entspricht [Y] in der Auswahl oben',
	'RECURRING_EVENT_CASE_1'    => 'J: [X.] Tag im [Monat] jedes [Y.] Jahr',
	'RECURRING_EVENT_CASE_2'    => 'J: [X.] [Wochentag] im [Monat] jedes [Y.] Jahr',
	'RECURRING_EVENT_CASE_3'    => 'J: [X.] [Wochentag] von vollen Wochen im [Monat] jedes [Y.] Jahr',
	'RECURRING_EVENT_CASE_4'    => 'J: [X.] vom letzten [Wochentag] im [Monat] jedes [Y.] Jahr',
	'RECURRING_EVENT_CASE_5'    => 'J: [X.] vom letzten [Wochentag] von vollen Wochen im [Monat] jedes [Y.] Jahr',
	'RECURRING_EVENT_CASE_6'    => 'M: [X.] Tag des Monats jeden [Y.] Monat',
	'RECURRING_EVENT_CASE_7'    => 'M: [X.] [Wochentag] des Monats jeden [Y.] Monat',
	'RECURRING_EVENT_CASE_8'    => 'M: [X.] [Wochentag] von vollen Wochen des Monats jeden [Y.] Monat',
	'RECURRING_EVENT_CASE_9'    => 'M: [X.] des letzten [Wochentag] des Monats jeden [Y.] Monat',
	'RECURRING_EVENT_CASE_10'    => 'M: [X.] des letzten [Wochentag] von vollen Wochen des Monats jeden [Y.] Monat',
	'RECURRING_EVENT_CASE_11'    => 'W: [Wochentag] jede [Y.] Woche',
	'RECURRING_EVENT_CASE_12'    => 'T: Jeden [Y.] Tag',

	'RETURN_CALENDAR'			=> '%sZurück zum Kalender%s',
	'START_DATE'				=> 'Beginn Datum',
	'START_TIME'				=> 'Beginn Zeit',
	'TO_TIME'					=> 'Bis',
	'TRACK_RSVPS'				=> 'Aufzeichnung Teilnahmeliste',
	'TRACK_RSVPS_ON'			=> 'Aufzeichnung Teilnahmeliste ist aktiviert.',
	'TRACK_RSVPS_OFF'			=> 'Aufzeichnung Teilnahmeliste ist deaktiviert.',
	'USER_CANNOT_DELETE_EVENT'	=> 'Du hast leider keine Berechtigung, Termine zu löschen.',
	'USER_CANNOT_EDIT_EVENT'	=> 'Du hast leider keine Berechtigung, Termine zu bearbeiten.',
	'USER_CANNOT_POST_EVENT'	=> 'Du hast leider keine Berechtigung, Termine anzulegen.',
	'USER_CANNOT_VIEW_EVENT'	=> 'Du hast leider keine Berechtigung, Termine anzusehen.',
	'VIEW_EVENT'				=> '%sAngelegten Termin ansehen%s',
	'WEEK'						=> 'Woche',
	'ZERO_LENGTH_EVENT'			=> 'Ein Termin kann nicht zur gleichen Zeit beginnen und enden!',

));

?>
