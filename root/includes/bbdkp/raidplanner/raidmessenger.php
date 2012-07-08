<?php

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

class raidmessenger
{
	/**
	 * holds users that get notified on raid change or addition
	 *
	 * @var array
	 */
	public $send_user_data = array();
	
	/**
	 * template
	 *
	 * @var array
	 */
	public $patterns = array();
	
	/**
	 * initialises array with users who get notified
	 *
	 * @param int $trigger
	 */
	public function get_notifiable_users($trigger)
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
				// get raidplan participants
				$sql = 'SELECT DISTINCT u.username, u.user_allow_massemail, u.user_allow_pm, u.user_id, u.user_email, u.user_lang
						FROM ' . RP_SIGNUPS . ' l, ' . USERS_TABLE . ' u 
						WHERE l.poster_id = u.user_id 
						AND l.raidplan_id = ' . $this->id ;
				break;
		}
		
		$result = $db->sql_query($sql);
		$this->send_user_data = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
	}
	

	
}
?>