<?php
/**
* Raidplanner controller
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.0.4
*/
namespace bbdkp\views;

use bbdkp\views\raidplanner\DisplayFrame;
use bbdkp\views\raidplanner\Raidplan_display;
use bbdkp\raidplanner\rpblocks;
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
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/Raidplan.' . $phpEx);
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
    include($phpbb_root_path . 'includes/bbdkp/block/rpblocks.' . $phpEx);
}

/**
 * Class viewPlanner
 * @package bbdkp\views
 */
class viewPlanner implements iViews
{

    public $cal;
    /**
     * construct view class
     * @param viewNavigation $Navigation
     */

    public function __construct(viewNavigation $Navigation)
    {
        $this->buildpage($Navigation);
    }

    public $guild_id;
    public $game_id;
    public $dkpsys_id;

    /**
     * Build the page
     * @param viewNavigation $Navigation
     */
    public function buildpage(viewNavigation $Navigation)
    {
        global $template, $phpbb_root_path, $phpEx, $auth, $user;
        $user->add_lang ( array ('mods/raidplanner'));
        $this->game_id = $Navigation->getGameId();
        $this->guild_id = $Navigation->getGuildId();
        $this->dkpsys_id = $Navigation->getDkpsysId();

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
        $this->cal = new DisplayFrame($this, $view_mode);

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

        $blocks = new \bbdkp\raidplanner\rpblocks($Navigation);
        $blocks->display();

        // breadcrumbs
        $navlinks_array = array(
            array(
                'DKPPAGE'		=> $user->lang['MENU_PLANNER'],
                'U_DKPPAGE'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", '&amp;page=planner&amp;guild_id=' . $Navigation->getGuildId()),
            ),
        );

        foreach($navlinks_array as $name)
        {
            $template->assign_block_vars('dkpnavlinks', array(
                'DKPPAGE' => $name['DKPPAGE'],
                'U_DKPPAGE' => $name['U_DKPPAGE'],
            ));
        }

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
        $cal = new $calendarclass($this);
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

        $postaction = "";

        if(isset($_POST['addraidplan']))
        {
            $postaction = 'addraidplan';
        }
        elseif(isset($_POST['editraidplan']))
        {
            $postaction = 'editraidplan';
        }
        elseif(isset($_POST['addraid']))
        {
            $postaction = 'addraid';
        }
        elseif(isset($_POST['updateraid']))
        {
            $postaction = 'updateraid';
        }
        elseif(isset($_POST['deleteraidplan']))
        {
            $postaction = 'deleteraidplan';
        }
        elseif(isset($_POST['pushraidplan']))
        {
            $postaction = 'pushraidplan';
        }

        $eventlist = $this->cal->getEventlist();
        $raidplan_display = new Raidplan_display($this);
        $raidplan = new Raidplan($this->game_id, $this->guild_id, $eventlist, $raidplan_id);

        switch($postaction)
        {
            case 'addraidplan':
                //show add form
                $raidplan_display->showadd($raidplan, $this->cal);
                break;
            case 'editraidplan':
                //show edit form
                $raidplan_display->showadd($raidplan, $this->cal);
                break;
            case 'addraid':
            case 'updateraid':
                if (confirm_box(true))
                {
                     // insert in database
                    $id = $this->AddUpdateRaidplan($raidplan_display);
                    $url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $id);
                    redirect($url);
                }
                else
                {
                    // request_var edit or new raidplan
                    $raidplan_display->SetRaidplan($raidplan, $postaction);
                }
                break;
            case 'deleteraidplan':
                $raidplan->raidplan_delete();

                break;
            case 'pushraidplan':
                $raidplan->raidplan_push();
                $raidplan_display->DisplayRaidplan($raidplan);
                break;
            default:
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
            $signup->signupmessenger(4, $raidplan, $this->cal->getEventlist());
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
        $signup->signupmessenger(6, $raidplan, $this->cal->getEventlist());
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

        $signup->signupmessenger(4, $raidplan, $this->cal->getEventlist());
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
        $signup->signupmessenger(5, $raidplan, $this->cal->getEventlist());
        $raidplan->Get_Raidplan();
        $raidplan->Check_auth();
        $raidplan_display->DisplayRaidplan($raidplan);

    }

}
