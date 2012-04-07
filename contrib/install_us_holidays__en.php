<?php
/**
*
* @author alightner alightner@hotmail.com
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2008 alightner
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* After successfully installing the calendar mod, you can install US holidays (in english)
* with this file.  Simply upload this file in your root forum directory, and navigate to
* it in your browser window.  Note you must be logged into the forum as an admin when
* accessing this page.
*
* You should also check your "Calendar Settings" page in the ACP before
* installing the holidays.  If your "Auto Populate Recurring Events" setting
* is 0, recurring events will not work right in your calendar, because the cron job
* used to populate event occurrences will never run.  Please set this to 1 at a
* minimum.  Also the "Auto Populate Limits" setting determines how far into the
* future you want to create recurring events when the cron job runs.  If you set
* this value to 30 you will never see recurring events in the calendar unless they
* are a month or less away.  If you want to see all of the holidays when you
* first install them, you should set this value to 365.
*
* Do not forget to delete the file when you are finished installing
* the holidays so you do not accidentally install them twice.
*/

/**
* @ignore
*/
define('IN_PHPBB', true); // we tell the page that it is going to be using phpBB, this is important.
$phpbb_root_path = './'; // See phpbb_root_path documentation
$phpEx = substr(strrchr(__FILE__, '.'), 1); // Set the File extension for page-wide usage.
include($phpbb_root_path . 'common.' . $phpEx); // include the common.php file, this is important, especially for database connects.
include($phpbb_root_path . 'includes/functions_calendar.' . $phpEx); // contains the functions that "do the work".

// Start session management -- This will begin the session for the user browsing this page.
$user->session_begin();
$auth->acl($user->data);

// Language file (see documentation related to language files)
$user->setup('calendar');

// If users such as bots don't have permission to view any events
// you don't want them wasting time in the calendar...
// Is the user able to view ANY events?
if ( !$auth->acl_get('a_') )
{
	trigger_error( 'NO_AUTH_OPERATION' );
}

$sql = 'SELECT COUNT(etype_id) as num_etypes
		FROM ' . CALENDAR_EVENT_TYPES_TABLE;
$result = $db->sql_query($sql);
$num_etypes = $db->sql_fetchfield('num_etypes');
$db->sql_freeresult($result);
$num_etypes++;
$null_string = "";

$etype_id = 0;

/* add the new event type and set etype_index using $num_etypes */
$sql = 'INSERT INTO ' . CALENDAR_EVENT_TYPES_TABLE . ' ' . $db->sql_build_array('INSERT', array(
	'etype_index'		=> (int) $num_etypes,
	'etype_full_name'	=> (string) 'Holiday',
	'etype_display_name'=> (string) 'Holiday',
	'etype_color'		=> (string) $null_string,
	'etype_image'		=> (string) $null_string,
	));
$db->sql_query($sql);
$etype_id = $db->sql_nextid();

$frequency_type = array();
$sort_timestamp = array();
$week_index = array();
$first_day_of_week = array();
$event_subject = array();

$frequency_type[0] = 1;
$sort_timestamp[0] = 1199145600;
$week_index[0] = 0;
$first_day_of_week[0] = 0;
$event_subject[0] = 'New Year\'s Day';

$frequency_type[1] = 2;
$sort_timestamp[1] = 1200873600;
$week_index[1] = 3;
$first_day_of_week[1] = 0;
$event_subject[1] = 'Martin Luther King, Jr. Day';

$frequency_type[2] = 1;
$sort_timestamp[2] = 1201910400;
$week_index[2] = 0;
$first_day_of_week[2] = 0;
$event_subject[2] = 'Groundhog Day';

$frequency_type[3] = 1;
$sort_timestamp[3] = 1202947200;
$week_index[3] = 0;
$first_day_of_week[3] = 0;
$event_subject[3] = 'Valentine\'s Day';

$frequency_type[4] = 2;
$sort_timestamp[4] = 1203292800;
$week_index[4] = 3;
$first_day_of_week[4] = 0;
$event_subject[4] = 'President\'s Day';

$frequency_type[5] = 1;
$sort_timestamp[5] = 1205712000;
$week_index[5] = 0;
$first_day_of_week[5] = 0;
$event_subject[5] = 'St. Patrick\'s Day';

$frequency_type[6] = 1;
$sort_timestamp[6] = 1207008000;
$week_index[6] = 0;
$first_day_of_week[6] = 0;
$event_subject[6] = 'April Fool\'s Day';

$frequency_type[7] = 1;
$sort_timestamp[7] = 1208822400;
$week_index[7] = 0;
$first_day_of_week[7] = 0;
$event_subject[7] = 'Earth Day';

$frequency_type[8] = 4;
$sort_timestamp[8] = 1209081600;
$week_index[8] = 1;
$first_day_of_week[8] = 0;
$event_subject[8] = 'Arbor Day';

$frequency_type[9] = 1;
$sort_timestamp[9] = 1209945600;
$week_index[9] = 0;
$first_day_of_week[9] = 0;
$event_subject[9] = 'Cinco De Mayo';

$frequency_type[10] = 2;
$sort_timestamp[10] = 1210464000;
$week_index[10] = 2;
$first_day_of_week[10] = 0;
$event_subject[10] = 'Mother\'s Day';

$frequency_type[11] = 4;
$sort_timestamp[11] = 1211760000;
$week_index[11] = 1;
$first_day_of_week[11] = 0;
$event_subject[11] = 'Memorial Day';

$frequency_type[12] = 1;
$sort_timestamp[12] = 1213401600;
$week_index[12] = 0;
$first_day_of_week[12] = 0;
$event_subject[12] = 'Flag Day';

$frequency_type[13] = 2;
$sort_timestamp[13] = 1213488000;
$week_index[13] = 3;
$first_day_of_week[13] = 0;
$event_subject[13] = 'Father\'s Day';

$frequency_type[14] = 1;
$sort_timestamp[14] = 1215129600;
$week_index[14] = 0;
$first_day_of_week[14] = 0;
$event_subject[14] = 'Independence Day';

$frequency_type[15] = 2;
$sort_timestamp[15] = 1220227200;
$week_index[15] = 1;
$first_day_of_week[15] = 0;
$event_subject[15] = 'Labor Day';

$frequency_type[16] = 2;
$sort_timestamp[16] = 1221350400;
$week_index[16] = 2;
$first_day_of_week[16] = 0;
$event_subject[16] = 'Grandparent\'s Day';

$frequency_type[17] = 2;
$sort_timestamp[17] = 1223856000;
$week_index[17] = 2;
$first_day_of_week[17] = 0;
$event_subject[17] = 'Columbus Day';

$frequency_type[18] = 1;
$sort_timestamp[18] = 1225411200;
$week_index[18] = 0;
$first_day_of_week[18] = 0;
$event_subject[18] = 'Halloween';

$frequency_type[19] = 1;
$sort_timestamp[19] = 1226361600;
$week_index[19] = 0;
$first_day_of_week[19] = 0;
$event_subject[19] = 'Veterans Day';

$frequency_type[20] = 2;
$sort_timestamp[20] = 1227744000;
$week_index[20] = 4;
$first_day_of_week[20] = 0;
$event_subject[20] = 'Thanksgiving';

$frequency_type[21] = 3;
$sort_timestamp[21] = 1227830400;
$week_index[21] = 4;
$first_day_of_week[21] = 4;
$event_subject[21] = 'Black Friday';

$frequency_type[22] = 1;
$sort_timestamp[22] = 1228608000;
$week_index[22] = 0;
$first_day_of_week[22] = 0;
$event_subject[22] = 'Pearl Harbor Day';

$frequency_type[23] = 1;
$sort_timestamp[23] = 1230076800;
$week_index[23] = 0;
$first_day_of_week[23] = 0;
$event_subject[23] = 'Christmas Eve';

$frequency_type[24] = 1;
$sort_timestamp[24] = 1230163200;
$week_index[24] = 0;
$first_day_of_week[24] = 0;
$event_subject[24] = 'Christmas Day';

$frequency_type[25] = 1;
$sort_timestamp[25] = 1230681600;
$week_index[25] = 0;
$first_day_of_week[25] = 0;
$event_subject[25] = 'New Year\'s Eve';



for( $i = 0; $i < 26; $i++ )
{
	$sql = 'INSERT INTO ' . CALENDAR_RECURRING_EVENTS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
		'etype_id'				=> (int) $etype_id,
		'frequency'				=> (int) 1,
		'frequency_type'		=> (int) $frequency_type[$i],
		'first_occ_time'		=> (int) $sort_timestamp[$i],
		'final_occ_time'		=> (int) 0,
		'event_all_day'			=> (int) 1,
		'event_duration'		=> (int) 0,
		'week_index'			=> (int) $week_index[$i],
		'first_day_of_week'		=> (int) $first_day_of_week[$i],
		'last_calc_time'		=> (int) 0,
		'next_calc_time'		=> (int) $sort_timestamp[$i],
		'event_subject'			=> (string) $event_subject[$i],
		'event_body'			=> (string) $event_subject[$i],
		'poster_id'				=> (int) $user->data['user_id'],
		'poster_timezone'		=> (int) 0,
		'poster_dst'			=> (int) 0,
		'event_access_level'	=> (int) 2,
		'group_id'				=> (int) 0,
		'group_id_list'			=> (string) ',',
		'bbcode_uid'			=> (string) $null_string,
		'bbcode_bitfield'		=> (string) $null_string,
		'enable_bbcode'			=> (int) 1,
		'enable_magic_url'		=> (int) 1,
		'enable_smilies'		=> (int) 1,
		'track_rsvps'			=> (int) 0,
		'allow_guests'			=> (int) 0,
	));
	$db->sql_query($sql);
}

// now populate the new recurring events
populate_calendar(0);

trigger_error( 'US Holidays have been successfully installed' );


?>
