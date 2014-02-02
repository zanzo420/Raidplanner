<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9.0
*/

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

$user->add_lang ( array ('mods/raidplanner'));

//get permissions
if ( !$auth->acl_get('u_raidplanner_view_raidplans') )
{
	trigger_error( 'USER_CANNOT_VIEW_RAIDPLAN' );
}
// GET
$view_mode = request_var('view', '');
$mode=request_var('mode', '');

// display header
if (!class_exists('rpframe', false))
{
	include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpframe.' . $phpEx);
}
$cal = new rpframe();
$cal->display();

switch( $view_mode )
{
	case "raidplan":
		
		if (!class_exists('rpraid', false))
		{
			include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpraid.' . $phpEx);
		}
		
		$raidplan_id = request_var('hidden_raidplanid', request_var('raidplanid', 0));
		$raid = new rpraid($raidplan_id);
		
		switch($mode)
		{
			case 'signup':
				
				// add a new signup
				if(isset($_POST['signmeup' . $raidplan_id]))
				{
					if (!class_exists('rpsignup', false))
					{
						include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
					}
					$signup = new rpsignup();
					$signup->signup($raidplan_id);
					$signup->signupmessenger(4, $raid);
					$raid->make_obj();
					$raid->display();
				}
				break;
				
			case 'delsign':
				
				// delete a signup
				if (!class_exists('rpsignup', false))
				{
					include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
				}
				$signup_id = request_var('signup_id', 0);
				$signup = new rpsignup();
				$signup->getSignup($signup_id, $raid->eventlist->events[$raid->event_type]['dkpid']);
				if ($signup->deletesignup($signup_id, $raidplan_id) ==3)
				{
					if($raid->raid_id > 0)
					{
						//raid was pushed already
						$raid->deleteraider($signup->dkpmemberid);
					}
				}
				$signup->signupmessenger(6, $raid);
				
				$raid->make_obj();
				$raid->display();
				break;
				
			case 'editsign':
				
				// edit a signup comment				
				if (!class_exists('rpsignup', false))
				{
					include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
				}
				
				$signup_id = request_var('signup_id', 0);
				$signup = new rpsignup();
				$signup->editsignupcomment($signup_id);
				
				$raid->display();
				break;		
						
			case 'requeue':
				
				// requeue for a raid role
				if (!class_exists('rpsignup', false))
				{
					include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
				}
				$signup_id = request_var('signup_id', 0);
				$signup = new rpsignup();
				$signup->requeuesignup($signup_id);
				
				$signup->signupmessenger(4, $raid);
				$raid->make_obj();
				$raid->display();
				break;		
				
			case 'confirm':
				
				// confirm a member for a raid role
				if (!class_exists('rpsignup', false))
				{
					include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
				}
				$signup_id = request_var('signup_id', 0);
				$signup = new rpsignup();
				$signup->confirmsignup($signup_id);
				
				if($config['rp_rppushmode'] == 0 && $raid->signups['confirmed'] > 0 )
				{
					//autopush
					$raid->raidplan_push();
				}
				$signup->signupmessenger(5, $raid);
				$raid->make_obj();
				$raid->display();
				break;	
				
			case 'showadd':
				
				// show the newraid or editraid form
				$raid->showadd($cal, $raidplan_id);
				break;	
				
			case 'delete':
				
				// delete a raid				
				if(!$raid->raidplan_delete())
				{
					$raid->display();
				}
				break;	
						
			case 'push':
				//push to bbdkp
				if(!$raid->raidplan_push())
				{
					$raid->display();
				}
				break;
				
			default:
			// show the raid view form
			$raid->display();
			
			break;
		}
		break;

   case "next":		
      // display upcoming raidplans
      $template_body = "calendar_next_raidplans_for_x_days.html";
      $daycount = request_var('daycount', 60 );
      $user_id = request_var('u', 0);
      if( $user_id == 0 )
      {
      	// display all raids
      	$cal->display_next_raidplans_for_x_days( $daycount );
      }
      else
      {
      	// display signed up raids
      	$cal->display_users_next_raidplans_for_x_days($daycount, $user_id);
      }
      $template->assign_vars(array(
		'S_PLANNER_UPCOMING'	=> true,
		));
      break;
	case "day":
		// display all of the raidplans on this day
		if (!class_exists('rpday', false))
		{
			include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpday.' . $phpEx);
		}
		$cal = new rpday();
		// display calendar
		$cal->display();		
		break;
	case "week":
		if (!class_exists('rpweek', false))
		{
			// display the entire week
			include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpweek.' . $phpEx);
		}
		$cal = new rpweek();
		// display calendar
		$cal->display();
		break;
	case "month":
	default:	
		if (!class_exists('rpmonth', false))
		{
			//display the entire month
			include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpmonth.' . $phpEx);
		}
		$cal = new rpmonth();
		// display calendar
		$cal->display();
		break;
}

if (!class_exists('rpblocks', false))
{
	//display the blocks
	include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpblocks.' . $phpEx);
}
$blocks = new rpblocks();
$blocks->display();

// Output the page
page_header($user->lang['PAGE_TITLE']); 

?>