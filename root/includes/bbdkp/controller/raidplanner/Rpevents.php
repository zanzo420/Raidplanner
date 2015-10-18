<?php
/**
*
* @author Sajaki
* @package bbDKP Raidplanner
* @copyright (c) 2011 Sajaki
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.0
*/
namespace bbdkp\controller\raidplanner;


/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}
if (!class_exists('\bbdkp\controller\raids\Events'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/raids/Events.$phpEx");
}

class rpevents
{
	public $events = array();
	function __construct($dkpsys_id)
	{
        $eventlist = new \bbdkp\controller\Raids\events;
        $eventlist->listevents(0,'b.dkpsys_id, a.event_name', $dkpsys_id, 0, false );

		foreach($eventlist->events as $activeevent)
		{
			$this->events[$activeevent['event_id']]['event_name'] = $activeevent['event_name'];
			$this->events[$activeevent['event_id']]['color'] = $activeevent['event_color'];
			$this->events[$activeevent['event_id']]['imagename'] = $activeevent['event_imagename'];
			$this->events[$activeevent['event_id']]['dkpid'] = $activeevent['dkpsys_id'];
			$this->events[$activeevent['event_id']]['value'] = $activeevent['event_value'];
            $this->events[$activeevent['event_id']]['dkpsys_status'] = $activeevent['event_value'];
		}
	}

}
