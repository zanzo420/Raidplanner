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

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(

	//settings 
    '12_HOURS'								=> '12 Stunden',
    '24_HOURS'								=> '24 Stunden',
    'AUTO_POPULATE_EVENT_FREQUENCY'			=> 'Auto füllen der wiederkehrende Raids',
    'AUTO_POPULATE_EVENT_FREQUENCY_EXPLAIN'	=> 'Wie oft (in Tagen) sollen wiederkehrenden Raids in dem Kalender gefüllt werden? Hinweis Wenn du 0 wählst, werden wiederkehrenden Raids nie in den Kalender aufgenommen',
    'AUTO_POPULATE_EVENT_LIMIT'				=> 'Auto füllen Grenzwerte',
    'AUTO_POPULATE_EVENT_LIMIT_EXPLAIN'		=> 'Wie viele Tage im Voraus sollen die wiederkehrende Raids gefüllt werden? Mit anderen Worten, willst du wiederkehrenden Raids nur für 30, 45 oder mehr Tage vor dem Raid in dem Kalender sehen?',
    'AUTO_PRUNE_EVENT_FREQUENCY'			=> 'Auto bereinigen vergangener Raids',
    'AUTO_PRUNE_EVENT_FREQUENCY_EXPLAIN'	=> 'Wie oft (in Tagen) sollen vergangenen Raids aus dem Kalender bereinigt werden? Hinweis Wenn Sie 0 wählen, werden vergangene Raids niemals automatisch bereinigt, sie müssen von Hand gelöscht werden.',
    'AUTO_PRUNE_EVENT_LIMIT'				=> 'Auto bereinigen Grenzwerte',
    'AUTO_PRUNE_EVENT_LIMIT_EXPLAIN'		=> 'Wie viele Tage nach einem Raid möchtest du ihn zum nächsten Auto bereinigen hinzufügen? Mit anderen Worten, willst du, dass alle Raids in dem Kalender für 0, 30, oder 45 Tage nach dem Raid bleiben?',
    'CLICK_PLUS_HOUR'						=> 'Verschiebe alle Raids um eine Stunde.',
    'CLICK_PLUS_HOUR_EXPLAIN'				=> 'Die Möglichkeit, alle Raids im Kalender 1 Stunde + / - zu verschieben, hilft wenn du Die Forum Sommerzeit-Einstellung zurücksetzt. Beachte, wenn auf die Links zu den Raids geklickt werden, gehen die Änderungen, verloren. Bitte sende das Formular, um die Arbeit zu sichern, bevor du die Raids um+ / - 1 Stunde verschiebst.',
    'COLOR'									=> 'Farbe',
    'DATE_FORMAT'							=> 'Datumsformat',
    'DATE_FORMAT_EXPLAIN'					=> 'Versuche &quot;d M, Y&quot;',
    'DATE_TIME_FORMAT'						=> 'Datums-und Zeitformat',
    'DATE_TIME_FORMAT_EXPLAIN'				=> 'versuche &quot;d M, Y h:i a&quot; oder &quot;d M, Y H:i&quot;',
    'DELETE'								=> 'Löschen',
    'DISPLAY_12_OR_24_HOURS'				=> 'Zeige Zeit Format',
    'DISPLAY_12_OR_24_HOURS_EXPLAIN'		=> 'Sollene die Zeiten im 12-Stunden-Modus mit AM / PM oder 24-Stunden-Modus angezeigt werden? Dies hat keine Auswirkungen auf das ​​Format der Zeiten für den Benutzer - das wird in ihrem Profil eingestellt. Dies hat nur Auswirkungen auf das Pulldown-Menü für die Auswahl der Zeit beim Erstellen / Bearbeiten von Raids und die zeitgesteuerte Überschriften auf der Tage-Kalender Ansicht.',
    'DISPLAY_HIDDEN_GROUPS'					=> 'Zeige versteckte Gruppen',
    'DISPLAY_HIDDEN_GROUPS_EXPLAIN'			=> 'Sollen Benutzern die Möglichkeit haben Mitglieder von versteckten Gruppen zu sehen und einzuladen? Wenn diese Einstellung deaktiviert ist, wird nur Gruppe Administratoren in der Lage sein die Mitglieder der versteckten Gruppe zu sehen und einzuladen .',
    'DISPLAY_NAME'							=> 'Zeige Ereignissname im Kalender',
    'DISPLAY_EVENTS_ONLY_1_DAY'				=> 'Zeige Raids einen Tag',
    'DISPLAY_EVENTS_ONLY_1_DAY_EXPLAIN'		=> 'Zeige Raids nur am Tag an dem sie Beginnen (ignoriere deren End-Datum/-Zeit).',
    'DISPLAY_FIRST_WEEK'					=> 'Zeige aktuelle Woche',
    'DISPLAY_FIRST_WEEK_EXPLAIN'			=> 'Soll die aktuelle Woche auf der Foren-Übersicht angezeigt werden?',
    'DISPLAY_NEXT_RAIDS'					=> 'Zeige bevorstehenden Raids',
    'DISPLAY_NEXT_RAIDS_EXPLAIN'			=> 'Gebe die Anzahl der anzuzeigenden bevorstehenden Raids an.',
    'DISPLAY_TRUNCATED_SUBJECT'				=> 'Kürze Betreff',
    'DISPLAY_TRUNCATED_SUBJECT_EXPLAIN'		=> 'Lange Namen in der Betreffzeile können auf dem Kalender eine Menge Platz gebrauchen. Wie viele Zeichen sollen im Betreff auf dem Kalender angezeigt werden? (0 eingeben, wenn der Titel nicht gekürzt werden soll)',
    'EDIT'									=> 'Bearbeiten',
    'EDIT_ETYPE'							=> 'Bearbeite Ereignis',
    'EDIT_ETYPE_EXPLAIN'					=> 'Gebe an wie der Raid-Typ angezeigt werden soll.',
    'FIRST_DAY'								=> 'Erster Tag',
    'FIRST_DAY_EXPLAIN'						=> 'Welcher Tag soll als erster der Woche angezeigt werden?',
    'FULL_NAME'								=> 'Voller Name',
    'FRIDAY'								=> 'Freitag',
    'ICON_URL'								=> 'URL für icon',
    'MANAGE_ETYPES'							=> 'Verwalte Ereignisse',
    'MANAGE_ETYPES_EXPLAIN'					=> 'Raid-Typen werden genutzt um den Kalender zu organisieren. Du kannst hier Raid-Typen hinzufügen, bearbeiten, löschen oder sortieren.',
    'MINUS_HOUR'							=> 'Verschiebe alle Raids Minus (-) eine Stunde',
    'MONDAY'								=> 'Montag',
    'NO_EVENT_TYPE_ERROR'					=> 'Konnte angegebene Raid-Typ nicht finden.',
    'PLUS_HOUR'								=> 'Verschiebe alle Raids Plus (+) eine Stunde',
    'PLUS_HOUR_CONFIRM'						=> 'Bist du sicher, dass alle Raids um %1$s Stunden verschoben werden sollen?',
    'PLUS_HOUR_SUCCESS'						=> 'Erfolgreich alle Raids um %1$s Stunden verschoben.',
	'ROLEICON'								=> 'Rollen Symbol',
	'SATURDAY'								=> 'Samstag',
    'SUNDAY'								=> 'Sonntag',
    'TIME_FORMAT'							=> 'Zeit-Format',
    'TIME_FORMAT_EXPLAIN'					=> 'Versuche &quot;h:i a&quot; oder &quot;H:i&quot;',
    'THURSDAY'								=> 'Donnerstag',
    'TUESDAY'								=> 'Dienstag',
    'USER_CANNOT_MANAGE_CALENDAR'			=> 'du hast keine Berechtigung, um die Kalender-Einstellungen oder Raid-Typen zu verwalten.',
    'WEDNESDAY'								=> 'Mittwoch',
	'USER_CANNOT_MANAGE_RAIDPLANNER'		=> 'Du bist nicht berechtigt, um die Raidplaner Einstellungen zu verwalten', 
	'RPADVANCEDOPTIONS'						=> 'Erweiterte Optionen', 
	'RPSETTINGS'							=> 'Einstellungen', 
	'RPSETTINGS_UPDATED'					=> 'Raidplaner Einstellungen erfolgreich aktualisiert',
	'ADVRPSETTINGS_UPDATED'					=> 'Erweiterte Raidplaner Einstellungen erfolgreich aktualisiert', 
	'RP_UPD_MOD'							=> 'Raidplanner aktualisiert auf %s', 
	'RP_UNINSTALL_MOD'						=> 'Raidplanner deinstalliert', 

	
	//confirms
	'ROLE_DELETE_SUCCESS'		=> 'Die Rolle %s wurde gelöscht.', 
	'CONFIRM_DELETE_ROLE'		=> 'Bitte bestätige das du die Raid-Rolle %s löschen möchtest. Wenn es geplante Raids mit dieser Rolle gibt, kann sie nicht gelöscht werden.', 
    'DELETE_RAIDPLAN_CONFIRM'	=> 'Bitte bestätige das du diese Raidplan löschen möchtest.',
	'CONFIRM_ADDRAID'			=> 'Bitte bestätige das du diesen Raid hinzufügen möchtest.',
	'CONFIRM_UPDATERAID'		=> 'Bitte bestätige das du diesen Raid aktualisieren möchtest.',
		
	'CHOOSEPROFILE'				=> 'Wähle Raidprofil', 
	'RAIDROLES'					=> 'Raid Rollen', 
	'RAIDROLE'					=> 'Rolle', 

	'ALL_DAY'				 	=> 'Ganztägiger Raid',
	'ALLOW_GUESTS'				=> 'Den Mitgliedern erlauben, Gäste mitzubringen',
	'ALLOW_GUESTS_ON'			=> 'Mitglieder können Gäste zu diesem Raid mitbringen.',
	'ALLOW_GUESTS_OFF'			=> 'Mitglieder können KEINE Gäste zu diesem Raid mitbringen.',
	'AM'						=> 'AM',
	'AVAILABLE'					=> 'Verfügbar',
	'ATTENDANCE'        		=> 'Teilnahme', 
	'CALENDAR_POST_RAIDPLAN'	=> 'Erstelle neuen Raid',
	'CALENDAR_EDIT_RAIDPLAN'	=> 'Bearbeite Raid',
	'CALENDAR_TITLE'			=> 'Planer',
	'RAIDPLANNER'				=> 'Raid Planer',
	'NEWRAID'					=> 'Neuer Raid',

	'CALENDAR_NUMBER_ATTEND'=> 'Die Zahl der Personen, die du zu diesem Raid mitbringst',
	'CALENDAR_NUMBER_ATTEND_EXPLAIN'=> '(Wähle 1 für dich)',
	'CALENDAR_RESPOND'		=> 'Bitte hier Registrieren',
	'CALENDAR_WILL_ATTEND'	=> 'Anmelden als',

	'CANNOTSIGNUP'			=> 'Du kannst dich nich anmelden, weil du kein DKP-Charakter mit deinem Account verbunden hast.',
	'CLOCK'					=> 'Zeit',	
	'RAIDCHARACTER'			=> 'Raidcharakter', 
	'COL_HEADCOUNT'			=> 'Anzahl',
	'COL_WILL_ATTEND'		=> 'Wird Teilnehmen?',
	'COMMENTS'				=> 'Kommentare',
	'CONFIRMED'				=> 'Bestätigt',

	'DAY'					=> 'Tag',
	'DAY_OF'				=> 'Tag des ', //??
	'DECLINE'				=> 'Ablehnen', 
	'DELETE_ALL_EVENTS'		=> 'Lösche alle Ereignisse dieses Raids.',
	'DETAILS'				=> 'Details',
	'DELETE_RAIDPLAN'		=> 'Lösche raid',

	'EDIT'					=> 'Bearbeiten',
	'EDIT_ALL_EVENTS'		=> 'Bearbeite alle Ereignisse dieses Raids.',
	
	'EMPTY_EVENT_MESSAGE'		=> 'Du musst eine Nachricht angeben.',
	'EMPTY_EVENT_SUBJECT'		=> 'Du musst einen Betreff angeben.',
	'EMPTY_EVENT_MESSAGE_RAIDS'	=> 'Du musst eine Nachricht für diesen Raid angeben.',
	'EMPTY_EVENT_SUBJECT_RAIDS'	=> 'Du musst einen Betreff für diesen Raid angeben.',
	
	'EDITRAIDROLES'				=> 'Bearbeite Raid-Rollen' ,

	'END_DATE'					=> 'End-Datum',
	'END_RECURRING_EVENT_DATE'	=> 'Letzte Ereignisse:',
	'END_TIME'					=> 'End-Zeit',
	'EVENT_ACCESS_LEVEL'			=> 'Wer kann diesen Raid sehen?',
	'EVENT_ACCESS_LEVEL_GROUP'		=> 'Gruppe',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> 'Privat',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> 'Öffentlich',
	'EVENT_CREATED_BY'		=> 'Raid Erstellt von',
	'EVENT_DELETED'				=> 'Dieser Raid wurde erfolgreich gelöscht.',
	'EVENT_EDITED'				=> 'Dieser Raid wurde erfolgreich bearbeitet.',
	'EVENT_GROUP'				=> 'Welche Gruppe(n) kann(können) diesen Raid sehen?',
	'EVENT_STORED'				=> 'Dieser Raid wurde erfolgreich erstellt.',
	'EVENT_TYPE'				=> 'Ereignis',
	'EVERYONE'				=> 'Jeder',

	'FROM_TIME'				=> 'Von',
	'FREQUENCEY_LESS_THAN_1'	=> 'Wiederkehrende Raids müssen eine Häufigkeit von mehr als oder gleich 1 haben',
	'FROZEN_TIME'			=> 'Friere Raid Zeit ein.',
	'FROZEN_EXPLAIN'		=> 'Friert den Raid X Stunden vor der Start Zeit ein. Die Zugriffsrechte unterscheiden sich pro Rolle',

	'EXPIRE_TIME'			=> 'Verfall Raid Zeit',
	'EXPIRE_EXPLAIN'		=> 'Raid verfällt x Stunden nach der Start Zeit. Die Zugriffsrechte unterscheiden sich pro Rolle. ',
 
	'HOW_MANY_PEOPLE'		=> 'Schnellanzahl',
	'HOUR'					=> 'Stunde',
	'INVALID_RAIDPLAN'			=> 'Der Raid, den du versuchst anzuschauen, existiert nicht.',
	'INVITE_INFO'			=> 'Eingeladen',
	'INVITE_TIME'			=> 'Einlade-Zeit',

	'MESSAGE_BODY_EXPLAIN'		=> 'Geben deine Nachricht ein. Sie kann nicht mehr als <strong>%d</strong> Zeichen beinhalten.',

	'MAYBE'					=> 'Vielleicht',
	'AVAILABLE'				=> 'Verfügbar', 
	'MINUTE'				=> 'Minute', 
	'MONTH'					=> 'Monat',
	'MONTH_OF'				=> 'Monat von ',
	'MY_EVENTS'				=> 'Meine Raids',
	'LOCKED'				=> 'Geschlossen', 
	'FROZEN'				=> 'Raid ist Eingefroren',
	'NOCHAR'				=> 'Keine Charaktere', 
	'SIGNED_UP'				=> 'Registriert', 
	'SIGNED_OFF'			=> 'Abgemeldet', 

	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'LOGIN_EXPLAIN_POST_RAIDPLAN'	=> 'Du musst dich anmelden, um Raids hinzuzufügen, zu bearbeiten oder zu löschen.',

	'NEGATIVE_LENGTH_RAIDPLAN'		=> 'Der Raid kann nicht enden bevor er gestartet hat.',
	'NEVER'						=> 'Niemals',
	'NEW_RAIDPLAN'				=> 'Neuer Raidplan',
	'NEW_EVENT'					=> 'Neuer Raid',
	'NO_RAIDPLAN'				=> 'Der angeforderte Raid existiert nicht..',
	'NO_EVENT_TYPES'			=> 'Der Administrator hat keine Raid Typen für diesen Kalender angelegt. Die Raid-Erstellung wurde deaktiviert.',
	'NO_GROUP_SELECTED'			=> 'Es wurden keine Gruppen für diese Gruppe Raid ausgewählt.',
	'NO_POST_EVENT_MODE'		=> 'Kein Beitrag-Modus angegeben.',
	'NO_EVENTS_TODAY'			=> 'Es sind keine Raids an diesem Tag geplant.',
	'NO_RAIDS_SCHEDULED'		=> 'Keine Raids geplant.',
	'NOTAVAILABLE'				=> 'Nicht verfügbar', 

	'OCCURS_EVERY'			=> 'Erscheint jeden',
	
	'PAGE_TITLE'			=> 'Kalender',
	'PM'						=> 'PM',
	'PRIVATE_RAIDPLAN'			=> 'Dieser raid ist Privat. Du musst eingeladen und angemeldet sein um diesen Raid zu sehen..',

	'RAIDROLES'				=> 'Raid Rollen' ,
	'RAIDROLE'				=> 'Raid Rolle' ,
	'RAIDINFO'				=> 'Raid Info' ,
	'RAIDWHEN'				=> 'Wann?' ,
	'RAIDREPEAT'			=> 'Wiederholt sich?' ,
	'RAIDLEADER'			=> 'Raid Leiter' ,

	'RECURRING_RAIDPLAN'			=> 'Wiederkehrende Raid',
	'RECURRING_EVENT_TYPE'			=> 'Wiederholungstyp: ',
	'RECURRING_EVENT_TYPE_EXPLAIN'	=> 'Tipp Entscheidungen beginnen mit einem Buchstaben um ihre Häufigkeit anzugeben: A - Jährliche, M - Monatliche, W - Wöchentliche, D - Täglich',
	'RECURRING_EVENT_FREQ'			=> 'Raid-Häufigkeit:',
	'RECURRING_EVENT_FREQ_EXPLAIN'	=> 'Dieser Wert repräsentiert [Y] in der Wahl oberhalb',
	
	'RECURRING_EVENT_CASE_1'    => 'A: [Xter] Tag von [Monatsname] jedes [Y] Jahr/e',
	'RECURRING_EVENT_CASE_2'    => 'A: [Xter] [Wochentagsname] von [Monatsname] every [Y] Year(s)',
	'RECURRING_EVENT_CASE_3'    => 'M: [Xter] Tag des Monats alle [Y] Monate',
	'RECURRING_EVENT_CASE_4'    => 'M: [Xter] [Wochentagsname] des Monats alle [Y] Monate',
	'RECURRING_EVENT_CASE_5'    => 'W: [Wochentagsname] jede [Y] Woche',
	'RECURRING_EVENT_CASE_6'    => 'D: Jeden [Y] Tag',
	
	'RETURN_CALENDAR'			=> '%sZurück zum Kalender%s',

	'RAIDPROFILE1'				=> '10-Mann', 
	'RAIDPROFILE2'				=> '25-Mann', 

	'RP_SHOW_WELCOME'			=> 'Zeige Willkommensnachricht',
	'RP_WELCOME'				=> 'Willkommensnachricht',
	'RP_WELCOME_EXPLAIN'		=> 'Nachricht wird über dem Planer angezeigt. Unterstützt BBCodes. ', 
	'RP_WELCOME_DEFAULT'		=> '[b]Willkommen in unserem Raid Planer[/b]! Alle Raids werden hier geplant. Du benötigst keinen neuen Account, solange du im Forum angemeldet bist.', 
	'SHOW_PREV'					=> 'Zeige vorherige', 
	'SHOW_NEXT'					=> 'Zeige nächste', 
	'SIGNUPS'					=> 'Anmeldungen', 
	'START_DATE'				=> 'Startdatum',
	'START_TIME'				=> 'Startzeit',
	'RAID_DATE'					=> 'Raid Datum',
	'SIGN_UP'					=> 'Registrieren',
	'RAID_INVITE_TIME'			=> 'Lade-Zeit',
	'RAID_INVITE_TIME_DEFAULT'	=> 'Standard Raid Einladungs Zeit',
	'RAID_START_TIME'			=> 'Startzeit',
	'RAID_END_TIME'				=> 'Endzeit',
	'START'						=> 'Start', 
	'INVITE'					=> 'Einladen', 
	'DEFAULT_RAID_START_TIME'   => 'Standard Raid-Startzeit',
	'DEFAULT_RAID_END_TIME'   	=> 'Standard Raid Endzeit',
	'TO_TIME'					=> 'Zu',
	'TOPSIGNUPS'				=> 'Top Anmeldungen',
	'TENTATIVE'					=> 'Vorläufig',
	'TIME_ZONE'					=> 'Alle Zeiten sind ', 
	'TRACK_SIGNUPS'				=> 'Verfolge Teilnahme',
	'TRACK_SIGNUPS_EXPLAIN'		=> '',
	'TRACK_SIGNUPS_ON'			=> 'Die Teilnahme Beobachtung ist aktiviert.',
	'TRACK_SIGNUPS_OFF'			=> 'Die Teilnahme Beobachtung ist deaktiviert.',

	'UPCOMING_RAIDS'			=> 'Bevorstehende Raids',
	'USER_CANNOT_VIEW_RAIDPLAN' => 'Du hast keine Berechtigung, diesen Raid zu sehen.',
	'USER_CANNOT_DELETE_RAIDPLAN'	=> 'Du hast keine Berechtigung, diesen Raid zu löschen.',
	'USER_CANNOT_EDIT_RAIDPLAN'	=> 'Du hast keine Berechtigung, diesen Raid zu bearbeiten.',
	'USER_CANNOT_POST_RAIDPLAN'	=> 'Du hast keine Berechtigung, diesen Raid zu erstellen.',
	'USER_ALREADY_SIGNED_UP'	=> '%s ist bereits zu diesem Raid angemeldet.',

	'VIEW_RAIDPLAN'				=> '%sZeige den erstellten Raid%s',
	'WEEK'						=> 'Woche',

	'WATCH_CALENDAR_TURN_ON'	=> 'Beobachte den Kalender',
	'WATCH_CALENDAR_TURN_OFF'	=> 'Beobachte nicht den Kalender',
	'WATCH_EVENT_TURN_ON'		=> 'Beobachte diesen Raid',
	'WATCH_EVENT_TURN_OFF'		=> 'Beobachte nicht diesen Raid',

	'WEEK'						=> 'Woche',
	'WEEK_OF'					=> 'Woche vom ',
	
	'ZERO_LENGTH_RAIDPLAN'		=> 'Der Raid kann nicht zur gleichen Zeit Enden und Beginnen.',
	'ERROR_RAIDSTARTBEFORENOW'	=> 'Kann keine Raids in der Vergangenheit hinzufügen.',
	'ERROR_NOCANVAS'			=> 'Ihr Browser unterstützt leider keine Canvas/HTML5.', 

	'ZEROTH_FROM'				=> '0. von ',
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