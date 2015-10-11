<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.0.2
*/
namespace bbdkp\controller\raidplanner;
use bbdkp\controller\raidplanner\Raidmessenger;

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

use bbdkp\controller\points\Points;
use \bbdkp\controller\members\Members;

/**
 * implements Raid signups
 *
 */
class RaidplanSignup
{
    protected $signup_id=0;
    protected $raidplan_id;
    protected $poster_id;
    protected $poster_name;
    protected $poster_colour;
    protected $poster_ip;

    /**
     * 0 unavailable 1 maybe 2 available 3 confirmed
     *
     * @var int
     */
    protected $signup_val;
    protected $signup_time;
    protected $signup_count;

    protected $dkpmemberid;
    protected $dkpmembername;
    protected $dkmemberpurl;
    protected $classname;
    protected $imagename;
    protected $colorcode;
    protected $raceimg;
    protected $genderid;
    protected $level;

    protected $dkp_current;
    protected $priority_ratio;
    protected $lastraid;
    protected $attendanceP1;

    protected $comment;
    protected $bbcode = array();

    protected $roleid;
    protected $role_name;
    protected $confirm;

    /**
     * @param mixed $attendanceP1
     */
    public function setAttendanceP1($attendanceP1)
    {
        $this->attendanceP1 = $attendanceP1;
    }

    /**
     * @return mixed
     */
    public function getAttendanceP1()
    {
        return $this->attendanceP1;
    }

    /**
     * @param array $bbcode
     */
    public function setBbcode($bbcode)
    {
        $this->bbcode = $bbcode;
    }

    /**
     * @return array
     */
    public function getBbcode()
    {
        return $this->bbcode;
    }

    /**
     * @param mixed $classname
     */
    public function setClassname($classname)
    {
        $this->classname = $classname;
    }

    /**
     * @return mixed
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * @param mixed $colorcode
     */
    public function setColorcode($colorcode)
    {
        $this->colorcode = $colorcode;
    }

    /**
     * @return mixed
     */
    public function getColorcode()
    {
        return $this->colorcode;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $confirm
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
    }

    /**
     * @return mixed
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * @param mixed $dkmemberpurl
     */
    public function setDkmemberpurl($dkmemberpurl)
    {
        $this->dkmemberpurl = $dkmemberpurl;
    }

    /**
     * @return mixed
     */
    public function getDkmemberpurl()
    {
        return $this->dkmemberpurl;
    }

    /**
     * @param mixed $dkp_current
     */
    public function setDkpCurrent($dkp_current)
    {
        $this->dkp_current = $dkp_current;
    }

    /**
     * @return mixed
     */
    public function getDkpCurrent()
    {
        return $this->dkp_current;
    }

    /**
     * @param mixed $dkpmemberid
     */
    public function setDkpmemberid($dkpmemberid)
    {
        $this->dkpmemberid = $dkpmemberid;
    }

    /**
     * @return mixed
     */
    public function getDkpmemberid()
    {
        return $this->dkpmemberid;
    }

    /**
     * @param mixed $dkpmembername
     */
    public function setDkpmembername($dkpmembername)
    {
        $this->dkpmembername = $dkpmembername;
    }

    /**
     * @return mixed
     */
    public function getDkpmembername()
    {
        return $this->dkpmembername;
    }

    /**
     * @param mixed $genderid
     */
    public function setGenderid($genderid)
    {
        $this->genderid = $genderid;
    }

    /**
     * @return mixed
     */
    public function getGenderid()
    {
        return $this->genderid;
    }

    /**
     * @param mixed $imagename
     */
    public function setImagename($imagename)
    {
        $this->imagename = $imagename;
    }

    /**
     * @return mixed
     */
    public function getImagename()
    {
        return $this->imagename;
    }

    /**
     * @param mixed $lastraid
     */
    public function setLastraid($lastraid)
    {
        $this->lastraid = $lastraid;
    }

    /**
     * @return mixed
     */
    public function getLastraid()
    {
        return $this->lastraid;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $poster_colour
     */
    public function setPosterColour($poster_colour)
    {
        $this->poster_colour = $poster_colour;
    }

    /**
     * @return mixed
     */
    public function getPosterColour()
    {
        return $this->poster_colour;
    }

    /**
     * @param mixed $poster_id
     */
    public function setPosterId($poster_id)
    {
        $this->poster_id = $poster_id;
    }

    /**
     * @return mixed
     */
    public function getPosterId()
    {
        return $this->poster_id;
    }

    /**
     * @param mixed $poster_ip
     */
    public function setPosterIp($poster_ip)
    {
        $this->poster_ip = $poster_ip;
    }

    /**
     * @return mixed
     */
    public function getPosterIp()
    {
        return $this->poster_ip;
    }

    /**
     * @param mixed $poster_name
     */
    public function setPosterName($poster_name)
    {
        $this->poster_name = $poster_name;
    }

    /**
     * @return mixed
     */
    public function getPosterName()
    {
        return $this->poster_name;
    }

    /**
     * @param mixed $priority_ratio
     */
    public function setPriorityRatio($priority_ratio)
    {
        $this->priority_ratio = $priority_ratio;
    }

    /**
     * @return mixed
     */
    public function getPriorityRatio()
    {
        return $this->priority_ratio;
    }

    /**
     * @param mixed $raceimg
     */
    public function setRaceimg($raceimg)
    {
        $this->raceimg = $raceimg;
    }

    /**
     * @return mixed
     */
    public function getRaceimg()
    {
        return $this->raceimg;
    }

    /**
     * @param mixed $raidplan_id
     */
    public function setRaidplanId($raidplan_id)
    {
        $this->raidplan_id = $raidplan_id;
    }

    /**
     * @return mixed
     */
    public function getRaidplanId()
    {
        return $this->raidplan_id;
    }

    /**
     * @param mixed $role_name
     */
    public function setRoleName($role_name)
    {
        $this->role_name = $role_name;
    }

    /**
     * @return mixed
     */
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * @param mixed $roleid
     */
    public function setRoleid($roleid)
    {
        $this->roleid = $roleid;
    }

    /**
     * @return mixed
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * @param mixed $signup_count
     */
    public function setSignupCount($signup_count)
    {
        $this->signup_count = $signup_count;
    }

    /**
     * @return mixed
     */
    public function getSignupCount()
    {
        return $this->signup_count;
    }

    /**
     * @param int $signup_id
     */
    public function setSignupId($signup_id)
    {
        $this->signup_id = $signup_id;
    }

    /**
     * @return int
     */
    public function getSignupId()
    {
        return $this->signup_id;
    }

    /**
     * @param mixed $signup_time
     */
    public function setSignupTime($signup_time)
    {
        $this->signup_time = $signup_time;
    }

    /**
     * @return mixed
     */
    public function getSignupTime()
    {
        return $this->signup_time;
    }

    /**
     * @param int $signup_val
     */
    public function setSignupVal($signup_val)
    {
        $this->signup_val = $signup_val;
    }

    /**
     * @return int
     */
    public function getSignupVal()
    {
        return $this->signup_val;
    }


    public function __construct()
    {

    }

    /**
     * makes a Signup object
     *
     * @param $signup_id
     */
    public function getSignup($signup_id)
	{
		global $db;
		$this->signup_id = $signup_id;
		$sql = 'SELECT * from ' . RP_SIGNUPS . ' a INNER JOIN ' . RP_ROLES . ' b
		    ON a.role_id = b.role_id
		    WHERE a.signup_id = ' . $this->signup_id;

        //cache for a day
		$result = $db->sql_query($sql, 86400);
		$row = $db->sql_fetchrow($result);
		if( !$row )
		{
			trigger_error( 'INVALID_SIGNUP' );
		}
		$db->sql_freeresult($result);

		$this->raidplan_id = $row['raidplan_id'];
		$this->poster_id = $row['poster_id'];
		$this->poster_name = $row['poster_name'];
		$this->poster_colour = $row['poster_colour'];
		$this->poster_ip = $row['poster_ip'];
		$this->signup_time = $row['post_time'];
		$this->signup_val = $row['signup_val'];
		$this->signup_count = $row['signup_count'];
		$this->comment = $row['signup_detail'];
		$this->bbcode['bitfield']= $row['bbcode_bitfield'];
		$this->bbcode['uid']= $row['bbcode_uid'];
		$this->confirm = $row['role_confirm'];
		$this->dkpmemberid = $row['dkpmember_id'];
		$this->roleid = $row['role_id'];
		$this->role_name = $row['role_name'];
        unset ($row);

	}

    /**
     * returns the sql to fill signups/signoff array
     */
    public static function GetSignupSQL ($raidplan_id)
    {
        global $config, $db;
        $sql = 'SELECT a.raidplan_id, a.role_id,  a.role_needed, a.role_signedup, a.role_confirmed, b.signup_id, b.poster_id, b.signup_count, b.role_confirm,
              b.poster_name, b.poster_ip, b.poster_colour, b.post_time, b.signup_val, b.signup_detail,
              b.bbcode_bitfield, b.bbcode_options, b.bbcode_uid, b.dkpmember_id, r.role_id,  r.role_id,  r.role_icon, rl.name as role_name,
              m.member_name , m.member_level, m.member_gender_id,
              c.colorcode , c.imagename,  l.name AS member_class,
              l1.name AS member_race, ra.image_female, ra.image_male
              FROM ' . RP_RAIDPLAN_ROLES . ' a
              INNER JOIN ' . RP_SIGNUPS . ' b ON a.raidplan_id = b.raidplan_id AND a.role_id = b.role_id
              INNER JOIN ' . BB_GAMEROLE_TABLE . ' r ON a.role_id = r.role_id
              INNER JOIN ' . BB_LANGUAGE . " rl ON rl.attribute_id = r.role_id AND rl.game_id = r.game_id AND rl.language= 'en' AND rl.attribute = 'role'
              INNER JOIN " . MEMBER_LIST_TABLE . ' m ON b.dkpmember_id = m.member_id
              INNER JOIN ' . CLASS_TABLE .  ' c ON m.game_id = c.game_id AND m.member_class_id = c.class_id
              INNER JOIN ' . BB_LANGUAGE . " l ON l.attribute_id = c.class_id AND l.game_id = c.game_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
              INNER JOIN " . RACE_TABLE .  ' ra ON m.game_id = ra.game_id AND m.member_race_id = ra.race_id
              INNER JOIN ' . BB_LANGUAGE . " l1 ON l1.attribute_id = ra.race_id AND l1.game_id = ra.game_id AND l1.language= '" . $config['bbdkp_lang'] . "' AND l1.attribute = 'race'
              WHERE a.raidplan_id = " . $raidplan_id;

            return $db->sql_query($sql, 86400);
    }

    /**
     * get all my chars
     *
     * @param $raidplanid
     * @return array
     * @internal param int $userid
     * @internal param int $raidplan_id
     */
	public function getmychars($raidplanid)
	{
		global $db, $user;

		// get memberinfo
		$sql_array = array();

		$sql_array['SELECT'] = ' s.*,  m.member_id, m.member_name, m.member_level, m.member_gender_id ';
	    $sql_array['FROM'] 	= array(MEMBER_LIST_TABLE 	=> 'm');
	    $sql_array['LEFT_JOIN'] = array(
			array( 'FROM'	=> array( RP_SIGNUPS => 's'),
				   'ON'	=> 's.dkpmember_id = m.member_id and s.raidplan_id = ' . (int) $raidplanid
				)
		);
	    $sql_array['WHERE'] = 'm.member_rank_id !=90 AND m.phpbb_user_id =  ' . $user->data['user_id'];

		$mychars = array();
		$sql = $db->sql_build_query('SELECT', $sql_array);

        //cache for a week
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$mychars[] = array(
				'is_signedup'  => (isset($row['signedup_val']) ? 1: 0),
				'signedup_val' => (isset($row['signedup_val']) ? $row['signedup_val']: 0),
				'role_id' 	   => (isset($row['role_id']) ? $row['role_id'] : ''),
				'id' 		   => $row['member_id'],
				'name' 		   => $row['member_name'] );
		}
		$db->sql_freeresult($result);
		return $mychars;
	}

    /**
     * registers signup
     *
     * @param $raidplan_id
     * @return bool
     */
    public function signup($raidplan_id)
	{
		global  $user;

		$this->raidplan_id = $raidplan_id;

		$this->poster_id = $user->data['user_id'];
		$this->poster_name = $user->data['username'];
		$this->poster_colour = $user->data['user_colour'];
		$this->poster_ip = $user->ip;
		$this->signup_time = time() - $user->timezone - $user->dst;

		// 0 unavailable 1 maybe 2 available 3 confirmed
		$this->signup_val = request_var('signup_val'. $raidplan_id, 2);
		$this->roleid = request_var('signuprole'. $raidplan_id, 0);
		$this->dkpmemberid = request_var('signupchar'. $raidplan_id, 0);
		$this->comment = utf8_normalize_nfc(request_var('signup_detail'. $raidplan_id, '', true));
		$this->signup_count = 1;

		$this->bbcode['uid'] = $this->bbcode['bitfield'] = $options = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($this->comment, $this->bbcode['uid'],
                $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);

		$this->storesignup();

		return true;
	}

	/**
	 * stores a signup
	 *
	 */
	private function storesignup()
	{
		global $user, $cache, $db;

		$sql_signup = array(
			'raidplan_id'	=> $this->raidplan_id,
			'poster_id'		=> $this->poster_id,
			'poster_name'	=> $this->poster_name,
			'poster_colour'	=> $this->poster_colour,
			'poster_ip'		=> $this->poster_ip,
			'post_time'		=> $this->signup_time,
			'signup_val'	=> $this->signup_val,
			'signup_count'	=> $this->signup_count,
			'signup_detail'	=> $this->comment,
			'bbcode_bitfield' 	=> $this->bbcode['bitfield'],
			'bbcode_uid'		=> $this->bbcode['uid'],
			'bbcode_options'	=> 7,
			'dkpmember_id'	=> $this->dkpmemberid,
			'role_id'		=> $this->roleid

			);

        //destroy sql cache for signup / raidplan / roles table
        $cache->destroy( 'sql', RP_SIGNUPS );
        $cache->destroy( 'sql', RP_RAIDS_TABLE );
        $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

		/*
		 * start transaction
		 */
		$db->sql_transaction('begin');

		if($this->signup_id == 0)
		{
			//prevent double submit, check if signup for char already exists (ip+charname), ignore if it does
			$sql = "SELECT count(*) as doublecheck from " . RP_SIGNUPS . " WHERE raidplan_id = " . $this->raidplan_id .
			" and poster_ip = '" . $this->poster_ip . "'
			  and dkpmember_id = '" .  $this->dkpmemberid . "'";
			$result = $db->sql_query($sql);
			$check = (int) $db->sql_fetchfield('doublecheck');
			$db->sql_freeresult($result);
			if($check == 0)
			{
				//insert new
				$sql = 'INSERT INTO ' . RP_SIGNUPS . ' ' . $db->sql_build_array('INSERT', $sql_signup);
				$db->sql_query($sql);
				$signup_id = $db->sql_nextid();
				$this->signup_id = $signup_id;

				switch ( (int) $this->signup_val)
				{
					case 0:
						// no
						$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1 WHERE raidplan_id = " . $this->raidplan_id;
						$db->sql_query($sql);
						 break;
					case 1:
						// maybe
						$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_maybe = signup_maybe + 1 WHERE raidplan_id = " . $this->raidplan_id;
						$db->sql_query($sql);

						$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup + 1 WHERE raidplan_id = " . $this->raidplan_id .
						" AND role_id = " . $this->roleid ;
						$db->sql_query($sql);
						break;
					case 2:
						//yes
						$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_yes = signup_yes + 1 WHERE raidplan_id = " . $this->raidplan_id;
						$db->sql_query($sql);

						$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup + 1 WHERE raidplan_id = " . $this->raidplan_id .
					" AND role_id = " . $this->roleid ;

						$db->sql_query($sql);
						break;
				}

			}
			else
			{
				$sql = "SELECT signup_id from " . RP_SIGNUPS . " WHERE raidplan_id = " . $this->raidplan_id .
				" and poster_ip = '" . $this->poster_ip . "'
				  and dkpmember_id = '" .  $this->dkpmemberid . "'";
				$result = $db->sql_query($sql);
				$check = (int) $db->sql_fetchfield('signup_id');

				$this->getSignup($check);
				trigger_error(sprintf($user->lang['USER_ALREADY_SIGNED_UP'], $this->dkpmembername));
			}
		}

		unset ($sql_signup);

		$db->sql_transaction('commit');

        //destroy sql cache for signup / raidplan / roles table
        $cache->destroy( 'sql', RP_SIGNUPS );
        $cache->destroy( 'sql', RP_RAIDS_TABLE );
        $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

		$this->getSignup($this->signup_id);
		return true;
	}

    /**
     * Edit my signup comment
     *
     * @param $signup_id
     * @return bool
     */
    public function editSignupComment($signup_id)
	{
		global $cache, $db;


        $this->getSignup($signup_id);
		$message = utf8_normalize_nfc(request_var('signup_detail_' . $this->raidplan_id . '_' . $this->signup_id , '', true));
		$this->bbcode['uid'] = $this->bbcode['bitfield'] = $options = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($message, $this->bbcode['uid'], $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);
		$db->sql_transaction('begin');
		$sql_signup = array(
			'signup_detail'		=> $message,
			'bbcode_bitfield' 	=> $this->bbcode['bitfield'],
			'bbcode_uid'		=> $this->bbcode['uid'],
			'bbcode_options'	=> 7,
			);
		$sql = 'UPDATE ' . RP_SIGNUPS . ' SET ' . $db->sql_build_array('UPDATE', $sql_signup) . ' WHERE signup_id = ' . (int) $this->signup_id;
		unset($sql_signup);
		$db->sql_query($sql);
		$db->sql_transaction('commit');

        //destroy sql cache for signup table
        $cache->destroy( 'sql', RP_SIGNUPS );
		return true;

	}


    /**
     * requeues a deleted signup
     *
     * @param int $signup_id
     * @return bool
     */
	public function requeuesignup($signup_id)
	{
		global $cache, $db;
        //destroy sql cache for signup / raidplan / roles table
        $cache->destroy( 'sql', RP_SIGNUPS );
        $cache->destroy( 'sql', RP_RAIDS_TABLE );
        $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

		//make object
		$this->getSignup($signup_id);
		$this->signup_sync();
		switch ( (int) $this->signup_val)
		{
			case 0:
				$db->sql_transaction('begin');
				// decrease signup_no, set as maybe
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no - 1, signup_maybe = signup_maybe + 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);

				// set new role
				$this->roleid = request_var('signuprole_' . $this->raidplan_id . '_' .  (int) $this->signup_id , 0);
				// assign new role
				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup + 1 WHERE raidplan_id = " . $this->raidplan_id .
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);
				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 1, role_id = ' . $this->roleid . ' WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);

				//edit the comment
				$this->comment = utf8_normalize_nfc(request_var('signup_detail_' . $this->raidplan_id . '_' . $this->signup_id , '', true));

				$this->bbcode['uid'] = $this->bbcode['bitfield'] = $options = ''; // will be modified by generate_text_for_storage
				$allow_bbcode = $allow_urls = $allow_smilies = true;
				generate_text_for_storage($this->comment, $this->bbcode['uid'], $this->bbcode['bitfield'], $options, $allow_bbcode, $allow_urls, $allow_smilies);

				if($this->comment != '')
				{
					$sql = 'UPDATE ' . RP_SIGNUPS . " SET signup_detail = '" .  $db->sql_escape($this->comment) . "' WHERE signup_id = " . (int) $this->signup_id;
					$db->sql_query($sql);
				}
				$db->sql_transaction('commit');

                //AGAIN ! destroy sql cache for signup / raidplan / roles table
                $cache->destroy( 'sql', RP_SIGNUPS );
                $cache->destroy( 'sql', RP_RAIDS_TABLE );
                $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

				return true;
				break;
		}

		// if already >0 then don't do anything
		return false;

	}

	/**
	 * delete this signup and change to not available
	 *
	 * @param int $signup_id
	 * @param int $raidplan_id
	 *
	 * @return int
	 */
	public function deletesignup($signup_id, $raidplan_id)
	{
		global $cache, $db;

        //destroy sql cache for signup / raidplan / roles table
        $cache->destroy( 'sql', RP_SIGNUPS );
        $cache->destroy( 'sql', RP_RAIDS_TABLE );
        $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

		//make object
		$this->getSignup($signup_id);
		$this->signup_sync();

		switch ( (int) $this->signup_val)
		{
			case 1:
				// maybe
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_maybe = signup_maybe - 1 WHERE raidplan_id = " . $raidplan_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 WHERE raidplan_id = " . $raidplan_id .
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);

				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return 1;
				break;
			case 2:
				//yes
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_yes = signup_yes - 1 WHERE raidplan_id = " . $raidplan_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 WHERE raidplan_id = " . $raidplan_id .
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);

				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return 2;
				break;
			case 3:

				//confirmed
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_no = signup_no + 1, signup_confirmed = signup_confirmed - 1 WHERE raidplan_id = " . $raidplan_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_confirmed = role_confirmed - 1 WHERE raidplan_id = " . $raidplan_id .
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);

				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 0 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');
				return 3;
				break;
		}


		// if already 0 then don't do anything
		return 0;
	}

	/**
	 * synchronises raidplan stats
     * make sure that calling function cleared sql cache
	 */
	private function signup_sync()
	{
		global $db;

		$db->sql_transaction('begin');
		$sql = "update " . RP_RAIDS_TABLE . " set signup_no = (select count(*) from " . RP_SIGNUPS .
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 0) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$sql = "update " . RP_RAIDS_TABLE . " set signup_maybe = (select count(*) from " . RP_SIGNUPS .
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 1) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$sql = "update " . RP_RAIDS_TABLE . " set signup_yes = (select count(*) from " . RP_SIGNUPS .
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 2) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$sql = "update " . RP_RAIDS_TABLE . " set signup_confirmed = (select count(*) from " . RP_SIGNUPS .
			" where raidplan_id = " . (int) $this->raidplan_id. " and signup_val = 3) where raidplan_id = " . (int) $this->raidplan_id;
		$db->sql_query($sql);
		$db->sql_transaction('commit');
	}


    /**
     * confirms a signup
     *
     * @param int $signup_id
     * @return bool
     */
	public function confirmsignup($signup_id)
	{
		global $cache, $db;

        //destroy sql cache for signup / raidplan / roles table
        $cache->destroy( 'sql', RP_SIGNUPS );
        $cache->destroy( 'sql', RP_RAIDS_TABLE );
        $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

		//make object
		$this->getSignup($signup_id);
		$this->signup_sync();
		switch ( (int) $this->signup_val)
		{
			case 1:
				// maybe
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_maybe = signup_maybe - 1, signup_confirmed = signup_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1, role_confirmed = role_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id .
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);

				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 3, role_confirm = 1 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');

                //destroy sql cache for signup / raidplan / roles table
                $cache->destroy( 'sql', RP_SIGNUPS );
                $cache->destroy( 'sql', RP_RAIDS_TABLE );
                $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

                return true;
				break;

			case 2:
				// yes
				$db->sql_transaction('begin');
				$sql = "UPDATE " . RP_RAIDS_TABLE . " SET signup_yes = signup_yes - 1, signup_confirmed = signup_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . RP_RAIDPLAN_ROLES . " SET role_signedup = role_signedup - 1 , role_confirmed = role_confirmed + 1 WHERE raidplan_id = " . $this->raidplan_id .
				" AND role_id = " . $this->roleid ;
				$db->sql_query($sql);

				$sql = 'UPDATE ' . RP_SIGNUPS . ' SET signup_val = 3, role_confirm = 1 WHERE signup_id = ' . (int) $this->signup_id;
				$db->sql_query($sql);
				$db->sql_transaction('commit');

                // again destroy sql cache for signup / raidplan / roles table
                $cache->destroy( 'sql', RP_SIGNUPS );
                $cache->destroy( 'sql', RP_RAIDS_TABLE );
                $cache->destroy( 'sql', RP_RAIDPLAN_ROLES );

                return true;
				break;
		}

		// if already >0 then don't do anything
		return false;

	}

	/* signupmessenger
	**
	** eventhandler for
	** 4) raidplan new signup +
	**   send to raidleader
	** 5) raidplan signup unavail -
	**   send to raidleader
	** 6) raidplan signup confirm ++
	**   send to member
	**
	*/
	function signupmessenger($trigger, Raidplan $raidplan, $eventlist)
	{
		global $user, $config;
		global $phpEx, $phpbb_root_path;

		include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
		include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);

		// get recipient data (email, etc)
		if (!class_exists('\bbdkp\controller\raidplanner\Raidmessenger'))
		{
			require("{$phpbb_root_path}includes/bbdkp/controller/raidplanner/raidmessenger.$phpEx");
		}

		$rpm = new Raidmessenger();
		$rpm->get_notifiable_users($trigger, $this->raidplan_id, $this->signup_id);

		$emailrecipients = array();
		$messenger = new \messenger();

		foreach($rpm->send_user_data as $id => $row)
		{
			$data=array();
			// get template
			switch ($trigger)
			{
				case 4:
					// send signup to RL
					$messenger->template('signup_new', $row['user_lang']);
					$subject =  '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['NEWSIGN'] . ': ' .
                        $eventlist->events[$raidplan->getEventType()]['event_name'] . ' ' .
                        $user->format_date($raidplan->getStartTime()  , $config['rp_date_time_format'], true);
					$data['address_list'] = array('u' => array($raidplan->getPoster() => 'to'));

					break;
				case 5:
					// send confirmation to RL and raider
					$messenger->template('signup_confirm', $row['user_lang']);
					$subject = '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['CONFIRMSIGN'] . ': ' . $eventlist->events[$raidplan->getEventType()]['event_name'] . ' ' .
                        $user->format_date($raidplan->getStartTime(), $config['rp_date_time_format'], true);
					$data['address_list'] = array('u' =>
						array(
							$row['user_id'] => 'to'
						));

					break;
				case 6:
					// send cancellation to RL and raider
					$messenger->template('signup_unsign', $row['user_lang']);
					$subject = '[' . $user->lang['RAIDPLANNER']  . '] ' . $user->lang['UNSIGNED'] . ': ' . $eventlist->events[$raidplan->getEventType()]['event_name'] . ' ' .
                        $user->format_date($raidplan->getStartTime(), $config['rp_date_time_format'], true);
					$data['address_list'] = array('u' =>
						array(
							$row['user_id'] => 'to'
						));
					break;
			}
		   $userids = array($raidplan->getPoster());
		   $rlname = array();
		   user_get_id_name($userids, $rlname);

		   $messenger->assign_vars(array(
		   		'RAIDLEADER'		=> $rlname[$raidplan->getPoster()],
				'EVENT_SUBJECT'		=> $subject,
		   		'SIGNUP_TIME'		=> $user->format_date($this->signup_time, $config['rp_date_time_format'], true),
				'USERNAME'			=> htmlspecialchars_decode($user->data['username']),
		   		'RAIDER'			=> $this->dkpmembername,
		   		'EVENT'				=> $eventlist->events[$raidplan->getEventType()]['event_name'],
		   		'ROLE'				=> $this->role_name,
				'INVITE_TIME'		=> $user->format_date($raidplan->getInviteTime(), $config['rp_date_time_format'], true),
				'START_TIME'		=> $user->format_date($raidplan->getStartTime(), $config['rp_date_time_format'], true),
				'END_TIME'			=> $user->format_date($raidplan->getEndTime(), $config['rp_date_time_format'], true),
				'TZ'				=> $user->lang['tz'][(int) $user->data['user_timezone']],
				'U_RAIDPLAN'		=> generate_board_url() . "/dkp.$phpEx?page=planner&amp;view=raidplan&amp;raidplanid=".$raidplan->id
			));

			$messenger->msg = trim($messenger->tpl_obj->assign_display('body'));
			$messenger->msg = str_replace("\r\n", "\n", $messenger->msg);

		    $messenger->msg = utf8_normalize_nfc($messenger->msg);
	   		$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
	   		$allow_bbcode = $allow_smilies = $allow_urls = true;
	   		generate_text_for_storage($messenger->msg, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	   		$messenger->msg = generate_text_for_display($messenger->msg, $uid, $bitfield, $options);

			$data['from_user_id']      = $user->data['user_id'];
		    $data['from_username']     = $user->data['username'];
		    $data['icon_id']           = 0;
		    $data['from_user_ip']      = $user->data['user_ip'];
		    $data['enable_bbcode']     = true;
		    $data['enable_smilies']    = true;
		    $data['enable_urls']       = true;
		    $data['enable_sig']        = true;
		    $data['message']           = $messenger->msg;
		    $data['bbcode_bitfield']   = $this->bbcode['bitfield'];
		    $data['bbcode_uid']        = $this->bbcode['uid'];


			if($config['rp_pm_signup'] == 1 &&  (int) $row['user_allow_pm'] == 1)
			{
				// send a PM
				submit_pm('post',$subject, $data, false);
			}

			if($config['rp_email_signup'] == 1 && $row['user_email'] != '')
			{
				//send email, reuse messenger object
			   $email = $messenger;
			   $emailrecipients[]=$row['username'];
			   $email->to($row['user_email'], $row['username']);
			   $email->anti_abuse_headers($config, $user);
			   $email->send(0);
			}

		}

		if($config['rp_email_signup'] == 1 && isset($email))
		{
			$email->save_queue();
			$emailrecipients = implode(', ', $emailrecipients);
			add_log('admin', 'LOG_MASS_EMAIL', $emailrecipients);
		}


	}



}
