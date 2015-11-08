<?php
/**
 * Raidplanner installer
 * @package bbDkp-installer
 * @author sajaki9@gmail.com
 * @copyright (c) 2010 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0.4
 */

define('UMIL_AUTO', true);
define('IN_PHPBB', true);
define('ADMIN_START', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
$user->add_lang ( array ('mods/raidplanner'));

// We only allow a founder install this MOD
if ($user->data['user_type'] != USER_FOUNDER)
{
    if ($user->data['user_id'] == ANONYMOUS)
    {
        login_box('', 'LOGIN');
    }

    trigger_error('NOT_AUTHORISED', E_USER_WARNING);
}

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
    trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

if (!file_exists($phpbb_root_path . 'install/index.' . $phpEx))
{
    trigger_error('Warning! Install directory has wrong name. it must be \'install\'. Please rename it and launch again.', E_USER_WARNING);
}


// only allow install when bbDKP 1.3.0 is also installed
if  (!isset ($config['bbdkp_version']) )
{
    trigger_error('bbDKP must be installed first.');
}
else
{
    if(version_compare($config['bbdkp_version'], '1.4.0') == -1 )
    {
        trigger_error('Radplanner 1.0.4 requires bbDKP 1.4.0 or higher.');
    }
}

// The name of the mod to be displayed during installation.
$mod_name = 'Raidplanner';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'bbdkp_raidplanner';

/*
* The language file which will be included when installing
*/
$language_file = 'mods/raidplanner';

/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prraidplan cut-off or stretching.
*/
$logo_img = 'install/logo.png';

$announce = encode_announcement($user->lang['RP_WELCOME_DEFAULT']);

/*
* Run Options
*/
$options = array(
);

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(

    '1.0.4'    => array(

        //adding some tables
        'table_add' => array(

            array( $table_prefix. 'rp_raids', array(
                'COLUMNS'			=> array(
                    'raidplan_id'			=> array('INT:8', NULL, 'auto_increment' ),
                    'etype_id' 			    => array('INT:8', 0),
                    'sort_timestamp' 	    => array('BINT', 0),
                    'raidplan_invite_time' 	=> array('BINT', 0),
                    'raidplan_start_time' 	=> array('BINT', 0),
                    'raidplan_end_time' 	=> array('BINT', 0),
                    'raidplan_all_day'   	=> array('UINT', 0),
                    'raidplan_day'   		=> array('VCHAR:10', ''),
                    'raidplan_subject'   	=> array('VCHAR_UNI:255', ''),
                    'raidplan_body'   		=> array('MTEXT', ''),
                    'poster_id'		 	    => array('UINT', 0),
                    'raidplan_access_level' => array('BOOL', 0),
                    'group_id' 			    => array('UINT', 0),
                    'group_id_list' 		=> array('VCHAR_UNI:255', ''),
                    'enable_bbcode' 		=> array('BOOL', 1),
                    'enable_smilies' 	=> array('BOOL', 1),
                    'enable_magic_url' 	=> array('BOOL', 1),
                    'bbcode_bitfield' 	=> array('VCHAR:255', ''),
                    'bbcode_uid' 		=> array('VCHAR:8', ''),
                    'bbcode_options'	=> array('UINT', 7),
                    'track_signups' 	=> array('BOOL', 0),
                    'signup_yes' 		=> array('UINT', 0),
                    'signup_no' 		=> array('UINT', 0),
                    'signup_maybe' 		=> array('UINT', 0),
                    'signup_confirmed'	=> array('UINT', 0),
                    'recurr_id' 		=> array('UINT', 0),
                    'raidteam'          => array('INT:8', 0),
                    'raid_id'           => array('INT:8', 0),

                ),
                'PRIMARY_KEY'	=> array('raidplan_id')),
            ),

            array($table_prefix. 'rp_signups', array(
                'COLUMNS'			=> array(
                    'signup_id'			=> array('INT:8', NULL, 'auto_increment' ),
                    'raidplan_id' 		=> array('INT:8', 0),
                    'poster_id' 		=> array('INT:8', 0),
                    'poster_name' 		=> array('VCHAR:255', ''),
                    'poster_colour' 	=> array('VCHAR:6', ''),
                    'poster_ip'   		=> array('VCHAR:40', ''),
                    'post_time'   		=> array('TIMESTAMP', 0),
                    'signup_val'   		=> array('BOOL', 0),
                    'signup_count'   	=> array('USINT', 0),
                    'signup_detail'		=> array('MTEXT', ''),
                    'bbcode_bitfield' 	=> array('VCHAR:255', ''),
                    'bbcode_uid' 		=> array('VCHAR:8', ''),
                    'bbcode_options' 	=> array('UINT', 7),
                    'dkpmember_id'		=> array('INT:8', 0),
                    'role_id'			=> array('INT:8', 0),
                    'role_confirm'		=> array('BOOL', 0),
                ),
                'PRIMARY_KEY'	=> array('signup_id'),
                'KEYS'            => array(
                    'raidplan_id'   => array('INDEX', 'raidplan_id'),
                    'poster_id'     => array('INDEX', 'poster_id'),
                    'eid_post_time' => array('INDEX', array('raidplan_id', 'post_time'))
                )
            )),

            array( $table_prefix. 'rp_raidplanroles', array(
                'COLUMNS'			=> array(
                    'raidplandet_id'	=> array('INT:8', NULL, 'auto_increment' ),
                    'raidplan_id' 		=> array('INT:8', 0),
                    'role_id' 			=> array('INT:8', 0),
                    'role_needed' 		=> array('INT:8', 0),
                    'role_signedup' 	=> array('INT:8', 0),
                    'role_confirmed' 	=> array('INT:8', 0),

                ),
                'PRIMARY_KEY'	=> array('raidplandet_id')),
            ),


            array(
                $table_prefix. 'rp_announcement' , array(
                'COLUMNS'        => array(
                    'announcement_id'    	=> array('INT:8', NULL, 'auto_increment'),
                    'announcement_title' 	=> array('VCHAR_UNI', ''),
                    'announcement_msg'   	=> array('TEXT_UNI', ''),
                    'announcement_timestamp' => array('TIMESTAMP', 0),
                    'bbcode_bitfield' 		=> array('VCHAR:255', ''),
                    'bbcode_uid' 			=> array('VCHAR:8', ''),
                    'user_id'     			=> array('INT:8', 0),
                    'bbcode_options'		=> array('UINT', 7),
                ),
                'PRIMARY_KEY'    => 'announcement_id'),
            ),

            array(
                $table_prefix. 'rp_teams' , array(
                'COLUMNS'  => array(
                    'teams_id'  => array('INT:8', NULL, 'auto_increment'),
                    'team_name' => array('VCHAR_UNI', ''),
                    'guild_id' => array('USINT', 0),
                    'game_id' => array('VCHAR:10', 0),
                    'team_size' => array('INT:8', 0),

                ),
                'PRIMARY_KEY'    => 'teams_id'),
            ),

            array(
                $table_prefix. 'rp_teamsizes' , array(
                'COLUMNS'        => array(
                    'role_id'    	  => array('INT:8', 0),
                    'teams_id'     	  => array('INT:8', 0),
                    'role_needed'  =>  array('INT:8', 0,
                        'game_id'  =>  array('VCHAR:10', 0),
                    ),
                ),
            ),

            )),

        'table_row_insert'	=> array(
            array(  $table_prefix. 'rp_announcement',
                array(
                    array(
                        'announcement_title' => 'Raid sign-up tool',
                        'announcement_timestamp' => (int) time(),
                        'announcement_msg' => $announce['text'],
                        'bbcode_uid' => $announce['uid'],
                        'bbcode_bitfield' => $announce['bitfield'],
                        'user_id' => $user->data['user_id'] ),
                ),
                array(  $table_prefix. 'rp_teams',
                    array(
                        array('team_name' => 'Team 1', 'team_needed' => 10),
                        array('team_name' => 'Team 2', 'team_needed' => 15),
                    )
                ),
                array(  $table_prefix. 'rp_teamsizes',
                    array(
                        array('role_id' => 1,  'teams_id' => 1, 'team_needed' => 1),
                        array('role_id' => 2,  'teams_id' => 1, 'team_needed' => 1),
                        array('role_id' => 3,  'teams_id' => 1, 'team_needed' => 1),
                        array('role_id' => 4,  'teams_id' => 1, 'team_needed' => 1),
                        array('role_id' => 5,  'teams_id' => 1, 'team_needed' => 2),
                        array('role_id' => 6,  'teams_id' => 1, 'team_needed' => 2),

                        array('role_id' => 1,  'teams_id' => 2, 'team_needed' => 3),
                        array('role_id' => 2,  'teams_id' => 2, 'team_needed' => 2),
                        array('role_id' => 3,  'teams_id' => 2, 'team_needed' => 2),
                        array('role_id' => 4,  'teams_id' => 2, 'team_needed' => 2),
                        array('role_id' => 5,  'teams_id' => 2, 'team_needed' => 3),
                        array('role_id' => 6,  'teams_id' => 2, 'team_needed' => 3),
                    )
                ),

            ),

        ),

        // raid permission
        'permission_add' => array(
            /* admin */
            array('u_raidplanner_push', true),
            array('a_raid_config', true),
            /* mod */
            array('m_raidplanner_edit_other_users_raidplans', true),
            array('m_raidplanner_delete_other_users_raidplans', true),
            array('m_raidplanner_edit_other_users_signups', true),

            /* user */
            array('u_raidplanner_view_raidplans', true),
            array('u_raidplanner_view_headcount', true),
            array('u_raidplanner_signup_raidplans', true),
            array('u_raidplanner_create_raidplans', true),
            array('u_raidplanner_create_public_raidplans', true),
            array('u_raidplanner_create_group_raidplans', true),
            array('u_raidplanner_create_private_raidplans', true),
            array('u_raidplanner_create_recurring_raidplans', true),
            array('u_raidplanner_edit_raidplans', true),
            array('u_raidplanner_delete_raidplans', true),

        ),

        // Assign default permissions
        'permission_set' => array(
            // can create raidplans that recur
            array('ADMINISTRATORS', 'u_raidplanner_push', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_push', 'group', true),
            /*set admin permissions */
            //may configure raidplanner
            array('ADMINISTRATORS', 'a_raid_config', 'group', true),

            /*set moderator pemissions */
            // allows editing other peoples raidplans
            array('ADMINISTRATORS', 'm_raidplanner_edit_other_users_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'm_raidplanner_edit_other_users_raidplans', 'group', true),

            // allows deleting other peoples raidplans
            array('ADMINISTRATORS', 'm_raidplanner_delete_other_users_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'm_raidplanner_edit_other_users_raidplans', 'group', true),

            // allows editing other peoples signup
            array('ADMINISTRATORS', 'm_raidplanner_edit_other_users_signups', 'group', true),
            array('GLOBAL_MODERATORS', 'm_raidplanner_edit_other_users_signups', 'group', true),

            /*set user permissions */
            // allows viewing raids
            array('ADMINISTRATORS', 'u_raidplanner_view_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_view_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_view_raidplans', 'group', true),
            array('NEWLY_REGISTERED', 'u_raidplanner_view_raidplans', 'group', true),
            array('GUESTS', 'u_raidplanner_view_raidplans', 'group', true),

            // view raid participation
            array('ADMINISTRATORS', 'u_raidplanner_view_headcount', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_view_headcount', 'group', true),
            array('REGISTERED', 'u_raidplanner_view_headcount', 'group', true),

            // allows signing up for an raidplan or raid
            array('ADMINISTRATORS', 'u_raidplanner_signup_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_signup_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_signup_raidplans', 'group', true),
            array('NEWLY_REGISTERED', 'u_raidplanner_signup_raidplans', 'group', true),
            array('GUESTS', 'u_raidplanner_signup_raidplans', 'group', true),

            // allows creating raids
            array('ADMINISTRATORS', 'u_raidplanner_create_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_create_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_create_raidplans', 'group', true),

            // allows public raidplans where every member can subscribe
            array('ADMINISTRATORS', 'u_raidplanner_create_public_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_create_public_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_create_public_raidplans', 'group', true),

            // allows group raidplans where only usergroups can subscribe
            array('ADMINISTRATORS', 'u_raidplanner_create_group_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_create_group_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_create_group_raidplans', 'group', true),

            // allows private raidplans - only for you - eg hairdresser
            array('ADMINISTRATORS', 'u_raidplanner_create_private_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_create_private_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_create_private_raidplans', 'group', true),

            // can create raidplans that recur
            array('ADMINISTRATORS', 'u_raidplanner_create_recurring_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_create_recurring_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_create_recurring_raidplans', 'group', true),

            // allows editing raids
            array('ADMINISTRATORS', 'u_raidplanner_edit_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_edit_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_edit_raidplans', 'group', true),

            // allows deleting your own raidplans
            array('ADMINISTRATORS', 'u_raidplanner_delete_raidplans', 'group', true),
            array('GLOBAL_MODERATORS', 'u_raidplanner_delete_raidplans', 'group', true),
            array('REGISTERED', 'u_raidplanner_delete_raidplans', 'group', true),


        ),

        'module_add' => array(
            // hook ucp module to ucp_dkp
            array('ucp', 'UCP_DKP', array(
                'module_basename'   => 'planner',
                'module_mode'       => array('raidplanner_registration') ,
            ),
            ),

            /* hook acp module to dkp_raids */
            array('acp', 'ACP_DKP_RAIDS', array(
                'module_basename' => 'raidplanner',
                'modes'           => array(
                    'rp_settings' ,
                    'rp_cal_settings' ,
                    'rp_teams',
                    'rp_teams_edit',
                ))),

        ),

        // adding some configs
        'config_add' => array(
            array('rp_pm_rpchange', 0, false),
            array('rp_email_rpchange', 0, false),
            array('rp_pm_signup', 0, false),
            array('rp_email_signup', 0, false),
            array('rp_rppushmode', 1, false),

            array('rp_enable_past_raids', false),
            array('rp_show_portal', 1, false),
            array('rp_first_day_of_week', 0, false),
            array('rp_index_display_week', 0, false),
            array('rp_display_next_raidplans', 5),
            array('rp_hour_mode', 24),
            array('rp_display_truncated_name', 0, false),
            array('rp_prune_frequency', 0, false),
            array('rp_last_prune', 0, false),
            array('rp_prune_limit', 31536000, false),
            array('rp_display_hidden_groups', 0, false),
            array('rp_time_format', 'H:i', false),
            array('rp_date_format', 'M d, Y', false),
            array('rp_date_time_format', 'M d, Y H:i', false),
            array('rp_disp_raidplans_only_on_start', 0, false),
            array('rp_populate_frequency', 86400, false),
            array('rp_last_populate', 0, false),
            array('rp_populate_limit', 94608000, false),
            array('rp_default_invite_time', 1200, false),
            array('rp_default_start_time', 1230, false),
            array('rp_default_end_time', 0, false),
            array('rp_default_freezetime', 60, false),
            array('rp_default_expiretime', 60, false),
            array('rp_show_raidplanner', 60, false),
            array('rp_show_welcomemsg', 1, false),
            array('rp_welcomemessage', '', false),
            array('rp_show_name', 0, false),

        ),

        'custom' => array('purgecaches', 'versionupdater'),

    ));

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

/**
 * encode announcement text
 *
 * @param unknown_type $text
 * @return unknown
 */
function encode_announcement($text)
{
    $uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
    $allow_bbcode = $allow_urls = $allow_smilies = true;
    generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
    $announce['text']=$text;
    $announce['uid']=$uid;
    $announce['bitfield']=$bitfield;
    return $announce;
}

/**
 * global function for clearing cache
 * @param $action
 * @param $version
 * @return string
 */
function purgecaches($action, $version)
{
    global $umil;

    $umil->cache_purge();
    $umil->cache_purge('imageset');
    $umil->cache_purge('template');
    $umil->cache_purge('theme');
    $umil->cache_purge('auth');

    return 'UMIL_CACHECLEARED';
}

/**
 * this function fills the plugin table.
 *
 * @param string $action
 * @param string $version
 * @return string
 */
function versionupdater($action, $version)
{
    global $table_prefix, $user, $umil;
    switch ($action)
    {
        case 'install' :
        case 'update' :
            $umil->db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_plugins WHERE name = 'raidplanner'");
            // We insert new data in the plugin table
            $umil->table_row_insert($table_prefix . 'bbdkp_plugins',
                array(
                    array(
                        'name'  => 'raidplanner',
                        'value'  => '1',
                        'version'  => $version,
                        'orginal_copyright'  => 'Sajaki',
                        'bbdkp_copyright'  => 'bbDKP Team',
                    ),
                ));

            return array('command' => sprintf($user->lang['RP_UPD_MOD'], $version) , 'result' => 'SUCCESS');

            break;

        case 'uninstall' :
            $umil->db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_plugins WHERE name = 'apply'");
            return array(
                'command' => sprintf($user->lang['RP_UNINSTALL_MOD'], $version) ,
                'result' => 'SUCCESS');
            break;

    }
}
