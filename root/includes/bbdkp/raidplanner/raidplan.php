<?php
/**
 *
 * @author Sajaki
 * @package bbDKP Raidplanner
 * @copyright (c) 2011 Sajaki
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 0.12.0
 */
namespace bbdkp\raidplanner;
use bbdkp\raidplanner;
use bbdkp\raidplanner\raidmessenger;
use bbdkp\controller\raids\RaidController;
use bbdkp\controller\raids\Raiddetail;
use bbdkp\controller\points\PointsController;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
    exit;
}

/**
 * implements a raid plan
 *
 */
class Raidplan
{
    /**
     * pk
     * raidplan_id
     *
     * @var int
     */
    protected $id;
    private $eventlist;

    /**
     * raidplan event type
     * etype_id
     *
     * @var int
     */
    protected $event_type;

    /**
     * Invite time timestamp
     * raidplan_invite_time
     *
     * @var int
     */
    protected $invite_time;

    /**
     * Start time timestamp
     * raidplan_start_time
     *
     * @var int
     */
    protected $start_time;

    /**
     * endtime timestamp
     * raidplan_end_time
     *
     * @var int
     */
    protected $end_time;

    /**
     * 1 if allday event, 0 if timed event
     * raidplan_all_day
     *
     * @var int
     */
    protected $all_day;

    /**
     * day of alldayevent (dd-mm-yyyy)
     * raidplan_day
     *
     * @var string
     */
    protected $day;

    /**
     * one line subject
     * raidplan_subject VARCHAR 255
     *
     * @var string
     */
    public $subject;

    /**
     * raidplan_body MEDIUMTEXT
     *
     * @var unknown_type
     */
    public $body;
    public $bbcode = array();

    /**
     * poster_id
     *
     * @var unknown_type
     */
    protected $poster;

    /**
     * access level 0 = personal, 1 = groups, 2 = all
     * default to 2
     * @var int
     */
    protected $accesslevel = 2;

    private $auth_cansee = false;
    private $auth_canedit = false;
    private $auth_candelete = false;
    private $auth_canadd = false;
    private $auth_signup_raidplans = false;
    private $group_id;
    private $group_id_list;

    /**
     * array of possible roles
     *
     * @var array
     */
    public $roles= array();

    /**
     * array of raid roles, subarray of signups per role
     *
     * @var array
     */
    public $raidroles= array();

    /**
     * raidteam int
     *
     * @var int
     */
    protected $raidteam;


    /**
     * Team name
     *
     * @var string
     */
    protected $raidteamname;

    /**
     * signups allowed ?
     *
     * @var boolean
     */
    protected $signups_allowed;

    /**
     * aray of signups
     *
     * @var array
     */
    public $signups =array();

    /**
     * If you currently signed up as maybe
     *
     * @var boolean
     */
    protected $signed_up_maybe;

    /**
     * array of signoffs
     *
     * @var array
     */
    public $signoffs= array();

    /**
     * If you are currently signed off
     *
     * @var boolean
     */
    protected $signed_off;

    /**
     * all my eligible chars
     *
     * @var array
     */
    public $mychars = array();

    /**
     * url of the poster
     *
     * @var string
     */
    protected $poster_url = '';

    /**
     * string representing invited groups
     *
     * @var string
     */
    protected $invite_list = '';


    /**
     * If raid is locked due to authorisation ?
     *
     * @var boolean
     */
    public $locked;

    /**
     * if raid signups are frozen ?
     */
    protected $frozen;

    /**
     * If user has no characters bound then set nochar to true
     *
     * @var boolean
     */
    protected $nochar;

    /**
     * If you currently signed up as available
     *
     * @var boolean
     */
    protected $signed_up;


    /**
     * If you currently confirmed
     *
     * @var boolean
     */
    protected $confirmed;

    /**
     * bbdkp raid_id
     *
     * @var unknown_type
     */
    protected $raid_id;

    /**
     * redirect link for raid
     *
     * @var string
     */
    protected $link;


    /**
     * constructor
     *
     * @param int $id
     */
    function __construct($id = 0)
    {
        global $phpEx, $phpbb_root_path;
        $this->id = $id;

        if (!class_exists('\bbdkp\raidplanner\RaidAuth'))
        {
            include($phpbb_root_path . 'includes/bbdkp/raidplanner/RaidAuth.' . $phpEx);
        }


        if (!class_exists('\bbdkp\raidplanner\rpevents'))
        {
            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpevents.' . $phpEx);
        }
        $this->eventlist= new rpevents();

        if($this->id !=0)
        {
            $this->make_obj();
            $this->Check_auth();
        }

    }

    /**
     * raidplan class property getter
     * @param string $fieldName
     */
    public function __get($fieldName)
    {
        global $user;

        if (property_exists($this, $fieldName))
        {
            return $this->$fieldName;
        }
        else
        {
            trigger_error($user->lang['ERROR'] . '  '. $fieldName, E_USER_WARNING);
        }
    }

    /**
     * raidplan class property setter
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value)
    {
        global $user;
        switch ($property)
        {
            case 'xxx':
                // is readonly
                break;
            default:
                if (property_exists($this, $property))
                {
                    $this->$property = $value;
                }
                else
                {
                    trigger_error($user->lang['ERROR'] . '  '. $property, E_USER_WARNING);
                }
        }
    }

    /**
     * make raidplan object for display
     *
     */
    public function make_obj()
    {
        global $user, $config, $phpEx, $phpbb_root_path, $db;

        // reinitialise
        $this->event_type = 0;
        $this->invite_time = 0;
        $this->start_time = 0;
        $this->end_time = 0;
        $this->all_day=0;
        $this->day='';
        $this->subject='';
        $this->body='';
        $this->bbcode = array();
        $this->poster=0;
        $this->accesslevel=2;
        $this->group_id=0;
        $this->raidteam=0;
        $this->raidteamname="";
        $this->group_id_list=array();
        $this->roles= array();
        $this->signoffs= array();
        $this->raidroles= array();
        $this->signups =array();
        $this->mychars = array();

        $this->auth_cansee = false;
        $this->auth_canedit = false;
        $this->auth_candelete = false;
        $this->auth_canadd = false;
        $this->auth_signup_raidplans = false;

        $this->poster_url = '';
        $this->invite_list = '';
        $this->signups_allowed=false;
        $this->locked= true;
        $this->frozen= true;
        $this->confirmed=false;
        $this->nochar= true;
        $this->signed_up=false;
        $this->signed_off=false;
        $this->signed_up_maybe=false;
        $this->raid_id=0;
        $this->link='';

        // populate properties
        $sql = 'SELECT * FROM ' . RP_RAIDS_TABLE . ' WHERE raidplan_id = '. (int) $this->id;
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        if(!$row)
        {
            trigger_error( 'INVALID_RAIDPLAN' );
        }

        $this->link = generate_board_url() . "/dkp.$phpEx?page=planner&view=raidplan&raidplanid=" . $this->id;
        $this->raid_id = $row['raid_id'];

        $this->accesslevel=$row['raidplan_access_level'];
        $this->poster=$row['poster_id'];
        $this->group_id=$row['group_id'];
        $this->group_id_list=$row['group_id_list'];

        // now go add raid properties
        $this->event_type= $row['etype_id'];
        $this->invite_time=$row['raidplan_invite_time'];
        $this->start_time=$row['raidplan_start_time'];
        $this->end_time=$row['raidplan_end_time'];
        $this->all_day=$row['raidplan_all_day'];
        $this->day=$row['raidplan_day'];

        $this->subject=$row['raidplan_subject'];
        $this->body=$row['raidplan_body'];

        $this->bbcode['bitfield']= $row['bbcode_bitfield'];
        $this->bbcode['uid']= $row['bbcode_uid'];
        //enable_bbcode & enable_smilies & enable_magic_url always 1

        //if signups are allowed
        $this->signups['no'] = $row['signup_no'];
        $this->signups['maybe'] = $row['signup_maybe'];
        $this->signups['yes'] = $row['signup_yes'];
        $this->signups['confirmed'] = $row['signup_confirmed'];

        $this->signups_allowed = true;
        if ($row['track_signups'] == 0)
        {
            //no tracking
            $this->signups_allowed = false;
        }

        //if raid invite time is in the past then raid signups are frozen.
        $this->frozen = false;
        if ($config['rp_default_freezetime'] != 0 && $config['rp_enable_past_raids'] == 0)
        {
            //compare invite epoch time plus (raid freeze time in hours times 3600) with the current epoch time. if expired then freeze signups
            if( $this->invite_time + (3600 * (int) $config['rp_default_freezetime'])  < time() )
            {
                $this->frozen = true;
            }
        }

        //get your raid team
        $this->raidteam = $row['raidteam'];

        unset ($row);

        $sql = 'SELECT * FROM ' . RP_TEAMS . '
					ORDER BY teams_id';
        $db->sql_query($sql);
        $result = $db->sql_query($sql);
        while ( $row = $db->sql_fetchrow ( $result ) )
        {
            if($this->raidteam == (int) $row['teams_id'])
            {
                $this->raidteamname = $row ['team_name'];
                break 1;
            }
        }
        $db->sql_freeresult($result);
        unset ($row);


        // get array of raid roles with signups and confirmations per role (available+confirmed)
        $this->raidroles = array();
        //get array of possible roles
        $this->get_roles();
        $this->get_raid_roles();

        // attach signups to roles (available+confirmed)
        $this->getSignups();

        //get all that signed unavailable
        $this->get_unavailable();

        // lock signup pane if you have no characters bound to your account
        $this->nochar = false;
        if(count ($this->mychars) == 0)
        {
            $this->nochar = true;
        }
        $this->locked = false;

        // are you currently signed up for a raidplan ?
        // check it, and lock signup pane if your char is already registered for a role
        // setting signed_up, signed_up_maybe,confirmed to true locks popup/pane
        $this->signed_up = false;

        $this->signed_up_maybe = false;
        foreach($this->raidroles as $rid => $myrole)
        {
            // there are signups?
            if(is_array($myrole['role_signups']))
            {
                //loop them
                foreach($myrole['role_signups'] as $signid => $asignup)
                {
                    if(isset($this->mychars))
                    {
                        foreach($this->mychars as $chid => $mychar)
                        {
                            if($mychar['id'] == $asignup->dkpmemberid)
                            {
                                switch ($asignup->signup_val)
                                {
                                    case 1:
                                        $this->signed_up_maybe = true;
                                        break;
                                    case 2:
                                        $this->signed_up = true;
                                        break;
                                }

                            }
                        }

                    }
                }
            }

            if(is_array($myrole['role_confirmations']))
            {
                foreach($myrole['role_confirmations'] as $asignup)
                {
                    if(isset($this->mychars))
                    {
                        foreach($this->mychars as $chid => $mychar)
                        {
                            if($mychar['id'] == $asignup->dkpmemberid)
                            {
                                $this->confirmed = true;
                            }
                        }
                    }
                }
            }
        }

        // also lock signup pane if your char is signed off
        $this->signed_off = false;
        if(is_array($this->signoffs))
        {
            foreach($this->signoffs as $signoffid => $asignoff)
            {
                if(isset($this->mychars))
                {
                    foreach($this->mychars as $chid => $mychar)
                    {
                        if($mychar['id'] == $asignoff->dkpmemberid)
                        {
                            $this->signed_off = true;
                            $this->signed_up = false;
                        }
                    }
                }
            }
        }

        $sql = 'SELECT user_id, username, user_colour FROM ' . USERS_TABLE . ' WHERE user_id = '.$db->sql_escape($this->poster);
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        $this->poster_url = get_username_string( 'full', $this->poster, $row['username'], $row['user_colour'] );

        //depending on access level invite different phpbb groups.
        switch( $this->accesslevel )
        {
            case 0:
                // personal raidplan... only raidplan creator is invited
                $this->invite_list = $this->poster_url;
                break;
            case 1:
                if( $this->group_id != 0 )
                {
                    // this is a group raidplan... only phpbb accounts of this group are invited
                    $sql = 'SELECT group_name, group_type, group_colour FROM ' . GROUPS_TABLE . '
								WHERE group_id = '.$db->sql_escape($this->group_id);

                    $result = $db->sql_query($sql);
                    $group_data = $db->sql_fetchrow($result);
                    $db->sql_freeresult($result);

                    $temp_list = (($group_data['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $group_data['group_name']] : $group_data['group_name']);
                    $temp_url = append_sid("{$phpbb_root_path}memberlist.$phpEx", "mode=group&amp;g=".$this->group_id);
                    $temp_color_start = "";
                    $temp_color_end = "";
                    if( $group_data['group_colour'] !== "" )
                    {
                        $temp_color_start = "<span style='color:#".$group_data['group_colour']."'>";
                        $temp_color_end = "</span>";
                    }
                    $this->invite_list = "<a href='".$temp_url."'>".$temp_color_start.$temp_list.$temp_color_end."</a>";
                }
                else
                {
                    // multiple groups invited
                    $group_list = explode( ',', $this->group_id_list );
                    $num_groups = sizeof( $group_list );
                    for( $i = 0; $i < $num_groups; $i++ )
                    {
                        if( $group_list[$i] == "")
                        {
                            continue;
                        }

                        // group raidplan... only phpbb accounts  of specified group are invited
                        $sql = 'SELECT group_name, group_type, group_colour FROM ' . GROUPS_TABLE . '
									WHERE group_id = '.$db->sql_escape($group_list[$i]);
                        $result = $db->sql_query($sql);
                        $group_data = $db->sql_fetchrow($result);
                        $db->sql_freeresult($result);
                        $temp_list = (($group_data['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $group_data['group_name']] : $group_data['group_name']);
                        $temp_url = append_sid("{$phpbb_root_path}memberlist.$phpEx", "mode=group&amp;g=".$group_list[$i]);
                        $temp_color_start = "";
                        $temp_color_end = "";
                        if( $group_data['group_colour'] !== "" )
                        {
                            $temp_color_start = "<span style='color:#".$group_data['group_colour']."'>";
                            $temp_color_end = "</span>";
                        }

                        if( $this->invite_list == "" )
                        {
                            $this->invite_list = "<a href='".$temp_url."'>".$temp_color_start.$temp_list.$temp_color_end."</a>";
                        }
                        else
                        {
                            $this->invite_list .=  ", " . "<a href='".$temp_url."'>".$temp_color_start.$temp_list.$temp_color_end."</a>";
                        }
                    }
                }
                break;
            case 2:
                // public raidplan... everyone is invited
                $this->invite_list = $user->lang['EVERYONE'];
                break;
        }
    }


    public function Check_auth()
    {
        $RaidAuth = new RaidAuth($this);

        $this->auth_cansee = $RaidAuth->checkauth('see');
        if(!$this->auth_cansee)
        {
            trigger_error( 'USER_CANNOT_VIEW_RAIDPLAN' );
        }
        $this->auth_canedit = $RaidAuth->checkauth('edit');
        $this->auth_candelete = $RaidAuth->checkauth('delete');
        $this->auth_canadd = $RaidAuth->checkauth('add');
        $this->auth_signup_raidplans = $RaidAuth->checkauth('signup_raidplans');
        unset ($RaidAuth);
    }

    /**
     *
     * insert new or update existing raidplan object
     *
     */
    public function storeplan()
    {
        global $db;

        $sql_raid = array(
            'etype_id'		 		=> (int) $this->event_type,
            'poster_id'		 		=> $this->poster,
            'sort_timestamp'		=> $this->start_time,
            'raidplan_invite_time'	=> $this->invite_time,
            'raidplan_start_time'	=> $this->start_time,
            'raidplan_end_time'		=> $this->end_time,
            'raidplan_all_day'		=> $this->all_day,
            'raidplan_day'			=> $this->day,
            'raidteam'				=> $this->raidteam,
            'raidplan_subject'		=> $this->subject,
            'raidplan_body'			=> $this->body,
            'raidplan_access_level'	=> $this->accesslevel,
            'group_id'				=> $this->group_id,
            'group_id_list'			=> $this->group_id_list,
            'enable_bbcode'			=> 1,
            'enable_smilies'		=> 1,
            'enable_magic_url'		=> 1,
            'bbcode_bitfield'		=> $this->bbcode['bitfield'],
            'bbcode_uid'			=> $this->bbcode['uid'],
            'track_signups'			=> $this->signups_allowed,
            'signup_yes'			=> $this->signups['yes'],
            'signup_no'				=> $this->signups['no'],
            'signup_maybe'			=> $this->signups['maybe'],
        );

        /*
         * start transaction
         */
        $db->sql_transaction('begin');

        if( $this->id == 0)
        {
            //insert new
            $sql = 'INSERT INTO ' . RP_RAIDS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_raid);
            $db->sql_query($sql);
            $this->id = $db->sql_nextid();
            $this->raidmessenger(1);
        }
        else
        {
            // update
            $sql = 'UPDATE ' . RP_RAIDS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_raid) . '
		    WHERE raidplan_id = ' . (int) $this->id;
            $db->sql_query($sql);
            $this->raidmessenger(2);

        }
        unset ($sql_raid);

        $db->sql_transaction('commit');

    }

    /**
     * inserts or updates raidroles
     *
     */
    public function store_raidroles()
    {
        global $db;

        /*
         * start transaction
         */
        $db->sql_transaction('begin');

        foreach($this->raidroles as $role_id => $role)
        {

            if( $this->id == 0)
            {
                $sql_raidroles = array(
                    'raidplan_id'		=> $this->id,
                    'role_id'			=> $role_id,
                    'role_needed'		=> $role['role_needed']
                );

                //insert new
                $sql = 'INSERT INTO ' . RP_RAIDPLAN_ROLES . ' ' . $db->sql_build_array('INSERT', $sql_raidroles);
                $db->sql_query($sql);

            }
            else
            {
                // update
                $sql_raidroles = array(
                    'role_id'			=> $role_id,
                    'role_needed'		=> $role['role_needed']
                );

                $sql = 'UPDATE ' . RP_RAIDPLAN_ROLES . '
	    		SET ' . $db->sql_build_array('UPDATE', $sql_raidroles) . '
			    WHERE raidplan_id = ' . (int)  $this->id . '
			    AND role_id = ' . $role_id;

                $db->sql_query($sql);
            }
        }

        $db->sql_transaction('commit');

        unset($sql_raidroles);
        unset($role_id);
        unset($role);


    }

    /**
     * delete a Raid plan
     *
     */
    public function raidplan_delete()
    {
        // recheck if user can delete
        global $user, $db, $phpbb_root_path, $phpEx;

        if($this->auth_candelete == false)
        {
            trigger_error('USER_CANNOT_DELETE_RAIDPLAN');
        }

        if (confirm_box(true))
        {
            //recall vars

            $this->id = request_var('raidplan_id', 0);

            if( $this->id != 0)
            {
                $this->raidmessenger(3);

                $db->sql_transaction('begin');

                // delete all the signups for this raidplan before deleting the raidplan
                $sql = 'DELETE FROM ' . RP_SIGNUPS . ' WHERE raidplan_id = ' . $db->sql_escape( $this->id);
                $db->sql_query($sql);

                // Delete event
                $sql = 'DELETE FROM ' . RP_RAIDS_TABLE . ' WHERE raidplan_id = '.$db->sql_escape( $this->id);
                $db->sql_query($sql);

                $sql = 'DELETE FROM ' . RP_RAIDPLAN_ROLES . ' WHERE raidplan_id = '.$db->sql_escape( $this->id);
                $db->sql_query($sql);

                $db->sql_transaction('commit');

                $day = gmdate("d", $this->start_time);
                $month = gmdate("n", $this->start_time);
                $year =	gmdate('Y', $this->start_time);

                unset($this);

                $meta_info = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calM=".$month."&amp;calY=".$year);
                $message = $user->lang['EVENT_DELETED'];


                meta_refresh(3, $meta_info);
                $message .= '<br /><br />' . sprintf($user->lang['RETURN_CALENDAR'], '<a href="' . $meta_info . '">', '</a>');
                trigger_error($message);
            }

        }
        else
        {
            $s_hidden_fields = build_hidden_fields(array(
                    'raidplan_id'=> $this->id,
                    'page'	=> 'planner',
                    'view'	=> 'raidplan',
                    'action'	=> 'delete')
            );

            return confirm_box(false, $user->lang['DELETE_RAIDPLAN_CONFIRM'], $s_hidden_fields);

        }


    }

    /**
     * builds raid roles property, needed sor displaying signups
     *
     */
    private function get_raid_roles()
    {
        global $db;

        $sql_array = array(
            'SELECT'    => 'rr.raidplandet_id, rr.role_needed, rr.role_signedup, rr.role_confirmed,
	    					r.role_id, r.role_name, r.role_color, r.role_icon ',
            'FROM'      => array(
                RP_ROLES   => 'r',
                RP_RAIDPLAN_ROLES   => 'rr'
            ),
            'WHERE'		=>  'r.role_id = rr.role_id and rr.raidplan_id = ' . $this->id,
            'ORDER_BY'  => 'r.role_id'
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        $signups = array();
        $confirmations = array();
        $this->raidroles = array();
        while ( $row = $db->sql_fetchrow ( $result ) )
        {
            $this->raidroles[$row['role_id']]['role_name'] = $row['role_name'];
            $this->raidroles[$row['role_id']]['role_color'] = $row['role_color'];
            $this->raidroles[$row['role_id']]['role_icon'] = $row['role_icon'];
            $this->raidroles[$row['role_id']]['role_needed'] = $row['role_needed'];
            $this->raidroles[$row['role_id']]['role_signedup'] = $row['role_signedup'];
            $this->raidroles[$row['role_id']]['role_confirmed'] = $row['role_confirmed'];
            $this->raidroles[$row['role_id']]['role_confirmations'] =  $confirmations;
            $this->raidroles[$row['role_id']]['role_signups'] =  $signups;
        }
        $db->sql_freeresult($result);
    }


    /**
     * builds roles property, needed when you make new raid
     *
     */
    public function get_roles()
    {
        global $db;

        $sql_array = array(
            'SELECT'    => 'r.role_id, r.role_name, r.role_color, r.role_icon ',
            'FROM'      => array(
                RP_ROLES   => 'r'
            ),
            'ORDER_BY'  => 'r.role_id'
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        while ( $row = $db->sql_fetchrow ( $result ) )
        {
            $this->roles[$row['role_id']]['role_name'] = $row['role_name'];
            $this->roles[$row['role_id']]['role_color'] = $row['role_color'];
            $this->roles[$row['role_id']]['role_icon'] = $row['role_icon'];
        }
        $db->sql_freeresult($result);
    }

    /**
     * selects all signups that have a role, then makes signup objects, returns array of objects to role code
     * 0 unavailable 1 maybe 2 available 3 confirmed
     *
     */
    private function getSignups()
    {
        global $phpEx, $phpbb_root_path, $db;

        if (!class_exists('\bbdkp\raidplanner\RaidplanSignup'))
        {
            require("{$phpbb_root_path}includes/bbdkp/raidplanner/RaidplanSignup.$phpEx");
        }


        // fill mychars array for popup
        $rpsignup = new RaidplanSignup();
        $this->mychars = $rpsignup->getmychars($this->id);
        unset($rpsignup);

        //fill signups array
        foreach ($this->raidroles as $roleid => $role)
        {
            $sql = "select * from " . RP_SIGNUPS . " where raidplan_id = " . $this->id . " and signup_val > 0 and role_id  = " . $roleid;
            $result = $db->sql_query($sql);

            while ($row = $db->sql_fetchrow($result))
            {
                //bind all public object vars of signup class instance to signup array and add to role array
                $rpsignup = new RaidplanSignup();
                $rpsignup->getSignup($row['signup_id'], $this->eventlist->events[$this->event_type]['dkpid']);
                if($rpsignup->signup_val == 1 || $rpsignup->signup_val == 2)
                {
                    // maybe + available
                    $this->raidroles[$roleid]['role_signups'][] = $rpsignup;
                }
                elseif($rpsignup->signup_val == 3)
                {
                    //confirmed
                    $this->raidroles[$roleid]['role_confirmations'][] = $rpsignup;
                }
                unset($rpsignup);
            }

            $db->sql_freeresult($result);
        }

        unset($roleid);
        unset($role);

    }

    /**
     * get all those that signed unavailable
     * 0 unavailable 1 maybe 2 available 3 confirmed
     *
     */
    public function get_unavailable()
    {
        global $db, $config, $phpbb_root_path, $db, $phpEx;

        if (!class_exists('\bbdkp\raidplanner\RaidplanSignup'))
        {
            require("{$phpbb_root_path}includes/bbdkp/raidplanner/RaidplanSignup.$phpEx");
        }
        $rpsignup = new RaidplanSignup();

        $sql = "select * from " . RP_SIGNUPS . " where raidplan_id = " . $this->id . " and signup_val = 0";
        $result = $db->sql_query($sql);
        $this->signoffs = array();

        while ($row = $db->sql_fetchrow($result))
        {
            $rpsignup->getSignup($row['signup_id'], $this->eventlist->events[$this->event_type]['dkpid']);
            //get all public object vars to signup array and bind to role
            $this->signoffs[] = $rpsignup;
        }
        unset($rpsignup);
        $db->sql_freeresult($result);
    }


    /**
     * raidmessenger
     *
     * eventhandler for
     * raidplan add
     *   send to all who have a dkp member with points
     * raidplan update
     *   send to raidplan participants
     * raidplan delete
     *   send to raidplan participants
     *
     * @param $trigger
     */
    private function raidmessenger($trigger)
    {
        global $user, $config;
        global $phpEx, $phpbb_root_path;

        include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
        include_once($phpbb_root_path . 'includes/functions.' . $phpEx);
        include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
        include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);

        if (!class_exists('\bbdkp\raidplanner\raidmessenger'))
        {
            require("{$phpbb_root_path}includes/bbdkp/raidplanner/raidmessenger.$phpEx");
        }
        $rpm = new raidmessenger();
        $rpm->get_notifiable_users($trigger, $this->id);

        $emailrecipients = array();
        $messenger = new \messenger();
        foreach($rpm->send_user_data as $id => $row)
        {
            $data=array();
            // get template
            switch ($trigger)
            {
                case 1:
                    $messenger->template('raidplan_add', $row['user_lang']);
                    $subject = '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['NEWRAID'] . ': ' . $this->eventlist->events[$this->event_type]['event_name'] . ' ' . $user->format_date($this->start_time, $config['rp_date_time_format'], true);
                    break;
                case 2:
                    $messenger->template('raidplan_update', $row['user_lang']);
                    $subject =  '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['UPDRAID'] . ': ' . $this->eventlist->events[$this->event_type]['event_name'] . ' ' .$user->format_date($this->start_time, $config['rp_date_time_format'], true);
                    break;
                case 3:
                    $messenger->template('raidplan_delete', $row['user_lang']);
                    $subject =  '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['DELRAID'] . ': ' . $this->eventlist->events[$this->event_type]['event_name'] . ' ' . $user->format_date($this->start_time, $config['rp_date_time_format'], true);
                    break;
            }

            $userids = array($this->poster);
            $rlname = array();
            user_get_id_name($userids, $rlname);

            $messenger->assign_vars(array(
                'RAIDLEADER'		=> $rlname[$this->poster],
                'USERNAME'			=> htmlspecialchars_decode($row['username']),
                'EVENT_SUBJECT'		=> $subject,
                'EVENT'				=> $this->eventlist->events[$this->event_type]['event_name'],
                'INVITE_TIME'		=> $user->format_date($this->invite_time, $config['rp_date_time_format'], true),
                'START_TIME'		=> $user->format_date($this->start_time, $config['rp_date_time_format'], true),
                'END_TIME'			=> $user->format_date($this->end_time, $config['rp_date_time_format'], true),
                'TZ'				=> $user->lang['tz'][(int) $user->data['user_timezone']],
                'U_RAIDPLAN'		=> generate_board_url() . "/dkp.$phpEx?page=planner&amp;view=raidplan&amp;raidplanid=".$this->id
            ));

            $messenger->msg = trim($messenger->tpl_obj->assign_display('body'));
            $messenger->msg = str_replace("\r\n", "\n", $messenger->msg);

            $messenger->msg = utf8_normalize_nfc($messenger->msg);
            $uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
            $allow_bbcode = $allow_smilies = $allow_urls = true;
            generate_text_for_storage($messenger->msg, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
            $messenger->msg = generate_text_for_display($messenger->msg, $uid, $bitfield, $options);

            $data = array(
                'address_list'      => array('u' => array($row['user_id'] => 'to')),
                'from_user_id'      => $user->data['user_id'],
                'from_username'     => $user->data['username'],
                'icon_id'           => 0,
                'from_user_ip'      => $user->data['user_ip'],

                'enable_bbcode'     => true,
                'enable_smilies'    => true,
                'enable_urls'       => true,
                'enable_sig'        => true,

                'message'           => $messenger->msg,
                'bbcode_bitfield'   => $this->bbcode['bitfield'],
                'bbcode_uid'        => $this->bbcode['uid'],
            );

            if($config['rp_pm_rpchange'] == 1 &&  (int) $row['user_allow_pm'] == 1)
            {
                // send a PM
                submit_pm('post',$subject, $data, false);
            }

            if($config['rp_email_rpchange'] == 1 && $row['user_email'] != '')
            {
                //send email, reuse messenger object
                $email = $messenger;
                $emailrecipients[]=$row['username'];
                $email->to($row['user_email'], $row['username']);
                $email->anti_abuse_headers($config, $user);
                $email->send(0);
            }

        }

        if($config['rp_email_rpchange'] == 1 && isset($email))
        {
            $email->save_queue();
            $emailrecipients = implode(', ', $emailrecipients);
            add_log('admin', 'LOG_MASS_EMAIL', $emailrecipients);
        }


    }


    /**
     * adds raid to bbdkp
     *
     */
    public function raidplan_push()
    {
        global $db, $user, $config, $phpbb_root_path, $phpEx ;

        if (!class_exists('\bbdkp\controller\raids\RaidController'))
        {
            require("{$phpbb_root_path}includes/bbdkp/controller/raids/RaidController.$phpEx");
        }
        // check if raid exists in bbdkp
        if ($this->raid_id > 0)
        {
            $RaidController = new RaidController;

            $raidinfo = array (
                'raid_id' 	 => (int) $this->raid_id,
                'event_id' 	 => $this->event_type,
                'raid_value' => (float) $this->eventlist->events[$this->event_type]['value'],
                'time_bonus' => 0,
                'raid_note'  => $this->body,
                'raid_start' => $this->start_time,
                'raid_end' 	 => $this->end_time,
            );

            $RaidController->update_raid($raidinfo);

            //get all confirmed raiders
            $raid_attendees = array();
            foreach($this->raidroles as $key => $role)
            {
                foreach($role['role_confirmations'] as $confirmation)
                {
                    $raid_attendees[] = $confirmation->dkpmemberid;
                }
            }

            // now check if any of them are not registered in dkp, if they are not then add them
            $raiddetail = new Raiddetail($this->raid_id);
            $raiddetail->Get($this->raid_id);

            $registered = array();
            foreach ($raiddetail->raid_details as $member_id => $attendee)
            {
                $registered[] = (int) $member_id;
            }
            //
            $to_add = array_diff($raid_attendees, $registered);

            if (count($to_add) > 0)
            {
                foreach($to_add as $member_id)
                {
                    $newraider = new Raiddetail($this->raid_id);
                    $newraider->raid_value = (float) $this->eventlist->events[$this->event_type]['value'];
                    $newraider->time_bonus = 0;
                    $newraider->zerosum_bonus = 0;
                    $newraider->raid_decay = 0;
                    $newraider->dkpid = $this->eventlist->events[$this->event_type]['dkpid'];
                    $newraider->member_id = $member_id;
                    $newraider->create();
                    unset($newraider);
                }
            }

        }
        else
        {
            //new raid

            if($config['rp_rppushmode'] == 0 && $this->signups['confirmed'] > 0 )
            {
                // automatic mode, don't ask permisison
                $raid_attendees = array();
                foreach($this->raidroles as $key => $role)
                {
                    foreach($role['role_confirmations'] as $confirmation)
                    {
                        $raid_attendees[] = $confirmation->dkpmemberid;
                    }
                }

                // timebonus is hardcoded to zero but could be changed later...
                $raid = array(
                    'raid_note' 				=> $this->body,
                    'raid_value' 				=> $this->eventlist->events[$this->event_type]['value'],
                    'raid_timebonus'	        => request_var ('hidden_raid_timebonus', 0.00 ),
                    'zerosum_bonus'	            => 0,
                    'raid_decay'	            => 0,
                    'raid_start'			 	=> $this->start_time,
                    'raid_end' 					=> $this->end_time,
                    'event_name'				=> $this->eventlist->events[$this->event_type]['event_name'],
                    'event_id' 					=> $this->event_type,
                    'dkpid'						=> $this->eventlist->events[$this->event_type]['dkpid'],
                    'raid_attendees' 			=> $raid_attendees
                );
                $this->exec_pushraid($raid);

            }
            else
            {

                //insert
                if (confirm_box ( true ))
                {
                    // recall hidden vars
                    $raid = array(
                        'raid_note' 		=> utf8_normalize_nfc (request_var ( 'hidden_raid_note', ' ', true )),
                        'raid_value' 		=> request_var ('hidden_raid_value', 0.00 ),
                        'raid_timebonus'	=> request_var ('hidden_raid_timebonus', 0.00 ),
                        'zerosum_bonus'	    => 0,
                        'raid_decay'	    => 0,
                        'raid_start' 		=> request_var ('hidden_startraid_date', 0),
                        'raid_end'			=> request_var ('hidden_endraid_date', 0),
                        'event_name'		=> utf8_normalize_nfc (request_var ( 'hidden_raid_name', ' ', true )),
                        'event_id' 			=> request_var ('hidden_event_id', 0),
                        'dkpid'				=> request_var ('hidden_dkpid', 0),
                        'raid_attendees' 	=> request_var ('hidden_raid_attendees', array ( 0 => 0 )),
                    );

                    $this->exec_pushraid($raid);

                }
                else
                {

                    // store raidinfo as hidden vars
                    // this clears the $_POST array
                    $raid_attendees = array();
                    foreach($this->raidroles as $key => $role)
                    {
                        foreach($role['role_confirmations'] as $confirmation)
                        {
                            $raid_attendees[] = $confirmation->dkpmemberid;
                        }
                    }

                    // timebonus is hardcoded to zero but could be changed later...
                    $s_hidden_fields = build_hidden_fields(array(
                            'hidden_raid_id' 			=> $this->raid_id,
                            'hidden_raid_note' 			=> $this->body,
                            'hidden_event_id' 			=> $this->event_type,
                            'hidden_raid_name'			=> $this->eventlist->events[$this->event_type]['event_name'],
                            'hidden_raid_value' 		=> $this->eventlist->events[$this->event_type]['value'],
                            'hidden_dkpid'				=> $this->eventlist->events[$this->event_type]['dkpid'],
                            'hidden_raid_timebonus' 	=> 0,
                            'hidden_startraid_date' 	=> $this->start_time,
                            'hidden_endraid_date' 		=> $this->end_time,
                            'hidden_raid_attendees' 	=> $raid_attendees,
                            'add'    					=> true,
                        )
                    );

                    confirm_box(false, sprintf($user->lang['CONFIRM_CREATE_RAID'],
                        $this->eventlist->events[$this->event_type]['event_name']) , $s_hidden_fields);
                }

            }


        }

        unset($RaidController);

    }

    /**
     * private subroutine for raidplan_push
     *
     * @param array $raid
     * @return int|null|number
     */
    private function exec_pushraid($raid)
    {
        global $db, $phpbb_root_path, $phpEx;

        if (!class_exists('\bbdkp\controller\raids\RaidController'))
        {
            require("{$phpbb_root_path}includes/bbdkp/controller/raids/RaidController.$phpEx");
        }
        if (!class_exists('\bbdkp\controller\points\PointsController'))
        {
            require("{$phpbb_root_path}includes/bbdkp/controller/points/PointsController.$phpEx");
        }

        $RaidController = new RaidController($raid['dkpid']);
        $RaidController->init_newraid();
        $event = $RaidController->eventinfo[$raid['event_id']];
        $raidinfo = array(
            'raid_note' 		=> (string) $raid['raid_note'],
            'event_id' 			=> $raid['event_id'],
            'raid_start' 		=> (int) $raid['raid_start'],
            'raid_end'			=> (int) $raid['raid_end'],
        );

        $raid_detail = array();
        foreach ( $raid['raid_attendees'] as $member_id )
        {
            $raid_detail[] = array(
                'member_id'    => (int) $member_id,
                'raid_value'   => (float) $raid['raid_value'],
                'zerosum_bonus'   => (float) $raid['zerosum_bonus'],
                'time_bonus'   => (float) $raid['raid_timebonus'],
                'raid_decay'   => (float) $raid['raid_decay'],
            );
        }

        $raid_id = $RaidController->add_raid($raidinfo, $raid_detail);

        $PointsController = new PointsController();
        $PointsController->add_points($raid_id);

        //store raid_id
        $sql = 'UPDATE ' . RP_RAIDS_TABLE . ' SET raid_id = '  . $raid_id . ' WHERE raidplan_id = ' . $this->id;
        $db->sql_query($sql);

        unset($RaidController);
        unset($PointsController);
        return $raid_id;
    }


    /**
     * kick raider after raid was pushed
     *
     * @param int $member_id
     *
     */
    public function deleteraider($member_id)
    {

        global $phpbb_root_path, $phpEx, $db;
        if (!class_exists('\bbdkp\controller\raids\RaidController'))
        {
            require("{$phpbb_root_path}includes/bbdkp/controller/raids/RaidController.$phpEx");
        }
        $RaidController = new RaidController($this->raid_id);
        $RaidController->deleteraider($this->raid_id,$member_id);
        unset($RaidController);
    }

}
