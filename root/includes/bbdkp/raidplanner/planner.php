<?php
/**
*
* @author alightner, Salaki
* @package phpBB Calendar
* @version $Id $
* @copyright (c) 2009 alightner
* @copyright (c) 2010 Sajaki 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

$user->add_lang ( array ('mods/raidplanner'));
include($phpbb_root_path . 'includes/bbdkp/raidplanner/raidplanner_display.' . $phpEx);

//get permissions
if ( !$auth->acl_get('u_raidplanner_view_raidplans') )
{
	trigger_error( 'NO_AUTH_OPERATION' );
}

if( !$user->data['is_bot'] && $user->data['user_id'] != ANONYMOUS )
{
	$calWatch = request_var( 'calWatch', 2 );
	$watchclass = new calendar_watch();
				
	if( $calWatch < 2 )
	{
		$watchclass->calendar_watch_calendar( $calWatch );
	}
	else
	{
		$watchclass->calendar_mark_user_read_calendar( $user->data['user_id'] );
	}
}

$view_mode = request_var('view', 'month');

$cal = new displayplanner;
switch( $view_mode )
{

   case "next":
      // display next raidplans for specified number of days
      $template_body = "calendar_next_raidplans_for_x_days.html";
      $daycount = request_var('daycount', 60 );
      $user_id = request_var('u', 0);
      if( $user_id == 0 )
      {
      	$cal->display_next_raidplans_for_x_days( $daycount );
      }
      else
      {
      	$cal->display_users_next_raidplans_for_x_days($daycount, $user_id);
      }
      break;

	case "raidplan":
		// display a single raidplan
		$template_body = "planner/planner_view_raidplan.html";
		$cal->display_plannedraid();
		break;

	case "day":
		// display all of the raidplans on this day
		$cal->display_day(0);
		$template_body = "planner/planner_view_day.html";
		break;

	case "week":
		// display the entire week
		$cal->display_week(0);
		$template_body = "planner/planner.html";
		break;

	case "month":
		// display the entire month
		$template_body = "planner/planner.html";
		$cal->displaymonth();
		
		break;
}

$watcher = new calendar_watch(); 

$s_watching_calendar = array();
$watcher->calendar_init_s_watching_calendar( $s_watching_calendar );

$template->assign_vars(array(
		'U_WATCH_CALENDAR' 		=> $s_watching_calendar['link'],
		'L_WATCH_CALENDAR' 		=> $s_watching_calendar['title'],
		'S_WATCHING_CALENDAR'	=> $s_watching_calendar['is_watching'],
		)
	);

// Output the page
page_header($user->lang['PAGE_TITLE']); 

// Set the filename of the template you want to use for this file.
$template->set_filenames(array(
	'body' => $template_body)
);

page_footer();

?>
