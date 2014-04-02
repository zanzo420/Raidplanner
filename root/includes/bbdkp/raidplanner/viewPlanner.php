<?php
/**
* Raidplanner controller
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.10.0
*/
namespace bbdkp\views;

use bbdkp\raidplanner\DisplayRaidCalendar;
use bbdkp\raidplanner\Raidplan;
use bbdkp\raidplanner\RaidplanSignup;
use bbdkp\raidplanner\rpday;
use bbdkp\raidplanner\rpweek;
use bbdkp\raidplanner\rpmonth;
use bbdkp\raidplanner\rpblocks;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

// Include the base class
if (!class_exists('\bbdkp\admin\Admin'))
{
    require("{$phpbb_root_path}includes/bbdkp/admin/admin.$phpEx");
}

if (!class_exists('\bbdkp\raidplanner\DisplayRaidCalendar', false))
{
    include($phpbb_root_path . 'includes/bbdkp/raidplanner/DisplayRaidCalendar.' . $phpEx);
}

/**
 * Class viewBossprogress
 * @package bbdkp\views
 */
class viewPlanner implements iViews
{

    /**
     * construct viewclass
     * @param viewNavigation $Navigation
     */
    function __construct(viewNavigation $Navigation)
    {
        $this->buildpage($Navigation);
    }


    /**
     * View Raidplans page
     * @param viewNavigation $Navigation
     */
    public function buildpage(viewNavigation $Navigation)
    {

        global $auth, $user, $phpbb_root_path, $phpEx, $config;
        $user->add_lang ( array ('mods/raidplanner'));

        //get permissions
        if ( !$auth->acl_get('u_raidplanner_view_raidplans') )
        {
            \trigger_error( 'USER_CANNOT_VIEW_RAIDPLAN' );
        }

        // display header
        $cal = new DisplayRaidCalendar();
        $cal->display();

        $view_mode = request_var('view', '');
        switch( $view_mode )
        {
            case "raidplan":

                if (!class_exists('\bbdkp\raidplanner\Raidplan', false))
                {
                    include($phpbb_root_path . 'includes/bbdkp/raidplanner/raidplan.' . $phpEx);
                }

                $raidplan_id = request_var('hidden_raidplanid', request_var('raidplanid', 0));
                $raid = new Raidplan($raidplan_id);

                // GET
                $mode=request_var('mode', '');
                switch($mode)
                {
                    case 'signup':

                        // add a new signup
                        if(isset($_POST['signmeup' . $raidplan_id]))
                        {

                            if (!class_exists('\bbdkp\raidplanner\RaidplanSignup', false))
                            {
                                include($phpbb_root_path . 'includes/bbdkp/raidplanner/RaidplanSignup.' . $phpEx);
                            }
                            $signup = new RaidplanSignup();
                            $signup->signup($raidplan_id);
                            $signup->signupmessenger(4, $raid);
                            $raid->make_obj();
                            $raid->display();
                        }
                        break;

                    case 'delsign':

                        // delete a signup
                        if (!class_exists('\bbdkp\raidplanner\RaidplanSignup', false))
                        {
                            include($phpbb_root_path . 'includes/bbdkp/raidplanner/RaidplanSignup.' . $phpEx);
                        }
                        $signup_id = request_var('signup_id', 0);
                        $signup = new RaidplanSignup();
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
                        if (!class_exists('\bbdkp\raidplanner\RaidplanSignup', false))
                        {
                            include($phpbb_root_path . 'includes/bbdkp/raidplanner/RaidplanSignup.' . $phpEx);
                        }

                        $signup_id = request_var('signup_id', 0);
                        $signup = new RaidplanSignup();
                        $signup->editsignupcomment($signup_id);

                        $raid->display();
                        break;

                    case 'requeue':

                        // requeue for a raid role

                        if (!class_exists('\bbdkp\raidplanner\RaidplanSignup', false))
                        {
                            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
                        }
                        $signup_id = request_var('signup_id', 0);
                        $signup = new RaidplanSignup();
                        $signup->requeuesignup($signup_id);

                        $signup->signupmessenger(4, $raid);
                        $raid->make_obj();
                        $raid->display();
                        break;

                    case 'confirm':

                        // confirm a member for a raid role
                        if (!class_exists('\bbdkp\raidplanner\RaidplanSignup', false))
                        {
                            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpsignups.' . $phpEx);
                        }
                        $signup_id = request_var('signup_id', 0);
                        $signup = new RaidplanSignup();
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
                global $template;
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
                if (!class_exists('\bbdkp\raidplanner\rpday', false))
                {
                    include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpday.' . $phpEx);
                }
                $cal = new rpday();
                // display calendar
                $cal->display();
                break;
            case "week":
                if (!class_exists('\bbdkp\raidplanner\rpweek', false))
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
                if (!class_exists('\bbdkp\raidplanner\rpmonth', false))
                {
                    //display the entire month
                    include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpmonth.' . $phpEx);
                }
                $cal = new rpmonth();
                // display calendar
                $cal->display();
                break;
        }

        if (!class_exists('\bbdkp\raidplanner\rpblocks', false))
        {
            //display the blocks
            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpblocks.' . $phpEx);
        }
        $blocks = new rpblocks();
        $blocks->display();

        // Output the page
        page_header($user->lang['PAGE_TITLE']);


    }



}

