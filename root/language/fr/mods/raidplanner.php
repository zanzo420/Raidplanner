<?php
/**
 * bbdkp acp language file for raidplanner module
 * 
 * @package bbDkp
 * @copyright 2010 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
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
    '12_HOURS'								=> '12 heures',
    '24_HOURS'								=> '24 heures',
    'AUTO_POPULATE_EVENT_LIMIT'				=> 'Limite de génération',
    'AUTO_POPULATE_EVENT_LIMIT_EXPLAIN'		=> 'Combien de jours à l’avance les raids doivent être crées ?',
    'AUTO_PRUNE_EVENT_FREQUENCY'			=> 'Suppression de vieux Raids',
    'AUTO_PRUNE_EVENT_FREQUENCY_EXPLAIN'	=> 'Indiquez la fréquence de nettoyage de vieux raids (0 pour ne jamais auto-supprimmer)',
    'AUTO_PRUNE_EVENT_LIMIT'				=> 'Limite de suppression',
    'AUTO_PRUNE_EVENT_LIMIT_EXPLAIN'		=> 'Indiquez le nombre de jours qu’un raid paut rester dans le calendrier. ',
    'CLICK_PLUS_HOUR'						=> 'Bouge tous les raids d’une heure.',
    'CLICK_PLUS_HOUR_EXPLAIN'				=> ' ',
    'COLOR'									=> 'Couleur',
    'DATE_FORMAT'							=> 'Format de Date',
    'DATE_FORMAT_EXPLAIN'					=> 'Essaye &quot;d M, Y&quot;',
    'DATE_TIME_FORMAT'						=> 'Format Date et Heure',
    'DATE_TIME_FORMAT_EXPLAIN'				=> 'Défaut &quot;d H, Y h:i a&quot; or &quot;d H, Y H:i&quot;',
    'DELETE'								=> 'Supprime',
    'DISPLAY_12_OR_24_HOURS'				=> 'Formatage heure 12/24',
    'DISPLAY_12_OR_24_HOURS_EXPLAIN'		=> 'Montre les menus de temps déroulants en 12 ou 24 heures.',
    'DISPLAY_HIDDEN_GROUPS'					=> 'Montre Groupes cachés',
    'DISPLAY_HIDDEN_GROUPS_EXPLAIN'			=> 'Vous voulez inviter des groupes cachées ? Si activé, les groupes seront visibles aux administrateurs.',
    'DISPLAY_NAME'							=> 'Montrer nom évènement',
    'DISPLAY_EVENTS_ONLY_1_DAY'				=> 'Montre seulement le premier jour',
    'DISPLAY_EVENTS_ONLY_1_DAY_EXPLAIN'		=> 'Montrer les raids qui durent plus qu’une journée seulement le jour de commencement.',
    'DISPLAY_FIRST_WEEK'					=> 'Montrer semaine en cours',
    'DISPLAY_FIRST_WEEK_EXPLAIN'			=> 'Affiche la semaine en cours sur l’indexe du forum',
    'DISPLAY_NEXT_RAIDS'					=> 'Prochains Raids',
    'DISPLAY_NEXT_RAIDS_EXPLAIN'			=> 'Indique le nombre de raids à venir.',
    'DISPLAY_TRUNCATED_SUBJECT'				=> 'Raccourcir Sujet',
    'DISPLAY_TRUNCATED_SUBJECT_EXPLAIN'		=> 'Puisque le nom peut être long, indiquez le nombre maximal de caractères à afficher (0 pour ne pas raccourcir)',
    'EDIT'									=> 'Edition',
    'EDIT_ETYPE'							=> 'Edition Evènement',
    'EDIT_ETYPE_EXPLAIN'					=> 'Indique le nom de l’évènement.',
    'FIRST_DAY'								=> 'Premier jour',
    'FIRST_DAY_EXPLAIN'						=> 'Quel jour est le premier de la semaine ?',
    'FULL_NAME'								=> 'Nom complet',
    'FRIDAY'								=> 'Vendredi',
    'ICON_URL'								=> 'URL pour icône',
    'MINUS_HOUR'							=> 'Bouge tous les raids moins (-) une heure',
    'MONDAY'								=> 'Lundi',
    'NO_EVENT_TYPE_ERROR'					=> 'Evènement pas trouvé.',
    'PLUS_HOUR'								=> 'Bouge tous les raids plus (+) une heure',
    'PLUS_HOUR_CONFIRM'						=> 'ëtes-vous sûr de vouloir bouger tous vos raids de %1$s heure ? ',
    'PLUS_HOUR_SUCCESS'						=> 'Tous les raids ont été bougés de %1$s heure.',
	'ROLEICON'								=> 'Icone Rôle',
	'SATURDAY'								=> 'Samedi',
    'SUNDAY'								=> 'Dimanche',
    'TIME_FORMAT'							=> 'Format Temps',
    'TIME_FORMAT_EXPLAIN'					=> 'Exemple: &quot;h:i a&quot; or &quot;H:i&quot;',
    'THURSDAY'								=> 'Jeudi',
    'TUESDAY'								=> 'Mardi',
    'USER_CANNOT_MANAGE_CALENDAR'			=> 'Vous n’avez pas le pouvoir de modifier les paramêtres du Raidplanner.',
    'WEDNESDAY'								=> 'Mercredi',
	'USER_CANNOT_MANAGE_RAIDPLANNER'		=> 'Vous n’avez pas le pouvoir de modifier les paramêtres du Raidplanner.', 
	'RPADVANCEDOPTIONS'						=> 'Options avançees', 
	'RPSETTINGS'							=> 'Options', 
	'RPSETTINGS_UPDATED'					=> 'Paramètres Raidplanner modifiés avec succès',
	'ADVRPSETTINGS_UPDATED'					=> 'Paramètres Raidplanner avancées modifiés avec succès', 
	'RP_UPD_MOD'							=> 'Raidplanner mis à jour à %s', 
	'RP_UNINSTALL_MOD'						=>  'Raidplanner désinstallé', 
	
	//confirms
	'ROLE_DELETE_SUCCESS'		=> 'Le rôle %s a été supprimé.', 
	'CONFIRM_DELETE_ROLE'		=> 'Veuillez confirmer la suppression du rôle %s.', 
    'DELETE_RAIDPLAN_CONFIRM'	=> 'Veuillez confirmer la suppression de ce rendez-vous.',
	'CONFIRM_ADDRAID'			=> 'Veuillez confirmer ce rendez-vous.',
	'CONFIRM_UPDATERAID'		=> 'Veuillez confirmer la mise à jour de ce rendez-vous.',
		
	'CHOOSEPROFILE'				=> 'Choisir Rôle', 
	'RAIDROLES'					=> 'Rôles', 
	'RAIDROLE'					=> 'Rôle', 

	'ALL_DAY'				 	=> 'Rendez-vous journée',
	'ALLOW_GUESTS'				=> 'Acceptez des compagnons aux invités. ',
	'ALLOW_GUESTS_ON'			=> 'Les participants peuvent amener des compagnons.',
	'ALLOW_GUESTS_OFF'			=> 'Les participants ne peuvent pas être accompagnés.',
	'AM'						=> 'AM',
	'AVAILABLE'					=> 'Disponible',
	'ATTENDANCE'        		=> 'Présence', 
	'CALENDAR_POST_RAIDPLAN'	=> 'Créer nouveau Raid',
	'CALENDAR_EDIT_RAIDPLAN'	=> 'Edition Raid',
	'CALENDAR_TITLE'			=> 'Calendrier',
	'RAIDPLANNER'				=> 'Raid Planner',
	'NEWRAID'					=> 'Nouveau Raid',

	'CALENDAR_NUMBER_ATTEND'=> 'Nombre de participants',
	'CALENDAR_NUMBER_ATTEND_EXPLAIN'=> '(saisir 1 si vous êtes seul)',
	'CALENDAR_RESPOND'		=> 'Veuillez vous enregistre içi',
	'CALENDAR_WILL_ATTEND'	=> 'Signer comme',

	'CANNOTSIGNUP'			=> 'Inscription impossible car vous n’avez pas de caractères liées à votre compte.',
	
	'RAIDCHARACTER'			=> 'Caractère', 
	'COL_HEADCOUNT'			=> 'Nombre',
	'COL_WILL_ATTEND'		=> 'Est attendu?',
	'COMMENTS'				=> 'Commentaires',
	'CONFIRMED'				=> 'Confirmé',

	'DAY'					=> 'Jour',
	'DAY_OF'				=> 'Jour de',
	'DECLINE'				=> 'Decliné', 
	'DELETE_ALL_EVENTS'		=> 'Supprime tous les occurances de ce raid.',
	'DETAILS'				=> 'Details',
	'DELETE_RAIDPLAN'			=> 'Supprime raid',

	'EDIT'					=> 'Edition',
	'EDIT_ALL_EVENTS'		=> 'Edition de tous les occurences de ce raid.',
	
	'EMPTY_EVENT_MESSAGE'		=> 'Veuillez entrer un Message.',
	'EMPTY_EVENT_SUBJECT'		=> 'Veuillez entrer un Sujet.',
	'EMPTY_EVENT_MESSAGE_RAIDS'	=> 'un message est obligatoire.',
	'EMPTY_EVENT_SUBJECT_RAIDS'	=> 'un Sujet est obligatoire',
	
	'EDITRAIDROLES'				=> 'Edition des rôles' ,

	'END_DATE'					=> 'Date de fin',
	'END_RECURRING_EVENT_DATE'	=> 'Derniere occurence:',
	'END_TIME'					=> 'Heure de fin',
	'EVENT_ACCESS_LEVEL'			=> 'Qui peut voir ce Raid ?',
	'EVENT_ACCESS_LEVEL_GROUP'		=> 'Groupe',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> 'Personnel',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> 'Public',
	'EVENT_CREATED_BY'		=> 'Raid Posté par',
	'EVENT_DELETED'				=> 'Ce raid a été supprimé.',
	'EVENT_EDITED'				=> 'Ce Raid a été édité avec succes.',
	'EVENT_GROUP'				=> 'Quel groupe peut voir ce raid ?',
	'EVENT_STORED'				=> 'Ce raid a été créé avec succès.',
	'EVENT_TYPE'				=> 'Evenement',
	'EVERYONE'				=> 'Tout le monde',

	'FROM_TIME'				=> 'De',
	'FREQUENCEY_LESS_THAN_1'	=> 'Fréquence de Raids Récurrents doit être >= 1',
	'FROZEN_TIME'			=> 'Souscription bloqué après x heures  (en fonction des permissions)',
	'FROZEN_EXPLAIN'		=> ' ',
	'EXPIRE_TIME'			=> 'Edition rdv blocqué',
	'EXPIRE_EXPLAIN'		=> 'Après x heures le rdv n’est plus éditable (en fonction des permissions) ',
 
	'HOW_MANY_PEOPLE'		=> 'Nombre de participants',
	'HOUR'					=> 'Heure',
	'INVALID_RAIDPLAN'			=> 'Ce Raid n’existe pas',
	'INVITE_INFO'			=> 'Invité',
	'INVITE_TIME'			=> 'Temps d’invitation',

	'MESSAGE_BODY_EXPLAIN'		=> 'Saisissez votre message, il ne peut excéder <strong>%d</strong> caractères.',

	'MAYBE'					=> 'Peut-être',
	'AVAILABLE'				=> 'Disponible', 
	'MINUTE'				=> 'Minute', 
	'MONTH'					=> 'Mois',
	'MONTH_OF'				=> 'Mois de ',
	'MY_EVENTS'				=> 'Mes Raids',

	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'LOGIN_EXPLAIN_POST_RAIDPLAN'	=> 'Vous devez vous conecter afin de pouvoir ajouter/éditer/supprimer des rendez-vous.',

	'NEGATIVE_LENGTH_RAIDPLAN'		=> 'Le Rendez-vous ne peut commencer après sa termination.',
	'NEVER'						=> 'Jamais',
	'NEW_RAIDPLAN'				=> 'Nouveau Raid',
	'NEW_EVENT'					=> 'Nouveau Rendez-vous',
	'NO_RAIDPLAN'				=> 'Ce Rendez-vous n’existe pas.',
	'NO_EVENT_TYPES'			=> 'L’administrateur n’a pas ajouté de types d’évènements.',
	'NO_GROUP_SELECTED'			=> 'Pas de groupes selectionnés.',
	'NO_POST_EVENT_MODE'		=> 'No post mode specified.',
	'NO_EVENTS_TODAY'			=> 'Pas de rendez-vous ce jour.',
	'NO_RAIDS_SCHEDULED'		=> 'Pas de Raids ce jour.',
	'NOTAVAILABLE'				=> 'Pas disponible', 

	'OCCURS_EVERY'			=> 'Revient chaque',
	
	'PAGE_TITLE'			=> 'Calendrier',
	'PM'						=> 'PM',
	'PRIVATE_RAIDPLAN'			=> 'Ce Raid est privé.  Vous devez être invité pour le voir.',

	'RAIDROLES'				=> 'Rôles de raid' ,
	'RAIDROLE'				=> 'Rôle', 
	'RAIDINFO'				=> 'Info de Raid' ,
	'RAIDWHEN'				=> 'Quand ?' ,
	'RAIDREPEAT'			=> 'Répéter ?' ,
	'RAIDLEADER'			=> 'Leader' ,

	'RECURRING_RAIDPLAN'			=> 'Raid en Répétition',
	'RECURRING_EVENT_TYPE'			=> 'Type de Répétition: ',
	'RECURRING_EVENT_TYPE_EXPLAIN'	=> 'type de fréquence indiqué par lettre de début: A - Annual, M - Monthly, W - Weekly, D - Daily',
	'RECURRING_EVENT_FREQ'			=> 'Raid frequency:',
	'RECURRING_EVENT_FREQ_EXPLAIN'	=> 'Cette valeur represente [Y] ci-dessus',
	
	'RECURRING_EVENT_CASE_1'    => 'A: [Xième] Jour de [Mois] chaque [Y] année',
	'RECURRING_EVENT_CASE_2'    => 'A: [Xième] [jour de semaine] de [nom de mois] chaque [Y] Année',
	'RECURRING_EVENT_CASE_3'    => 'M: [Xième] Jour du mois tous les [Y] mois',
	'RECURRING_EVENT_CASE_4'    => 'M: [Xième] [jour de semaine] du mois tous les [Y] Mois',
	'RECURRING_EVENT_CASE_5'    => 'W: [jour de semaine] tous les [Y] semaines',
	'RECURRING_EVENT_CASE_6'    => 'D: Tous les [Y] Jours',
	
	'RETURN_CALENDAR'			=> '%s Retour au calendrier %s',

	'RAIDPROFILE1'				=> '10-man', 
	'RAIDPROFILE2'				=> '25-man', 

	'RP_SHOW_WELCOME'			=> 'Montre message d’acceuil',
	'RP_WELCOME'				=> 'Message d’acceuil',
	'RP_WELCOME_EXPLAIN'		=> 'Message au dessus du calendrier. bbcode supporté. ', 
	'RP_WELCOME_DEFAULT'		=> '[b]Bienvenu au Calendrier[/b]! ', 
	'SHOW_PREV'					=> 'Précédent', 
	'SHOW_NEXT'					=> 'Suivant', 
	'SIGNUPS'					=> 'Inscriptions', 
	'START_DATE'				=> 'Date Début',
	'START_TIME'				=> 'Temps Début',
	'RAID_DATE'					=> 'Date du Raid',
	'SIGN_UP'					=> 's’inscrire',
	'RAID_INVITE_TIME'			=> 'Temps d’invitation',
	'RAID_INVITE_TIME_DEFAULT'	=> 'Temps d’invitation pr défaut',
	'RAID_START_TIME'			=> 'Temps de début',
	'RAID_END_TIME'				=> 'Temps de Fin',
	'START'						=> 'Début', 
	'INVITE'					=> 'Invitation', 
	'DEFAULT_RAID_START_TIME'   => 'Temps de début du Raid',
	'DEFAULT_RAID_END_TIME'   	=> 'Temps de fin du Raid',
	'TO_TIME'					=> 'Vers',
	'TOPSIGNUPS'				=> 'Top Participants',
	'TENTATIVE'					=> 'Tentatif',
	'TIME_ZONE'					=> 'Temps exprimés en ', 
	'TRACK_SIGNUPS'				=> 'Inscription obligatoire',
	'TRACK_SIGNUPS_ON'			=> 'Inscription activée.',
	'TRACK_SIGNUPS_OFF'			=> 'Inscription désactivée.',

	'UPCOMING_RAIDS'			=> 'Raids à venir',
	'USER_CANNOT_VIEW_RAIDPLAN'=> 'Vous ne pouvez pas voir ce raid.',
	'USER_CANNOT_DELETE_RAIDPLAN'	=> 'Vous ne pouvez pas supprimer des raids.',
	'USER_CANNOT_EDIT_RAIDPLAN'	=> 'Vous ne pouvez pas éditer des raids.',
	'USER_CANNOT_POST_RAIDPLAN'	=> 'Vous ne pouvez pas créer des raids.',
	'USER_ALREADY_SIGNED_UP'	=> '%s est déjà incrit dans ce raid.',

	'VIEW_RAIDPLAN'				=> '%s Voir vos Raids %s',
	'WEEK'						=> 'Semaine',
	'CLOCK'						=> 'Heure',

	'WATCH_CALENDAR_TURN_ON'	=> 'Voir le calendrier',
	'WATCH_CALENDAR_TURN_OFF'	=> 'Stop watching the calendar',
	'WATCH_EVENT_TURN_ON'		=> 'Voir ce Raid',
	'WATCH_EVENT_TURN_OFF'		=> 'Ne pas voir ce raid',

	'WEEK'						=> 'Semaine',
	'WEEK_OF'					=> 'Semaine de ',
	
	'ZERO_LENGTH_RAIDPLAN'			=> 'Ce raid ne peut pas terminer avant son commencement.',
	'ERROR_RAIDSTARTBEFORENOW'			=> 'On ne peut pas ajouter des raids dans le passé.',
	'ERROR_NOCANVAS'			=> 'Canvas/HTML5 n’est pas supporté par votre navigateur', 

	'ZEROTH_FROM'				=> '0ième de ',
	'numbertext'			=> array(
		'0'		=> '0ième',
		'1'		=> '1ième',
		'2'		=> '2ième',
		'3'		=> '3ième',
		'4'		=> '4ième',
		'5'		=> '5ième',
		'6'		=> '6ième',
		'7'		=> '7ième',
		'8'		=> '8ième',
		'9'		=> '9ième',
		'10'	=> '10ième',
		'11'	=> '11ième',
		'12'	=> '12ième',
		'13'	=> '13ième',
		'14'	=> '14ième',
		'15'	=> '15ième',
		'16'	=> '16ième',
		'17'	=> '17ième',
		'18'	=> '18ième',
		'19'	=> '19ième',
		'20'	=> '20ième',
		'21'	=> '21ième',
		'22'	=> '22ième',
		'23'	=> '23ième',
		'24'	=> '24ième',
		'25'	=> '25ième',
		'26'	=> '26ième',
		'27'	=> '27ième',
		'28'	=> '28ième',
		'29'	=> '29ième',
		'30'	=> '30ième',
		'31'	=> '31ième',
		'n'		=> 'n-ième' ),

));

?>