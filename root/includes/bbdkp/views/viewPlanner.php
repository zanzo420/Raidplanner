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

if (!class_exists('\bbdkp\views\raidplanner\rpblocks', false))
{
    //display left side blocks
    include($phpbb_root_path . 'includes/bbdkp/block/Rpblocks.' . $phpEx);
}

/**
 * Class viewPlanner
 * @package bbdkp\views
 */
class viewPlanner implements iViews
{

    private $cal;
    /**
     * construct view class
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

        $action = request_var('action', 'display');
        // display header
        $this->cal = new DisplayFrame($view_mode);
        $this->cal->display();

        switch($view_mode)
        {
            case "raidplan":
                // display a raidplan
                $this->ViewRaidplan($raidplan_id, $action);
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
    private function ViewRaidplan($raidplan_id, $action)
    {
        global $phpbb_root_path, $phpEx;

        $addraidplan	= (isset($_POST['addraidplan']) ) ? true : false;
        $editraidplan	= (isset($_POST['editraidplan'])) ? true : false;
        $submit	= (isset($_POST['addraid'])) ? true : false;
        $update	= (isset($_POST['updateraid'])) ? true : false;
        $deleteraidplan	= (isset($_POST['deleteraidplan'])) ? true : false;
        $pushraidplan	= (isset($_POST['pushraidplan'])) ? true : false;

        if($addraidplan)
        {
            //show add form
            $raidplan = new Raidplan();
            $raidplan_display = new Raidplan_display();
            $raidplan_display->showadd($raidplan, $this->cal);
        }
        elseif($editraidplan)
        {
            //show edit form
            $raidplan = new Raidplan($raidplan_id);
            $raidplan_display = new Raidplan_display();
            $raidplan_display->showadd($raidplan, $this->cal);
        }
        elseif (($submit || $update) && confirm_box(true))
        {
             // insert in database
            $raidplan_display = new Raidplan_display();
            $id = $this->AddUpdateRaidplan($raidplan_display);
            $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $id);
            redirect($url);

        }
        elseif ($submit || $update)
        {
            // request_var edit or new raidplan
            $raidplan = new Raidplan($raidplan_id);
            $raidplan_display = new Raidplan_display();
            $raidplan_display->SetRaidplan($raidplan, $submit, $update);
        }
        elseif($deleteraidplan || $action =='deleteraidplan')
        {
            $raidplan = new Raidplan($raidplan_id);
            if(!$raidplan->raidplan_delete())
            {
                $raidplan_display = new Raidplan_display();
                $raidplan_display->DisplayRaidplan($raidplan);
            }
        }
        elseif($pushraidplan || $action =='pushraidplan')
        {
            $raidplan = new Raidplan($raidplan_id);
            $raidplan->raidplan_push();
        }
        else
        {
            $raidplan = new Raidplan($raidplan_id);
            $raidplan_display = new Raidplan_display();
            switch($action)
            {
                case 'signup':
                    $this->AddSignup($raidplan, $raidplan_display);
                    $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $raidplan->id);
                    redirect($url);
                    break;

                case 'delsign':
                    $this->DeleteSignup($raidplan, $raidplan_display);
                    $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $raidplan->id);
                    redirect($url);
                    break;

                case 'editsign':
                    $this->EditComment($raidplan, $raidplan_display);
                    $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $raidplan->id);
                    redirect($url);
                    break;

                case 'requeue':
                    $this->Requeue($raidplan, $raidplan_display);
                    $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $raidplan->id);
                    redirect($url);
                    break;

                case 'confirm':
                    $this->ConfirmSignup($raidplan, $raidplan_display);
                    $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $raidplan->id);
                    redirect($url);
                    break;

                case 'showadd':
                    $raidplan_display->showadd($raidplan, $this->cal);
                    break;

                default:
                    $raidplan_display->DisplayRaidplan($raidplan);
                    break;
            }

        }

        unset($raidplan);
        unset($raidplan_display);

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
        $action = $raidplan->Store_Raidplan();
        // store the raid & roles.
        $raidplan->store_raidroles($action);
        //make object
        $raidplan->Get_Raidplan();
        $raidplan->Check_auth();

        return $raidplan->getId();
        // display it
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
            $raidplan->Get_Raidplan();
            $raidplan->Check_auth();
            $raidplan_display->DisplayRaidplan($raidplan);
        }
    }

    private function DeleteSignup(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->getSignup($signup_id);
        if ($signup->deletesignup($signup_id, $raidplan->id) ==3)
        {
            if($raidplan->getRaidId() > 0)
            {
                //raid was pushed already
                $raidplan->deleteraider($signup->getDkpmemberid());
            }
        }
        $signup->signupmessenger(6, $raidplan);
        $raidplan->Get_Raidplan();
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
        $raidplan->Get_Raidplan();
        $raidplan->Check_auth();
        $raidplan_display->DisplayRaidplan($raidplan);
    }

    private function ConfirmSignup(Raidplan $raidplan, Raidplan_display $raidplan_display)
    {
        global $config;
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->confirmsignup($signup_id);
        $confirmed = $raidplan->getSignups();

        if($config['rp_rppushmode'] == 0 && $confirmed['confirmed'] > 0 )
        {
            //autopush
            $raidplan->raidplan_push();
        }
        $signup->signupmessenger(5, $raidplan);
        $raidplan->Get_Raidplan();
        $raidplan->Check_auth();
        $raidplan_display->DisplayRaidplan($raidplan);

    }

}
