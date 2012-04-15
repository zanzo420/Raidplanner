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
		
		// create RP_VIEW_OPTIONS
		$view_mode=request_var('view', 'month');
		$this->mode_sel_code = "<select name='view' id='view'>";
		$this->mode_sel_code .= "<option value='month'>".$user->lang['MONTH']."</option>";
		$this->mode_sel_code .= "<option value='week'>".$user->lang['WEEK']."</option>";
		$this->mode_sel_code .= "<option value='day'>".$user->lang['DAY']."</option>";
		$this->mode_sel_code .= "</select>";
				
		$temp_find_str = "value='".$view_mode."'>";
		$temp_replace_str = "value='".$view_mode."' selected='selected'>";
		$this->mode_sel_code = str_replace( $temp_find_str, $temp_replace_str, $this->mode_sel_code );
		
		// month dropdown
		$this->month_sel_code  = "<select name='calM' id='calM'>\n";
		for( $i = 1; $i <= 12; $i++ )
		{
			$selected = ($this->date['month_no'] == $i ) ? ' selected="selected"' : '';
			$this->month_sel_code .= '<option value="'.$i.'"'.$selected.'>'.$user->lang['datetime'][$this->month_names[$i]].'</option>';
		}
		$this->month_sel_code .= "</select>";

		//day dropdown
		$this->day_sel_code  = "<select name='calD' id='calD'>";
		$begin = 1;
		for( $i = $begin; $i <= $this->days_in_month; $i++ )
		{
			$selected = ( (int) $this->date['day'] == $i ) ? ' selected="selected"' : '';
			$this->day_sel_code .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
		}
		$this->day_sel_code .= "</select>";
		
		//year dropdown
		$temp_year	=	gmdate('Y');
		$this->year_sel_code  = "<select name='calY' id='calY'>";
		for( $i = $temp_year-1; $i < ($temp_year+5); $i++ )
		{
			$selected = ( (int) $this->date['year'] == $i ) ? ' selected="selected"' : '';
			$this->year_sel_code .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
		}
		$this->year_sel_code .= "</select>";

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
			'MODE_VIEW_OPTIONS' 	=> $this->mode_sel_code, 
			'CALENDAR_VIEW_OPTIONS' => $this->month_sel_code.' '.$this->day_sel_code.' '.$this->year_sel_code,
			'CALENDAR_PREV'			=> $prev_link,
			'CALENDAR_NEXT'			=> $next_link,
			'WELCOME_MSG'			=> $this->message,
		));
		
		
	
	}
	
}

?>