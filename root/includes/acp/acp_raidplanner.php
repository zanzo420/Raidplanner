<?php
/**
 * This class manages the Raidplanner settings
 *
 * @author Sajaki@bbdkp.com
 * @package bbDkp.acp
 * @copyright (c) 2010 bbdkp http://code.google.com/p/bbdkp/
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0.4
 *
 **/

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
    exit;
}
/**
 * This class manages the Raidplanner settings
 *
 */
class acp_raidplanner
{
    public $u_action;
    public function main($id, $mode)
    {
        global $db, $user, $auth, $template, $sid, $cache;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;


        $user->add_lang ( array ('mods/raidplanner', 'mods/dkp_common' ));
        if (!$auth->acl_get('a_raid_config'))
        {
            trigger_error($user->lang['USER_CANNOT_MANAGE_RAIDPLANNER'] );
        }


        // main tabs
        $template->assign_vars ( array (
            'RAIDPLANNER_VERSION'	=> $config['bbdkp_raidplanner'],
            'U_RP_PLANNER_SETTINGS' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_settings" ),
            'U_RP_CAL_SETTINGS' 	=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_cal_settings" ),
            'U_RP_ROLES' 			=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_roles" ),
            'U_RP_TEAMS' 		    => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_teams" ),
            'U_RP_COMPOSITION' 		=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_composition" ),

        ));


        $link = '<br /><a href="' .  append_sid("{$phpbb_root_path}adm/index.$phpEx", "i=raidplanner&amp;" ) . '"><p>'. $user->lang['RETURN_RP']. '</p></a>';
        $action	= request_var('action', '');

        switch ($mode)
        {
            case 'rp_settings' :
                $update	= (isset($_POST['update_rp_settings'])) ? true : false;
                // check the form key
                if ($update )
                {
                    if (!check_form_key('acp_raidplanner'))
                    {
                        trigger_error('FORM_INVALID');
                    }
                }

                // update Raidplan settings
                if($update)
                {

                    $invitehour	= request_var('event_invite_hh', 0) * 60 + request_var('event_invite_mm', 0);
                    set_config  ( 'rp_default_invite_time',  $invitehour,0);

                    $starthour =  request_var('event_start_hh', 0) * 60 + request_var('event_start_mm', 0);
                    set_config  ( 'rp_default_start_time',  $starthour,0);

                    $endhour =  request_var('event_end_hh', 0) * 60 + request_var('event_end_mm', 0);
                    set_config  ( 'rp_default_end_time',  $endhour,0);

                    $freezetime	= request_var('freeze_time', 0);
                    set_config  ( 'rp_default_freezetime',  $freezetime,0);

                    $expire_time = request_var('expire_time', 0);
                    set_config  ( 'rp_default_expiretime',  $expire_time,0);

                    $rp_enable_past_raids=  request_var('rp_enable_past_raids', 0);
                    set_config  ( 'rp_enable_past_raids', $rp_enable_past_raids, 0) ;

                    $disp_upcoming	= request_var('disp_next_raidplans', 0);
                    set_config  ( 'rp_display_next_raidplans',  $disp_upcoming,0);

                    $send_pm_rpchange=  request_var('send_pm_rpchange', 0);
                    set_config  ( 'rp_pm_rpchange', $send_pm_rpchange, 0) ;

                    $send_email_rpchange = request_var('send_email_rpchange', 0);
                    set_config  ( 'rp_email_rpchange', $send_email_rpchange, 0) ;

                    $send_pm_signup = request_var('send_pm_signup', 0);
                    set_config  ( 'rp_pm_signup', $send_pm_signup, 0);

                    $send_email_signup =  request_var('send_email_signup', 0);
                    set_config  ( 'rp_email_signup', $send_email_signup, 0) ;

                    $push_mode = request_var('push_mode', 0);
                    set_config  ( 'rp_rppushmode', $push_mode ,0);
                    $cache->destroy('config');

                    $message="";
                    $message .= '<br />' . sprintf( $user->lang['RPSETTINGS_UPDATED'], E_USER_NOTICE);

                    meta_refresh(1, $this->u_action);
                    trigger_error($message . $link);
                }

                // build presets for invite hour pulldown
                $invhour = intval($config['rp_default_invite_time'] / 60);
                for ($i = 0; $i <= 23; $i++)
                {
                    $template->assign_block_vars('event_invite_hh', array(
                        'KEY' 		=> $i,
                        'VALUE' 	=> $i,
                        'SELECTED' 	=> ($i == $invhour ) ? ' selected="selected"' : '',
                    ));
                }

                // build presets for invite minute pulldown
                $invmin = $config['rp_default_invite_time'] - ($invhour*60);
                for ($i = 0; $i <= 59; $i++)
                {
                    $template->assign_block_vars('event_invite_mm', array(
                        'KEY' 		=> $i,
                        'VALUE' 	=> $i,
                        'SELECTED' 	=> ($i == $invmin ) ? ' selected="selected"' : '',
                    ));
                }

                // build presets for start hour pulldown
                $starthour = intval($config['rp_default_start_time'] / 60);
                for ($i = 0; $i <= 23; $i++)
                {
                    $template->assign_block_vars('event_start_hh', array(
                        'KEY' 		=> $i,
                        'VALUE' 	=> $i,
                        'SELECTED' 	=> ($i == $starthour ) ? ' selected="selected"' : '',
                    ));
                }

                // build presets for start minute pulldown
                $startmin =  $config['rp_default_start_time'] - ($starthour* 60);
                for ($i = 0; $i <= 59; $i++)
                {
                    $template->assign_block_vars('event_start_mm', array(
                        'KEY' 		=> $i,
                        'VALUE' 	=> $i,
                        'SELECTED' 	=> ($i == $startmin ) ? ' selected="selected"' : '',
                    ));
                }

                // build presets for end hour pulldown
                $endhour = intval($config['rp_default_end_time'] / 60);
                for ($i = 0; $i <= 23; $i++)
                {
                    $template->assign_block_vars('event_end_hh', array(
                        'KEY' 		=> $i,
                        'VALUE' 	=> $i,
                        'SELECTED' 	=> ($i == $endhour ) ? ' selected="selected"' : '',
                    ));
                }

                // build presets for end minute pulldown
                $endmin = $config['rp_default_end_time'] - ($endhour*60);
                for ($i = 0; $i <= 59; $i++)
                {
                    $template->assign_block_vars('event_end_mm', array(
                        'KEY' 		=> $i,
                        'VALUE' 	=> $i,
                        'SELECTED' 	=> ($i == $endmin ) ? ' selected="selected"' : '',
                    ));
                }


                $template->assign_vars(array(

                    'RAIDPLANNER_VERSION'	=> $config['bbdkp_raidplanner'],

                    'ENABLEPASTRAIDS_CHECKED'	=> ((int) $config['rp_enable_past_raids'] == 1) ? "checked='checked'" :'' ,
                    'FROZEN_TIME'		=> $config['rp_default_freezetime'],
                    'EXPIRE_TIME'		=> $config['rp_default_expiretime'],


                    'DISP_NEXT_EVENTS_DISABLED'	=> ( $config['rp_index_display_week'] == 1 ) ? "disabled='disabled'" : '',
                    'DISP_NEXT_EVENTS'	=> $config['rp_display_next_raidplans'],


                    'DISP_EVENTS_1_DAY_CHECKED'	=> ( $config['rp_disp_raidplans_only_on_start'] == '1' ) ? "checked='checked'" : '',

                    'SEL_AUTOPUSHRAIDPLAN'		=> ((int) $config['rp_rppushmode'] == 0 ) ? "selected='selected'" : '',
                    'SEL_MANUALPUSHRAIDPLAN'		=> ((int) $config['rp_rppushmode'] == 1 ) ? "selected='selected'" : '',
                    'SEL_NOPUSHRAIDPLAN'		=> ((int) $config['rp_rppushmode'] == 2 ) ? "selected='selected'" : '',

                    'SENDPMRP_CHECKED'		=> ((int) $config['rp_pm_rpchange'] == 1) ? "checked='checked'" :'' ,
                    'SENDEMAILRP_CHECKED'	=> ((int) $config['rp_email_rpchange'] == 1) ? "checked='checked'" :'' ,
                    'SENDPMSIGN_CHECKED'	=> ((int) $config['rp_pm_signup'] == 1) ? "checked='checked'" :'' ,
                    'SENDEMAILSIGN_CHECKED'	=> ((int) $config['rp_email_signup'] == 1) ? "checked='checked'" :'' ,

                    'PRUNE_FREQ'		=> (int) $config['rp_prune_frequency'] / 86400,
                    'PRUNE_LIMIT'		=> (int) $config['rp_prune_limit'] / 86400,
                    'POPULATE_FREQ'		=> (int) $config['rp_populate_frequency'] / 86400,
                    'POPULATE_LIMIT'	=> (int) $config['rp_populate_limit'] / 86400,
                    'U_PLUS_HOUR'		=> $this->u_action."&amp;calPlusHour=1&amp;plusVal=1",
                    'U_MINUS_HOUR'		=> $this->u_action."&amp;calPlusHour=1&amp;plusVal=0",
                    'U_ACTION'			=> $this->u_action,
                ));


                $this->tpl_name = 'dkp/acp_' . $mode;
                $form_key = 'acp_raidplanner';
                add_form_key($form_key);
                break;

            case 'rp_cal_settings':
                $updatecal = (isset($_POST['update_rp_settings_cal'])) ? true : false;
                // check the form key
                if ($updatecal)
                {
                    if (!check_form_key('acp_raidplanner'))
                    {
                        trigger_error('FORM_INVALID');
                    }
                }

                // update all calendar settings
                if( $updatecal )
                {
                    set_config  ( 'rp_show_welcomemsg',  (isset ( $_POST ['show_welcome'] )) ? 1 : 0 , 0);

                    $text = utf8_normalize_nfc(request_var('welcome_message', '', true));

                    $uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
                    $allow_bbcode = $allow_urls = $allow_smilies = true;
                    generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
                    $sql = 'UPDATE ' . RP_RAIDPLAN_ANNOUNCEMENT . " SET
							announcement_msg = '" . (string) $db->sql_escape($text) . "' ,
							announcement_timestamp = ".  (int) time() ." ,
							bbcode_bitfield = 	'".  (string) $bitfield ."' ,
							bbcode_uid = 		'".  (string) $uid ."'
							WHERE announcement_id = 1";
                    $db->sql_query($sql);


                    $disp_week	= request_var('disp_week', 0);
                    set_config  ( 'rp_index_display_week',  $disp_week,0);

                    $first_day	= request_var('first_day', 0);
                    set_config  ( 'rp_first_day_of_week',  $first_day,0);

                    $hour_mode = request_var('hour_mode', 12);
                    set_config  ( 'rp_hour_mode',  $hour_mode,0);

                    $disp_trunc = request_var('disp_trunc', 0);
                    set_config  ( 'rp_display_truncated_name',  $disp_trunc,0);

                    $disp_hidden_groups	= request_var('disp_hidden_groups', '');
                    set_config  ( 'rp_display_hidden_groups',  $disp_hidden_groups,0);

                    $disp_raidplans_1_day = request_var('disp_raidplans_1_day', 0);
                    set_config  ( 'rp_disp_raidplans_only_on_start',  $disp_raidplans_1_day,0);

                    $date_format = request_var('date_format', 'M d, Y');
                    set_config  ( 'rp_date_format',  $date_format,0);

                    $show_name = request_var('show_name', 0);
                    set_config  ( 'rp_show_name',  $show_name,0);

                    $date_time_format = request_var('date_time_format', 'M d, Y h:i a');
                    set_config  ( 'rp_date_time_format',  $date_time_format,0);

                    $time_format = request_var('time_format', 'h:i a');
                    set_config  ( 'rp_time_format',  $time_format,0);

                    // prune_freq is entered in days by user, but stored in seconds
                    $prune_freq = request_var('prune_freq', 0);
                    $prune_freq = 86400 * $prune_freq;
                    set_config  ( 'rp_prune_frequency',  $prune_freq,0);

                    // prune_limit is entered in days by user, but stored in seconds
                    $prune_limit = request_var('prune_limit', 0);
                    $prune_limit = 86400 * $prune_limit;
                    set_config  ( 'rp_prune_limit',  $prune_limit,0);

                    // auto populate recurring raidplan settings
                    // populate_freq is entered in days by user, but stored in seconds
                    $populate_freq = request_var('populate_freq', 0);
                    $populate_freq = 86400 * $populate_freq;
                    set_config  ( 'rp_populate_frequency',  $populate_freq,0);

                    // populate_limit is entered in days by user, but stored in seconds
                    $populate_limit = request_var('populate_limit', 0);
                    $populate_limit = 86400 * $populate_limit;
                    set_config  ( 'rp_populate_limit',  $populate_limit,0);

                    $cache->destroy('config');

                    $message = '<br />' . sprintf( $user->lang['CALSETTINGS_UPDATED'], E_USER_NOTICE);

                    meta_refresh(1, $this->u_action);
                    trigger_error($message . $link);
                }

                $sel_monday = $sel_tuesday = $sel_wednesday = $sel_thursday = $sel_friday = $sel_saturday = $sel_sunday = "";
                switch($config['rp_first_day_of_week']  )
                {
                    case 0:
                        $sel_monday = "selected='selected'";
                        break;
                    case 1:
                        $sel_tuesday = "selected='selected'";
                        break;
                    case 2:
                        $sel_wednesday = "selected='selected'";
                        break;
                    case 3:
                        $sel_thursday = "selected='selected'";
                        break;
                    case 4:
                        $sel_friday = "selected='selected'";
                        break;
                    case 5:
                        $sel_saturday = "selected='selected'";
                        break;
                    case 6:
                        $sel_sunday = "selected='selected'";
                        break;
                }

                // get welcome msg
                $sql = 'SELECT announcement_msg, bbcode_bitfield, bbcode_uid FROM ' . RP_RAIDPLAN_ANNOUNCEMENT;
                $db->sql_query($sql);
                $result = $db->sql_query($sql);
                while ( $row = $db->sql_fetchrow($result) )
                {
                    $text = $row['announcement_msg'];
                    $bitfield = $row['bbcode_bitfield'];
                    $uid = $row['bbcode_uid'];
                }
                $textarr = generate_text_for_edit($text, $uid, $bitfield, 7);

                $template->assign_vars(array(
                    'SHOW_WELCOME'		=> ((int) $config ['rp_show_welcomemsg'] == 1) ? 'checked="checked"' : "",
                    'WELCOME_MESSAGE' 	=> $textarr['text'],
                    'DISP_WEEK_CHECKED'	=> ( $config['rp_index_display_week'] == 1 ) ? "checked='checked'" : '',
                    'SEL_MONDAY'		=> $sel_monday,
                    'SEL_TUESDAY'		=> $sel_tuesday,
                    'SEL_WEDNESDAY'		=> $sel_wednesday,
                    'SEL_THURSDAY'		=> $sel_thursday,
                    'SEL_FRIDAY'		=> $sel_friday,
                    'SEL_SATURDAY'		=> $sel_saturday,
                    'SEL_SUNDAY'		=> $sel_sunday,
                    'SEL_12_HOURS'		=> ($config['rp_hour_mode'] == 12) ? "selected='selected'" :'',
                    'SEL_24_HOURS'		=> ($config['rp_hour_mode'] != 12) ? "selected='selected'" :'' ,
                    'DISP_TRUNCATED'	=> $config['rp_display_truncated_name'],
                    'DISP_HIDDEN_GROUPS_CHECKED'	=> ($config['rp_display_hidden_groups'] == '1' ) ? "checked='checked'" : '',
                    'DATE_FORMAT'		=> $config['rp_date_format'],
                    'DATE_TIME_FORMAT'	=> $config['rp_date_time_format'],
                    'TIME_FORMAT'		=> $config['rp_time_format'],
                    'SHOW_NAME'				=> ((int) $config ['rp_show_name'] == 1) ? 'checked="checked"' : "",
                ));


                $this->tpl_name = 'dkp/acp_' . $mode;
                $form_key = 'acp_raidplanner';
                add_form_key($form_key);
                break;

            case 'rp_teams':
                /*******************************************/
                /*        RAID TEAMS  phpbb_rp_teams	   */
                /*   Team composition  phpbb_rp_teamsize   */
                /*******************************************/
                $update_raidrolesize = (isset($_POST['update_raidrolesize'])) ? true : false;
                $updateteam = (isset($_POST['teamupdate'])) ? true : false;
                $deleteteam = (request_var('teamdelete', '') != '') ? true : false;
                $addteam = (isset($_POST['teamadd'])) ? true : false;
                // check the form key
                if ($updateteam || $addteam )
                {
                    if (!check_form_key('acp_raidplanner'))
                    {
                        trigger_error('FORM_INVALID');
                    }
                }

                // end of handlers
                // build form

                // select raid teams
                $sql = 'SELECT * FROM ' . RP_TEAMS . '
						ORDER BY teams_id';
                $db->sql_query($sql);
                $result1 = $db->sql_query($sql);

                $total_teams = 0;
                while ( $row = $db->sql_fetchrow($result1) )
                {
                    $total_teams++;
                    $template->assign_block_vars('team_row', array(
                        'TEAM_ID' 		=> $row['teams_id'],
                        'TEAMNAME' 		=> $row['team_name'],
                        'TEAMSIZE' 		=> $row['team_needed'],
                        'U_DELETE' 		=> $this->u_action. '&amp;teamdelete=1&amp;teams_id=' . $row['teams_id'],
                    ));

                    // select raid composition
                    $sql = 'SELECT a.team_needed as maxneeded,
					a.team_name, b.role_name, c.teams_id, c.role_id, c.team_needed
					FROM ' . RP_TEAMS . ' a
					CROSS JOIN ' . RP_ROLES . ' b
					LEFT JOIN ' . RP_TEAMSIZE . ' c ON c.teams_id=a.teams_id AND b.role_id=c.role_id
					WHERE a.teams_id = ' . (int) $row['teams_id'] . '
					ORDER BY teams_id, role_id';

                    $db->sql_query($sql);
                    $result2 = $db->sql_query($sql);
                    while ( $row2 = $db->sql_fetchrow($result2) )
                    {
                        $total_teams++;
                        $template->assign_block_vars('team_row.teamsize_row', array(
                            'ROLE_ID' 		=> (int) $row2['role_id'],
                            'ROLENAME' 		=> $row2['role_name'],
                            'TEAMSIZE' 		=> (int)$row2['maxneeded'],
                            'ROLESIZE' 		=> (int) $row2['team_needed'] == '' ? 0 :$row2['team_needed'],
                            'ROLEPCT' 		=> (double) round($row2['team_needed'] / $row2['maxneeded'], 2) * 100,
                        ));
                    }
                    $db->sql_freeresult($result2);
                }
                $db->sql_freeresult($result1);


                if($addteam)
                {
                    $data = array(
                        'team_name'     => utf8_normalize_nfc(request_var('newteamname', 'New Team', true)),
                        'team_needed'     => request_var('newteamsize', 0),
                    );

                    $error = $data['team_name'] == '' ? trigger_error($user->lang['TEAMROLE_NAME_EMPTY'], E_USER_WARNING): '';
                    $error = $data['team_needed'] == '' ? trigger_error($user->lang['TEAMROLE_SIZE_EMPTY'], E_USER_WARNING): '';

                    $sql = 'INSERT INTO ' . RP_TEAMS . $db->sql_build_array('INSERT', $data);
                    $db->sql_query($sql);
                    $team_id = $db->sql_nextid();

                    // get rolelist from template
                    $rolenames = utf8_normalize_nfc(request_var('rolename_hidden', array( 0 => ''), true));
                    //insert default value in RP_TEAMS

                    $n = 0;
                    $totalassigned = 0;
                    $i = 0;
                    foreach($rolenames as $key => $rolename)
                    {
                        $n=0;
                        $i += 1;

                        if($i == count($rolenames))
                        {
                            // ex: need 40, have 6 slots
                            $n = $data['team_needed'] - $totalassigned;
                        }
                        else
                        {
                            if($totalassigned < (int) $data['team_needed'])
                            {
                                $n=1;
                            }
                            elseif($totalassigned == (int) $data['team_needed'])
                            {
                                // ex: need only 3, have 6 slots
                                $n = $data['team_needed'] - $totalassigned;
                            }
                        }

                        $totalassigned += $n;

                        $sql_ary[] = array(
                            'teams_id'      => $team_id,
                            'role_id'       => $key,
                            'team_needed'   => $n
                        );


                    }
                    $db->sql_multi_insert(RP_TEAMSIZE, $sql_ary);

                    $success_message = sprintf($user->lang['TEAM_ADD_SUCCESS'], utf8_normalize_nfc(request_var('newteamname', 'New Team', true)));
                    meta_refresh(1, $this->u_action);
                    trigger_error($success_message . $link);

                }

                //user pressed edit team
                if($updateteam)
                {
                    $teamnames = utf8_normalize_nfc(request_var('team_name', array( 0 => ''), true));
                    $teamsize = request_var('team_size', array( 0 => ''));
                    foreach ( $teamnames as $team_id => $teamname )
                    {
                        $data = array(
                            'team_name'     	=> $teamname,
                            'team_needed'     	=> $teamsize[$team_id],
                        );

                        $error = $data['team_name'] == '' ? trigger_error($user->lang['TEAMROLE_NAME_EMPTY'], E_USER_WARNING): '';
                        $error = $data['team_needed'] == '' ? trigger_error($user->lang['TEAMROLE_SIZE_EMPTY'], E_USER_WARNING): '';

                        $sql = 'UPDATE ' . RP_TEAMS . ' SET ' . $db->sql_build_array('UPDATE', $data) . '
					   	     WHERE teams_id=' . (int) $team_id;
                        $db->sql_query($sql);

                    }

                    $success_message = $user->lang['TEAM_UPDATE_SUCCESS'];
                    meta_refresh(1, $this->u_action);
                    trigger_error($success_message . $link);
                }

                //used pressed red cross to delete team
                if ($deleteteam)
                {
                    if (confirm_box(true))
                    {
                        $sql = 'DELETE from ' . RP_TEAMS . ' WHERE teams_id = ' . request_var('hiddenteamid', 0) ;
                        $db->sql_query($sql);

                        $sql = 'DELETE from ' . RP_TEAMSIZE . ' WHERE teams_id = ' . request_var('hiddenteamid', 0) ;
                        $db->sql_query($sql);

                        meta_refresh(1, $this->u_action);
                        trigger_error(sprintf($user->lang['TEAM_DELETE_SUCCESS'], request_var('hiddenteamid', 0)) . $link, E_USER_WARNING);

                    }
                    else
                    {
                        // @todo check if there are scheduled raids with this team, ask permission
                        /*
                         *
                         $sql= "SELECT count(*) as countteam FROM " . RP_RAIDS_TABLE . " WHERE raidteam = " . request_var('teams_id', 0);
                        $result = $db->sql_query($sql);
                        $total_raidplans = (int) $db->sql_fetchfield('countteam');
                        if($total_raidplans  > 0 )
                        {
                            trigger_error(sprintf($user->lang['TEAM_DELETE_FAIL'], request_var('hiddenteamid', 0)) . $link, E_USER_WARNING);
                        }
                        */


                        // get field content
                        $s_hidden_fields = build_hidden_fields(array(
                                // set roledelete to 1. so when this gets in the $_POST output, the $deleterole becomes true
                                'teamdelete'	=> 1,
                                'hiddenteamid'	=> request_var('teams_id', 0),
                            )
                        );

                        // ask for permission
                        confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_TEAM'], request_var('teams_id', 0)), $s_hidden_fields);
                    }
                }


                if($update_raidrolesize)
                {
                    // get max team size from form
                    $maxteamsize= request_var('maxteamsize', array( 0 => 0), true);
                    // get team compositions from form
                    $array = request_var('teamsize', array( 0 => array( 0 => 0)), true);
                    //
                    foreach ( $array as $team_id => $teamroles )
                    {
                        $teamtotal = 0;
                        foreach ( $teamroles as $role_id => $roleneeded )
                        {
                            $teamtotal += $roleneeded;
                        }

                        if ($teamtotal > $maxteamsize[$team_id])
                        {
                            $success_message = sprintf($user->lang['TEAMROLE_UPDATE_FAIL'],$team_id, $maxteamsize[$team_id], $teamtotal);
                            meta_refresh(3, $this->u_action);
                            trigger_error($success_message . $link, E_USER_WARNING);
                        }

                        $roleneeded= 0;
                        foreach ( $teamroles as $role_id => $roleneeded )
                        {
                            $sql = 'UPDATE ' . RP_TEAMSIZE . '
							 	 SET team_needed = ' . $roleneeded . '
						   	     WHERE teams_id=' . (int) $team_id . '
						   	     AND role_id = ' . $role_id;
                            $db->sql_query($sql);
                        }
                    }

                    $success_message = $user->lang['TEAMROLE_UPDATE_SUCCESS'];
                    meta_refresh(1, $this->u_action);
                    trigger_error($success_message . $link);
                }


                $this->tpl_name = 'dkp/acp_' . $mode;
                $form_key = 'acp_raidplanner';
                add_form_key($form_key);

                break;

        }
    }

}
