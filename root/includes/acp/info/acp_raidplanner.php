<?php
/**
* @package raidplanner.acp
* @copyright (c) 2014 bbdkp https://github.com/bbDKP
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.13.0
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
* @package module_install
*/

class acp_raidplanner_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_raidplanner',
			'title'		=> 'ACP_RAIDPLANNER',
			'version'	=> '0.13.0',
			'modes'		=> array(
				'rp_settings'	    => array('title' => 'RP_PLANNER_SETTINGS',      'display' => 1, 'auth' => 'acl_a_raid_config', 'cat' => array('ACP_DKP_RAIDS')),
                'rp_cal_settings'	=> array('title' => 'RP_CAL_SETTINGS',      'display' => 0, 'auth' => 'acl_a_raid_config', 'cat' => array('ACP_DKP_RAIDS')),
                'rp_roles'			=> array('title' => 'RP_ROLES',             'display' => 0, 'auth' => 'acl_a_raid_config', 'cat' => array('ACP_DKP_RAIDS')),
                'rp_teams'			=> array('title' => 'RP_TEAMS',             'display' => 0, 'auth' => 'acl_a_raid_config', 'cat' => array('ACP_DKP_RAIDS')),
		),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>
