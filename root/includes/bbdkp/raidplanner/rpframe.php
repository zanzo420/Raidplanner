<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

// Include the base class
if (!class_exists('calendar'))
{
	require($phpbb_root_path . 'includes/bbdkp/raidplanner/calendar.' . $phpEx);
}

/**
 * implements a calendar frame
 *
 */
class rpframe extends calendar
{
	private $mode = '';
	private $message = '';
	
	/**
	 * 
	 */
	function __construct()
	{
		$this->mode="frame";
		parent::__construct($this->mode);
	}
	
	/**
	 * @see calendar::display()
	 * implements abstract method
	 */
	public function display()
	{	
		$this->displayCalframe();
	}
	
	/**
	 * Displays common Calendar elements, header message
	 * 
	 */
	private function displayCalframe()
	{
		global $config, $user, $template, $db, $phpEx, $phpbb_root_path;
		
		// set WELCOME_MSG
		$sql = 'SELECT announcement_msg, bbcode_uid, bbcode_bitfield, bbcode_options FROM ' . RP_RAIDPLAN_ANNOUNCEMENT;
		$db->sql_query($sql);
		$result = $db->sql_query($sql);
		while ( $row = $db->sql_fetchrow($result) )
		{
			$text = $row['announcement_msg'];
			$bbcode_uid = $row['bbcode_uid'];
			$bbcode_bitfield = $row['bbcode_bitfield'];
			$bbcode_options = $row['bbcode_options'];
		}
		
		$this->message = generate_text_for_display($text, $bbcode_uid, $bbcode_bitfield, $bbcode_options);
		
		$view_mode=request_var("view", "month");
		$content = array(
			"month"	=> $user->lang['MONTH'],
			"week"	=> $user->lang['WEEK'],
			"day"	=> $user->lang['DAY'],
			);
		foreach ($content as $key => $value)
		{
			$template->assign_block_vars('viewoptions', array(
				'KEY' 		=> $key, 
				'VALUE' 	=> $value,
				'SELECTED' 	=> ($view_mode == $key) ? ' selected="selected"' : '', 
			));
		}

		//day dropdown
		for( $i = 1; $i <= $this->days_in_month; $i++ )
		{
			$template->assign_block_vars('dayoptions', array(
					'KEY' 		=> $i,
					'VALUE' 	=> $i,
					'SELECTED' 	=> ( (int) $this->date['day'] == $i ) ? ' selected="selected"' : '',
			));
		}
		
		// month dropdown
		for( $i = 1; $i <= 12; $i++ )
		{
			$template->assign_block_vars('monthoptions', array(
					'KEY' 		=> $i,
					'VALUE' 	=> $user->lang['datetime'][$this->month_names[$i]],
					'SELECTED' 	=> ($this->date['month_no'] == $i ) ? ' selected="selected"' : '', 
			));
		}
		
		//year dropdown
		$temp_year	= gmdate('Y');
		for( $i = $temp_year-1; $i < ($temp_year+5); $i++ )
		{
			$template->assign_block_vars('yearoptions', array(
					'KEY' 		=> $i,
					'VALUE' 	=> $i,
					'SELECTED' 	=> ( (int) $this->date['year'] == $i ) ? ' selected="selected"' : '', 
			));
		}

		// make url of buttons
		if($view_mode === "week")
		{
			$this->date['prev_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-7, $this->date['year'] ));
			$this->date['next_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+7, $this->date['year'] ));
			$this->date['prev_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-7, $this->date['year']));
			$this->date['next_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+7, $this->date['year']));
			$this->date['prev_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-7, $this->date['year']));
			$this->date['next_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+7, $this->date['year']));
			
			// set previous & next links
			$prev_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=week&amp;calD=".$this->date['prev_day']."&amp;calM=".$this->date['prev_month']."&amp;calY=".$this->date['prev_year']);
			$next_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=week&amp;calD=".$this->date['next_day']."&amp;calM=".$this->date['next_month']."&amp;calY=".$this->date['next_year']);
		}
		elseif($view_mode === "day")
		{
		
			$this->date['prev_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year'] ));
			$this->date['next_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year'] ));
			$this->date['prev_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year']));
			$this->date['next_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year']));
			$this->date['prev_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year']));
			$this->date['next_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year']));
			
			$prev_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=day&amp;calD=".$this->date['prev_day']."&amp;calM=".$this->date['prev_month']."&amp;calY=".$this->date['prev_year']);
			$next_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=day&amp;calD=".$this->date['next_day']."&amp;calM=".$this->date['next_month']."&amp;calY=".$this->date['next_year']);
		}
		elseif($view_mode === "raidplan" )
		{
		
			$this->date['prev_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year'] ));
			$this->date['next_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year'] ));
			$this->date['prev_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year']));
			$this->date['next_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year']));
			$this->date['prev_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year']));
			$this->date['next_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year']));
			
			$prev_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;mode=showadd&amp;calD=".$this->date['prev_day']."&amp;calM=".$this->date['prev_month']."&amp;calY=".$this->date['prev_year']);
			$next_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;mode=showadd&amp;calD=".$this->date['next_day']."&amp;calM=".$this->date['next_month']."&amp;calY=".$this->date['next_year']);
		}		
		elseif($view_mode === "month")
		{
			$this->date['prev_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no']-1, $this->date['day'], $this->date['year'] ));
			$this->date['next_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no']+1, $this->date['day'], $this->date['year'] ));
			$this->date['prev_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'] -1, $this->date['day'], $this->date['year']));
			$this->date['next_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'] +1, $this->date['day'], $this->date['year']));
			$this->date['prev_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'] -1, $this->date['day'], $this->date['year']));
			$this->date['next_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'] +1, $this->date['day'], $this->date['year']));
			
			$prev_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calM=".$this->date['prev_month']."&amp;calY=".$this->date['prev_year']);
			$next_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calM=".$this->date['next_month']."&amp;calY=".$this->date['next_year']);
		}
		
		$template->assign_vars(array(
			'CURRENT_TIME'			=> sprintf($user->lang['CURRENT_TIME'], $user->format_date(time(), false, true)), 
			'TZ'					=> $this->timezone,
			'S_PLANNER_RAIDFRAME'	=> true,
			'S_SHOW_WELCOME_MSG'	=> ($config ['rp_show_welcomemsg'] == 1) ? true : false,
			'CALENDAR_PREV'			=> $prev_link,
			'CALENDAR_NEXT'			=> $next_link,
			'WELCOME_MSG'			=> $this->message,
			'FRAME_CALD'			=> $this->date['day'],
			'FRAME_CALM'			=> $this->date['month_no'],
			'FRAME_CALY'			=> $this->date['year'],
			'DAY_VIEW_URL'  		=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=day&amp;calD=".$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']),
			'WEEK_VIEW_URL' 		=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=week&amp;calD=".$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']),
			'MONTH_VIEW_URL' 		=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calD=".$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']),

		));
		
		
	
	}
	
}

?>