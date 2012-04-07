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
    '12_HOURS'								=> '12 Stunden',
    '24_HOURS'								=> '24 Stunden',
    'AUTO_POPULATE_EVENT_FREQUENCY'			=> 'Anzeige Wiederkehrende Termine',
    'AUTO_POPULATE_EVENT_FREQUENCY_EXPLAIN'	=> 'Wie oft (in Tagen) sollen wiederkehrende Termine im Kalender angezeigt werden?  Wenn du 0 wählst, werden wiederkehrende Termine nicht in den Kalender eingetragen!',
    'AUTO_POPULATE_EVENT_LIMIT'				=> 'Anzeige-Begrenzung',
    'AUTO_POPULATE_EVENT_LIMIT_EXPLAIN'		=> 'Wieviel Tage im Vorraus sollen wiederkehrende Termine angezeigt werden?  Mit anderen Worten, willst du wiederkehrende Termine nur 30, 45 oder mehr Tage vor dem Termin sehen?',
    'AUTO_PRUNE_EVENT_FREQUENCY'			=> 'Automatisches Löschen alter Termine',
    'AUTO_PRUNE_EVENT_FREQUENCY_EXPLAIN'	=> 'Wie häufig (in Tagen) sollen alte Termine vom Kalender gelöscht werden?  Wenn du 0 wählst, werden alte Termine nicht gelöscht, du mußt sie manuell entfernen.',
    'AUTO_PRUNE_EVENT_LIMIT'				=> 'Begrenzung automatisches Löschen',
    'AUTO_PRUNE_EVENT_LIMIT_EXPLAIN'		=> 'Nach wie vielen Tagen soll der Termin der Löschliste hinzugefügt werden?  Z.B., sollen alle Termine 0, 30 oder 45 Tage erhalten bleiben?',
    'CALENDAR_ETYPE_NAME'					=> 'Name Termin-Typ',
    'CALENDAR_ETYPE_COLOR'					=> 'Farbe Termin-Typ',
    'CALENDAR_ETYPE_ICON'					=> 'Icon URL Termin-Typ',
    'CALENDAR_SETTINGS_EXPLAIN'				=> 'Kalender-Einstellungen',
    'CHANGE_EVENTS_TO'						=> 'Ändere alle Termine dieses Typs zu',
    'CLICK_PLUS_HOUR'						=> 'Verschiebe ALLE Termine um eine Stunde.',
    'CLICK_PLUS_HOUR_EXPLAIN'				=> 'Die Möglichkeit, alle Termine im Kalender um +/- eine Stunde zu verschieben hilft dir, wenn du die Sommerzeiteinstellungen des Forums zurückstellst.  Bemerkung: Anklicken der Links, um Termine zu verschieben, führt zum Verlust aller Änderungen, die du oben gemacht hast.  Bitte sende das Formular erst ab, um deine Arbeit zu sichern, bevor du Termine um +/- eine Stunde verschiebst.',
    'COLOR'									=> 'Farbe',
    'CREATE_EVENT_TYPE'						=> 'Neuer Termin-Typ',
    'DATE_FORMAT'							=> 'Datumsformat',
    'DATE_FORMAT_EXPLAIN'					=> 'Versuche &quot;M d, Y&quot;',
    'DATE_TIME_FORMAT'						=> 'Datums- und Zeitformat',
    'DATE_TIME_FORMAT_EXPLAIN'				=> 'Versuche &quot;M d, Y h:i a&quot; oder &quot;M d, Y H:i&quot;',
    'DELETE'								=> 'Löschen',
    'DELETE_ALL_EVENTS'						=> 'Lösche alle Termine dieses Typs',
    'DELETE_ETYPE'							=> 'Lösche Termin-Typ',
    'DELETE_ETYPE_EXPLAIN'					=> 'Sicher, daß du diesen Termin-Typ löschen willst?',
    'DELETE_LAST_EVENT_TYPE'				=> 'Warnung: Das ist der letzte Termin-Typ!',
    'DELETE_LAST_EVENT_TYPE_EXPLAIN'		=> 'Wenn dieser Termin-Typ gelöscht wird, gibt es keinen Termin mehr! Erst wenn ein neuer Termin-Typ angelegt wurde, können wieder Termine eingetragen werden.',
    'DISPLAY_12_OR_24_HOURS'				=> 'Anzeige Zeit-Format',
    'DISPLAY_12_OR_24_HOURS_EXPLAIN'		=> 'Sollen die Zeiten im 12-Stunden-Format mit AM/PM oder im 24-Stunden-Format angezeigt werden?  Dies betrifft nicht die Anzeige beim Benutzer - diese wird in deren Profil eingestellt.  Dies betrifft nur die Pulldown-Menüs der Zeiten-Auswahl beim Anlegen/Bearbeiten von Terminen und Zeit-Felder bei der Tagesansicht.',
    'DISPLAY_HIDDEN_GROUPS'					=> 'Zeige versteckte Gruppen',
    'DISPLAY_HIDDEN_GROUPS_EXPLAIN'			=> 'Sollen Benutzer versteckte Benutzergruppen sehen und zu Terminen einladen können? Wenn diese Option deaktiviert ist, können nur (Gruppen-)Administratoren Mitglieder versteckter Gruppen sehen bzw. zu Terminen einladen.',
    'DISPLAY_NAME'							=> 'Angezeigter Name (kann LEER sein)',
    'DISPLAY_EVENTS_ONLY_1_DAY'				=> 'Zeige Termine 1 Tag',
    'DISPLAY_EVENTS_ONLY_1_DAY_EXPLAIN'		=> 'Zeige Termine nur an dem Tag, an dem sie beginnen (ignoriert das Enddatum/die Endzeit).',
    'DISPLAY_FIRST_WEEK'					=> 'Zeige aktuelle Woche',
    'DISPLAY_FIRST_WEEK_EXPLAIN'			=> 'Soll die aktuelle Woche auf dem Forum-Index angezeigt werden?',
    'DISPLAY_NEXT_EVENTS'					=> 'Zeige die nächsten Termine',
    'DISPLAY_NEXT_EVENTS_EXPLAIN'			=> 'Sieviele Termine sollen auf dem Index angezeigt werden? Diese Option wird ignoriert, wenn die Anzeige der aktuellen Woche aktiviert ist.',
    'DISPLAY_TRUNCATED_SUBJECT'				=> 'Betreff abkürzen',
    'DISPLAY_TRUNCATED_SUBJECT_EXPLAIN'		=> 'Lange Bezeichnungen im Betreff benötigen viel Platz im Kalender.  Wie viele Zeichen sollen im Betreff des Termins angezeigt werden? (0 eingeben, wenn die Anzahl nicht beschränkt werden soll)',
    'EDIT'									=> 'Bearbeiten',
    'EDIT_ETYPE'							=> 'Termin-Typ bearbeiten',
    'EDIT_ETYPE_EXPLAIN'					=> 'Wähle aus, wie dieser Termin-Typ angezeigt werden soll.',
    'FIRST_DAY'								=> 'Erster Tag',
    'FIRST_DAY_EXPLAIN'						=> 'Welcher Tag ist der erste Wochentag?',
    'FULL_NAME'								=> 'Name',
    'FRIDAY'								=> 'Freitag',
    'ICON_URL'								=> 'URL für Icon',
    'MANAGE_ETYPES'							=> 'Verwaltung Termin-Typen',
    'MANAGE_ETYPES_EXPLAIN'					=> 'Termin-Typen helfen den Kalender zu organisieren. Du kannst hier Termin-Typen hinzufügen, bearbeiten oder löschen.',
    'MINUS_HOUR'							=> 'Verschiebe alle Termine um eine Stunde zurück (-).',
    'MONDAY'								=> 'Montag',
    'NO_EVENT_TYPE_ERROR'					=> 'Termin-Typ nicht gefunden.',
    'PLUS_HOUR'								=> 'Verschiebe alle Termine um eine Stunde vorwärts (+).',
    'PLUS_HOUR_CONFIRM'						=> 'Sicher, daß du alle Termine um %1$s Stunde(n) verschieben willst?',
    'PLUS_HOUR_SUCCESS'						=> 'Alle Termine erfolgreich um %1$s Stunde(n) verschoben.',
    'SATURDAY'								=> 'Samstag',
    'SUNDAY'								=> 'Sonntag',
    'TIME_FORMAT'							=> 'Zeitformat',
    'TIME_FORMAT_EXPLAIN'					=> 'Versuche &quot;h:i a&quot; oder &quot;H:i&quot;',
    'THURSDAY'								=> 'Donnerstag',
    'TUESDAY'								=> 'Dienstag',
    'USER_CANNOT_MANAGE_CALENDAR'			=> 'Du hast leider keine Berechtigung, die Kalender-Einstellungen oder Termin-Typen zu verwalten.',
    'WEDNESDAY'								=> 'Mittwoch',

));

?>
