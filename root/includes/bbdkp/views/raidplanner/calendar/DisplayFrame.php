<?php
/**
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.12.0
*/
namespace bbdkp\views\raidplanner;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

// Include the base class
if (!class_exists('\bbdkp\views\raidplanner\RaidCalendar'))
{
    require($phpbb_root_path . 'includes/bbdkp/views/raidplanner/calendar/RaidCalendar.' . $phpEx);
}
/**
 * implements a calendar frame
 *
 */
class DisplayFrame extends RaidCalendar
{
	private $view_mode = '';
	private $Message = '';
	
	/**
	 * 
	 */
	function __construct($view_mode)
	{
		$this->view_mode = $view_mode;
		parent::__construct($this->view_mode);
	}
	
	/**
	 * @see calendar::display()
	 * implements abstract method
	 */
	public function display()
	{
        $this->getMessage();
        $this->DisplayCalendar();
	}

    /**
     * Set Welcome message
     */
    private function getMessage()
    {
        global $db;
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
        $this->Message = \generate_text_for_display($text, $bbcode_uid, $bbcode_bitfield, $bbcode_options);
    }

	/**
	 * Displays common Calendar elements, header message
	 * 
	 */
	private function DisplayCalendar()
	{
		global $config, $user, $template, $phpEx, $phpbb_root_path;
		
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
		if($this->view_mode === "raidplan" )
		{

			$this->date['prev_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year'] ));
			$this->date['next_day'] = gmdate("d", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year'] ));
			$this->date['prev_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year']));
			$this->date['next_month'] = gmdate("n", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year']));
			$this->date['prev_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']-1, $this->date['year']));
			$this->date['next_year']  = gmdate("Y", gmmktime(0,0,0, $this->date['month_no'], $this->date['day']+1, $this->date['year']));

			$prev_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=showadd&amp;calD=".$this->date['prev_day']."&amp;calM=".$this->date['prev_month']."&amp;calY=".$this->date['prev_year']);
			$next_link = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=showadd&amp;calD=".$this->date['next_day']."&amp;calM=".$this->date['next_month']."&amp;calY=".$this->date['next_year']);
		}
		elseif($this->view_mode === "day")
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
		elseif($this->view_mode === "week")
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
		elseif($this->view_mode === "month")
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
			'WELCOME_MSG'			=> $this->Message,
			'FRAME_CALD'			=> $this->date['day'],
			'FRAME_CALM'			=> $this->date['month_no'],
			'FRAME_CALY'			=> $this->date['year'],
			'DAY_VIEW_URL'  		=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=day&amp;calD=".$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']),
			'WEEK_VIEW_URL' 		=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=week&amp;calD=".$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']),
			'MONTH_VIEW_URL' 		=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calD=".$this->date['day']."&amp;calM=".$this->date['month_no']."&amp;calY=".$this->date['year']),

		));
		
		
	
	}
	
}