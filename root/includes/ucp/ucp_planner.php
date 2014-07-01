<?php
/**
* This is the user interface for the ucp planner integration
*
* @package ucp
* @copyright (c) 2011 bbDKP
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @author Sajaki
* @version 1.0
*/
use bbdkp\controller\raidplanner\Raidplan;
use bbdkp\controller\raidplanner\RaidplanSignup;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') )
{
    exit;
}
@define('IN_BBDKP', true);

if (!class_exists('\bbdkp\controller\raidplanner\RaidplanSignup'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/raidplanner/RaidplanSignup.$phpEx");
}

if (!class_exists('Raidplan'))
{
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/Raidplan.' . $phpEx);
}

class ucp_planner
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpEx;

		$user->add_lang(array('mods/raidplanner', 'mods/dkp_common'));

        if ( !function_exists('group_memberships') )
        {
            include_once($phpbb_root_path . 'includes/functions_user.'.$phpEx);
        }

	    // get the groups of which this user is part of.
	    $groups = group_memberships(false,$user->data['user_id']);
	    $group_options = "";
	    // build the sql to get access
	    foreach ($groups as $grouprec)
	    {
			if( $group_options != "" )
			{
				$group_options .= " OR ";
			}
			$group_options .= "group_id = ".$grouprec['group_id']. " OR group_id_list LIKE '%,".$grouprec['group_id']. ",%'";
	    }

		// build template
		$daycount = request_var('daycount', 7 );
		$disp_date_format = $config['rp_date_format'];
	    $disp_date_time_format = $config['rp_date_time_format'];

		// show all in coming year
		$start_temp_date = time() - 86400 ;
		$sort_timestamp_cutoff = $start_temp_date + 86400*365;

		$sql_array = array(
		    'SELECT'    => ' r.raidplan_id  ',

		    'FROM'      => array(
		        RP_RAIDS_TABLE => 'r',
		    ),

		    'WHERE'     =>  '
		    	 (r.raidplan_access_level = 2)
		    	OR  (r.poster_id = '. $db->sql_escape($user->data['user_id']) .' )
		    	OR  (r.raidplan_access_level = 1 AND ('.$group_options.') )
		        AND r.raidplan_start_time >= '. (int) $start_temp_date.'
		        AND r.raidplan_start_time <= '. (int) $sort_timestamp_cutoff ,

		    'ORDER_BY' => 'r.raidplan_start_time ASC ',
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $config['rp_display_next_raidplans'], 0);

		$template_output = array();
		while ($row = $db->sql_fetchrow($result))
		{
			unset($raidplan);
			$raidplan = new Raidplan($row['raidplan_id']);
			if(strlen( $raidplan->getEventlist()->events[$raidplan->getEventType()]['imagename'] ) > 1)
			{
				$eventimg = $phpbb_root_path . "images/bbdkp/event_images/" . $raidplan->getEventlist()->events[$raidplan->getEventType()]['imagename'] . ".png";

			}
			else
			{
				$eventimg = $phpbb_root_path . "images/bbdkp/event_images/dummy.png";
			}

			$subj = $raidplan->getSubject();
			if( $config['rp_display_truncated_name'] > 0 )
			{
				if(utf8_strlen($raidplan->getSubject()) > $config['rp_display_truncated_name'])
				{
					$subj = truncate_string(utf8_strlen($raidplan->getSubject()), $config['rp_display_truncated_name']) . '…';
				}
			}

			$delete_url = "";
			$edit_url = "";
			if($user->data['is_registered'])
			{
				// can user edit ?
				if( $auth->acl_get('u_raidplanner_edit_raidplans') &&
				(($user->data['user_id'] == $raidplan->getPoster()) || $auth->acl_get('m_raidplanner_edit_other_users_raidplans') ))
				{
					 $edit_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=showadd&amp;raidplanid=". $raidplan->id);
				}
				//can user delete ?
				if( $auth->acl_get('u_raidplanner_delete_raidplans') &&
				(($user->data['user_id'] == $raidplan->getPoster())|| $auth->acl_get('m_raidplanner_delete_other_users_raidplans') ))
				{
					$delete_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=delete&amp;raidplanid=".$raidplan->id);
				}
			}
            $eventlist = $raidplan->getEventlist();

			$template->assign_block_vars('raids', array(
				'RAID_ID'				=> $raidplan->id,
				'IMAGE' 				=> $eventimg,
				'EVENTNAME'			 	=> $eventlist->events[$raidplan->getEventType()]['event_name'],
				'EVENT_URL'  			=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=".$raidplan->id),
				'EVENT_ID'  			=> $raidplan->id,
				'COLOR' 				=> $eventlist->events[$raidplan->getEventType()]['color'],
				'SUBJECT'				=> $subj,
				'U_DELETE' 				=> $delete_url,
				'U_EDIT' 				=> $edit_url,
				'POSTER'				=> $raidplan->getPosterUrl(),
				'START_TIME'			=> $user->format_date($raidplan->getStartTime(), $disp_date_time_format, true),
				'START_TIME'			=> $user->format_date($raidplan->getStartTime(), $config['rp_date_format'], true),
				'END_TIME' 				=> $user->format_date($raidplan->getEndTime(), $config['rp_time_format'], true),
				'DISPLAY_BOLD'			=> ($user->data['user_id'] == $raidplan->getPoster()) ? true : false,
			));

			// get signups
			foreach($raidplan->getRaidroles() as $key => $role)
			{
				foreach($role['role_signups'] as  $signup)
				{

                    if (is_object($signup) && $signup instanceof RaidplanSignup )
                    {

                        switch ($signup->getSignupVal() )
                        {

                            case 0:
                                $signupcolor = '#00FF00';
                                $signuptext = $user->lang['YES'];
                                break;
                            case 1:
                                $signupcolor = '#FF0000';
                                $signuptext = $user->lang['NO'];
                                break;
                            case 2:
                                $signupcolor = '#FFCC33';
                                $signuptext = $user->lang['MAYBE'];
                                break;

                        }

                        $template->assign_block_vars('raids.signups', array(
                            'COLOR' 		=> $signupcolor,
                            'CHARNAME'  	=> $signup->getDkpmembername(),
                            'COLORCODE' 	=> ($signup->getColorcode() == '') ? '#123456' : $signup->getColorcode(),
                            'CLASS_IMAGE' 	=> (strlen($signup->getImagename()) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $signup->getImagename() . ".png" : '',
                            'S_CLASS_IMAGE_EXISTS' => (strlen($signup->getImagename()) > 1) ? true : false,
                            'VALUE_TXT' 	=> " : " . $signuptext
                        ));


                    }

				}
			}

			$db->sql_freeresult($result);

		}

		$db->sql_freeresult($result);

		switch ($mode)
		{
			case 'raidplanner_registration' :
					$this->tpl_name 	= 'planner/ucp_planner';
					$template->assign_vars(array(
							'U_COUNT_ACTION'	=> $this->u_action,
							'DAYCOUNT'			=> $daycount ));
			break;

		}

	}
}
