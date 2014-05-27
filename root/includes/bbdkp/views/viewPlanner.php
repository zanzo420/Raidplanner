<?php
/**
* Raidplanner controller
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.12
*/
namespace bbdkp\views;

use bbdkp\views\raidplanner\DisplayFrame;
use bbdkp\views\raidplanner\Raidplan_display;
use bbdkp\views\raidplanner\rpblocks;

use bbdkp\controller\raidplanner\Raidplan;
use bbdkp\controller\raidplanner\RaidplanSignup;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

if (!class_exists('\bbdkp\controller\raidplanner\Raidplan', false))
{
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/raidplan.' . $phpEx);
}

if (!class_exists('\bbdkp\controller\raidplanner\RaidplanSignup', false))
{
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/RaidplanSignup.' . $phpEx);
}

if (!class_exists('\bbdkp\views\raidplanner\DisplayFrame', false))
{
    include($phpbb_root_path . 'includes/bbdkp/views/raidplanner/calendar/DisplayFrame.' . $phpEx);
}

if (!class_exists('\bbdkp\views\raidplanner\Raidplan_display', false))
{
    include($phpbb_root_path . 'includes/bbdkp/views/raidplanner/raidplan/Raidplan_display.' . $phpEx);
}

if (!class_exists('\bbdkp\raidplanner\rpblocks', false))
{
    //display left side blocks
    include($phpbb_root_path . 'includes/bbdkp/views/raidplanner/block/Rpblocks.' . $phpEx);
}

/**
 * Class viewPlanner
 * @package bbdkp\views
 */
class viewPlanner implements iViews
{

    private $cal;
    /**
     * construct viewclass
     * @param viewNavigation $Navigation
     */
    public function __construct(viewNavigation $Navigation)
    {
        $this->buildpage($Navigation);
    }

    /**
     * Build the page
     * @param viewNavigation $Navigation
     */
    public function buildpage(viewNavigation $Navigation)
    {
        global $auth, $user;
        $user->add_lang ( array ('mods/raidplanner'));

        //get permissions
        if ( !$auth->acl_get('u_raidplanner_view_raidplans') )
        {
            \trigger_error( 'USER_CANNOT_VIEW_RAIDPLAN' );
        }

        $raidplan_id = request_var('hidden_raidplanid', request_var('raidplanid', 0));

        $valid_viewsmodes = array(
            'day',
            'week',
            'month',
            'raidplan',
        );

        $view_mode = request_var('view', 'month');
        if (!in_array($view_mode, $valid_viewsmodes))
        {
            trigger_error($user->lang['USER_INVALID_RAIDPLANVIEW'] . ' "' . $view_mode . '" ' );
        }

        // display header
        $this->cal = new DisplayFrame($view_mode);
        $this->cal->display();

        switch($view_mode)
        {
            case "raidplan":
                // display a raidplan
                $this->ViewRaidplan($raidplan_id);
                break;

            case "day":
            case "week":
            case "month":
                // show the calendar
                $this->ViewCalendar($view_mode);
                break;
            default:
                trigger_error($user->lang['USER_INVALIDVIEW']);
                break;
        }


        $blocks = new rpblocks();
        $blocks->display();

        // Output the page
        page_header($user->lang['PAGE_TITLE']);
    }

    /**
     * Build the calendar
     * @param $view_mode
     */
    private function ViewCalendar($view_mode)
    {
        global $phpbb_root_path, $phpEx;

        // display wanted calendar
        $calendarclass = '\bbdkp\views\raidplanner\rp' . $view_mode;
        if (!class_exists( $calendarclass, false))
        {
            include($phpbb_root_path . 'includes/bbdkp/views/raidplanner/calendar/rp' . $view_mode .'.' . $phpEx);
        }
        $cal = new $calendarclass();
        $cal->display();
        unset($cal);
    }

    /**
     * Builds the actual raidplan
     *
     */
    private function ViewRaidplan($raidplan_id)
    {

        global $user, $template;

        $raidplan = new Raidplan($raidplan_id);

        $action = request_var('action', 'display');

        $raidplan_display = new Raidplan_display();

        $valid_actions = array(
            'signup',
            'delsign',
            'editsign',
            'requeue',
            'confirm',
            'showadd',
            'delete',
            'push',
            'display',
        );

        if (!in_array($action, $valid_actions))
        {
            trigger_error($user->lang['USER_INVALIDACTION'] . ' "' . $action . '" ' );
        }


        switch($action)
        {
            case 'signup':
                $this->AddSignup($raidplan, $raidplan_display);
                break;

            case 'delsign':
                $this->DeleteSignup($raidplan, $raidplan_display);
                break;

            case 'editsign':
                $this->EditComment($raidplan, $raidplan_display);
                break;

            case 'requeue':
                $this->Requeue($raidplan, $raidplan_display);
                break;

            case 'confirm':
                $this->ConfirmSignup($raidplan, $raidplan_display);
                break;

            case 'showadd':

                //show the add/edit raidplan form
                $submit	= (isset($_POST['addraid'])) ? true : false;
                $update	= (isset($_POST['updateraid'])) ? true : false;

                if (($submit || $update) && confirm_box(true))
                {
                     // insert in database
                     $this->AddUpdateRaidplan($raidplan_display);
                     break;
                }
                elseif ($submit || $update)
                {
                    // collect data
                    $error = array();
                    $raidplan->poster = $user->data['user_id'];

                    // get member group id
                    $raidplan->group_id_list = ',';
                    $raidplan->group_id = 0;
                    $group_id_array = request_var('calGroupId', array(0));
                    $num_group_ids = sizeof( $group_id_array );
                    if( $num_group_ids == 1 )
                    {
                        // if only one group pass the groupid
                        $raidplan->group_id = $group_id_array[0];
                    }
                    elseif( $num_group_ids > 1 )
                    {
                        // if we want multiple groups then pass the array
                        for( $group_index = 0; $group_index < $num_group_ids; $group_index++ )
                        {
                            if( $group_id_array[$group_index] == "" )
                            {
                                continue;
                            }
                            $raidplan->group_id_list .= $group_id_array[$group_index] . ",";
                        }
                    }

                    $raidplan->accesslevel = request_var('accesslevel', 0);
                    //assign raid team
                    switch($raidplan->accesslevel)
                    {
                        case 0:
                            //non raid, manual event.
                            $raidplan->signups_allowed = 0;
                            $raidplan->raidteam = 0;
                            break;
                        case 1:
                            //all
                            // if we selected group access but didn't actually choose a group then throw error
                            if ($num_group_ids < 1)
                            {
                                $error[] = $user->lang['NO_GROUP_SELECTED'];
                            }
                            //no break
                        case 2:
                            $raidplan->signups_allowed = 1;
                            $raidroles = request_var('role_needed', array(0=> 0));
                            foreach($raidroles as $role_id => $needed)
                            {
                                $raidplan->raidroles[$role_id] = array(
                                    'role_needed' => (int) $needed,
                                );
                            }
                            $raidplan->raidteam = request_var('teamselect', request_var('team_id', 0));

                    }

                    $raidplan->signups['yes'] = 0;
                    $raidplan->signups['no'] = 0;
                    $raidplan->signups['maybe'] = 0;
                    //set event type
                    $raidplan->event_type = request_var('bbdkp_events', 0);

                    // invite/start date values from pulldown click
                    $inv_d = request_var('calD', 0);
                    $inv_m = request_var('calM', 0);
                    $inv_y = request_var('calY', 0);
                    $inv_hr = request_var('calinvHr', 0);
                    $inv_mn = request_var('calinvMn', 0);
                    $raidplan->invite_time = gmmktime($inv_hr, $inv_mn, 0, $inv_m, $inv_d, $inv_y) - $user->timezone - $user->dst;

                    $start_hr = request_var('calHr', 0);
                    $start_mn = request_var('calMn', 0);
                    $raidplan->start_time = gmmktime($start_hr, $start_mn, 0, $inv_m, $inv_d, $inv_y) - $user->timezone - $user->dst;

                    $end_m = request_var('calMEnd', 0);
                    $end_d = request_var('calDEnd', 0);
                    $end_y = request_var('calYEnd', 0);

                    $end_hr = request_var('calEndHr', 0);
                    $end_mn = request_var('calEndMn', 0);
                    $raidplan->end_time = gmmktime( $end_hr, $end_mn, 0, $end_m, $end_d, $end_y ) - $user->timezone - $user->dst;
                    if ($raidplan->end_time < $raidplan->start_time)
                    {
                        //check for enddate before begindate
                        // if the end hour is earlier than start hour then roll over a day
                        $raidplan->end_time += 3600*24;
                    }

                    //if this is not an "all day event"
                    $raidplan->all_day=0;
                    $raidplan->day = sprintf('%2d-%2d-%4d', $inv_d, $inv_m, $inv_y);

                    // read subjectline
                    $raidplan->subject = utf8_normalize_nfc(request_var('subject', '', true));

                    //read comment section
                    $raidplan->body = utf8_normalize_nfc(request_var('message', '', true));

                    $raidplan->bbcode['uid'] = $raidplan->bbcode['bitfield'] = $options = '';
                    $allow_bbcode = $allow_urls = $allow_smilies = true;
                    generate_text_for_storage($raidplan->body, $raidplan->bbcode['uid'], $raidplan->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);

                    $raidplan->Check_auth();

                    if(count($error) > 0)
                    {
                        trigger_error(implode($error,"<br /> "), E_USER_WARNING);
                    }

                    $str  = serialize($raidplan);
                    $str1 = base64_encode($str);

                    if($submit)
                    {

                        if(!$raidplan->auth_canadd)
                        {
                           trigger_error('USER_CANNOT_POST_RAIDPLAN');
                        }

                        $s_hidden_fields = build_hidden_fields(array(
                                'addraid'	=> true,
                                'raidobject'	=> $str1
                            )
                        );

                        $template->assign_vars(array(
                                'S_HIDDEN_FIELDS'	 => $s_hidden_fields)
                        );
                        confirm_box(false, $user->lang['CONFIRM_ADDRAID'], $s_hidden_fields);
                    }

                    if($update)
                    {


                        if(!$raidplan->auth_canedit)
                        {
                            trigger_error('USER_CANNOT_EDIT_RAIDPLAN');
                        }

                        $s_hidden_fields = build_hidden_fields(array(
                                'updateraid'	=> true,
                                'raidobject'	=> $str1,
                                'raidplan_id'	=> $this->id
                            )
                        );

                        $template->assign_vars(array(
                                'S_HIDDEN_FIELDS'	 => $s_hidden_fields)
                        );

                        confirm_box(false, $user->lang['CONFIRM_UPDATERAID'], $s_hidden_fields);
                    }
                }

                //show add form
                $raidplan_display->showadd($raidplan, $this->cal);
                break;

            case 'delete':
                // delete a raid

                $delete	= (isset($_POST['delete'])) ? true : false;
                if($delete)
                {
                    if(!$raidplan->raidplan_delete())
                    {
                        $raidplan_display->DisplayRaidplan($raidplan);
                    }
                }
                break;
            case 'push':
                //push to bbdkp
                if(!$raidplan->raidplan_push())
                {
                    $raidplan_display->DisplayRaidplan($raidplan);
                }
                break;
            case 'display':
            default:
                // show the raid view form
                $raidplan_display->DisplayRaidplan($raidplan);
                break;
        }
        unset($raidplan);
    }

    /**
     * Add new raidplan
     * @param Raidplan_display $raidplan_display
     * @return int
     */
    private function AddUpdateRaidplan(Raidplan_display $raidplan_display)
    {
        //get string
        $str = request_var('raidobject', '');
        $str1 = base64_decode($str);
        $raidplan = unserialize($str1);

        if (! $raidplan instanceof \bbdkp\controller\raidplanner\Raidplan)
        {
            trigger_error('ERROR',E_USER_WARNING);
        }

        $action = $raidplan->storeplan();
        // store the raid & roles.
        $raidplan->store_raidroles($action);

        //make object
        $raidplan->make_obj();
        $raidplan->Check_auth();
        // display it
        $raidplan_display->DisplayRaidplan($raidplan);
        return $action;
    }

    /**
     *
     * @param Raidplan $raidplan
     * @param Raidplan_display $raidplan_display
     */
    private function AddSignup(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {
        // add a new signup
        if(isset($_POST['signmeup' . $raidplan->id]))
        {
            $signup = new RaidplanSignup();
            $signup->signup($raidplan->id);
            $signup->signupmessenger(4, $raidplan);
            $raidplan->make_obj();
            $raidplan->Check_auth();
            $raidplan_display->DisplayRaidplan($raidplan);
        }
    }

    private function DeleteSignup(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {
        $signup_id = request_var('signup_id', 0);

        $signup = new RaidplanSignup();
        $signup->getSignup($signup_id, $raidplan->eventlist->events[$raidplan->event_type]['dkpid']);
        if ($signup->deletesignup($signup_id, $raidplan->id) ==3)
        {
            if($raidplan->raid_id > 0)
            {
                //raid was pushed already
                $raidplan->deleteraider($signup->dkpmemberid);
            }
        }
        $signup->signupmessenger(6, $raidplan);

        $raidplan->make_obj();
        $raidplan->Check_auth();
        $raidplan_display->DisplayRaidplan($raidplan);

    }


    private function EditComment(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {

        // edit a signup comment

        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->editsignupcomment($signup_id);

        $raidplan_display->DisplayRaidplan($raidplan);
    }

    private function Requeue(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {
        // requeue for a raid role
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->requeuesignup($signup_id);

        $signup->signupmessenger(4, $raidplan);
        $raidplan->make_obj();
        $raidplan->Check_auth();
        $raidplan_display->DisplayRaidplan($raidplan);
    }

    private function ConfirmSignup(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {
        global $config;
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->confirmsignup($signup_id);

        if($config['rp_rppushmode'] == 0 && $raidplan->signups['confirmed'] > 0 )
        {
            //autopush
            $raidplan->raidplan_push();
        }
        $signup->signupmessenger(5, $raidplan);
        $raidplan->make_obj();
        $raidplan->Check_auth();
        $raidplan_display->DisplayRaidplan($raidplan);
    }
}
