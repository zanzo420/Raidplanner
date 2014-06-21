<?php
/**
 *
 * @author Sajaki
 * @package bbDKP Raidplanner
 * @copyright (c) 2011 Sajaki
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 0.12
 */
namespace bbdkp\controller\raidplanner;
use \bbdkp\controller\raidplanner\Raidplan;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
    exit;
}

/**
 * Authorisation checker
 *
 * @package bbdkp\raidplanner
 */
class RaidAuth
{

    /**
     * public authorisation for the action requested
     * @var
     */

    public $raidplan;

    private $auth_cansee = false;
    private $auth_canedit = false;
    private $auth_candelete = false;
    private $auth_canadd = false;
    private $auth_signup_raidplans = false;

    private $auth_add_signup = false;
    private $auth_delete_signup = false;
    private $auth_edit_signup = false;
    private $auth_confirm_signup = false;

    private $valid_actions = array(
        'see',
        'edit',
        'delete',
        'add',
        'signup_raidplans',
        'add_signup',
        'edit_signup',
        'delete_signup',
        'confirm_signup',
    );

    private $valid_accesslevel = array(0,1,2);

    public function __construct(Raidplan $raidplan)
    {
        global $user;

        if (!in_array($raidplan->getAccesslevel(), $this->valid_accesslevel))
        {
            trigger_error($user->lang['USER_INVALIDACTION']);
        }
        $this->raidplan = $raidplan;

    }


    /**
     * Check authorisation
     * @param $action
     * @return bool
     */
    public function checkauth($action)
    {
        global $user;
        // valid action ?
        if (!in_array($action, $this->valid_actions))
        {
            trigger_error($user->lang['USER_INVALIDACTION']);
        }

        switch($action)
        {
            case 'see':
                $this->CanSeeRaid();
                return $this->auth_cansee;
            case 'edit':
                $this->CanEditRaid();
                return $this->auth_canedit;
            case 'delete':
                $this->CanDeleteRaid();
                return $this->auth_candelete;
            case 'add':
                $this->CanAddNewRaid();
                return $this->auth_canadd;
            case 'signup_raidplans':
                $this->Check_signup_raidplans();
                return $this->auth_signup_raidplans;
            case 'add_signup':
                $this->Can_add_signup();
                return $this->auth_add_signup;
            case 'edit_signup':
                $this->Can_edit_signup();
                return $this->auth_edit_signup;
            case 'delete_signup':
                $this->Can_delete_signup();
                return $this->auth_delete_signup;
            case 'confirm_signup':
                $this->Can_confirm_signup();
                return $this->auth_confirm_signup;

        }


    }

    /**
     * checks if user is allowed to *see* raid
     *
     * @return void
     */
    private function CanSeeRaid()
    {
        global $auth, $user, $db;

        if ( ! $auth->acl_get('u_raidplanner_view_raidplans') )
        {
            $this->auth_cansee = false;
            return 0;
        }

        if ($this->raidplan->getPoster() == $user->data['user_id'])
        {
            //own raids - creator always can see
            $this->auth_cansee = true;
            return 1;
        }

        // if not own raid then look at access level.
        switch($this->raidplan->getAccesslevel())
        {
            case 0:
                // personal raidplan... only raidplan creator is invited
                $this->auth_cansee = false;
                break;
            case 1:
                // group raidplan... only members of specified phpbb usergroup are invited
                // is this user a member of the group?
                if($this->raidplan->getGroupId() !=0)
                {
                    $sql = 'SELECT g.group_id
                            FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
                            WHERE ug.user_id = '.$db->sql_escape($user->data['user_id']).'
                                AND g.group_id = ug.group_id
                                AND g.group_id = '.$db->sql_escape($this->raidplan->getGroupId()).'
                                AND ug.user_pending = 0';
                    //cache for a week
                    $result = $db->sql_query($sql, 604800);
                    if($result)
                    {
                        $row = $db->sql_fetchrow($result);
                        if( $row['group_id'] == $this->raidplan->getGroupId() )
                        {
                            $this->auth_cansee = true;
                        }
                    }
                    $db->sql_freeresult($result);
                }
                else
                {
                    $group_list = explode( ',', $this->raidplan->getGroupIdList());
                    $num_groups = sizeof( $group_list );
                    $group_options = '';
                    for( $i = 0; $i < $num_groups; $i++ )
                    {
                        if( $group_list[$i] == "" )
                        {
                            continue;
                        }
                        if( $group_options != "" )
                        {
                            $group_options = $group_options . " OR ";
                        }
                        $group_options = $group_options . "g.group_id = ".$group_list[$i];
                    }
                    $sql = 'SELECT g.group_id
                            FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
                            WHERE ug.user_id = '.$db->sql_escape($user->data['user_id']).'
                                AND g.group_id = ug.group_id
                                AND ('.$group_options.')
                                AND ug.user_pending = 0';
                    $result = $db->sql_query($sql, 604800);
                    if( $result )
                    {
                        $this->auth_cansee = true;
                    }
                    $db->sql_freeresult($result);
                }
                break;
            case 2:
                // public raidplan... everyone is invited
                $this->auth_cansee = true;
                break;
        }

    }




    /**
     * checks if user can post new raid
     *
     * @return void
     */
    private function CanAddNewRaid()
    {
        global $auth;
        $this->auth_canadd = false;
        switch ($this->raidplan->getAccesslevel())
        {
            case 0:
                // can create personal appointment ?
                if ( $auth->acl_get('u_raidplanner_create_private_raidplans') )
                {
                    $this->auth_canadd = true;
                }
                break;
            case 1:
                // can create group raid ? -- only group members can attend
                if ( $auth->acl_get('u_raidplanner_create_group_raidplans') )
                {
                    $this->auth_canadd = true;
                }
                break;
            case 2:
                //can make public raid ? -- every member can attend
                if ( $auth->acl_get('u_raidplanner_create_public_raidplans') )
                {
                    $this->auth_canadd = true;
                }
                break;

        }

    }

    /**
     * checks if user can edit the raid(s)
     *
     * @return void
     */
    private function CanEditRaid()
    {
        global $user, $auth, $config;

        $this->auth_canedit = true;

        if ($user->data['is_bot'])
        {
            $this->auth_canedit = false;
        }
        elseif ($user->data['is_registered'] )
        {
            // has user right to edit raidplans?
            if (!$auth->acl_get('u_raidplanner_edit_raidplans') )
            {
                $this->auth_canedit = false;
            }
            else
            {

                if($this->raidplan->id == 0)
                {
                    $this->auth_canedit = true;
                    return true;
                }

                // has user right to edit others raids ?
                if (!$auth->acl_get('m_raidplanner_edit_other_users_raidplans') && ($user->data['user_id'] != $this->raidplan->getPoster()) )
                {
                    $this->auth_canedit = false;
                }

                // @todo testing
                // if raid expired then no edits possible even if user can edit own raids...
                // this way officers cant fiddle with statistics
                if ($config['rp_default_expiretime'] != 0 && $config['rp_enable_past_raids'] == 0)
                {
                    if (time() + $user->timezone + $user->dst - date('Z') - $this->raidplan->getEndTime() > $config['rp_default_expiretime']*60)
                    {
                        // assign editing expired raids only to administrator.
                        if (!$auth->acl_get('a_raid_config') )
                        {
                            $this->auth_canedit = false;
                        }
                    }

                }
            }
        }
    }

    /**
     * checks if user can delete raid(s)
     *
     * @return void
     */
    private function CanDeleteRaid()
    {
        global $user, $auth;
        $this->auth_candelete = false;

        if ($user->data['is_registered'] )
        {
            if($auth->acl_get('u_raidplanner_delete_raidplans'))
            {

                $this->auth_candelete = true;

                // is raidleader trying to delete other raid ?
                if ($user->data['user_id'] != $this->raidplan->getPoster())
                {
                    if (! $auth->acl_get('m_raidplanner_delete_other_users_raidplans'))
                    {
                        $this->auth_candelete = false;
                    }
                }
            }

        }

    }

    /**
     * checks if the new event is one that members can sign up to (rsvp) only valid for accesslevel 1/2
     *
     * @return void
     */
    private function Check_signup_raidplans()
    {
        global $auth;
        if($this->raidplan->id == 0)
        {
            $this->auth_canedit = false;
            return false;
        }

        $this->auth_signup_raidplans = false;
        if( $auth->acl_get('u_raidplanner_signup_raidplans'))
        {
            $this->auth_signup_raidplans = true;
        }
    }

    private function Can_add_signup()
    {
        $this->auth_add_signup = false;
    }


    private function Can_delete_signup()
    {
        $this->auth_delete_signup = false;
    }

    private function Can_edit_signup()
    {
        $this->auth_edit_signup = false;
    }

    private function Can_confirm_signup()
    {
        $this->auth_confirm_signup = false;
    }

}


