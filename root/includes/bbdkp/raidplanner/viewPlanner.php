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

use bbdkp\raidplanner\DisplayFrame;
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

if (!class_exists('\bbdkp\raidplanner\DisplayFrame', false))
{
    include($phpbb_root_path . 'includes/bbdkp/raidplanner/DisplayFrame.' . $phpEx);
}

if (!class_exists('\bbdkp\raidplanner\Raidplan', false))
{
    include($phpbb_root_path . 'includes/bbdkp/raidplanner/raidplan.' . $phpEx);
}

if (!class_exists('\bbdkp\raidplanner\RaidplanSignup', false))
{
    include($phpbb_root_path . 'includes/bbdkp/raidplanner/RaidplanSignup.' . $phpEx);
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
        global $auth, $user, $phpbb_root_path, $phpEx;
        $user->add_lang ( array ('mods/raidplanner'));

        //get permissions
        if ( !$auth->acl_get('u_raidplanner_view_raidplans') )
        {
            \trigger_error( 'USER_CANNOT_VIEW_RAIDPLAN' );
        }

        $raidplan_id = request_var('hidden_raidplanid', request_var('raidplanid', 0));
        $view_mode = request_var('view', 'month');
        $mode=request_var('mode', '');

        // display header
        $this->cal = new DisplayFrame();
        $this->cal->display();
        switch( $view_mode )
        {
            case "raidplan":
                // display one raidplan
                $this->ViewRaidplan($mode, $raidplan_id);
                break;

            case "day":
            case "week":
            case "month":
                $this->ViewCalendar($view_mode);
                break;
            default:
        }

        if (!class_exists('\bbdkp\raidplanner\rpblocks', false))
        {
            //display left side blocks
            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpblocks.' . $phpEx);
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
        // display calendar
        $calendarclass = '\bbdkp\raidplanner\rp' . $view_mode;
        if (!class_exists( $calendarclass, false))
        {
            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rp' . $view_mode .'.' . $phpEx);
        }
        $cal = new $calendarclass();
        $cal->display();
        unset($cal);
    }


    /**
     * Builds the actual raidplan
     *
     */
    private function ViewRaidplan($mode, $raidplan_id)
    {
        global $user, $template;
        $raidplan = new Raidplan($raidplan_id);

        switch($mode)
        {
            case 'signup':
                $this->AddSignup($raidplan);
                break;

            case 'delsign':

                $this->DeleteSignup($raidplan);
                break;

            case 'editsign':
                $this->EditComment($raidplan);
                break;

            case 'requeue':
                $this->Requeue($raidplan);
                break;

            case 'confirm':
                $this->ConfirmSignup($raidplan);
                break;

            case 'showadd':

                $submit	= (isset($_POST['addraid'])) ? true : false;
                $update	= (isset($_POST['updateraid'])) ? true : false;

                if (($submit || $update) && confirm_box(true))
                {
                     // insert in database
                     $this->AddUpdateRaidplan();
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
                    switch($raidplan->accesslevel)
                    {
                        case 0:
                            //personal, no signups
                            $raidplan->signups_allowed = 0;
                            break;
                        case 1:
                            $raidplan->signups_allowed = 1;
                            // if we selected group access but didn't actually choose a group then throw error
                            if ($num_group_ids < 1)
                            {
                                $error[] = $user->lang['NO_GROUP_SELECTED'];
                            }

                            break;
                        case 2:
                            //all
                            $raidplan->signups_allowed = 1;
                    }


                    //get raid team
                    $raidplan->raidteam = request_var('teamselect', request_var('team_id', 0));

                    $raidroles = request_var('role_needed', array(0=> 0));

                    foreach($raidroles as $role_id => $needed)
                    {
                        $raidplan->raidroles[$role_id] = array(
                            'role_needed' => (int) $needed,
                        );
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
                           // trigger_error('USER_CANNOT_POST_RAIDPLAN');
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
                $raidplan->showadd($this->cal);
                break;

            case 'delete':
                // delete a raid

                $delete	= (isset($_POST['delete'])) ? true : false;
                if($delete)
                {
                    if(!$raidplan->raidplan_delete())
                    {
                        $raidplan->display();
                    }
                }
                break;
            case 'push':
                //push to bbdkp
                if(!$raidplan->raidplan_push())
                {
                    $raidplan->display();
                }
                break;
            default:
                // show the raid view form
                $raidplan->display();
                break;
        }
        unset($raidplan);
    }


    /**
     * Add new raidplan
     *
     * @return int
     */
    private function AddUpdateRaidplan()
    {
        //get string
        $str = request_var('raidobject', '');
        $str1 = base64_decode($str);
        $raidplan = unserialize($str1);

        if (! $raidplan instanceof \bbdkp\raidplanner\Raidplan)
        {
            trigger_error('ERROR',E_USER_WARNING);
        }

        $raidplan->storeplan();
        // store the raid roles.
        $raidplan->store_raidroles();
        //make object
        $raidplan->make_obj();
        $raidplan->Check_auth();
        // display it
        $raidplan->display();
        return 0;
    }


    private function AddSignup(Raidplan $raidplan)
    {
        // add a new signup
        if(isset($_POST['signmeup' . $raidplan->id]))
        {


            $signup = new RaidplanSignup();
            $signup->signup($raidplan->id);
            $signup->signupmessenger(4, $raidplan);
            $raidplan->make_obj();
            $raidplan->Check_auth();
            $raidplan->display();
        }
    }

    private function DeleteSignup(Raidplan $raidplan)
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
        $raidplan->display();

    }


    private function EditComment(Raidplan $raidplan)
    {

        // edit a signup comment

        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->editsignupcomment($signup_id);

        $raidplan->display();
    }

    private function Requeue(Raidplan $raidplan)
    {
        // requeue for a raid role
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->requeuesignup($signup_id);

        $signup->signupmessenger(4, $raidplan);
        $raidplan->make_obj();
        $raidplan->Check_auth();
        $raidplan->display();
    }

    private function ConfirmSignup(Raidplan $raidplan)
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
        $raidplan->display();
    }






}

