<?php
/**
 *
 * @author Sajaki
 * @package bbDKP Raidplanner
 * @copyright (c) 2014 Sajaki
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 0.12.0
 */
namespace bbdkp\views\raidplanner;
use bbdkp\controller\raidplanner\Raidplan;
use bbdkp\controller\raidplanner\RaidplanSignup;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
    exit;
}
if (!class_exists('\bbdkp\controller\raidplanner\Raidplan'))
{
    include($phpbb_root_path . 'includes/bbdkp/controller/raidplanner/raidplan.' . $phpEx);
}
if (!class_exists('\bbdkp\controller\raidplanner\RaidplanSignup'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/raidplanner/RaidplanSignup.$phpEx");
}
/**
 * raid plan view class
 *
 */
class Raidplan_display
{

    /***
     * display a raidplan with signup form
     *
     * @param Raidplan $raidplan
     */
    public function DisplayRaidplan(Raidplan $raidplan)
    {
        global $auth, $user, $config, $template, $phpEx, $phpbb_root_path;

        // check if it is private
        if( !$raidplan->getAuthCansee())
        {
            trigger_error( 'PRIVATE_RAIDPLAN' );
        }

        // format the raidplan message
        $bbcode_options = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;
        $body = $raidplan->getBody();
        $bbcode = $raidplan->getBbcode();
        $message = generate_text_for_display($body, isset($bbcode['uid'])  ? $bbcode['uid'] : '', isset($bbcode['bitfield']) ? $bbcode['bitfield'] : '', $bbcode_options);


        // translate raidplan start and end time into user's timezone
        $day = gmdate("d", $raidplan->getStartTime());
        $month = gmdate("n", $raidplan->getStartTime());
        $year =	gmdate('Y', $raidplan->getStartTime());

        // url to add raid
        $add_raidplan_url = "";
        if ( $auth->acl_gets('u_raidplanner_create_public_raidplans', 'u_raidplanner_create_group_raidplans', 'u_raidplanner_create_private_raidplans'))
        {
            $add_raidplan_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=showadd&amp;calD=".
                $day."&amp;calM=". $month. "&amp;calY=".$year);
        }

       /* make the url for the edit button
        *  show if user is registered
        * belongs to u_raidplanner_edit_raidplans usergroup
        * created this raid or belongs to group that can edit any raid
        */
        $edit_url = "";
        if( $user->data['is_registered']
            && $auth->acl_get('u_raidplanner_edit_raidplans') &&
            (($user->data['user_id'] == $raidplan->getPoster() ) || $auth->acl_get('m_raidplanner_edit_other_users_raidplans')))
        {
            $edit_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=showadd&amp;raidplanid=".
                $raidplan->id."&amp;calD=".$day."&amp;calM=".$month."&amp;calY=".$year);
        }

        /* make the url for the delete button */
        $delete_url = "";
        if( $user->data['is_registered'] && $auth->acl_get('u_raidplanner_delete_raidplans') &&
            (($user->data['user_id'] == $raidplan->getPoster() )|| $auth->acl_get('m_raidplanner_delete_other_users_raidplans') ))
        {
            $delete_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=delete&amp;raidplanid=".
                $raidplan->id."&amp;calD=".$day."&amp;calM=".$month."&amp;calY=".$year);
        }

        $day_view_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=day&amp;calD=".$day ."&amp;calM=".
                $month."&amp;calY=".$year);
        $week_view_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=week&amp;calD=".$day ."&amp;calM=".
            $month."&amp;calY=".$year);
        $month_view_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calD=".$day."&amp;calM=".
            $month."&amp;calY=".$year);

        $total_needed = 0;

        /* make url for signup action */
        $signup_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=signup&amp;raidplanid=". $raidplan->id);

        //display signups
        if($raidplan->getAccesslevel() != 0)
        {
            //show my characters for signup
            foreach ($raidplan->getMychars() as $key => $mychar)
            {
                $template->assign_block_vars('mychars', array(
                    'MEMBER_ID'      	=> $mychar['id'],
                    'MEMBER_NAME'  	 	=> $mychar['name'],
                    'MEMBER_SELECTED'	=> ($mychar['signedup_val'] >= 1) ? ' selected="selected"' : ''

                ));
            }

            unset($key);
            unset($mychar);

            //loop all roles
            foreach($raidplan->getRaidroles() as $key => $role)
            {
                $total_needed += $role['role_needed'];

                // loop signups per role
                $template->assign_block_vars('raidroles', array(
                    'ROLE_ID'        => $key,
                    'ROLE_DISPLAY'   => (count($role['role_signups']) > 0 ? true : false),
                    'ROLE_NAME'      => $role['role_name'],
                    'ROLE_NEEDED'    => $role['role_needed'],
                    'ROLE_SIGNEDUP'  => $role['role_signedup'],
                    'ROLE_CONFIRMED' => $role['role_confirmed'],
                    'ROLE_COLOR'	 => $role['role_color'],
                    'S_ROLE_ICON_EXISTS' => (strlen($role['role_icon']) > 1) ? true : false,
                    'ROLE_ICON' 	 => (strlen($role['role_icon']) > 1) ? $phpbb_root_path . "images/bbdkp/raidrole_images/" . $role['role_icon'] . ".png" : '',
                ));

                $this->Signups_show_confirmed($raidplan, $role);
                $this->Signups_show_available($raidplan, $role);

            }
            $this->Signups_display_notavail($raidplan);
        }

        // button with url to push raidplan to bbdkp
        // this appears only if
        // 1) rp_rppushmode == 1
        // 2) the user belongs to group having u_raidplanner_push permission
        // 3) there are confirmations
        $push_raidplan_url = '';
        if ( $auth->acl_gets('u_raidplanner_push') &&  $config['rp_rppushmode'] == 1 && $raidplan->getSignups()['confirmed'] > 0 )
        {
            $push_raidplan_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=push&amp;raidplanid=". $raidplan->id);
        }

        // event image on top
        if(strlen( $raidplan->getEventlist()->events[$raidplan->getEventType()]['imagename'] ) > 1)
        {
            $eventimg = $phpbb_root_path . "images/bbdkp/event_images/" . $raidplan->getEventlist()->events[$raidplan->getEventType()]['imagename'] . ".png";

        }
        else
        {
            $eventimg = $phpbb_root_path . "images/bbdkp/event_images/dummy.png";
        }

        // we need to find out the time zone to display
        // if anon then get board timezone or else get the users timezone
        if ($user->data['user_id'] == ANONYMOUS)
        {
            //grab board default
            $tz = $config['board_timezone'];
        }
        else
        {
            // get user setting
            $tz = (int) $user->data['user_timezone'];
        }

        $template->assign_vars( array(
                'S_LOCKED'			=> $raidplan->getLocked(),
                'S_FROZEN'			=> $raidplan->getFrozen(),
                'S_NOCHAR'			=> $raidplan->getNochar(),
                'S_SIGNED_UP'		=> $raidplan->getSignedUp(),
                'S_SIGNED_OFF'		=> $raidplan->getSignedOff(),
                'S_CONFIRMED'		=> $raidplan->getConfirmed(),
                'S_CANSIGNUP'		=> $raidplan->getSignupsAllowed(),
                'S_LEGITUSER'		=> ($user->data['is_bot'] || $user->data['user_id'] == ANONYMOUS) ? false : true,
                'S_SIGNUPMAYBE'		=> $raidplan->getSignedUpMaybe(),
                'RAID_TOTAL'		=> $total_needed,
                'TZ'				=> $user->lang['tz'][$tz],

                'CURR_CONFIRMED_COUNT'	 => $raidplan->getSignups()['confirmed'],
                'S_CURR_CONFIRMED_COUNT' => ($raidplan->getSignups()['confirmed'] > 0) ? true: false,
                'CURR_CONFIRMEDPCT'	=> sprintf( "%.2f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['confirmed']) /  $total_needed, 2)*100 : 0)),

                'CURR_YES_COUNT'	=> $raidplan->getSignups()['yes'],
                'S_CURR_YES_COUNT'	=> ($raidplan->getSignups()['yes'] + $raidplan->getSignups()['maybe'] > 0) ? true: false,
                'CURR_YESPCT'		=> sprintf( "%.2f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['yes']) /  $total_needed, 2)*100 : 0)),

                'CURR_MAYBE_COUNT'	=> $raidplan->getSignups()['maybe'],
                'S_CURR_MAYBE_COUNT' => ($raidplan->getSignups()['maybe'] > 0) ? true: false,
                'CURR_MAYBEPCT'		=> sprintf( "%.2f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['maybe']) /  $total_needed, 2)*100 : 0)),

                'CURR_NO_COUNT'		=> $raidplan->getSignups()['no'],
                'S_CURR_NO_COUNT'	=> ($raidplan->getSignups()['no'] > 0) ? true: false,
                'CURR_NOPCT'		=> sprintf( "%.2f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['no']) /  $total_needed, 2)*100 : 0)),

                'CURR_TOTAL_COUNT'  => $raidplan->getSignups()['yes'] + $raidplan->getSignups()['maybe'],

                'ETYPE_DISPLAY_NAME'=> $raidplan->getEventlist()->events[$raidplan->getEventType()]['event_name'],
                'EVENT_COLOR'		=> $raidplan->getEventlist()->events[$raidplan->getEventType()]['color'],
                'EVENT_IMAGE' 		=> $eventimg,

                'SUBJECT'			=> $raidplan->getSubject(),
                'MESSAGE'			=> $message,

                'INVITE_TIME'		=> $user->format_date($raidplan->getInviteTime(), $config['rp_date_time_format'], true),
                'START_TIME'		=> $user->format_date($raidplan->getStartTime(), $config['rp_date_time_format'], true),
                'START_DATE'		=> $user->format_date($raidplan->getStartTime(), $config['rp_date_format'], true),
                'END_TIME'			=> $user->format_date($raidplan->getEndTime(), $config['rp_date_time_format'], true),

                'U_SIGNUP_MODE_ACTION' => $signup_url,
                'RAID_ID'			=> $raidplan->id,
                'S_PLANNER_RAIDPLAN'=> true,

                'POSTER'			=> $raidplan->getPosterUrl(),
                'INVITED'			=> $raidplan->getInviteList(),
                'TEAM_NAME'			=> $raidplan->getRaidteamname(),
                'U_EDITRAID'		=> $edit_url,
                'U_DELETERAID'		=> $delete_url,
                'U_ADDRAID'			=> $add_raidplan_url,
                'U_PUSHRAID'		=> $push_raidplan_url,

                'DAY_IMG'			=> $user->img('button_calendar_day', 'DAY'),
                'WEEK_IMG'			=> $user->img('button_calendar_week', 'WEEK'),
                'MONTH_IMG'			=> $user->img('button_calendar_month', 'MONTH'),
                'DAY_VIEW_URL'		=> $day_view_url,
                'WEEK_VIEW_URL'		=> $week_view_url,
                'MONTH_VIEW_URL'	=> $month_view_url,
            )
        );
    }

    /**
     * return raid plan info array to send to template for tooltips in day/week/month/upcoming calendar
     *
     * @param int $month this month
     * @param int $day today
     * @param int $year this year
     * @param string $group_options
     * @param string $mode
     * @return array
     */
    public function DisplayCalendarRaidTooltip($month, $day, $year, $group_options, $mode)
    {
        global $db, $user, $config, $phpbb_root_path, $phpEx;

        $raidplan_output = array();

        $x = 0;

        $raidplan_counter = 0;

        // build sql to find raids on this day
        $day = ($day < 10 ? ' ' . $day : $day);
        $month = ($month < 10 ? ' ' . $month : $month);

        $sql_array = array(
            'SELECT'    => 'r.raidplan_id ',
            'FROM'		=> array(RP_RAIDS_TABLE => 'r'),
            'WHERE'		=>  "(raidplan_access_level = 2
					   OR (r.poster_id = ". $db->sql_escape($user->data['user_id'])." )
					   OR (r.raidplan_access_level = 1 AND (" . $group_options. ")) )
					  AND (r.raidplan_day = '". $db->sql_escape($day . '-'. $month . '-' . $year) . "' ) " ,
            'ORDER_BY'	=> 'r.raidplan_start_time ASC'
        );

        $sql = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query_limit($sql, $x, 0);

        // we need to find out the time zone to display on tooltip
        if ($user->data['user_id'] == ANONYMOUS)
        {
            //grab board default
            $tz = $config['board_timezone'];
        }
        else
        {
            // get user setting
            $tz = (int) $user->data['user_timezone'];
        }
        $timezone = $user->lang['tz'][$tz];
        $rpcounter = 0;
        $raidplan = new Raidplan;

        while ($row = $db->sql_fetchrow($result))
        {

            $raidplan->id= $row['raidplan_id'];
            $raidplan->Get_Raidplan();
            $raidplan->Check_auth();

            $fsubj = $subj = censor_text($raidplan->getSubject());
            if( $config['rp_display_truncated_name'] > 0 )
            {
                if(utf8_strlen($subj) > $config['rp_display_truncated_name'])
                {
                    $subj = truncate_string($subj, $config['rp_display_truncated_name']) . '...';
                }
            }

            $correct_format = $config['rp_time_format'];
            if( $raidplan->getEndTime() - $raidplan->getStartTime() > 86400 )
            {
                $correct_format = $config['rp_date_time_format'];
            }

            $pre_padding = 0;
            $padding = 0;
            $post_padding = 0;

            /* if in dayview we need to shift the raid to its time */
            if($mode =="day")
            {
                // find padding values
                $pre_padding = 4 * $user->format_date($raidplan->getStartTime(), "H", true);
                $padding = 4 * $user->format_date($raidplan->getEndTime(), "H", true) - $pre_padding;
                $post_padding = 96 - $padding - $pre_padding;
            }

            $rolesinfo = array();
            $userchars = array();
            $total_needed = 0;

            // only show signup tooltip if user can actually sign up
            if($raidplan->getSignupsAllowed()== true
                && $raidplan->getLocked() == false
                && $raidplan->getFrozen() == false
                && $raidplan->getNochar() == false
                && $raidplan->getSignedUp() == false
                && $raidplan->getSignedOff() == false
                && $raidplan->getAccesslevel() != 0
                && !$user->data['is_bot']
                && $user->data['user_id'] != ANONYMOUS)
            {
                foreach ($raidplan->getMychars() as $key => $mychar)
                {
                    if($mychar['role_id'] == '')
                    {
                        $userchars[] = array(
                            'MEMBER_ID'      	=> $mychar['id'],
                            'MEMBER_NAME'  	 	=> $mychar['name'],

                        );
                    }
                }

                foreach($raidplan->getRoles() as $key => $role)
                {
                    $rolesinfo[] = array(
                        'ROLE_ID'        => $key,
                        'ROLE_NAME'      => $role['role_name'],
                    );
                    //@todo fix
                   //$total_needed += $role['role_needed'];
                }
            }

            if(strlen( $raidplan->getEventlist()->events[$raidplan->getEventType()]['imagename'] ) > 1)
            {
                $eventimg = $phpbb_root_path . "images/bbdkp/event_images/" . $raidplan->getEventlist()->events[$raidplan->getEventType()]['imagename'] . ".png";

            }
            else
            {
                $eventimg = $phpbb_root_path . "images/bbdkp/event_images/dummy.png";
            }

            $raidinfo = array(
                'TZ'					=> $timezone,
                'RAID_ID'				=> $raidplan->id,
                'PRE_PADDING'			=> $pre_padding,
                'POST_PADDING'			=> $post_padding,
                'PADDING'				=> $padding,
                'ETYPE_DISPLAY_NAME' 	=> $raidplan->getEventlist()->events[$raidplan->getEventType()]['event_name'],
                'FULL_SUBJECT' 			=> $fsubj,
                'EVENT_SUBJECT' 		=> $subj,
                'COLOR' 				=> $raidplan->getEventlist()->events[$raidplan->getEventType()]['color'],
                'IMAGE' 				=> $eventimg,
                'EVENT_URL'  			=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=".$raidplan->id),
                'EVENT_ID'  			=> $raidplan->id,

                // for popup
                'S_ANON'				=> ($user->data['user_id'] == ANONYMOUS) ? true : false,
                'S_LOCKED'				=> $raidplan->getLocked(),
                'S_FROZEN'				=> $raidplan->getFrozen(),
                'S_NOCHAR'				=> $raidplan->getNochar(),
                'S_SIGNED_UP'			=> $raidplan->getSignedUp(),
                'S_SIGNED_OFF'			=> $raidplan->getSignedOff(),
                'S_CONFIRMED'			=> $raidplan->getConfirmed(),
                'S_SIGNUPMAYBE'			=> $raidplan->getSignedUpMaybe(),
                'S_CANSIGNUP'			=> $raidplan->getSignupsAllowed(),
                'S_LEGITUSER'			=> ($user->data['is_bot'] || $user->data['user_id'] == ANONYMOUS) ? false : true,
                'S_SIGNUP_MODE_ACTION' 	=> append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=".$raidplan->id. "&amp;action=signup"),

                'INVITE_TIME'  			=> $user->format_date($raidplan->getInviteTime(), $correct_format, true),
                'START_TIME'			=> $user->format_date($raidplan->getStartTime(), $correct_format, true),
                'END_TIME' 				=> $user->format_date($raidplan->getEndTime(), $correct_format, true),

                'DISPLAY_BOLD'			=> ($user->data['user_id'] == $raidplan->getPoster()) ? true : false,
                'ALL_DAY'				=> ($raidplan->getAllDay() == 1  ) ? true : false,
                'SHOW_TIME'				=> ($mode == "day" || $mode == "week" ) ? true : false,
                'COUNTER'				=> $raidplan_counter++,

                'RAID_TOTAL'			=> $total_needed,

                'CURR_CONFIRMED_COUNT'	 => $raidplan->getSignups()['confirmed'],
                'S_CURR_CONFIRMED_COUNT' => ($raidplan->getSignups()['confirmed'] > 0) ? true: false,
                'CURR_CONFIRMEDPCT'		=> sprintf( "%.0f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['confirmed']) /  $total_needed, 2) *100 : 0)),

                'CURR_YES_COUNT'		=> $raidplan->getSignups()['yes'],
                'S_CURR_YES_COUNT'		=> ($raidplan->getSignups()['yes'] + $raidplan->getSignups()['maybe'] > 0) ? true: false,
                'CURR_YESPCT'			=> sprintf( "%.0f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['yes']) /  $total_needed, 2) *100 : 0)),

                'CURR_MAYBE_COUNT'		=> $raidplan->getSignups()['maybe'],
                'S_CURR_MAYBE_COUNT' 	=> ($raidplan->getSignups()['maybe'] > 0) ? true: false,
                'CURR_MAYBEPCT'			=> sprintf( "%.0f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['maybe']) /  $total_needed, 2) *100 : 0)),

                'CURR_NO_COUNT'			=> $raidplan->getSignups()['no'],
                'S_CURR_NO_COUNT'		=> ($raidplan->getSignups()['no'] > 0) ? true: false,
                'CURR_NOPCT'			=> sprintf( "%.0f%%", ($total_needed > 0 ? round(($raidplan->getSignups()['no']) /  $total_needed, 2) *100 : 0)),

                'CURR_TOTAL_COUNT'  	=> $raidplan->getSignups()['yes'] + $raidplan->getSignups()['maybe'],

            );
            $rpcounter +=1;

            $hourslot = $user->format_date($raidplan->getInviteTime(), 'Hi', true);

            $raidplan_output[$hourslot . '_' . $rpcounter] = array(
                'raidinfo' => $raidinfo,
                'userchars' => $userchars,
                'raidroles' => $rolesinfo
            );

        }
        $db->sql_freeresult($result);

        return $raidplan_output;
    }

    /**
     * gets array with raid days
     * @param int $from
     * @param int $end
     *
     * @return array
     */
    public function GetRaiddaylist($from, $end)
    {
        //GMT: Tue, 01 Nov 2011 00:00:00 GMT
        global $user, $db;

        // build sql
        $sql_array = array(
            'SELECT'    => 'r.raidplan_start_time ',
            'FROM'		=> array(RP_RAIDS_TABLE => 'r' ),
            'WHERE'		=>  ' r.raidplan_start_time >= '. $db->sql_escape($from) . '
							 AND r.raidplan_start_time <= '. $db->sql_escape($end) ,
            'ORDER_BY'	=> 'r.raidplan_start_time ASC');

        $sql = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        $raiddaylist = array();
        while ($row = $db->sql_fetchrow($result))
        {

            $day = $user->format_date($row['raidplan_start_time'], "d", true);
            $month =  $user->format_date($row['raidplan_start_time'], "n", true);
            $year =  $user->format_date($row['raidplan_start_time'], "Y", true);

            // key is made to be unique
            $raiddaylist [$month . '-' . $day . '-' . $year] = array(
                'sig' => $month . '-' . $day . '-' . $year,
                'month' => $month,
                'day' => $day,
                'year' => $year
            );
        }

        $db->sql_freeresult($result);
        return $raiddaylist;

    }

    /**
     * show confirmed signups per role
     * @param Raidplan $raidplan
     * @param $role
     * @return array
     */
    private function Signups_show_confirmed(Raidplan $raidplan, $role)
    {

        global $auth, $user, $phpbb_root_path, $phpEx, $template, $config;


        foreach ($role['role_confirmations'] as $confirmation)
        {
            $confdetail = new RaidplanSignup();
            $confdetail->getSignup($confirmation->signup_id, $raidplan->getEventlist()->events[$raidplan->getEventType()]['dkpid']);

            $edit_text_array = generate_text_for_edit($confdetail->comment, $confdetail->bbcode['uid'], 7);
            $candeleteconf = false;
            $caneditconf = false;
            $editconfurl = "";
            $deleteconfurl = "";

            if ($auth->acl_get('m_raidplanner_edit_other_users_signups') || $confirmation->poster_id == $user->data['user_id']) {
                // then if signup is not frozen then show deletion button
                //@todo calculate frozen
                $candeleteconf = true;
                $caneditconf = true;
                $editconfurl = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=editsign&amp;raidplanid=" . $raidplan->id . "&amp;signup_id=" . $confdetail->signup_id);
                $deleteconfurl = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=delsign&amp;raidplanid=" . $raidplan->id . "&amp;signup_id=" . $confdetail->signup_id);
            }

            $signupcolor = '#006B02';
            $signuptext = $user->lang['CONFIRMED'];

            $template->assign_block_vars('raidroles.confirmations', array(
                'DKP_CURRENT' => ($config['bbdkp_epgp'] == 1) ? $confdetail->priority_ratio : $confdetail->dkp_current,
                'ATTENDANCEP1' => $confdetail->attendanceP1,
                'U_MEMBERDKP' => $confdetail->dkmemberpurl,
                'SIGNUP_ID' => $confdetail->signup_id,
                'RAIDPLAN_ID' => $confdetail->raidplan_id,
                'POST_TIME' => $user->format_date($confdetail->signup_time, $config['rp_date_time_format'], true),
                'POST_TIMESTAMP' => $confdetail->signup_time,
                'DETAILS' => generate_text_for_display($confdetail->comment, $confdetail->bbcode['uid'], $confdetail->bbcode['bitfield'], 7),
                'EDITDETAILS' => $edit_text_array['text'],
                'HEADCOUNT' => $confdetail->signup_count,
                'POSTER' => $confdetail->poster_name,
                'POSTER_URL' => get_username_string('full', $confdetail->poster_id, $confdetail->poster_name, $confdetail->poster_colour),
                'VALUE' => $confdetail->signup_val,
                'COLOR' => $signupcolor,
                'VALUE_TXT' => $signuptext,
                'CHARNAME' => $confdetail->dkpmembername,
                'LEVEL' => $confdetail->level,
                'CLASS' => $confdetail->classname,
                'COLORCODE' => ($confdetail->colorcode == '') ? '#123456' : $confdetail->colorcode,
                'CLASS_IMAGE' => (strlen($confdetail->imagename) > 1) ? $confdetail->imagename : '',
                'S_CLASS_IMAGE_EXISTS' => (strlen($confdetail->imagename) > 1) ? true : false,
                'RACE_IMAGE' => (strlen($confdetail->raceimg) > 1) ? $confdetail->raceimg : '',
                'S_RACE_IMAGE_EXISTS' => (strlen($confdetail->raceimg) > 1) ? true : false,
                'S_DELETE_SIGNUP' => $candeleteconf,
                'S_EDIT_SIGNUP' => $caneditconf,
                'S_SIGNUP_EDIT_ACTION' => $editconfurl,
                'U_DELETE' => $deleteconfurl,
            ));

        }

    }

    /**
     * loop available signups per role
     * @param Raidplan $raidplan
     * @param $role
     */
    private function Signups_show_available(Raidplan $raidplan, $role)
    {
        global $user, $auth, $phpbb_root_path, $phpEx, $template, $config;

        foreach ($role['role_signups'] as $signup)
        {
            $signupdetail = new RaidplanSignup();
            $signupdetail->getSignup($signup->signup_id, $raidplan->getEventlist()->events[$raidplan->getEventType()]['dkpid']);
            $edit_text_array = generate_text_for_edit($signupdetail->comment, $signupdetail->bbcode['uid'], 7);

            if ($signupdetail->signup_val == 1) {
                $signupcolor = '#C9B634';
                $signuptext = $user->lang['MAYBE'];
            } elseif ($signupdetail->signup_val == 2) {
                $signupcolor = '#FFB100';
                $signuptext = $user->lang['YES'];
            } elseif ($signupdetail->signup_val == 3) {
                $signupcolor = '#006B02';
                $signuptext = $user->lang['CONFIRMED'];
            }

            // if user can delete other signups ?
            $confirm_signup_url = "";
            $canconfirmsignup = false;
            if ($auth->acl_get('m_raidplanner_edit_other_users_signups')) {
                $canconfirmsignup = true;
                $confirm_signup_url = append_sid("{$phpbb_root_path}dkp.$phpEx",
                    "page=planner&amp;view=raidplan&amp;action=confirm&amp;raidplanid=" . $raidplan->id . "&amp;signup_id=" . $signupdetail->signup_id);
            }

            // if user can delete other signups or if own signup
            $candeletesignup = false;
            $caneditsignup = false;
            $deletesignupurl = "";
            $editsignupurl = "";
            $deletekey = 0;
            if ($auth->acl_get('m_raidplanner_edit_other_users_signups') || $signupdetail->poster_id == $user->data['user_id']) {
                // then if signup is not frozen then show deletion button
                //@todo calculate frozen
                $candeletesignup = true;
                $caneditsignup = true;
                $editsignupurl = append_sid("{$phpbb_root_path}dkp.$phpEx",
                    "page=planner&amp;view=raidplan&amp;action=editsign&amp;raidplanid=" . $raidplan->id . "&amp;signup_id=" . $signupdetail->signup_id);
                $deletekey = rand(1, 1000);
                $deletesignupurl = append_sid("{$phpbb_root_path}dkp.$phpEx",
                    "page=planner&amp;view=raidplan&amp;action=delsign&amp;raidplanid=" . $raidplan->id . "&amp;signup_id=" . $signupdetail->signup_id);
            }

            $template->assign_block_vars('raidroles.signups', array(
                'DKP_CURRENT' => ($config['bbdkp_epgp'] == 1) ? $signupdetail->priority_ratio : $signupdetail->dkp_current,
                'ATTENDANCEP1' => $signupdetail->attendanceP1,
                'U_MEMBERDKP' => $signupdetail->dkmemberpurl,
                'SIGNUP_ID' => $signupdetail->signup_id,
                'RAIDPLAN_ID' => $signupdetail->raidplan_id,
                'POST_TIME' => $user->format_date($signupdetail->signup_time, $config['rp_date_time_format'], true),
                'POST_TIMESTAMP' => $signup->signup_time,
                'DETAILS' => generate_text_for_display($signupdetail->comment, $signupdetail->bbcode['uid'], $signupdetail->bbcode['bitfield'], 7),
                'EDITDETAILS' => $edit_text_array['text'],
                'HEADCOUNT' => $signupdetail->signup_count,
                'POSTER' => $signupdetail->poster_name,
                'POSTER_URL' => get_username_string('full', $signupdetail->poster_id, $signupdetail->poster_name, $signupdetail->poster_colour),
                'VALUE' => $signupdetail->signup_val,
                'COLOR' => $signupcolor,
                'VALUE_TXT' => $signuptext,
                'CHARNAME' => $signupdetail->dkpmembername,
                'LEVEL' => $signupdetail->level,
                'CLASS' => $signupdetail->classname,
                'COLORCODE' => ($signupdetail->colorcode == '') ? '#123456' : $signupdetail->colorcode,
                'CLASS_IMAGE' => (strlen($signupdetail->imagename) > 1) ? $signupdetail->imagename : '',
                'S_CLASS_IMAGE_EXISTS' => (strlen($signupdetail->imagename) > 1) ? true : false,
                'RACE_IMAGE' => (strlen($signupdetail->raceimg) > 1) ? $signupdetail->raceimg : '',
                'S_RACE_IMAGE_EXISTS' => (strlen($signupdetail->raceimg) > 1) ? true : false,
                'S_DELETE_SIGNUP' => $candeletesignup,
                'S_EDIT_SIGNUP' => $caneditsignup,
                'S_SIGNUPMAYBE' => $raidplan->getSignedUpMaybe(),
                'S_SIGNUP_EDIT_ACTION' => $editsignupurl,
                'U_DELETE' => $deletesignupurl,
                'DELETEKEY' => $deletekey,
                'S_CANCONFIRM' => $canconfirmsignup,
                'U_CONFIRM' => $confirm_signup_url,

            ));

        }

    }

    /**
     * display signoffs
     * @param Raidplan $raidplan
     *
     */
    private function Signups_display_notavail(Raidplan $raidplan)
    {
        global $auth, $user, $phpbb_root_path, $phpEx, $template, $config;
        foreach ($raidplan->signoffs as $key => $signoff)
        {

            $signoffdetail = new RaidplanSignup();
            $signoffdetail->getSignup($signoff->signup_id, $raidplan->getEventlist()->events[$raidplan->getEventType()]['dkpid']);
            $edit_text_array = generate_text_for_edit($signoffdetail->comment, $signoffdetail->bbcode['uid'], 7);

            $requeue = false;
            $requeueurl = "";
            // allow requeueing your character
            if ($auth->acl_get('m_acl_m_raidplanner_delete_other_users_raidplans') || $signoffdetail->poster_id == $user->data['user_id']) {
                $requeue = true;
                $requeueurl = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;action=requeue&amp;raidplanid=" . $raidplan->id . "&amp;signup_id=" . $signoffdetail->signup_id);
            }

            $template->assign_block_vars('unavailable', array(
                'DKP_CURRENT' => ($config['bbdkp_epgp'] == 1) ? $signoffdetail->priority_ratio : $signoffdetail->dkp_current,
                'ATTENDANCEP1' => $signoffdetail->attendanceP1,
                'U_MEMBERDKP' => $signoffdetail->dkmemberpurl,
                'SIGNUP_ID' => $signoff->signup_id,
                'RAIDPLAN_ID' => $signoff->raidplan_id,
                'POST_TIME' => $user->format_date($signoff->signup_time, $config['rp_date_time_format'], true),
                'POST_TIMESTAMP' => $signoff->signup_time,
                'DETAILS' => generate_text_for_display($signoff->comment, $signoff->bbcode['uid'], $signoff->bbcode['bitfield'], 7),
                'EDITDETAILS' => $edit_text_array['text'],
                'POSTER' => $signoff->poster_name,
                'POSTER_URL' => get_username_string('full', $signoff->poster_id, $signoff->poster_name, $signoff->poster_colour),
                'VALUE' => $signoff->signup_val,
                'COLOR' => '#FF0000',
                'VALUE_TXT' => $user->lang['NO'],
                'CHARNAME' => $signoff->dkpmembername,
                'LEVEL' => $signoff->level,
                'CLASS' => $signoff->classname,
                'COLORCODE' => ($signoff->colorcode == '') ? '#123456' : $signoff->colorcode,
                'CLASS_IMAGE' => (strlen($signoff->imagename) > 1) ? $signoff->imagename : '',
                'S_CLASS_IMAGE_EXISTS' => (strlen($signoff->imagename) > 1) ? true : false,
                'RACE_IMAGE' => (strlen($signoff->raceimg) > 1) ? $signoff->raceimg : '',
                'S_RACE_IMAGE_EXISTS' => (strlen($signoff->raceimg) > 1) ? true : false,
                'S_REQUEUE_ACTION' => $requeueurl,
                'S_REQUEUE_SIGNUP' => $requeue,

            ));

            foreach ($raidplan->getRaidroles() as $key1 => $role)
            {
                $template->assign_block_vars('unavailable.raidroles', array(
                    'ROLE_ID' => $key1,
                    'ROLE_NAME' => $role['role_name'],
                ));

            }
        }
        unset($signoff);
        unset($key);
    }


    /**
     * display raidplan add/edit form
     *
     * @param Raidplan $raidplan
     * @param RaidCalendar $cal
     */
    public function showadd(Raidplan $raidplan, RaidCalendar $cal)
    {
        global $db, $auth, $user, $config, $template, $phpEx, $phpbb_root_path;
        include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
        $s_action = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=raidplan&amp;raidplanid=". $raidplan->getId() ."&amp;action=showadd");

        /*
         * fill template
         *
         */
        $user->setup('posting');
        $user->add_lang ( array ('posting', 'mods/dkp_common','mods/raidplanner'  ));

        if($raidplan->getId() > 0)
        {
            $page_title = $user->lang['CALENDAR_EDIT_RAIDPLAN'];
        }
        else
        {
            $page_title = $user->lang['CALENDAR_POST_RAIDPLAN'];
        }

        //count events from bbDKP, put them in a pulldown...
        foreach( $raidplan->getEventlist()->events as $eventid => $event)
        {
            $selected = '';

            if($raidplan->id == 0)
            {
                $selected = '';
            }
            else
            {
                if($raidplan->getEventType() == $eventid)
                {
                    $selected = ' selected="selected" ';
                }
            }

            $template->assign_block_vars('bbdkp_events_options', array(
                'KEY' 		=> $eventid,
                'VALUE' 	=> $event['event_name'] ,
                'SELECTED' 	=> $selected,
            ));

        }

        // populate raidplan acces level pulldowns
        $level_sel = array();
        if( $auth->acl_get('u_raidplanner_create_public_raidplans') )
        {
            $level_sel[2] = $user->lang['EVENT_ACCESS_LEVEL_PUBLIC'];
        }
        if( $auth->acl_get('u_raidplanner_create_group_raidplans') )
        {
            $level_sel[1] = $user->lang['EVENT_ACCESS_LEVEL_GROUP'];
        }
        if( $auth->acl_get('u_raidplanner_create_private_raidplans') )
        {
            $level_sel[0] =  $user->lang['EVENT_ACCESS_LEVEL_PERSONAL'];
        }

        foreach($level_sel as $key => $value)
        {
            $template->assign_block_vars('accesslevel_options', array(
                'KEY' 		=> $key,
                'VALUE' 	=> $value ,
                'SELECTED' 	=>  ($raidplan->getAccesslevel() == $key) ? ' selected="selected"' : '',
            ));
        }

        // Find what groups this user is a member of and add them to the list of groups to invite
        $disp_hidden_groups = $config['rp_display_hidden_groups'];
        if ( $auth->acl_get('u_raidplanner_nonmember_groups') )
        {
            if( $disp_hidden_groups == 1 )
            {
                $sql = 'SELECT g.group_id, g.group_name, g.group_type
						FROM ' . GROUPS_TABLE . ' g
						ORDER BY g.group_type, g.group_name';
            }
            else
            {
                $sql = 'SELECT g.group_id, g.group_name, g.group_type
						FROM ' . GROUPS_TABLE . ' g
						' . ((!$auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel')) ? ' 	WHERE g.group_type <> ' . GROUP_HIDDEN : '') . '
						ORDER BY g.group_type, g.group_name';
            }
        }
        else
        {
            if( $disp_hidden_groups == 1 )
            {
                $sql = 'SELECT g.group_id, g.group_name, g.group_type
						FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . " ug
						WHERE ug.user_id = ". $db->sql_escape($user->data['user_id']).'
							AND g.group_id = ug.group_id
							AND ug.user_pending = 0
						ORDER BY g.group_type, g.group_name';
            }
            else
            {
                $sql = 'SELECT g.group_id, g.group_name, g.group_type
						FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . " ug
						WHERE ug.user_id = ". $db->sql_escape($user->data['user_id'])."
							AND g.group_id = ug.group_id" . ((!$auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel')) ? ' 	AND g.group_type <> ' . GROUP_HIDDEN : '') . '
							AND ug.user_pending = 0
						ORDER BY g.group_type, g.group_name';
            }
        }

        $result = $db->sql_query($sql);

        while ($row = $db->sql_fetchrow($result))
        {
            $template->assign_block_vars('group_sel_options', array(
                'KEY' 		=> $row['group_id'],
                'VALUE' 	=> (($row['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $row['group_name']] : $row['group_name']) ,
                'SELECTED' 	=> '',
            ));
        }
        $db->sql_freeresult($result);

        /**
         *	populate Raid invite time select
         */
        $hour_mode = $config['rp_hour_mode'];
        $presetinvhour = intval( ($raidplan->getInviteTime() > 0 ? $user->format_date($raidplan->getInviteTime(), 'G', true) * 60: $config['rp_default_invite_time']) / 60);
        for( $i = 0; $i < 24; $i++ )
        {
            $mod_12 = $i % 12;
            if( $mod_12 == 0 )
            {
                $mod_12 = 12;
            }
            $am_pm = $user->lang['PM'];
            if( $i < 12 )
            {
                $am_pm = $user->lang['AM'];
            }
            $template->assign_block_vars('invhouroptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> ($hour_mode == 12) ? $mod_12.' '.$am_pm : $i,
                'SELECTED' 	=> ($i == $presetinvhour) ? ' selected="selected"' : '',
            ));

        }

        // minute
        if ( $raidplan->getInviteTime() > 0 )
        {
            $presetinvmin = $user->format_date($raidplan->getInviteTime(), 'i', true);
        }
        else
        {
            $presetinvmin = (int) $config['rp_default_invite_time'] - ($presetinvhour * 60) ;
        }

        for( $i = 0; $i <= 59; $i++ )
        {
            $template->assign_block_vars('invminoptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> $i,
                'SELECTED' 	=> ($i == $presetinvmin) ? ' selected="selected"' : '',
            ));
        }

        /**
         *	populate Raid start hour pulldown
         */
        $hour_start_selcode = "";
        $presetstarthour = intval( ($raidplan->getStartTime() > 0 ? $user->format_date($raidplan->getStartTime(), 'G', true) * 60: $config['rp_default_start_time']) / 60);
        for( $i = 0; $i < 24; $i++ )
        {
            $mod_12 = $i % 12;
            if( $mod_12 == 0 )
            {
                $mod_12 = 12;
            }
            $am_pm = $user->lang['PM'];
            if( $i < 12 )
            {
                $am_pm = $user->lang['AM'];
            }
            $template->assign_block_vars('starthouroptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> ($hour_mode == 12) ? $mod_12.' '.$am_pm : $i,
                'SELECTED' 	=> ($i == $presetstarthour) ? ' selected="selected"' : '',
            ));

        }

        /**
         *	populate Raid start minute pulldown
         */
        if($raidplan->id > 0)
        {
            $presetstartmin = $user->format_date($raidplan->getStartTime(), 'i', true);
        }
        else
        {
            $presetstartmin = (int) $config['rp_default_start_time'] - ($presetstarthour * 60) ;
        }

        for( $i = 0; $i <= 59; $i++ )
        {
            $template->assign_block_vars('startminoptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> $i,
                'SELECTED' 	=> ($i == $presetstartmin ) ? ' selected="selected"' : '',
            ));
        }

        /**
         * populate end day pulldown
         */
        for( $i = 1; $i <= $cal->days_in_month; $i++ )
        {
            $template->assign_block_vars('enddayoptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> $i,
                'SELECTED' 	=> ( (int) $cal->date['day'] == $i ) ? ' selected="selected"' : '',
            ));
        }

        // month dropdown
        for( $i = 1; $i <= 12; $i++ )
        {
            $template->assign_block_vars('endmonthoptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> $user->lang['datetime'][$cal->month_names[$i]],
                'SELECTED' 	=> ($cal->date['month_no'] == $i ) ? ' selected="selected"' : '',
            ));
        }

        $temp_year	= gmdate('Y');
        for( $i = $temp_year - 1; $i < ($temp_year + 5); $i++ )
        {
            $template->assign_block_vars('endyearoptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> $i,
                'SELECTED' 	=> ( (int) $cal->date['year'] == $i ) ? ' selected="selected"' : '',
            ));
        }

        /**
         *	populate Raid END time pulldown
         */
        $presetendhour = intval( ($raidplan->getEndTime() > 0 ? $user->format_date($raidplan->getEndTime(), 'G', true) * 60: $config['rp_default_end_time']) / 60);
        for( $i = 0; $i < 24; $i++ )
        {

            $mod_12 = $i % 12;
            if( $mod_12 == 0 )
            {
                $mod_12 = 12;
            }
            $am_pm = $user->lang['PM'];
            if( $i < 12 )
            {
                $am_pm = $user->lang['AM'];
            }

            $template->assign_block_vars('endhouroptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> ($hour_mode == 12) ? $mod_12.' '.$am_pm : $i,
                'SELECTED' 	=> ($i == $presetendhour) ? ' selected="selected"' : '',
            ));
        }

        /**
         *	populate Raid end minute pulldown
         */
        if ( $raidplan->getEndTime() > 0 )
        {
            $presetendmin = $user->format_date($raidplan->getEndTime(), 'i', true);
        }
        else
        {
            $presetendmin = (int) $config['rp_default_end_time'] - ($presetendhour * 60) ;
        }

        for( $i = 0; $i <= 59; $i++ )
        {
            $template->assign_block_vars('endminuteoptions', array(
                'KEY' 		=> $i,
                'VALUE' 	=> $i,
                'SELECTED' 	=> ($i == $presetendmin) ? ' selected="selected"' : '',
            ));
        }
        // format and translate to user timezone + dst
        //$invite_date_txt = $user->format_date($raidplan->getInviteTime(), $config['rp_date_time_format'], true);
        //$start_date_txt = $user->format_date($raidplan->getStartTime(), $config['rp_date_time_format'], true);
        //$end_date_txt = $user->format_date($raidplan->getEndTime(), $config['rp_date_time_format'], true);
        $day_view_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=day&amp;calD=".$cal->date['day'] .
                "&amp;calM=".$cal->date['month_no'].
                "&amp;calY=".$cal->date['year']);
        $week_view_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=week&amp;calD=".$cal->date['day'] .
                "&amp;calM=".$cal->date['month_no'].
                "&amp;calY=".$cal->date['year']);
        $month_view_url = append_sid("{$phpbb_root_path}dkp.$phpEx", "page=planner&amp;view=month&amp;calD=".$cal->date['day'].
                "&amp;calM=".$cal->date['month_no'].
                "&amp;calY=".$cal->date['year']);

        /*
         * make raid composition proposal
         */
        if($raidplan->getId() == 0)
        {
            // new raid
            $sql = 'SELECT * FROM ' . RP_TEAMS . '
					ORDER BY teams_id';
            $db->sql_query($sql);
            $result = $db->sql_query($sql);
            while ( $row = $db->sql_fetchrow ( $result ) )
            {
                $team_id = $row ['teams_id'];
                $template->assign_block_vars( 'team_row', array (
                    'VALUE' => $row ['teams_id'],
                    'SELECTED' => ' selected="selected"',
                    'OPTION' => $row ['team_name'] . ': ' .  $row['team_needed']));
            }
            $db->sql_freeresult($result);

            // make roles proposal
            $sql_array = array(
                'SELECT'    => 't.team_needed, r.role_id, r.role_name , r.role_color, r.role_icon ',
                'FROM'      => array(
                    RP_TEAMSIZE => 't',
                    RP_ROLES   	=> 'r'
                ),
                'ORDER_BY'  => 'r.role_id',
                'WHERE'  	=> 'r.role_id = t.role_id AND t.teams_id = ' . $team_id
            );
            $sql = $db->sql_build_query('SELECT', $sql_array);
            $result = $db->sql_query($sql);
            while ($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('teamsize', array(
                    'ROLE_COLOR'     => $row['role_color'],
                    'S_ROLE_ICON_EXISTS'	=>  (strlen($row['role_icon']) > 1) ? true : false,
                    'ROLE_ICON'      => (strlen($row['role_icon']) > 1) ? $phpbb_root_path .
                            "images/bbdkp/raidrole_images/" . $row['role_icon'] . ".png" : '',
                    'ROLE_ID'        => $row['role_id'],
                    'ROLE_NAME'      => $row['role_name'],
                    'ROLE_NEEDED'    => $row['team_needed'],
                ));
            }
            $db->sql_freeresult($result);

        }
        else
        {
            //repopulate dropdown from object
            $sql = 'SELECT * FROM ' . RP_TEAMS . '
					ORDER BY teams_id';
            $db->sql_query($sql);
            $result = $db->sql_query($sql);
            while ( $row = $db->sql_fetchrow ( $result ) )
            {
                $template->assign_block_vars( 'team_row', array (
                    'VALUE' 	=> $row ['teams_id'],
                    'SELECTED' 	=> ($row ['teams_id'] == $raidplan->getRaidteam()) ? ' selected="selected"' : '',
                    'OPTION' 	=> $row ['team_name'] . ': ' .  $row['team_needed']));
            }
            $db->sql_freeresult($result);
            unset($row);

            // get roles from object

            foreach($raidplan->getRaidroles() as $key => $role)
            {
                $template->assign_block_vars('teamsize', array(
                    'ROLE_COLOR'     => $role['role_color'],
                    'S_ROLE_ICON_EXISTS'	=>  (strlen($role['role_icon']) > 1) ? true : false,
                    'ROLE_ICON'      => (strlen($role['role_icon']) > 1) ? $phpbb_root_path . "images/bbdkp/raidrole_images/" . $role['role_icon'] . ".png" : '',
                    'ROLE_ID'        => $key,
                    'ROLE_NAME'      => $role['role_name'],
                    'ROLE_NEEDED'    => $role['role_needed'],
                ));
            }


        }

        $body = $raidplan->getBody();
        $bbcode = $raidplan->getBbcode();
        $message = generate_text_for_edit($body, isset($bbcode['uid'])  ? $bbcode['uid'] : '', isset($bbcode['bitfield']) ? $bbcode['bitfield'] : '', 7);

        // HTML, BBCode, Smilies, Images and Flash status
        $bbcode_status	= ($config['allow_bbcode']) ? true : false;
        $img_status		= ($bbcode_status) ? true : false;
        $flash_status	= ($bbcode_status && $config['allow_post_flash']) ? true : false;
        $url_status		= ($config['allow_post_links']) ? true : false;
        $smilies_status	= ($bbcode_status && $config['allow_smilies']) ? true : false;

        if ($smilies_status)
        {
            // Generate smiley listing
            $cal->generate_calendar_smilies('inline');
        }

        $inv_d = $cal->date['day'];
        $inv_m = $cal->date['month_no'];
        $inv_y = $cal->date['year'];

        $start_hr = request_var('calHr', 0);
        $start_mn = request_var('calMn', 0);
        $start_date = gmmktime(0, 0, 0, $inv_m, $inv_d, $inv_y) - $user->timezone - $user->dst;

        $ajaxpath = append_sid($phpbb_root_path . 'styles/' . $user->theme['template_path'] . '/template/planner/raidplan/ajax1.'. $phpEx, "ajax=1");
        $template->assign_vars(array(
            'S_POST_ACTION'				=> $s_action,
            'RAIDPLAN_ID'				=> $raidplan->id,
            'S_EDIT'					=> ($raidplan->id > 0) ? true : false,
            'S_DELETE_ALLOWED'			=> $raidplan->getAuthCandelete(),
            'S_BBCODE_ALLOWED'			=> $bbcode_status,
            'S_SMILIES_ALLOWED'			=> $smilies_status,
            'S_LINKS_ALLOWED'			=> $url_status,
            'S_BBCODE_IMG'				=> $img_status,
            'S_BBCODE_URL'				=> $url_status,
            'S_BBCODE_FLASH'			=> $flash_status,
            'S_BBCODE_QUOTE'			=> false,
            'S_PLANNER_ADD'				=> true,
            'TEAM_ID'					=> $raidplan->getRaidteam(),
            'TEAM_NAME'					=> $raidplan->getRaidteamname(),
            //'TEAM_SIZE'				=> $teamsize,
            'L_POST_A'					=> $page_title,
            'SUBJECT'					=> $raidplan->getSubject(),
            'MESSAGE'					=> $message['text'],
            'START_DATE'				=> $user->format_date($start_date, $config['rp_date_format'], true),
            'START_HOUR_SEL'			=> $hour_start_selcode,
            'DAY_VIEW_URL'				=> $day_view_url,
            'WEEK_VIEW_URL'				=> $week_view_url,
            'MONTH_VIEW_URL'			=> $month_view_url,

            'BBCODE_STATUS'				=> ($bbcode_status) ?
                    sprintf($user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>') :
                    sprintf($user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>'),
            'IMG_STATUS'				=> ($img_status) ? $user->lang['IMAGES_ARE_ON'] : $user->lang['IMAGES_ARE_OFF'],
            'FLASH_STATUS'				=> ($flash_status) ? $user->lang['FLASH_IS_ON'] : $user->lang['FLASH_IS_OFF'],
            'SMILIES_STATUS'			=> ($smilies_status) ? $user->lang['SMILIES_ARE_ON'] : $user->lang['SMILIES_ARE_OFF'],
            'URL_STATUS'				=> ($bbcode_status && $url_status) ? $user->lang['URL_IS_ON'] : $user->lang['URL_IS_OFF'],

            //javascript alerts
            'LA_ALERT_OLDBROWSER' 		=> $user->lang['ALERT_OLDBROWSER'],
            'UA_AJAXHANDLER1'		  	=> $ajaxpath,
        ));

        // Build custom bbcodes array
        display_custom_bbcodes();
    }


    /**
     * collect data from POST after submit or update in showadd
     *
     * @param $raidplan
     * @param $submit
     * @param $update
     */
    public function SetRaidplan(Raidplan $raidplan, $submit, $update)
    {
        global $user, $template;
        $error = array();

        // read subjectline
        $raidplan->setSubject(utf8_normalize_nfc(request_var('subject', '', true)));

        //read comment section
        $body = utf8_normalize_nfc(request_var('message', '', true));
        $bitfield = $uid = $options = '';
        $allow_bbcode = $allow_urls = $allow_smilies = true;
        generate_text_for_storage($body, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

        $raidplan->setBbcode(array($uid, $bitfield));
        $raidplan->setBody($body);
        $raidplan->setPoster($user->data['user_id']);

        // set access level
        $raidplan->setAccesslevel(request_var('accesslevel', 0));

        // set member group id
        $raidplan->setGroupId(0);
        $raidplan->setGroupIdList(',');
        $group_id_array = request_var('calGroupId', array(0));
        $num_group_ids = sizeof($group_id_array);
        if ($num_group_ids == 1)
        {
            // if only one group pass the groupid
            $raidplan->setGroupId($group_id_array[0]);
        }
        elseif ($num_group_ids > 1)
        {
            // if we want multiple groups then pass the array
            for ($group_index = 0; $group_index < $num_group_ids; $group_index++)
            {
                if ($group_id_array[$group_index] == "")
                {
                    continue;
                }
                $raidplan->setGroupIdList($group_id_array[$group_index] . ",");
            }
        }

        //assign raid team
        switch ($raidplan->getAccesslevel())
        {
            case 0:
                //non raid, manual event.
                $raidplan->setSignupsAllowed(0);
                $raidplan->setRaidteam(0);
                break;
            case 1:
                // if we selected group access but didn't actually choose a group then throw error
                if ($num_group_ids < 1)
                {
                    $error[] = $user->lang['NO_GROUP_SELECTED'];
                }
            //no break!
            case 2:
                //everyone invited
                $raidplan->setRaidteam(request_var('teamselect', request_var('team_id', 0)));
                $raidplan->setSignupsAllowed(1);
                $raidplan->init_raidplan_roles();
                $raidroles = request_var('role_needed', array(0 => 0));
                foreach ($raidroles as $role_id => $needed)
                {
                    $raidplan->setRaidroles($role_id, (int)$needed);
                }

        }

        //set event type
        $raidplan->setEventType(request_var('bbdkp_events', 0));

        // invite/start date values from pulldown click
        $inv_d = request_var('calD', 0);
        $inv_m = request_var('calM', 0);
        $inv_y = request_var('calY', 0);
        $inv_hr = request_var('calinvHr', 0);
        $inv_mn = request_var('calinvMn', 0);
        $raidplan->setInviteTime(gmmktime($inv_hr, $inv_mn, 0, $inv_m, $inv_d, $inv_y) - $user->timezone - $user->dst);

        $start_hr = request_var('calHr', 0);
        $start_mn = request_var('calMn', 0);
        $raidplan->setStartTime(gmmktime($start_hr, $start_mn, 0, $inv_m, $inv_d, $inv_y) - $user->timezone - $user->dst);

        $end_m = request_var('calMEnd', 0);
        $end_d = request_var('calDEnd', 0);
        $end_y = request_var('calYEnd', 0);
        $end_hr = request_var('calEndHr', 0);
        $end_mn = request_var('calEndMn', 0);
        $raidplan->setEndTime(gmmktime($end_hr, $end_mn, 0, $end_m, $end_d, $end_y) - $user->timezone - $user->dst);

        if ($raidplan->getEndTime() < $raidplan->getStartTime())
        {
            //check for enddate before begindate
            // if the end hour is earlier than start hour then roll over a day
            $temp = $raidplan->getEndTime();
            $raidplan->setEndTime($temp += 3600 * 24);
        }

        //if this is not an "all day event"
        $raidplan->setAllDay(0);
        $raidplan->setday(sprintf('%2d-%2d-%4d', $inv_d, $inv_m, $inv_y));


        $raidplan->Check_auth();

        if (count($error) > 0)
        {
            trigger_error(implode($error, "<br /> "), E_USER_WARNING);
        }

        $str = serialize($raidplan);
        $str1 = base64_encode($str);

        if ($submit)
        {

            if (!$raidplan->getAuthCanadd())
            {
                trigger_error('USER_CANNOT_POST_RAIDPLAN');
            }

            $s_hidden_fields = build_hidden_fields(array(
                    'addraid' => true,
                    'raidobject' => $str1
                )
            );

            $template->assign_vars(array(
                    'S_HIDDEN_FIELDS' => $s_hidden_fields)
            );
            confirm_box(false, $user->lang['CONFIRM_ADDRAID'], $s_hidden_fields);
        }

        if ($update)
        {
            if (!$raidplan->getAuthCanedit())
            {
                trigger_error('USER_CANNOT_EDIT_RAIDPLAN');
            }

            $s_hidden_fields = build_hidden_fields(array(
                    'updateraid' => true,
                    'raidobject' => $str1,
                    'raidplan_id' => $this->id
                )
            );

            $template->assign_vars(array(
                    'S_HIDDEN_FIELDS' => $s_hidden_fields)
            );

            confirm_box(false, $user->lang['CONFIRM_UPDATERAID'], $s_hidden_fields);
        }
    }

}