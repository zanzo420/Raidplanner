<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9.0
*/
namespace bbdkp\raidplanner;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

/**
 * Class raidmessenger
 * @package bbdkp\raidplanner
 */
class raidmessenger
{
	/**
	 * holds users that get notified on raid change or addition
	 *
	 * @var array
	 */
	public $send_user_data = array();
	
	/**
	 * initialises array with users who get notified
	 *
	 * @param int $trigger
	 */
	public function get_notifiable_users($trigger, $raidplan_id, $signup_poster = 0)
	{
		global $db;
		switch ($trigger)
		{
			case 1:
				// get all members that are invited and have a dkp account
				$sql = 'SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . MEMBER_LIST_TABLE . ' l, ' . USERS_TABLE . ' u 
						WHERE l.phpbb_user_id = u.user_id';
				break;
			case 2:
			case 3:
				// get raidplan participants and raid leader
				$sql = 'SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . RP_SIGNUPS . ' l, ' . USERS_TABLE . ' u 
						WHERE l.poster_id = u.user_id 
						AND l.raidplan_id = ' . $raidplan_id . '
						UNION 
						SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . RP_RAIDS_TABLE . ' r, ' . USERS_TABLE . ' u 
						WHERE r.poster_id = u.user_id 
						AND r.raidplan_id = ' . $raidplan_id;
			case 4:
				// get raidleader
				$sql = 'SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . RP_RAIDS_TABLE . ' r, ' . USERS_TABLE . ' u 
						WHERE r.poster_id = u.user_id 
						AND r.raidplan_id = ' . $raidplan_id;
			case 5:
			case 6:
				// get raidleader and raider
				$sql = 'SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . RP_SIGNUPS . ' l, ' . USERS_TABLE . ' u 
						WHERE l.poster_id = u.user_id 
						AND  l.signup_id = ' . $signup_poster . ' 
						AND l.raidplan_id = ' . $raidplan_id . '
						UNION
						SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . RP_RAIDS_TABLE . ' r, ' . USERS_TABLE . ' u 
						WHERE r.poster_id = u.user_id 
						AND r.raidplan_id = ' . $raidplan_id;
				
				break;
		}
		
		$result = $db->sql_query($sql);
		$this->send_user_data = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
	}
	

	
}
?>