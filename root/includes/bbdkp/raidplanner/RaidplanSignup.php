<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.10.0
*/
namespace bbdkp\raidplanner;
use bbdkp\raidplanner\raidmessenger;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}
// Include the member class
if (!class_exists('\bbdkp\controller\members\Members'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/members/Members.$phpEx");
}
// add points class
if (!class_exists('\bbdkp\controller\points\Points'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/points/Points.$phpEx");
}

/**
 * implements Raid signups
 *
 */
class RaidplanSignup
{
	
	protected $signup_id=0;
    protected $raidplan_id;
    protected $poster_id;
    protected $poster_name;
    protected $poster_colour;
    protected $poster_ip;
	
	/**
	 * 0 unavailable 1 maybe 2 available 3 confirmed
	 *
	 * @var int
	 */
    protected $signup_val;
    protected $signup_time;
    protected $signup_count;

    protected $dkpmemberid;
    protected $dkpmembername;
    protected $dkmemberpurl;
    protected $classname;
    protected $imagename;
    protected $colorcode;
    protected $raceimg;
    protected $genderid;
    protected $level;

    protected $dkp_current;
    protected $priority_ratio;
    protected $lastraid;
    protected $attendanceP1;

    protected $comment;
    protected $bbcode = array();

    protected $roleid;
    protected $role_name;
    protected $confirm;

    private $Points;
    private $Member;

    public function __construct()
    {
        $this->Points = new \bbdkp\controller\points\Points();
        $this->Member = new \bbdkp\controller\members\Members();
    }

    /**
     * Signup class property getter
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
     * makes a Signup object
     *
     * @param $signup_id
     * @param int $dkpid
     */
    public function getSignup($signup_id, $dkpid=1)
	{
		
		global $db, $config, $phpbb_root_path, $phpEx, $db;
		
		$this->signup_id = $signup_id;
		$sql = "select * from " . RP_SIGNUPS . " where signup_id = " . $this->signup_id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		if( !$row )
		{
			trigger_error( 'INVALID_SIGNUP' );
		}
		$db->sql_freeresult($result);
		
		$this->raidplan_id = $row['raidplan_id'];
		$this->poster_id = $row['poster_id'];
		$this->poster_name = $row['poster_name'];
		$this->poster_colour = $row['poster_colour'];
		$this->poster_ip = $row['poster_ip'];
		$this->signup_time = $row['post_time'];		
		$this->signup_val = $row['signup_val'];
		$this->signup_count = $row['signup_count'];
		$this->comment = $row['signup_detail'];
		$this->bbcode['bitfield']= $row['bbcode_bitfield'];
		$this->bbcode['uid']= $row['bbcode_uid'];
		$this->confirm = $row['role_confirm'];
		$this->dkpmemberid = $row['dkpmember_id'];
		$this->roleid = $row['role_id'];
        unset ($row);

        $sql='SELECT role_name FROM ' . RP_ROLES . ' WHERE role_id = ' . $this->roleid;
		$result = $db->sql_query($sql);
		$this->role_name = $db->sql_fetchfield('role_name');
		$db->sql_freeresult($result);

        $this->Member->member_id = (int) $this->dkpmemberid;
        $this->Member->Getmember();
		$this->dkpmembername = $this->Member->member_name;
		$this->classname = $this->Member->member_class;
		$this->imagename = $this->Member->class_image;
		$this->colorcode = $this->Member->colorcode;
		$this->raceimg = $this->Member->race_image;
		$this->level =  $this->Member->member_level;
		$this->genderid = $this->Member->member_gender_id;

        $this->Points->member_id = (int) $this->dkpmemberid;
        $this->Points->dkpid =  $this->dkpmemberid;
        $this->dkp_current = $this->Points->total_net;
		$this->priority_ratio = $this->Points->pr_net;
		$this->lastraid = $this->Points->lastraid;
		$this->attendanceP1 = 0;
		$this->dkmemberpurl = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=viewmember&amp;" . URI_NAMEID . '=' . $this->dkpmemberid . '&amp;' . URI_DKPSYS . '=' . $this->dkpmemberid );

	}
	
	
	/**
	 * get all my chars
	 *
	 * @param int $userid
	 * @param int $raidplan_id
	 */
	public function getmychars($raidplanid)
	{
		global $db, $user;
		
		// get memberinfo
		
		$sql_array = array();
		
		$sql_array['SELECT'] = ' s.*,  m.member_id, m.member_name, m.member_level, m.member_gender_id '; 
	    $sql_array['FROM'] 	= array(MEMBER_LIST_TABLE 	=> 'm');
	    $sql_array['LEFT_JOIN'] = array(
			array( 'FROM'	=> array( RP_SIGNUPS => 's'),
				   'ON'	=> 's.dkpmember_id = m.member_id and s.raidplan_id = ' . (int) $raidplanid
				)
		);
	    $sql_array['WHERE'] = 'm.member_rank_id !=90 AND m.phpbb_user_id =  ' . $user->data['user_id']; 		    	
		
		$mychars = array();
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$mychars[] = array(
				'is_signedup'  => (isset($row['signedup_val']) ? 1: 0),
				'signedup_val' => (isset($row['signedup_val']) ? $row['signedup_val']: 0), 
				'role_id' 	   => (isset($row['role_id']) ? $row['role_id'] : ''), 
				'id' 		   => $row['member_id'], 
				'name' 		   => $row['member_name'] );	
		}
		$db->sql_freeresult($result);
		return $mychars;
	}

    /**
     * registers signup
     *
     * @param $raidplan_id
     * @return bool
     */
    public function signup($raidplan_id)
	{
		global $user;
		
		$this->raidplan_id = $raidplan_id;
		
		$this->poster_id = $user->data['user_id'];
		$this->poster_name = $user->data['username'];
		$this->poster_colour = $user->data['user_colour'];
		$this->poster_ip = $user->ip;
		$this->signup_time = time() - $user->timezone - $user->dst;
		
		// 0 unavailable 1 maybe 2 available 3 confirmed
		$this->signup_val = request_var('signup_val'. $raidplan_id, 2);
		$this->roleid = request_var('signuprole'. $raidplan_id, 0);   
		$this->dkpmemberid = request_var('signupchar'. $raidplan_id, 0);
		$this->comment = utf8_normalize_nfc(request_var('signup_detail'. $raidplan_id, '', true));
		$this->signup_count = 1;
		
		$this->bbcode['uid'] = $this->bbcode['bitfield'] = $options = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($this->comment, $this->bbcode['uid'],
                $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);
		
		$this->storesignup();
		
		return true;
	}
	
	/**
	 * stores a signup
	 *
	 */
	private function storesignup()
	{
		global $user, $db;
		
		$sql_signup = array(
			'raidplan_id'	=> $this->raidplan_id,
			'poster_id'		=> $this->poster_id, 
			'poster_name'	=> $this->poster_name,
			'poster_colour'	=> $this->poster_colour,
			'poster_ip'		=> $this->poster_ip,
			'post_time'		=> $this->signup_time,
			'signup_val'	=> $this->signup_val,
			'signup_count'	=> $this->signup_count,
			'signup_detail'	=> $this->comment,
			'bbcode_bitfield' 	=> $this->bbcode['bitfield'],
			'bbcode_uid'		=> $this->bbcode['uid'],
			'bbcode_options'	=> 7, 
			'dkpmember_id'	=> $this->dkpmemberid, 
			'role_id'		=> $this->roleid
			
			);
		
		/*
		 * start transaction
		 */
		$db->sql_transaction('begin');
			
		if($this->signup_id == 0)
		{
			//prevent double submit, check if signup for char already exists (ip+charname), ignore if it does 
			$sql = "SELECT count(*) as doublecheck from " . RP_SIGNUPS . " WHERE raidplan_id = " . $this->raidplan_id . 
			" and poster_ip = '" . $this->poster_ip . "' 
			  and dkpmember_id = '" .  $this->dkpmemberid . "'";
			$result = $db->sql_query($sql);
			$check = (int) $db->sql_fetchfield('doublecheck');
			$db->sql_freeresult($result);
			if($check == 0)
			{
				//insert new
				$sql = 'INSERT INTO ' . RP_SIGNUPS . ' ' . $db->sql_build_array('INSERT', $sql_signup);
				$db->sql_query($sql);	
				$signup_id = $db->sql_nextid();
				$this->signup_id = $signup_id;
				
				switch ( (int) $this->signup_val)
				{
					case 0:
						// no
						$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1 WHERE raidplan_id = " . $this->raidplan_id;
						$db->sql_query($sql);
						 break;
					case 1:
						// maybe
						$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_maybe = signup_maybe + 1 WHERE raidplan_id = " . $this->raidplan_id;
						$db->sql_query($sql);
						
						$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup + 1 WHERE raidplan_id = " . $this->raidplan_id .  
						" AND role_id = " . $this->roleid ;
						$db->sql_query($sql);
						break;
					case 2:
						//yes
						$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_yes = signup_yes + 1 WHERE raidplan_id = " . $this->raidplan_id;
						$db->sql_query($sql);
						
						$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup + 1 WHERE raidplan_id = " . $this->raidplan_id .  
					" AND role_id = " . $this->roleid ;
						
						$db->sql_query($sql);
						break; 
				}
			
			}
			else 
			{
				$sql = "SELECT signup_id from " . RP_SIGNUPS . " WHERE raidplan_id = " . $this->raidplan_id . 
				" and poster_ip = '" . $this->poster_ip . "' 
				  and dkpmember_id = '" .  $this->dkpmemberid . "'";
				$result = $db->sql_query($sql);
				$check = (int) $db->sql_fetchfield('signup_id');
			
				$this->getSignup($check);
				trigger_error(sprintf($user->lang['USER_ALREADY_SIGNED_UP'], $this->dkpmembername));
			}
		}

		unset ($sql_signup);
		
		$db->sql_transaction('commit');
		$this->getSignup($this->signup_id);
		return true;
	}

    /**
     * Edit my signup comment
     *
     * @param $signup_id
     * @return bool
     */
    public function editSignupComment($signup_id)
	{
		global $db;
		//make object
		$this->getSignup($signup_id);
		$message = utf8_normalize_nfc(request_var('signup_detail_' . $this->raidplan_id . '_' . $this->signup_id , '', true));
		$this->bbcode['uid'] = $this->bbcode['bitfield'] = $options = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($message, $this->bbcode['uid'], $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);
		$db->sql_transaction('begin');
		$sql_signup = array(
			'signup_detail'		=> $message,
			'bbcode_bitfield' 	=> $this->bbcode['bitfield'],
			'bbcode_uid'		=> $this->bbcode['uid'],
			'bbcode_options'	=> 7, 
			);
		$sql = 'UPDATE ' . RP_SIGNUPS . ' SET ' . $db->sql_build_array('UPDATE', $sql_signup) . ' WHERE signup_id = ' . (int) $this->signup_id;
		unset($sql_signup);
		$db->sql_query($sql);
		$db->sql_transaction('commit');
		
		return true;

	}
		
	
	/**
	 * requeues a deleted signup
	 *
	 * @param int $signup_id
	 */
	public function requeuesignup($signup_id)
	{
		global $db;
		//make object
		$this->getSignup($signup_id);
		$this->signup_sync();
		switch ( (int) $this->signup_val)
		{
			case 0:
				$db->sql_transaction('begin');
				// decrease signup_no, set as maybe
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no - 1, signup_maybe = signup_maybe + 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);
				
				// set new role
				$this->roleid = request_var('signuprole_' . $this->raidplan_id . '_' .  (int) $this->signup_id , 0);    
				// assign new role
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup + 1 WHERE raidplan_id = " . $this->raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 1, role_id = ' . $this->roleid . ' WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);

				//edit the comment
				$this->comment = utf8_normalize_nfc(request_var('signup_detail_' . $this->raidplan_id . '_' . $this->signup_id , '', true));
				
				$this->bbcode['uid'] = $this->bbcode['bitfield'] = $options = ''; // will be modified by generate_text_for_storage
				$allow_bbcode = $allow_urls = $allow_smilies = true;
				generate_text_for_storage($this->comment, $this->bbcode['uid'], $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);
				
				if($this->comment != '')
				{
					$sql = 'UPDATE ' . RP_SIGNUPS . " SET signup_detail = '" .  $db->sql_escape($this->comment) . "' WHERE signup_id = " . (int) $this->signup_id;
					$db->sql_query($sql);
				}
				$db->sql_transaction('commit');
				
				return true;
				break;
		}
		
		// if already >0 then don't do anything
		return false;
		
	}
	
	/**
	 * delete this signup and change to not available
	 * 
	 * @param int $signup_id
	 * @param int $raidplan_id
	 * 
	 * @return int
	 */
	public function deletesignup($signup_id, $raidplan_id)
	{
		global $db;
		
		//make object
		$this->getSignup($signup_id);
		$this->signup_sync();
		
		switch ( (int) $this->signup_val)
		{
			case 1:
				// maybe
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_maybe = signup_maybe - 1 WHERE raidplan_id = " . $raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 WHERE raidplan_id = " . $raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return 1;
				break;
			case 2:
				//yes
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_yes = signup_yes - 1 WHERE raidplan_id = " . $raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 WHERE raidplan_id = " . $raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return 2;
				break; 
			case 3:
				
				//confirmed
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_confirmed = signup_confirmed - 1 WHERE raidplan_id = " . $raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_confirmed = role_confirmed - 1 WHERE raidplan_id = " . $raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);				
				$db->sql_transaction('commit');
				return 3;
				break; 
		}
		
		
		// if already 0 then don't do anything
		return 0;
	}
	
	/**
	 * synchronises raidplan stats 
	 * 
	 */
	private function signup_sync()
	{
		global $db;
		$db->sql_transaction('begin');
		$sql = "update " . RP_RAIDS_TABLE . " set signup_no = (select count(*) from " . RP_SIGNUPS . 
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 0) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$sql = "update " . RP_RAIDS_TABLE . " set signup_maybe = (select count(*) from " . RP_SIGNUPS . 
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 1) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$sql = "update " . RP_RAIDS_TABLE . " set signup_yes = (select count(*) from " . RP_SIGNUPS . 
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 2) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$sql = "update " . RP_RAIDS_TABLE . " set signup_confirmed = (select count(*) from " . RP_SIGNUPS . 
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 3) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$db->sql_transaction('commit');
	}

	
	/**
	 * confirms a signup
	 *
	 * @param int $signup_id
	 */
	public function confirmsignup($signup_id)
	{
		global $db;
		//make object
		$this->getSignup($signup_id);
		$this->signup_sync();
		switch ( (int) $this->signup_val)
		{
			case 1:
				// maybe
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_maybe = signup_maybe - 1, signup_confirmed = signup_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1, role_confirmed = role_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 3, role_confirm = 1 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return true;
				break;
				
			case 2:	
				// yes
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_yes = signup_yes - 1, signup_confirmed = signup_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 , role_confirmed = role_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 3, role_confirm = 1 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return true;
				break;
		}
		
		// if already >0 then don't do anything
		return false;
		
	}

	/* signupmessenger
	**
	** eventhandler for
	** 4) raidplan new signup +
	**   send to raidleader
	** 5) raidplan signup unavail -
	**   send to raidleader
	** 6) raidplan signup confirm ++
	**   send to member 
	**
	*/
	function signupmessenger($trigger, Raidplan $raidplan)
	{
		global $user, $config;
		global $phpEx, $phpbb_root_path;

		include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);

		// get recipient data (email, etc)  
		if (!class_exists('\bbdkp\raidplanner\raidmessenger'))
		{
			require("{$phpbb_root_path}includes/bbdkp/raidplanner/raidmessenger.$phpEx");
		}
		
		$rpm = new raidmessenger();
		$rpm->get_notifiable_users($trigger, $this->raidplan_id, $this->signup_id);
		
		$emailrecipients = array();
		$messenger = new \messenger();

		foreach($rpm->send_user_data as $id => $row)
		{
			$data=array();
			// get template
			switch ($trigger)
			{
				case 4:
					// send signup to RL				
					$messenger->template('signup_new', $row['user_lang']);
					$subject =  '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['NEWSIGN'] . ': ' . $raidplan->eventlist->events[$raidplan->event_type]['event_name'] . ' ' .$user->format_date($raidplan->start_time, $config['rp_date_time_format'], true);
					$data['address_list'] = array('u' => array($raidplan->poster => 'to'));
					
					break;
				case 5:
					// send confirmation to RL and raider
					$messenger->template('signup_confirm', $row['user_lang']);
					$subject = '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['CONFIRMSIGN'] . ': ' . $raidplan->eventlist->events[$raidplan->event_type]['event_name'] . ' ' . $user->format_date($raidplan->start_time, $config['rp_date_time_format'], true);
					$data['address_list'] = array('u' => 
						array(
							$row['user_id'] => 'to'
						));
					
					break;						
				case 6:
					// send cancellation to RL and raider
					$messenger->template('signup_unsign', $row['user_lang']);
					$subject = '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['UNSIGNED'] . ': ' . $raidplan->eventlist->events[$raidplan->event_type]['event_name'] . ' ' . $user->format_date($raidplan->start_time, $config['rp_date_time_format'], true);
					$data['address_list'] = array('u' => 
						array(
							$row['user_id'] => 'to'
						));
					break;						
			}
		   $userids = array($raidplan->poster);
		   $rlname = array();
		   user_get_id_name($userids, $rlname);
		   
		   $messenger->assign_vars(array(
		   		'RAIDLEADER'		=> $rlname[$raidplan->poster],
				'EVENT_SUBJECT'		=> $subject, 
		   		'SIGNUP_TIME'		=> $user->format_date($this->signup_time, $config['rp_date_time_format'], true),
				'USERNAME'			=> htmlspecialchars_decode($user->data['username']),
		   		'RAIDER'			=> $this->dkpmembername, 
		   		'EVENT'				=> $raidplan->eventlist->events[$raidplan->event_type]['event_name'], 
		   		'ROLE'				=> $this->role_name, 
				'INVITE_TIME'		=> $user->format_date($raidplan->invite_time, $config['rp_date_time_format'], true),
				'START_TIME'		=> $user->format_date($raidplan->start_time, $config['rp_date_time_format'], true),
				'END_TIME'			=> $user->format_date($raidplan->end_time, $config['rp_date_time_format'], true),
				'TZ'				=> $user->lang['tz'][(int) $user->data['user_timezone']],
				'U_RAIDPLAN'		=> generate_board_url() . "/dkp.$phpEx?page=planner&amp;view=raidplan&amp;raidplanid=".$raidplan->id
			));
			
			$messenger->msg = trim($messenger->tpl_obj->assign_display('body'));
			$messenger->msg = str_replace("\r\n", "\n", $messenger->msg);
			
		    $messenger->msg = utf8_normalize_nfc($messenger->msg);
	   		$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
	   		$allow_bbcode = $allow_smilies = $allow_urls = true;
	   		generate_text_for_storage($messenger->msg, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	   		$messenger->msg = generate_text_for_display($messenger->msg, $uid, $bitfield, $options); 
			
			$data['from_user_id']      = $user->data['user_id'];
		    $data['from_username']     = $user->data['username'];
		    $data['icon_id']           = 0;
		    $data['from_user_ip']      = $user->data['user_ip'];
		    $data['enable_bbcode']     = true;
		    $data['enable_smilies']    = true;
		    $data['enable_urls']       = true;
		    $data['enable_sig']        = true;
		    $data['message']           = $messenger->msg; 
		    $data['bbcode_bitfield']   = $this->bbcode['bitfield'];
		    $data['bbcode_uid']        = $this->bbcode['uid'];
			
	   
			if($config['rp_pm_signup'] == 1 &&  (int) $row['user_allow_pm'] == 1)
			{
				// send a PM
				submit_pm('post',$subject, $data, false);
			}
			
			if($config['rp_email_signup'] == 1 && $row['user_email'] != '')
			{
				//send email, reuse messenger object
			   $email = $messenger;
			   $emailrecipients[]=$row['username'];
			   $email->to($row['user_email'], $row['username']);
			   $email->anti_abuse_headers($config, $user);
			   $email->send(0);
			}
				
		}
		
		if($config['rp_email_signup'] == 1 && isset($email))
		{
			$email->save_queue();
			$emailrecipients = implode(', ', $emailrecipients);
			add_log('admin', 'LOG_MASS_EMAIL', $emailrecipients);
		}
		
		
	}
	
	

}

?>