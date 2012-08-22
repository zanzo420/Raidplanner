<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.7.0
*/


/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

/**
 * implements Raid signups
 *
 */
class rpsignup
{
	
	public $signup_id=0;
	public $raidplan_id;
	public $poster_id;
	public $poster_name;
	public $poster_colour;
	public $poster_ip;
	
	/**
	 * 0 unavailable 1 maybe 2 available 3 confirmed
	 *
	 * @var int
	 */
	public $signup_val;
	public $signup_time;
	public $signup_count;
	
	public $dkpmemberid;
	public $dkpmembername;
	public $dkmemberpurl;
	public $classname;
	public $imagename;
	public $colorcode;
	public $raceimg;
	public $genderid;
	public $level;
	
	public $dkp_current;
	public $priority_ratio;
	public $lastraid;
	public $attendanceP1;
	
	public $comment;
	public $bbcode = array();
	
	public $roleid;
	public $role_name;
	public $confirm;
	

	
	/**
	 * makes a rpsignup object
	 *
	 * @param int $signup_id  
	 */
	public function getSignup($signup_id, $dkpid=1)
	{
		
		global $db, $config, $phpbb_root_path, $phpEx, $db;
		
		$this->signup_id=$signup_id;
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
		
		$sql='SELECT role_name FROM ' . RP_ROLES . ' WHERE role_id = ' . $this->roleid;
		$result = $db->sql_query($sql);
		$this->role_name = $db->sql_fetchfield('role_name');
		$db->sql_freeresult($result);
		
		// get memberinfo
		$sql_array = array(
	    	'SELECT'    => ' s.*, m.member_id, m.member_name, m.member_level,  
		    				 m.member_gender_id, a.image_female, a.image_male, 
		    				 l.name as member_class , c.imagename, c.colorcode ', 
	    	'FROM'      => array(
		        RP_SIGNUPS	 		=> 's',
		        MEMBER_LIST_TABLE 	=> 'm',
		        CLASS_TABLE  		=> 'c',
		        RACE_TABLE  		=> 'a',
		        BB_LANGUAGE			=> 'l', 
		        
	    	),
	    
		    'WHERE'     =>  " l.attribute_id = c.class_id 
		    				  AND l.language = '" . $config['bbdkp_lang'] . "' 
	    					  AND l.attribute = 'class'
							  AND (m.member_class_id = c.class_id)
							  AND m.member_race_id =  a.race_id  
							  AND s.dkpmember_id = " . (int) $this->dkpmemberid . ' 
							  AND s.raidplan_id = ' . (int) $this->raidplan_id . '
							  AND s.dkpmember_id = m.member_id
							  AND m.game_id = c.game_id and m.game_id = a.game_id and m.game_id = l.game_id' 		    	
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$this->dkpmembername = $row['member_name'];
		$this->classname = $row['member_class'];
		$this->imagename = (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '';
		$this->colorcode = $row['colorcode'];
		$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);
		$this->raceimg = (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '';
		$this->level =  $row['member_level'];
		$this->genderid = $row['member_gender_id'];
		$this->dkp_current = 0;
		$this->priority_ratio = 0;
		$this->lastraid = 0;
		$this->attendanceP1 = 0;
		$this->dkmemberpurl = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=viewmember&amp;" . URI_NAMEID . '=' . $this->dkpmemberid . '&amp;' . URI_DKPSYS . '=' . $dkpid );
		unset ($row);
		
		/* get member dkp for the dkp pool to which the raid plan event belongs. */
		$sql_array = array(
		    'SELECT'    => 	'm.member_dkpid, m.member_status, m.member_lastraid, 
							sum(m.member_earned + m.member_adjustment - m.member_spent - ( ' . max(0, $config['bbdkp_basegp']) . ') ) AS member_current ',
		 
		    'FROM'      => array(
		        MEMBER_DKP_TABLE 	=> 'm',
		        EVENTS_TABLE 		=> 'e',
		        RP_RAIDS_TABLE 		=> 'rp',
		        RP_SIGNUPS 			=> 'rs',
		    	),
		 
		    'WHERE'     =>  ' rs.raidplan_id = rp.raidplan_id 
		    				  and rp.etype_id = e.event_id 
		    				  and e.event_dkpid = m.member_dkpid  
		    				  and rs.raidplan_id = ' . $this->raidplan_id . ' 
		    				  and m.member_id = rs.dkpmember_id and m.member_id = ' . $this->dkpmemberid ,  
		    'GROUP_BY' => 'm.member_dkpid, m.member_status, m.member_lastraid '
		);
		
		if($config['bbdkp_epgp'] == 1)
		{
			$sql_array[ 'SELECT'] .= ', sum(m.member_earned - m.member_raid_decay + m.member_adjustment) AS ep, sum(m.member_spent - m.member_item_decay ) AS gp, 
			CASE WHEN SUM(m.member_spent - m.member_item_decay) = 0 THEN ROUND((m.member_earned - m.member_raid_decay + m.member_adjustment) / ' . max(0, $config['bbdkp_basegp']) .', 2) 
			ELSE ROUND(SUM(m.member_earned - m.member_raid_decay + m.member_adjustment) / SUM(' . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay),2) END AS pr ' ;
		}

		$sql = $db->sql_build_query('SELECT_DISTINCT', $sql_array);
		
		if (($result = $db->sql_query ($sql)))
		{
			while ($row2 = $db->sql_fetchrow($result))
			{
				$this->dkp_current = $row2 ['member_current'];
				if($config['bbdkp_epgp'] == 1)
				{
					$this->priority_ratio = $row2 ['pr'];
				}
				$this->lastraid = $row2 ['member_lastraid'];
				// fetch the 30 day 
				$this->attendanceP1 = raidcount ( true, $row2 ['member_dkpid'], $config['bbdkp_list_p1'], $this->dkpmemberid ,2,false );
			}
		}
		unset ($row2);
		$db->sql_freeresult($result);
	}
	
	
	/**
	 * get all my chars
	 *
	 * @param int $userid
	 * @param int $raidplan_id
	 */
	public function getmychars($rpraidid)
	{
		global $db, $user;
		
		// get memberinfo
		
		$sql_array = array();
		
		$sql_array['SELECT'] = ' s.*,  m.member_id, m.member_name, m.member_level, m.member_gender_id '; 
	    $sql_array['FROM'] 	= array(MEMBER_LIST_TABLE 	=> 'm');
	    $sql_array['LEFT_JOIN'] = array(
			array( 'FROM'	=> array( RP_SIGNUPS => 's'),
				   'ON'	=> 's.dkpmember_id = m.member_id and s.raidplan_id = ' . (int) $rpraidid
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
	 * 
	 * registers signup
	 *
	 * @param unknown_type $raidplan_id
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
		generate_text_for_storage($this->comment, $this->bbcode['uid'], $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);
		
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
	
	public function editsignupcomment($signup_id)
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
	 * @param object $raid_id (pass byref)
	 */
	public function deletesignup($signup_id, rpraid &$thisraid)
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
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_maybe = signup_maybe - 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 WHERE raidplan_id = " . $this->raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return true;
				break;
			case 2:
				//yes
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_yes = signup_yes - 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 WHERE raidplan_id = " . $this->raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return true;
				break; 
			case 3:
				
				//confirmed
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_confirmed = signup_confirmed - 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);
				
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_confirmed = role_confirmed - 1 WHERE raidplan_id = " . $this->raidplan_id .  
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);				
				$db->sql_transaction('commit');
				
				if($thisraid->raid_id > 0)
				{
					//raid was pushed already
					$this->exec_decreasedkp_after_unsign($thisraid);
				}
				
				return true;
				break; 
		}
		
		
		// if already 0 then don't do anything
		return false;
	}
	
	

	/**
	 * if confirmed raider is unsigned after raid was pushed,
	 * decrease dkp points by event standard amount
	 * @param rpraid $raid
	 */
	private function exec_decreasedkp_after_unsign(rpraid &$raid)
	{
		global $phpbb_root_path, $phpEx, $db, $user;
		$dkpid = $raid->eventlist->events[$raid->event_type]['dkpid']; 
		$eventvalue = $raid->eventlist->events[$raid->event_type]['value'];
		$raid_start = $raid->start_time;
		$member_id = $this->dkpmemberid;
		//decrease dkp points
		if (!class_exists('acp_dkp_raid'))
		{
			require("{$phpbb_root_path}includes/acp/acp_dkp_raid.$phpEx");
		}
		$acp_dkp_raid = new acp_dkp_raid();
		$acp_dkp_raid->add_dkp (- $eventvalue, 0, $raid_start ,$dkpid , $member_id);
		
		//now delete raid participation record of that user
		$sql="DELETE FROM " . RAID_DETAIL_TABLE . " WHERE raid_id = " . $raid->id . " AND member_id = " . $member_id;
		$db->sql_query($sql);
	
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
	** ----------------
	** 4) raidplan new signup +
	**   send to raidleader
	** 5) raidplan signup unavail -
	**   send to raidleader
	** 6) raidplan signup confirm ++
	**   send to member 
	**
	*/
	function signupmessenger($trigger, rpraid $raidplan)
	{
		global $user, $config;
		global $phpEx, $phpbb_root_path;

		include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);

		// get recipient data (email, etc)  
		if (!class_exists('raidmessenger'))
		{
			require("{$phpbb_root_path}includes/bbdkp/raidplanner/raidmessenger.$phpEx");
		}
		
		$rpm = new raidmessenger();
		$rpm->get_notifiable_users($trigger, $this->raidplan_id, $this->signup_id);
		
		$emailrecipients = array();
		$messenger = new messenger();

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