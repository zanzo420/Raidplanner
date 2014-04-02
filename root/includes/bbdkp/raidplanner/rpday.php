<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2009 alightner
* @copyright (c) 2011 Sajaki : refactoring, adapting to bbdkp
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.10.0
*/
namespace bbdkp\raidplanner;


/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

// Include the base class
if (!class_exists('\bbdkp\raidplanner\RaidCalendar'))
{
	require($phpbb_root_path . 'includes/bbdkp/raidplanner/RaidCalendar.' . $phpEx);
}

/**
 * implements raidplan day view
 *
 */
class rpday extends RaidCalendar
{
	private $mode = '';
	

	function __construct()
	{
		$this->mode="day";
		parent::__construct($this->mode);
	}
	
	/**
	 * @see calendar::display()
	 *
	 */
	public function display()
	{
		global $auth, $user, $config, $template, $phpEx, $phpbb_root_path;
		
		$calendar_header_txt = sprintf($user->lang['LOCAL_DATE_FORMAT'], 
					$this->date['dayname'],
					$user->lang['datetime'][$this->date['month']], 
					$this->date['day'],
					$this->date['year'] );


		$hour_mode = $config['rp_hour_mode'];

		// add an url to add raids ?
		$add_raidplan_url = "";
		$addlink = false;
		if ( $auth->acl_gets('u_raidplanner_create_public_raidplans', 'u_raidplanner_create_group_raidplans', 'u_raidplanner_create_private_raidplans') )
		{
				
			$add_raidplan_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;mode=showadd&amp;calD=".
					$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']);
		
			if( (int) $this->date['month_no'] > (int) date('m') || ( (int) $this->date['month_no']  == (int) date('m')  && (int) $this->date['day'] >= (int) date('d') )
					|| (int) $this->date['year'] > (int) date('Y')
			)
			{
				$addlink = true;
			}
		}
		
		$calendar_days['BIRTHDAYS'] = "";
		if ( $auth->acl_get('u_raidplanner_view_raidplans') && $auth->acl_get('u_viewprofile'))
		{
			$birthdays = $this->generate_birthday_list( $this->Get1DoM($this->timestamp), $this->GetLDoM($this->timestamp));
			if(isset($birthdays[$this->date['day']]))
			{
				$birthdays = $birthdays[$this->date['day']]['bdays'];
				$template->assign_block_vars('all_day', 
					array(
						'BIRTHDAYS' => (string) $birthdays,
					));
			}
		}

		// Is the user able to view ANY raidplans?
		if ( $auth->acl_get('u_raidplanner_view_raidplans') )
		{
			// get raid info
			$raidplan_output = array();
			if (!class_exists('\bbdkp\raidplanner\Raidplan'))
			{
				include($phpbb_root_path . 'includes/bbdkp/raidplanner/raidplan.' . $phpEx);
			}
			$raidplan = new Raidplan();
			// get all raids on this day
			$raidplan_output = $raidplan->GetRaidinfo($this->date['month_no'], $this->date['day'], $this->date['year'], $this->group_options, "day");
		}
		
		/* assemble events */ 
		/* loop every hour */
		for( $i = 0; $i < 24; $i++ )
		{
			if( $hour_mode == 12 )
			{
				$time_header['TIME'] = $i % 12;
				if( $time_header['TIME'] == 0 )
				{
					$time_header['TIME'] = 12;
				}
				$time_header['AM_PM'] = $user->lang['PM'];
				if( $i < 12 )
				{
					$time_header['AM_PM'] = $user->lang['AM'];
				}
			}
			else
			{
				$o = "";
				if($i < 10 )
				{
					$o="0";
				}
				$time_header['TIME'] = $o . $i;
				$time_header['AM_PM'] = "";
			}
			
			$raidslots = array();
			
			foreach($raidplan_output as $key => $raid )
			{
				if(substr($key, 0,2) == $time_header['TIME'])
				{
					$raidslots[$time_header['TIME']] = 1; 
				}
			}
			
			$template->assign_block_vars('time_slots', 
				array(
					'TIME'  	=> $time_header['TIME'], 
					'AM_PM' 	=> $time_header['AM_PM'],
					'S_MARKED'	=> (array_key_exists($time_header['TIME'],$raidslots) == true) ? true : false
				));
		
			// loop the raidplans for each hour
			foreach($raidplan_output as $key => $raid )
			{
				//does the raid hour match this hour slot?  
				if (substr($key, 0,2) == $time_header['TIME'])
				{
					// color this timeslot as taken.
					
					
					//add it to template
					$template->assign_block_vars('time_slots.raidplans', $raid['raidinfo']);
					
					foreach($raid['userchars'] as $key => $char)
					{
						$template->assign_block_vars('time_slots.raidplans.userchars', $char);
					}
					unset($char);
					unset($key);
					
					foreach($raid['raidroles'] as $key => $raidrole)
					{
						$template->assign_block_vars('time_slots.raidplans.raidroles', $raidrole);
					}
					unset($raidrole);
					unset($key);
				}
			}
		}
		
		$template->assign_vars(array(
			'CALENDAR_HEADER'	=> $calendar_header_txt,
			'BIRTHDAYS'			=> $calendar_days['BIRTHDAYS'],
			'DAY'				=> $this->date['day'], 
			'ADD_LINK'			=> $add_raidplan_url,
			'S_ADD_LINK'		=> $addlink,
			'S_PLANNER_DAY'		=> true,		
			'EVENT_COUNT'		=> count($raidplan_output),
		));
		
		
	}
}

?>