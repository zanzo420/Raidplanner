<?php
/**
 *
 * @author Sajaki
 * @package bbDKP Raidplanner
 * @copyright (c) 2011 Sajaki
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 0.12.0
 */
namespace bbdkp\controller\raidplanner;
use bbdkp\controller\raidplanner;
use bbdkp\controller\raids\RaidController;
use bbdkp\controller\raids\Raiddetail;
use bbdkp\controller\points\PointsController;
use bbdkp\controller\raidplanner\Raidmessenger;
use bbdkp\controller\raidplanner\RaidplanSignup;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
    exit;
}

if (!class_exists('\bbdkp\controller\raidplanner\RaidAuth'))
{
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/RaidAuth.' . $phpEx);
}

if (!class_exists('\bbdkp\controller\raidplanner\rpevents'))
{
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/rpevents.' . $phpEx);
}

if (!class_exists('\bbdkp\controller\raidplanner\RaidplanSignup'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/raidplanner/RaidplanSignup.$phpEx");
}

if (!class_exists('\bbdkp\controller\raidplanner\Raidmessenger'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/raidplanner/Raidmessenger.$phpEx");
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
    public $id;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    private $eventlist;
    /**
     * @param \bbdkp\controller\raidplanner\rpevents $eventlist
     */
    public function setEventlist($eventlist)
    {
        $this->eventlist = $eventlist;
    }

    /**
     * @return \bbdkp\controller\raidplanner\rpevents
     */
    public function getEventlist()
    {
        return $this->eventlist;
    }

    /**
     * raidplan event type
     * etype_id
     *
     * @var int
     */
    private $event_type;

    /**
     * @param int $event_type
     */
    public function setEventType($event_type)
    {
        $this->event_type = $event_type;
    }

    /**
     * @return int
     */
    public function getEventType()
    {
        return $this->event_type;
    }

    /**
     * Invite time timestamp
     * raidplan_invite_time
     *
     * @var int
     */
    private $invite_time;
    /**
     * @param int $invite_time
     */
    public function setInviteTime($invite_time)
    {
        $this->invite_time = $invite_time;
    }

    /**
     * @return int
     */
    public function getInviteTime()
    {
        return $this->invite_time;
    }

    /**
     * Start time timestamp
     * raidplan_start_time
     *
     * @var int
     */
    private $start_time;
    /**
     * @param int $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return int
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * endtime timestamp
     * raidplan_end_time
     *
     * @var int
     */
    private $end_time;
    /**
     * @param int $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @return int
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * 1 if allday event, 0 if timed event
     * raidplan_all_day
     *
     * @var int
     */
    private $all_day;
    /**
     * @param int $all_day
     */
    public function setAllDay($all_day)
    {
        $this->all_day = $all_day;
    }

    /**
     * @return int
     */
    public function getAllDay()
    {
        return $this->all_day;
    }

    /**
     * day of alldayevent (dd-mm-yyyy)
     * raidplan_day
     *
     * @var string
     */
    private $day;

    /**
     * @param string $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * one line subject
     * raidplan_subject VARCHAR 255
     *
     * @var string
     */
    private $subject;
    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * raidplan_body MEDIUMTEXT
     *
     * @var unknown_type
     */
    private $body;
    /**
     * @param \bbdkp\controller\raidplanner\unknown_type $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return \bbdkp\controller\raidplanner\unknown_type
     */
    public function getBody()
    {
        return $this->body;
    }

    private $bbcode = array();
    /**
     * @param array $bbcode
     */
    public function setBbcode($bbcode)
    {
        $this->bbcode = $bbcode;
    }

    /**
     * @return array
     */
    public function getBbcode()
    {
        return $this->bbcode;
    }

    /**
     * poster_id
     *
     * @var unknown_type
     */
    private $poster;

    /**
     * @param \bbdkp\controller\raidplanner\unknown_type $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return \bbdkp\controller\raidplanner\unknown_type
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * access level 0 = personal, 1 = groups, 2 = all
     * default to 2
     * @var int
     */
    private $accesslevel = 2;
    /**
     * @param int $accesslevel
     */
    public function setAccesslevel($accesslevel)
    {
        $this->accesslevel = $accesslevel;
    }

    /**
     * @return int
     */
    public function getAccesslevel()
    {
        return $this->accesslevel;
    }

    private $auth_cansee = false;
    /**
     * @param boolean $auth_cansee
     */
    public function setAuthCansee($auth_cansee)
    {
        $this->auth_cansee = $auth_cansee;
    }

    /**
     * @return boolean
     */
    public function getAuthCansee()
    {
        return $this->auth_cansee;
    }

    private $auth_canedit = false;
    /**
     * @param boolean $auth_canedit
     */
    public function setAuthCanedit($auth_canedit)
    {
        $this->auth_canedit = $auth_canedit;
    }

    /**
     * @return boolean
     */
    public function getAuthCanedit()
    {
        return $this->auth_canedit;
    }


    private $auth_candelete = false;
    /**
     * @param boolean $auth_candelete
     */
    public function setAuthCandelete($auth_candelete)
    {
        $this->auth_candelete = $auth_candelete;
    }

    /**
     * @return boolean
     */
    public function getAuthCandelete()
    {
        return $this->auth_candelete;
    }



    private $auth_canadd = false;
    /**
     * @param boolean $auth_canadd
     */
    public function setAuthCanadd($auth_canadd)
    {
        $this->auth_canadd = $auth_canadd;
    }

    /**
     * @return boolean
     */
    public function getAuthCanadd()
    {
        return $this->auth_canadd;
    }


    private $auth_signup_raidplans = false;
    /**
     * @param boolean $auth_signup_raidplans
     */
    public function setAuthSignupRaidplans($auth_signup_raidplans)
    {
        $this->auth_signup_raidplans = $auth_signup_raidplans;
    }

    /**
     * @return boolean
     */
    public function getAuthSignupRaidplans()
    {
        return $this->auth_signup_raidplans;
    }


    private $group_id;
    /**
     * @param mixed $group_id
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }
    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    private $group_id_list;
    /**
     * @param mixed $group_id_list
     */
    public function setGroupIdList($group_id_list)
    {
        $this->group_id_list = $group_id_list;
    }

    /**
     * @return mixed
     */
    public function getGroupIdList()
    {
        return $this->group_id_list;
    }
    /**
     * array of possible roles
     *
     * @var array
     */
    private $roles= array();

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }


    /**
     * array of raid roles, subarray of signups per role
     *
     * @var array
     */
    private $raidroles= array();

    /**
     * initialises raidroles array. this takes no arguments, just sets default from role.
     */
    public function init_raidplan_roles()
    {
        $this->raidroles = $this->_init_raidplan_roles();
    }

    /**
     * set needed amount
     * @param $role_id
     * @param $needed
     */
    public function setRaidroles($role_id, $needed)
    {
        $this->raidroles[$role_id]['role_needed'] = $needed;
    }


    /**
     * @return array
     */
    public function getRaidroles()
    {
        return $this->raidroles;
    }

    /**
     * raidteam int
     *
     * @var int
     */
    private $raidteam;
    /**
     * @param int $raidteam
     */
    public function setRaidteam($raidteam)
    {
        $this->raidteam = $raidteam;
    }

    /**
     * @return int
     */
    public function getRaidteam()
    {
        return $this->raidteam;
    }

    /**
     * Team name
     *
     * @var string
     */
    private $raidteamname;

    /**
     * @param string $raidteamname
     */
    public function setRaidteamname($raidteamname)
    {
        $this->raidteamname = $raidteamname;
    }

    /**
     * @return string
     */
    public function getRaidteamname()
    {
        return $this->raidteamname;
    }

    /**
     * size of team
     * @var
     */
    private $RaidTeamNeeded;

    /**
     * @return string
     */
    public function getRaidTeamNeeded()
    {
        return $this->RaidTeamNeeded;
    }


    /**
     * @param $RaidTeamNeeded
     */
    public function setRaidTeamNeeded($RaidTeamNeeded)
    {
        $this->RaidTeamNeeded = (int) $RaidTeamNeeded;
    }

    /**
     * signups allowed ?
     *
     * @var boolean
     */
    private $signups_allowed;
    /**
     * @param boolean $signups_allowed
     */
    public function setSignupsAllowed($signups_allowed)
    {
        $this->signups_allowed = $signups_allowed;
    }

    /**
     * @return boolean
     */
    public function getSignupsAllowed()
    {
        return $this->signups_allowed;
    }

    /**
     * aray of signups
     *
     * @var array
     */
    private $signups =array();
    /**
     * @param array $signups
     */
    public function setSignups($signups)
    {
        $this->signups = $signups;
    }

    /**
     * @return array
     */
    public function getSignups()
    {
        return $this->signups;
    }

    /**
     * If you currently signed up as maybe
     *
     * @var boolean
     */
    private $signed_up_maybe;

    /**
     * @return boolean
     */
    public function getSignedUpMaybe()
    {

        $this->signed_up_maybe = false;
        foreach($this->raidroles as $k => $myrole)
        {
            // there are signups?
            if(is_array($myrole['role_signups']))
            {
                //loop signups
                foreach($myrole['role_signups'] as $l => $asignup)
                {
                    if(isset($this->mychars))
                    {
                        foreach($this->mychars as $m => $mychar)
                        {
                            if($mychar['id'] == $asignup->dkpmemberid && $asignup->signup_val == 1)
                            {
                                $this->signed_up_maybe = true;
                            }
                        }

                    }
                }
            }
        }
        return $this->signed_up_maybe;
    }

    /**
     * array of signoffs
     *
     * @var array
     */
    public $signoffs= array();
    /**
     * @param array $signoffs
     */
    public function setSignoffs($signoffs)
    {
        $this->signoffs = $signoffs;
    }

    /**
     * @return array
     */
    public function getSignoffs()
    {
        return $this->signoffs;
    }

    /**
     * If you are currently signed off
     *
     * @var boolean
     */
    private $signed_off;

    /**
     * @return boolean
     */
    public function getSignedOff()
    {

        // to lock signup pane if your char is signed off
        $this->signed_off = false;
        if(is_array($this->signoffs))
        {
            foreach($this->signoffs as $k => $asignoff)
            {
                if(isset($this->mychars))
                {
                    foreach($this->mychars as $l => $mychar)
                    {
                        if($mychar['id'] == $asignoff->dkpmemberid)
                        {
                            $this->signed_off = true;
                        }
                    }
                }
            }
        }
        return $this->signed_off;
    }

    /**
     * all my eligible chars
     *
     * @var array
     */
    private $mychars = array();
    /**
     * @param array $mychars
     */
    public function setMychars($mychars)
    {
        $this->mychars = $mychars;
    }

    /**
     * @return array
     */
    public function getMychars()
    {
        return $this->mychars;
    }


    /**
     * url of the poster
     *
     * @var string
     */
    private $poster_url = '';
    /**
     * @param string $poster_url
     */
    public function setPosterUrl($poster_url)
    {
        $this->poster_url = $poster_url;
    }

    /**
     * @return string
     */
    public function getPosterUrl()
    {
        return $this->poster_url;
    }

    /**
     * string representing invited groups
     *
     * @var string
     */
    private $invite_list = '';

    /**
     * @return string
     */
    public function getInviteList()
    {
        return $this->invite_list;
    }

    /**
     * If raid is locked due to authorisation ?
     *
     * @var boolean
     */
    private $locked;
    /**
     * @param boolean $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }


    /**
     * if raid signups are frozen ?
     */
    private $frozen;

    /**
     * if raid invite time is in the past then raid signups are frozen.
     * @return mixed
     */
    public function getFrozen()
    {
        global $config;
        $this->frozen = false;
        if ($config['rp_default_freezetime'] != 0 && $config['rp_enable_past_raids'] == 0)
        {
            //compare invite epoch time plus (raid freeze time in hours times 3600) with the current epoch time. if expired then freeze signups
            if( $this->invite_time + (3600 * (int) $config['rp_default_freezetime'])  < time() )
            {
                $this->frozen = true;
            }
        }
        return $this->frozen;
    }


    /**
     * If user has no characters bound then set nochar to true
     *
     * @var boolean
     */
    private $nochar;
    /**
     * @param boolean $nochar
     */
    public function setNochar($nochar)
    {
        $this->nochar = $nochar;
    }

    /**
     * @return boolean
     */
    public function getNochar()
    {
        return $this->nochar;
    }

    /**
     * If you currently signed up as available
     *
     * @var boolean
     */
    private $signed_up;
    /**
     * @return boolean
     */
    public function getSignedUp()
    {
        $this->signed_up = false;
        foreach($this->raidroles as $k => $myrole)
        {
            // there are signups?
            if(is_array($myrole['role_signups']))
            {
                //loop signups
                foreach($myrole['role_signups'] as $l => $asignup)
                {
                    if(isset($this->mychars))
                    {
                        foreach($this->mychars as $m => $mychar)
                        {
                            if($mychar['id'] == $asignup->dkpmemberid && $asignup->signup_val == 2)
                            {
                                $this->signed_up = true;
                            }
                        }

                    }
                }
            }
        }
        return $this->signed_up;

    }


    /**
     * If you currently confirmed
     *
     * @var boolean
     */
    private $confirmed;

    /**
     * @return boolean
     */
    public function getConfirmed()
    {
        $this->confirmed = false;
        foreach($this->raidroles as $k => $myrole)
        {
            // there are confirmations?
            if(is_array($myrole['role_confirmations']))
            {
                //loop confirmations
                foreach($myrole['role_confirmations'] as $aconfirmation)
                {
                    if(isset($this->mychars))
                    {
                        foreach($this->mychars as $l => $mychar)
                        {
                            if($mychar['id'] == $aconfirmation->dkpmemberid)
                            {
                                $this->confirmed = true;
                            }
                        }
                    }
                }
            }
        }
        return $this->confirmed;
    }

    /**
     * bbdkp raid_id
     *
     * @var unknown_type
     */
    private $raid_id;

    /**
     * @return int
     */
    public function getRaidId()
    {
        return $this->raid_id;
    }


    /**
     * redirect link for raid
     *
     * @var string
     */
    private $link;

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }


    /**
     * constructor
     *
     * @param int $id
     */
    function __construct($id = 0)
    {
        // reinitialise
        $this->id = $id;
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
        $this->RaidTeamNeeded = 0;
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
        $this->signups = array(
            'yes' => 0,
            'no'  => 0,
            'maybe' => 0,
            'confirmed' => 0
         );
        $this->raid_id=0;
        $this->link='';

        //get array of possible roles
        $this->roles = $this->_get_roles();
        $this->raidroles  = $this->_init_raidplan_roles();

        //fetch event list
        $this->eventlist= new rpevents();

        // if this is an existing raidplan then get the values from database
        if($this->id !=0)
        {
            $this->Get_Raidplan();
            $this->Check_auth();
        }
        //assume all
        $this->_set_InviteList(2, 0, 0);

    }

    /**
     * make raidplan object for display
     *
     */
    public function Get_Raidplan()
    {
        global $phpEx, $db;

        // populate properties
        $sql_array = array (
            'SELECT' => ' rp.*, t.teams_id, t.team_name, t.team_needed, u.username, u.user_colour ',
            'FROM' => array (
                RP_RAIDS_TABLE 	=> 'rp',
                RP_TEAMS 		=> 't' ,
                USERS_TABLE     =>  'u'
            ),
            'WHERE' => ' t.teams_id = rp.raidteam
				AND rp.raidplan_id = ' . (int) $this->id . '
                AND rp.poster_id = u.user_id ',
        );

        $sql = $db->sql_build_query('SELECT', $sql_array);
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
        $this->poster_url = get_username_string( 'full', $this->poster, $row['username'], $row['user_colour'] );
        $this->group_id=$row['group_id'];
        $this->group_id_list=$row['group_id_list'];
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
        $this->signups_allowed = ($row['track_signups'] == 0 ? false : true);
        $this->signups = array(
            'yes' => (int) $row['signup_yes'],
            'no'  => (int) $row['signup_no'],
            'maybe' => (int) $row['signup_maybe'],
            'confirmed' => (int) $row['signup_confirmed']
        );
        $this->raidteam = $row['raidteam'];
        $this->raidteamname = $row ['team_name'];
        $this->RaidTeamNeeded = (int) $row ['team_needed'];

        unset ($row);

        // fill mychars array
        $rpsignup = new RaidplanSignup();
        $this->mychars = $rpsignup->getmychars($this->id);
        unset($rpsignup);

        // lock signup pane if you have no characters bound to your account
        $this->nochar = count ($this->mychars) == 0 ? true : false;
        $this->locked = $this->nochar;

        // get array of raid roles
        $this->_get_raid_roles();
        // attach signups to roles (available+confirmed)
        $this->raidroles = $this->_get_Signups();
        //get all that signed unavailable
        $this->signoffs = $this->_get_signoffs();

        $this->invite_list = $this->_set_InviteList($this->accesslevel, $this->group_id, $this->group_id_list);

    }

    /**
     *
     */
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
    public function Store_Raidplan()
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
            'signup_yes'			=> (int) $this->signups['yes'],
            'signup_no'				=> (int) $this->signups['no'],
            'signup_maybe'			=> (int) $this->signups['maybe'],
            'signup_confirmed'      => (int) $this->signups['confirmed']
        );

        /*
         * start transaction
         */
        $db->sql_transaction('begin');

        if( $this->id == 0 )
        {
            //insert new
            $sql = 'INSERT INTO ' . RP_RAIDS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_raid);
            $db->sql_query($sql);
            $this->id = $db->sql_nextid();
            $this->raidmessenger(1);
            unset ($sql_raid);

            return 1;

        }
        else
        {
            // update
            $sql = 'UPDATE ' . RP_RAIDS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_raid) . '
		    WHERE raidplan_id = ' . (int) $this->id;
            $db->sql_query($sql);
            $this->raidmessenger(2);
            unset ($sql_raid);

            return 0;

        }
    }

    /**
     * inserts or updates raidroles
     *
     * @param int $mode
     */
    public function store_raidroles($mode = 0)
    {
        global $db;

        foreach($this->raidroles as $role_id => $role)
        {
            if( $mode == 1)
            {
                //insert
                $sql_raidroles = array(
                    'raidplan_id'		=> $this->id,
                    'role_id'			=> $role_id,
                    'role_needed'		=> (int) $role['role_needed']
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
                    'role_needed'		=> (int) $role['role_needed']
                );

                $sql = 'UPDATE ' . RP_RAIDPLAN_ROLES . '
	    		SET ' . $db->sql_build_array('UPDATE', $sql_raidroles) . '
			    WHERE raidplan_id = ' . (int)  $this->id . '
			    AND role_id = ' . $role_id;

                $db->sql_query($sql);
            }
        }

        /*
        * commit transaction
        */
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
     * builds roles property, needed when you make new raid
     * called by constructor only !
     */
    private function _get_roles()
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
        return $this->roles;
    }

    /**
     * sets initial raidroles array
     */
    private function _init_raidplan_roles()
    {
        foreach($this->roles as $id => $role)
        {
            $raidroles[$id]['role_name'] = $role['role_name'];
            $raidroles[$id]['role_color'] = $role['role_color'];
            $raidroles[$id]['role_icon'] = $role['role_icon'];
            $raidroles[$id]['role_needed'] = 0;
            $raidroles[$id]['role_signedup'] = 0;
            $raidroles[$id]['role_confirmed'] = 0;
            $raidroles[$id]['role_confirmations'] =  array();
            $raidroles[$id]['role_signups'] = array();
        }

        return $raidroles;

    }

    /**
     * gets raid roles property array, needed sor displaying signups
     *
     */
    private function _get_raid_roles()
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
            $this->raidroles[$row['role_id']]['role_needed'] = (int) $row['role_needed'];
            $this->raidroles[$row['role_id']]['role_signedup'] = (int) $row['role_signedup'];
            $this->raidroles[$row['role_id']]['role_confirmed'] = (int) $row['role_confirmed'];
            $this->raidroles[$row['role_id']]['role_confirmations'] = $confirmations;
            $this->raidroles[$row['role_id']]['role_signups'] = $signups;
        }
        $db->sql_freeresult($result);
        return $this->raidroles;
    }

    /**
     * Completes the raidroles, selects all signups that have a role, then makes signup objects, returns array of objects to role
     *  0 unavailable
     *  1 maybe
     *  2 available
     *  3 confirmed
     * called by constructor only !
     *
     */
    private function _get_Signups()
    {
        global $db;

        //fill signups array
        foreach ($this->raidroles as $role_id => $role)
        {
            $sql = "select signup_id from " . RP_SIGNUPS . " where raidplan_id = " . $this->id . " and signup_val > 0 and role_id  = " . $role_id;
            $result = $db->sql_query($sql);

            while ($row = $db->sql_fetchrow($result))
            {
                //bind all public object vars of signup class instance to signup array and add to role array
                $rpsignup = new RaidplanSignup();
                $rpsignup->getSignup($row['signup_id']);

                if($rpsignup->signup_val == 1 || $rpsignup->signup_val == 2)
                {
                    // maybe + available
                    $this->raidroles[$role_id]['role_signups'][] = $rpsignup;
                }
                elseif($rpsignup->signup_val == 3)
                {
                    //confirmed
                    $this->raidroles[$role_id]['role_confirmations'][] = $rpsignup;
                }
                unset($rpsignup);
            }

            $db->sql_freeresult($result);
        }

        unset($role_id);
        unset($role);
        unset($row);

        return $this->raidroles;

    }

    /**
     * get all those that signed unavailable
     * 0 unavailable 1 maybe 2 available 3 confirmed
     * called by constructor only !
     */
    private function _get_signoffs()
    {
        global $db;

        $rpsignup = new RaidplanSignup();

        $sql = "select * from " . RP_SIGNUPS . " where raidplan_id = " . $this->id . " and signup_val = 0";
        $result = $db->sql_query($sql);
        $this->signoffs = array();

        while ($row = $db->sql_fetchrow($result))
        {
            $rpsignup->getSignup($row['signup_id']);
            //get all public object vars to signup array and bind to role
            $this->signoffs[] = $rpsignup;
        }
        unset($rpsignup);
        $db->sql_freeresult($result);
        return $this->signoffs;
    }

    /**
     *  raidmessenger
     *
     *  eventhandler for
     *  raidplan add send to all who have a dkp member with points
     *  raidplan update send to raidplan participants
     *  raidplan delete send to raidplan participants
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

        $rpm = new \bbdkp\controller\raidplanner\Raidmessenger;
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
        global $user, $config, $phpbb_root_path, $phpEx ;

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

        global $phpbb_root_path, $phpEx;
        if (!class_exists('\bbdkp\controller\raids\RaidController'))
        {
            require("{$phpbb_root_path}includes/bbdkp/controller/raids/RaidController.$phpEx");
        }
        $RaidController = new RaidController($this->raid_id);
        $RaidController->deleteraider($this->raid_id,$member_id);
        unset($RaidController);
    }

    /**
     * depending on access level invite different phpbb groups.
     *
     * @param int $accesslevel 0, 1 or 2
     * @param string $group_id
     * @param string $group_id_list
     * @return string
     */
    private function _set_InviteList($accesslevel, $group_id, $group_id_list )
    {
        global $db, $user, $phpbb_root_path, $phpEx;
        $invite_list = "";

        switch ($accesslevel)
        {
            case 0:
                // personal raidplan... only raidplan creator is invited
                $invite_list = $this->poster_url;
                break;
            case 1:
                if ($this->group_id != 0)
                {
                    // this is a group raidplan... only phpbb accounts of this group are invited
                    $sql = 'SELECT group_name, group_type, group_colour FROM ' . GROUPS_TABLE . '
								WHERE group_id = ' . $db->sql_escape($group_id);

                    $result = $db->sql_query($sql);
                    $group_data = $db->sql_fetchrow($result);
                    $db->sql_freeresult($result);

                    $temp_list = (($group_data['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $group_data['group_name']] : $group_data['group_name']);
                    $temp_url = append_sid("{$phpbb_root_path}memberlist.$phpEx", "mode=group&amp;g=" . $group_id);
                    $temp_color_start = "";
                    $temp_color_end = "";
                    if ($group_data['group_colour'] !== "")
                    {
                        $temp_color_start = "<span style='color:#" . $group_data['group_colour'] . "'>";
                        $temp_color_end = "</span>";
                    }
                    $invite_list = "<a href='" . $temp_url . "'>" . $temp_color_start . $temp_list . $temp_color_end . "</a>";
                }
                else
                {
                    // multiple groups invited
                    $group_list = explode(',', $group_id_list);
                    $num_groups = sizeof($group_list);
                    for ($i = 0; $i < $num_groups; $i++)
                    {
                        if ($group_list[$i] == "")
                        {
                            continue;
                        }

                        // group raidplan... only phpbb accounts  of specified group are invited
                        $sql = 'SELECT group_name, group_type, group_colour FROM ' . GROUPS_TABLE . '
									WHERE group_id = ' . $db->sql_escape($group_list[$i]);
                        $result = $db->sql_query($sql);
                        $group_data = $db->sql_fetchrow($result);
                        $db->sql_freeresult($result);
                        $temp_list = (($group_data['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $group_data['group_name']] : $group_data['group_name']);
                        $temp_url = append_sid("{$phpbb_root_path}memberlist.$phpEx", "mode=group&amp;g=" . $group_list[$i]);
                        $temp_color_start = "";
                        $temp_color_end = "";
                        if ($group_data['group_colour'] !== "")
                        {
                            $temp_color_start = "<span style='color:#" . $group_data['group_colour'] . "'>";
                            $temp_color_end = "</span>";
                        }

                        if ($invite_list == "")
                        {
                            //at first loop
                            $invite_list = "<a href='" . $temp_url . "'>" . $temp_color_start . $temp_list . $temp_color_end . "</a>";
                        }
                        else
                        {
                            $invite_list .= ", " . "<a href='" . $temp_url . "'>" . $temp_color_start . $temp_list . $temp_color_end . "</a>";
                        }

                    }
                }
                break;
            case 2:
                // public raidplan... everyone is invited
                $invite_list = $user->lang['EVERYONE'];
                break;
        }
        return $invite_list;

    }

}
