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
        $this->cal = new DisplayFrame();
        $this->cal->display();
        unset($this->cal);

        $view_mode = request_var('view', '');
        switch( $view_mode )
        {
            case "raidplan":
                // display one raidplan
                $this->ViewRaidplan();
                break;
            case "day":
            case "week":
            case "month":
                // display calendar
                $calendarclass = '\bbdkp\raidplanner\rp' . $view_mode;
                if (!class_exists( $calendarclass, false))
                {
                    include($phpbb_root_path . 'includes/bbdkp/raidplanner/rp' . $view_mode .'.' . $phpEx);
                }
                $this->cal = new $calendarclass();
                $this->cal->display();
                unset($this->cal);
                break;
            default:

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


    private function ViewRaidplan()
    {

        $raidplan_id = request_var('hidden_raidplanid', request_var('raidplanid', 0));
        $raid = new Raidplan($raidplan_id);
        $mode=request_var('mode', '');

        switch($mode)
        {
            case 'signup':
                $this->AddSignup();
                break;

            case 'delsign':

                $this->DeleteSignup();
                break;

            case 'editsign':
                $this->EditComment();
                break;

            case 'requeue':
                $this->Requeue();
                break;

            case 'confirm':
                $this->ConfirmSignup();
                break;

            case 'showadd':
                // show the newraid or editraid form
                $raid->showadd($this->cal, $raidplan_id);
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
    }

    private function AddSignup()
    {
        // add a new signup
        if(isset($_POST['signmeup' . $raidplan_id]))
        {


            $signup = new RaidplanSignup();
            $signup->signup($raidplan_id);
            $signup->signupmessenger(4, $raid);
            $raid->make_obj();
            $raid->display();
        }
    }

    private function DeleteSignup()
    {
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

    }


    private function EditComment()
    {

        // edit a signup comment

        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->editsignupcomment($signup_id);

        $raid->display();
    }

    private function Requeue()
    {
        // requeue for a raid role
        $signup_id = request_var('signup_id', 0);
        $signup = new RaidplanSignup();
        $signup->requeuesignup($signup_id);

        $signup->signupmessenger(4, $raid);
        $raid->make_obj();
        $raid->display();
    }

    private function ConfirmSignup()
    {

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
    }






}

