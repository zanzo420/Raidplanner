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

// Include the base class
if (!class_exists('\bbdkp\admin\Admin'))
{
    require("{$phpbb_root_path}includes/bbdkp/admin/admin.$phpEx");
}

if (!class_exists('\bbdkp\controller\games\Game'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/games/Game.$phpEx");
}

//include the guilds class
if (!class_exists('\bbdkp\controller\guilds\Guilds'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
}


/**
 * This class manages the Raidplanner settings
 *
 */
class acp_raidplanner extends \bbdkp\admin\Admin
{
    /**
     * url action
     * @var string
     */
    public $u_action;


    /**
     * Main
     *
     * @param $id
     * @param $mode
     */
    public function main($id, $mode)
    {
        global $db, $user, $auth, $template, $cache;
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
            'U_RP_TEAMS' 		    => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_teams" ),
            'U_RP_TEAMSLIST' 		=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_teams" ),
            'U_RP_TEAMSEDIT' 		=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=raidplanner&amp;mode=rp_teams_edit" ),
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

                $this->listteam($mode);
                break;

            case 'rp_teams_edit':

                $action = request_var('action', '');
                if($action=='delete')
                {
                    //call delete function
                }
                elseif ($action=='edit')
                {
                    //build edit template
                    $t = request_var('teams_id', 1);
                    $this->getteam($t);

                    $template->assign_vars(array(
                        'S_UPDATE' => true,
                    ));
                }
                else
                {
                    //build edit template

                    $template->assign_vars(array(
                        'S_ADD' => true,
                    ));

                }

                $guild_id = request_var(URI_GUILD, 1);
                $Guild = new \bbdkp\controller\guilds\Guilds();
                $guildlist   = $Guild->guildlist(1);
                foreach ($guildlist as $g)
                {
                    $template->assign_block_vars('guild_row', array(
                        'VALUE'    => $g['id'],
                        'SELECTED' => ($guild_id == $g['id']) ? ' selected="selected"' : '',
                        'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)'));
                }

                $game_id = request_var(URI_GAME, 1);
                if (isset($this->games))
                {
                    foreach ($this->games as $key => $gamename)
                    {
                        $template->assign_block_vars('game_row', array(
                            'VALUE'    => $key,
                            'SELECTED' => ($game_id == $key) ? ' selected="selected"' : '',
                            'OPTION'   => (!empty($gamename)) ? $gamename : '(None)'));
                    }
                }
                else
                {
                    trigger_error('ERROR_NOGAMES', E_USER_WARNING);
                }


                $addteam = (isset($_POST['addteam'])) ? true : false;
                $editteam = (isset($_POST['editteam'])) ? true : false;

               // check the form key
                if ($editteam || $addteam)
                {
                    if (!check_form_key('acp_raidplanner'))
                    {
                        trigger_error('FORM_INVALID');
                    }
                }


                if($addteam)
                {
                    $data = array(
                        'team_name'     => utf8_normalize_nfc(request_var('newteamname', 'New Team', true)),
                        'role_needed'     => request_var('newteamsize', 0),
                    );

                    $error = $data['team_name'] == '' ? trigger_error($user->lang['TEAMROLE_NAME_EMPTY'], E_USER_WARNING): '';
                    $error = $data['role_needed'] == '' ? trigger_error($user->lang['TEAMROLE_SIZE_EMPTY'], E_USER_WARNING): '';

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
                            $n = $data['role_needed'] - $totalassigned;
                        }
                        else
                        {
                            if($totalassigned < (int) $data['role_needed'])
                            {
                                $n=1;
                            }
                            elseif($totalassigned == (int) $data['role_needed'])
                            {
                                // ex: need only 3, have 6 slots
                                $n = $data['role_needed'] - $totalassigned;
                            }
                        }

                        $totalassigned += $n;

                        $sql_ary[] = array(
                            'teams_id'      => $team_id,
                            'role_id'       => $key,
                            'role_needed'   => $n
                        );


                    }
                    $db->sql_multi_insert(RP_TEAMSIZE, $sql_ary);

                    $success_message = sprintf($user->lang['TEAM_ADD_SUCCESS'], utf8_normalize_nfc(request_var('newteamname', 'New Team', true)));
                    meta_refresh(1, $this->u_action);
                    trigger_error($success_message . $link);

                }

                //user pressed edit team
                if($editteam)
                {
                    $teamnames = utf8_normalize_nfc(request_var('team_name', array( 0 => ''), true));
                    $teamsize = request_var('team_size', array( 0 => ''));
                    foreach ( $teamnames as $team_id => $teamname )
                    {
                        $data = array(
                            'team_name'     	=> $teamname,
                            'role_needed'     	=> $teamsize[$team_id],
                        );

                        $error = $data['team_name'] == '' ? trigger_error($user->lang['TEAMROLE_NAME_EMPTY'], E_USER_WARNING): '';
                        $error = $data['role_needed'] == '' ? trigger_error($user->lang['TEAMROLE_SIZE_EMPTY'], E_USER_WARNING): '';

                        $sql = 'UPDATE ' . RP_TEAMS . ' SET ' . $db->sql_build_array('UPDATE', $data) . '
					   	     WHERE teams_id=' . (int) $team_id;
                        $db->sql_query($sql);

                    }

                    $success_message = $user->lang['TEAM_UPDATE_SUCCESS'];
                    meta_refresh(1, $this->u_action);
                    trigger_error($success_message . $link);
                }

                $this->tpl_name = 'dkp/acp_' . $mode;
                $form_key = 'acp_raidplanner';
                add_form_key($form_key);

                break;


        }
    }

    /**
     * edit team
     */
    private function editteam($mode)
    {

        $this->tpl_name = 'dkp/acp_' . $mode;
        $form_key = 'acp_raidplanner';
        add_form_key($form_key);

        /*
        $editteam = (isset($_POST['editteam'])) ? true : false;
        $deleteteam = (request_var('deleteteam', '') != '') ? true : false;

        if(isset($this->games))
        {
            foreach ($this->games as $key => $gamename)
            {
                $template->assign_block_vars('game_row', array(
                    'VALUE' => $key ,
                    'SELECTED' => ( 1 == $key) ? ' selected="selected"' : '' ,
                    'OPTION' => (! empty($gamename)) ? $gamename : '(None)'));
            }
        }
        else
        {
            trigger_error('ERROR_NOGAMES', E_USER_WARNING );
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
							 	 SET role_needed = ' . $roleneeded . '
						   	     WHERE teams_id=' . (int) $team_id . '
						   	     AND role_id = ' . $role_id;
                    $db->sql_query($sql);
                }
            }

            $success_message = $user->lang['TEAMROLE_UPDATE_SUCCESS'];
            meta_refresh(1, $this->u_action);
            trigger_error($success_message . $link);
        }
        */

    }


    /**
     * list teams
     */
    private function listteam($mode)
    {

        global $user, $template, $phpbb_admin_path,$phpEx, $db;
        if (count($this->games) == 0)
        {
            trigger_error($user->lang['ERROR_NOGAMES'], E_USER_WARNING);
        }

        // select raid teams
        $sql = 'SELECT teams_id, team_name, team_size, game_id, guild_id FROM ' . RP_TEAMS . ' ORDER BY teams_id';

        $db->sql_query($sql);
        $result1 = $db->sql_query($sql);

        $total_teams = 0;
        while ( $row = $db->sql_fetchrow($result1) )
        {
            $total_teams++;
            $template->assign_block_vars('team_row', array(
                'TEAM_ID' 		=> $row['teams_id'],
                'TEAMNAME' 		=> $row['team_name'],
                'TEAMSIZE' 		=> $row['team_size'],
                'GAME_ID' 		=> $row['game_id'],
                'GUILD_ID' 		=> $row['guild_id'],
                'U_EDIT' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=raidplanner&amp;mode=rp_teams_edit&amp;action=edit&amp;teams_id=' . $row['teams_id']),
                'U_DELETE' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=raidplanner&amp;mode=rp_teams_edit&amp;action=delete&amp;teams_id=' . $row['teams_id']),
            ));
        }
        $db->sql_freeresult($result1);

        $this->tpl_name = 'dkp/acp_' . $mode;
        $form_key = 'acp_raidplanner';
        add_form_key($form_key);

    }

    /**
     * get team
     * @param $team_id
     */
    private function getteam($team_id)
    {
        // select raid teams
        global $template, $db;

        $sql = 'SELECT teams_id, team_name, team_size, game_id, guild_id
          FROM ' . RP_TEAMS . '
          WHERE teams_id = ' . $team_id;
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result))
        {
            $template->assign_vars(array(
                'TEAM_ID' 		=> $row['teams_id'],
                'TEAMNAME' 		=> $row['team_name'],
                'TEAMSIZE' 		=> $row['team_size'],
                'GAME_ID' 		=> $row['game_id'],
                'GUILD_ID' 		=> $row['guild_id'],
            ));
        }
        $db->sql_freeresult($result);
    }

}
