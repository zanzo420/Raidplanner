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
        $raid = new Raidplan($raidplan_id);

        switch($mode)
        {
            case 'signup':
                $this->AddSignup($raid);
                break;

            case 'delsign':

                $this->DeleteSignup($raid);
                break;

            case 'editsign':
                $this->EditComment($raid);
                break;

            case 'requeue':
                $this->Requeue($raid);
                break;

            case 'confirm':
                $this->ConfirmSignup($raid);
                break;

            case 'showadd':

                $submit	= (isset($_POST['addraid'])) ? true : false;
                $update	= (isset($_POST['updateraid'])) ? true : false;

                if (($submit || $update) && confirm_box(true))
                {
                     $this->AddUpdateRaidplan();
                }
                elseif ($submit || $update)
                {

                    $error = $raid->PrepareAdd($this->cal);
                    if(count($error) > 0)
                    {
                        trigger_error(implode($error,"<br /> "), E_USER_WARNING);
                    }

                    $str  = serialize($this);
                    $str1 = base64_encode($str);

                    if($submit)
                    {
                        if(!$raid->auth_canadd)
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
                        if(!$raid->auth_canedit)
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

                $raid->showadd($this->cal);
                break;

            case 'delete':
                // delete a raid

                $delete	= (isset($_POST['delete'])) ? true : false;
                if($delete)
                {
                    if(!$raid->raidplan_delete())
                    {
                        $raid->display();
                    }
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
        unset($raid);
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
        $raidplanobj = unserialize($str1);

        if (! $raidplanobj instanceof \bbdkp\raidplanner\Raidplan)
        {
            trigger_error('ERROR',E_USER_WARNING);
        }

        $raidplanobj->storeplan();
        // store the raid roles.
        $raidplanobj->store_raidroles();
        //make object
        $raidplanobj->make_obj();
        $raidplanobj->Check_auth();
        // display it
        $raidplanobj->display();
        return 0;
    }


    private function AddSignup(Raidplan $raid)
    {
        // add a new signup
        if(isset($_POST['signmeup' . $raid->id]))
        {


            $signup = new RaidplanSignup();
            $signup->signup($raid->id);
            $signup->signupmessenger(4, $raid);
            $raid->make_obj();
            $raid->Check_auth();
            $raid->display();
        }
    }

    private function DeleteSignup(Raidplan $raid)
    {
        $signup_id = request_var('signup_id', 0);

        $signup = new RaidplanSignup();
        $signup->getSignup($signup_id, $raid->eventlist->events[$raid->event_type]['dkpid']);
        if ($signup->deletesignup($signup_id, $raid->id) ==3)
        {
            if($raid->raid_id > 0)
            {
                //raid was pushed already
                $raid->deleteraider($signup->dkpmemberid);
            }
        }
        $signup->signupmessenger(6, $raid);

        $raid->make_obj();
        $raid->Check_auth();
        $raid->display();

    }


    private function EditComment(Raidplan $raid)
    {

        // edit a signup comment

        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->editsignupcomment($signup_id);

        $raid->display();
    }

    private function Requeue(Raidplan $raid)
    {
        // requeue for a raid role
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->requeuesignup($signup_id);

        $signup->signupmessenger(4, $raid);
        $raid->make_obj();
        $raid->Check_auth();
        $raid->display();
    }

    private function ConfirmSignup(Raidplan $raid)
    {
        global $config;
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
        $raid->Check_auth();
        $raid->display();
    }






}

