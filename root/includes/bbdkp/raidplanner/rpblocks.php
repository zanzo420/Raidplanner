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
 * raidplanner blocks
 *
 */
class rpblocks 
{
	
	public $group_options;
	
	/**
	 * @see calendar::display($x)
	 *
	 * @param int $x
	 */
	public function display()
	{
		global $template, $auth, $user, $db;
	
		// What groups is this user a member of?
	
		/* if raidplan was made by the admin for a hidden group members of the hidden group need 
			to be able to see the raidplan in the calendar */
	
		$sql = 'SELECT g.group_id, g.group_name, g.group_type
				FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
				WHERE ug.user_id = '.$db->sql_escape($user->data['user_id']).'
					AND g.group_id = ug.group_id
					AND ug.user_pending = 0
				ORDER BY g.group_type, g.group_name';
		$result = $db->sql_query($sql);
	
		$this->group_options = '';
		while ($row = $db->sql_fetchrow($result))
		{
			if( $this->group_options != "" )
			{
				$this->group_options .= " OR ";
			}
			$this->group_options .= "group_id = ".$row['group_id']. " OR group_id_list LIKE '%,".$row['group_id']. ",%'";
		}
		$db->sql_freeresult($result);
		
		if ( $auth->acl_get('u_raidplanner_view_raidplans') )
		{
			$this->_display_next_raidplans();
			$this->_display_top_signups();
		}
		else
		{
			$template->assign_vars(array(
				'S_PLANNER_UPCOMING'		=> false,
				'S_PLANNER_TOPSIGNUPS'		=> false,
			));
		}
	}
	
	
	/**
	 * Displays the signups
	 *
	 */
	private function _display_top_signups()
	{
		global $config, $user, $db, $template, $phpEx, $phpbb_root_path;
		// build sql 

		// get top signups
		$sql_array = array(
	    	'SELECT'    => ' count(s.dkpmember_id) as countsignups, s.dkpmember_id, m.member_id, m.member_name, m.member_level,  
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
							  AND s.dkpmember_id = m.member_id
							  AND s.signup_val > 0
							  AND m.game_id = c.game_id and m.game_id = a.game_id and m.game_id = l.game_id", 

			'GROUP_BY'	=>  's.dkpmember_id, m.member_id, m.member_name, m.member_level,  
		    				 m.member_gender_id, a.image_female, a.image_male, 
		    				 l.name, c.imagename, c.colorcode', 
		    
		   	'ORDER_BY'	=> 	'count(s.dkpmember_id) DESC'
		    				 
		    				 
		);
		
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$limit = 10;
		$result = $db->sql_query_limit($sql, $limit, 0);
		while ($row = $db->sql_fetchrow($result))
		{
			$dkpmembername = $row['member_name'];
			$classname = $row['member_class'];
			$imagename = (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '';
			$colorcode = $row['colorcode'];
			$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);
			$raceimg = (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '';
			$level =  $row['member_level'];
			$countsignups =  $row['countsignups'];
			
			$template->assign_block_vars('topsignups', array(
				'COUNTSIGNUPS'		=> $countsignups, 
				'CHARNAME'      	=> $dkpmembername,
				'LEVEL'         	=> $level,
				'CLASS'         	=> $classname,
				'COLORCODE'  		=> $colorcode,
		        'CLASS_IMAGE' 		=> $imagename,  
				'S_CLASS_IMAGE_EXISTS' => (strlen($imagename) > 1) ? true : false,
		       	'RACE_IMAGE' 		=> $raceimg,  
				'S_RACE_IMAGE_EXISTS' => (strlen($raceimg) > 1) ? true : false, 
			));
			
	
		}
		
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'S_PLANNER_TOPSIGNUPS'		=> true,
		));
	}
	
	/**
	 * displays the next x number of upcoming raidplans 
	 *
	 * @param string $this->group_options
	 */
	private function _display_next_raidplans()
	{
		global $config, $user, $db, $template, $phpEx, $phpbb_root_path;
		// build sql 
		$sql_array = array(
   			'SELECT'    => 'r.raidplan_id ',   
			'FROM'		=> array(RP_RAIDS_TABLE => 'r'), 
			'WHERE'		=>  '(raidplan_access_level = 2 
					   OR (r.poster_id = '. $db->sql_escape($user->data['user_id']).' ) OR (r.raidplan_access_level = 1 AND ('. $this->group_options.')) )  
					  AND (r.raidplan_start_time >= '. $db->sql_escape(time() ) . " )",
			'ORDER_BY'	=> 'r.raidplan_start_time ASC'
		);
		
		$sql = $db->sql_build_query('SELECT', $sql_array);
		
		$result = $db->sql_query_limit($sql, $config['rp_display_next_raidplans'], 0);
		if (!class_exists('rpraid', false))
		{
			include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpraid.' . $phpEx);
		}
					
		while ($row = $db->sql_fetchrow($result))
		{
			
			unset($rpraid);
			$rpraid = new rpraid($row['raidplan_id']);
			if(strlen( $rpraid->eventlist->events[$rpraid->event_type]['imagename'] ) > 1)
			{
				$eventimg = $phpbb_root_path . "images/event_images/" . $rpraid->eventlist->events[$rpraid->event_type]['imagename'] . ".png";
				
			}
			else 
			{
				$eventimg = $phpbb_root_path . "images/event_images/dummy.png";
			}
			
			$template->assign_block_vars('upcoming', array(
				'RAID_ID'				=> $rpraid->id,
				'EVENTNAME'			 	=> $rpraid->eventlist->events[$rpraid->event_type]['event_name'], 
				'EVENT_URL'  			=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=".$rpraid->id), 
				'EVENT_ID'  			=> $rpraid->id,
				'COLOR' 				=> $rpraid->eventlist->events[$rpraid->event_type]['color'],
				'SUBJECT'				=> censor_text($rpraid->subject),
				'IMAGE' 				=> $eventimg, 
				'START_TIME'			=> $user->format_date($rpraid->start_time, $config['rp_date_format'], true),
				'END_TIME' 				=> $user->format_date($rpraid->end_time, $config['rp_time_format'], true),
				'DISPLAY_BOLD'			=> ($user->data['user_id'] == $rpraid->poster) ? true : false,
			));
		}
		$db->sql_freeresult($result);
		
		$template->assign_vars(array(
			'S_PLANNER_UPCOMING'		=> true,
		));
				
	}
			
}

?>