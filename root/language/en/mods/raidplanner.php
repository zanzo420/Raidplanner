<?php
/**
 * bbdkp acp language file for raidplanner module
 * 
 * @package bbDkp
 * @copyright 2010 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(

	//settings 
    '12_HOURS'								=> '12 hours',
    '24_HOURS'								=> '24 hours',
    'AUTO_POPULATE_EVENT_FREQUENCY'			=> 'Auto Populate Recurring Raids',
    'AUTO_POPULATE_EVENT_FREQUENCY_EXPLAIN'	=> 'How often (in days) should recurring raids be populated in the calendar?  Note if you select 0, recurring raids will never get added to the calendar.',
    'AUTO_POPULATE_EVENT_LIMIT'				=> 'Auto Populate Limits',
    'AUTO_POPULATE_EVENT_LIMIT_EXPLAIN'		=> 'How many days in advance do you want to populated with recurring raids?  In other words, do you want to only see recurring raids in the calendar for 30, 45, or more days before the raid?',
    'AUTO_PRUNE_EVENT_FREQUENCY'			=> 'Auto Prune Past Raids',
    'AUTO_PRUNE_EVENT_FREQUENCY_EXPLAIN'	=> 'How often (in days) should past raids be pruned from the calendar?  Note if you select 0, past raids will never be auto-pruned, you will have to delete them by hand.',
    'AUTO_PRUNE_EVENT_LIMIT'				=> 'Auto Prune Limits',
    'AUTO_PRUNE_EVENT_LIMIT_EXPLAIN'		=> 'How many days after an raid do you want to add the raid to the next auto prune\'s delete list?  In other words, do you want all raids to remain in the calendar for 0, 30, or 45 days after the raid?',
    'CLICK_PLUS_HOUR'						=> 'Move ALL raids by one hour.',
    'CLICK_PLUS_HOUR_EXPLAIN'				=> 'Being able to move all raids in the calendar +/- one hour helps when you reset the boards daylight savings time setting.  Note clicking on the links to move the raids will loose any changes you have made above.  Please submit the form to save your work before moving the raids +/- one hour.',
    'COLOR'									=> 'Color',
    'DATE_FORMAT'							=> 'Date Format',
    'DATE_FORMAT_EXPLAIN'					=> 'Try &quot;M d, Y&quot;',
    'DATE_TIME_FORMAT'						=> 'Date and Time Format',
    'DATE_TIME_FORMAT_EXPLAIN'				=> 'Try &quot;M d, Y h:i a&quot; or &quot;M d, Y H:i&quot;',
    'DELETE'								=> 'Delete',
    'DISPLAY_12_OR_24_HOURS'				=> 'Display Time Format',
    'DISPLAY_12_OR_24_HOURS_EXPLAIN'		=> 'Do you want to display the times in 12 hour mode with AM/PM or 24 hour mode?  This does not effect what format the times are displayed to the user - that is set in their profile.  This only effects the pulldown menu for time selection when creating/editing raids and the timed headings on the view day calendar.',
    'DISPLAY_HIDDEN_GROUPS'					=> 'Display Hidden Groups',
    'DISPLAY_HIDDEN_GROUPS_EXPLAIN'			=> 'Do you want users to be able to see and invite members of hidden groups?  If this setting is disabled, only group administrators will be able to see and invite members of the hidden group.',
    'DISPLAY_NAME'							=> 'Display Event Name in Calendar',
    'DISPLAY_EVENTS_ONLY_1_DAY'				=> 'Display Raids 1 Day',
    'DISPLAY_EVENTS_ONLY_1_DAY_EXPLAIN'		=> 'Display raids only on the day they begin (ignore their end date/time).',
    'DISPLAY_FIRST_WEEK'					=> 'Display Current Week',
    'DISPLAY_FIRST_WEEK_EXPLAIN'			=> 'Would you like to have the current week displayed on the forum index?',
    'DISPLAY_NEXT_RAIDS'					=> 'Display Upcoming Raids',
    'DISPLAY_NEXT_RAIDS_EXPLAIN'			=> 'Specify the number of upcoming raids to display.',
    'DISPLAY_TRUNCATED_SUBJECT'				=> 'Truncate Subject',
    'DISPLAY_TRUNCATED_SUBJECT_EXPLAIN'		=> 'Long names in the subject can take up a lot of space on the calendar.  How many characters do you want displayed in the subject on the calendar? (enter 0 if you do not want to truncate the subject)',
    'EDIT'									=> 'Edit',
    'EDIT_ETYPE'							=> 'Edit Event',
    'EDIT_ETYPE_EXPLAIN'					=> 'Specify the way you want this raid type to display.',
    'FIRST_DAY'								=> 'First Day',
    'FIRST_DAY_EXPLAIN'						=> 'Which day should be displayed as the first day of the week?',
    'FULL_NAME'								=> 'Full Name',
    'FRIDAY'								=> 'Friday',
    'ICON_URL'								=> 'URL for icon',
    'MANAGE_ETYPES'							=> 'Manage Events',
    'MANAGE_ETYPES_EXPLAIN'					=> 'Raid types are used to help organize the calendar, you may add, edit, delete or reorder the raid types here.',
    'MINUS_HOUR'							=> 'Move all raids minus (-) one hour',
    'MONDAY'								=> 'Monday',
    'NO_EVENT_TYPE_ERROR'					=> 'Failed to find specified raid type.',
    'PLUS_HOUR'								=> 'Move all raids plus (+) one hour',
    'PLUS_HOUR_CONFIRM'						=> 'Are you sure you want to move all the raids by %1$s hour?',
    'PLUS_HOUR_SUCCESS'						=> 'Successfully moved all raids by %1$s hour.',
	'ROLEICON'								=> 'Role Icon',
	'SATURDAY'								=> 'Saturday',
    'SUNDAY'								=> 'Sunday',
    'TIME_FORMAT'							=> 'Time Format',
    'TIME_FORMAT_EXPLAIN'					=> 'Try &quot;h:i a&quot; or &quot;H:i&quot;',
    'THURSDAY'								=> 'Thursday',
    'TUESDAY'								=> 'Tuesday',
    'USER_CANNOT_MANAGE_CALENDAR'			=> 'You do not have permission to manage the calendar settings or raid types.',
    'WEDNESDAY'								=> 'Wednesday',
	'USER_CANNOT_MANAGE_RAIDPLANNER'		=> 'You are not authorised to manage the raidplanner settings', 
	'RPADVANCEDOPTIONS'						=> 'Advanced options', 
	'RPSETTINGS'							=> 'Settings', 
	'RPSETTINGS_UPDATED'					=> 'Raidplanner settings updated successfully',
	'ADVRPSETTINGS_UPDATED'					=> 'Advanced Raidplanner settings updated successfully', 
	
	//confirms
	'ROLE_DELETE_SUCCESS'		=> 'The role %s was deleted.', 
	'CONFIRM_DELETE_ROLE'		=> 'Please confirm you want to delete the raid role %s. If there are scheduled raids with this role then it can‘t be deleted.', 
    'DELETE_RAIDPLAN_CONFIRM'	=> 'Please confirm you want to delete this Raidplan.',
	
	'CHOOSEPROFILE'				=> 'Choose Raidprofile', 
	'RAIDROLES'					=> 'Raid Roles', 
	'RAIDROLE'					=> 'Role', 

	'ALL_DAY'				 	=> 'All Day Raid',
	'ALLOW_GUESTS'				=> 'Allow members to bring guests to this raid',
	'ALLOW_GUESTS_ON'			=> 'Members are allowed to bring guests to this raid.',
	'ALLOW_GUESTS_OFF'			=> 'Members are not allowed to bring guests to this raid.',
	'AM'						=> 'AM',
	'AVAILABLE'					=> 'Available',

	'CALENDAR_POST_RAIDPLAN'	=> 'Create New Raid',
	'CALENDAR_EDIT_RAIDPLAN'	=> 'Edit Raid',
	'CALENDAR_TITLE'			=> 'Planner',
	'RAIDPLANNER'				=> 'Raid Planner',
	'NEWRAID'					=> 'New Raid',

	'CALENDAR_NUMBER_ATTEND'=> 'The number of people you are bringing to this raid',
	'CALENDAR_NUMBER_ATTEND_EXPLAIN'=> '(enter 1 for yourself)',
	'CALENDAR_RESPOND'		=> 'Please register here',
	'CALENDAR_WILL_ATTEND'	=> 'Signup as',

	'CANNOTSIGNUP'			=> 'you cannot sign up because you have no DKP characters linked to your account.',
	
	'RAIDCHARACTER'			=> 'Raidcharacter', 
	'COL_HEADCOUNT'			=> 'Count',
	'COL_WILL_ATTEND'		=> 'Will Attend?',
	'COMMENTS'				=> 'Comments',
	'CONFIRMED'				=> 'Confirmed',

	'DAY'					=> 'Day',
	'DAY_OF'				=> 'Day of ',
	'DECLINE'				=> 'Decline', 
	'DELETE_ALL_EVENTS'		=> 'Delete all occurrences of this raid.',
	'DETAILS'				=> 'Details',
	'DELETE_RAIDPLAN'			=> 'Delete raid',

	'EDIT'					=> 'Edit',
	'EDIT_ALL_EVENTS'		=> 'Edit all occurrences of this raid.',
	
	'EMPTY_EVENT_MESSAGE'		=> 'You must enter a message when posting Raids.',
	'EMPTY_EVENT_SUBJECT'		=> 'You must enter a subject when posting Raids.',
	'EMPTY_EVENT_MESSAGE_RAIDS'	=> 'You must enter a message when posting Raids.',
	'EMPTY_EVENT_SUBJECT_RAIDS'	=> 'You must enter a subject when posting Raids.',
	
	'EDITRAIDROLES'				=> 'Edit Raid Roles' ,

	'END_DATE'					=> 'End Date',
	'END_RECURRING_EVENT_DATE'	=> 'Last occurence:',
	'END_TIME'					=> 'End Time',
	'EVENT_ACCESS_LEVEL'			=> 'Who can see this raid?',
	'EVENT_ACCESS_LEVEL_GROUP'		=> 'Group',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> 'Personal',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> 'Public',
	'EVENT_CREATED_BY'		=> 'Raid Posted By',
	'EVENT_DELETED'				=> 'This raid has been deleted successfully.',
	'EVENT_EDITED'				=> 'This raid has been edited successfully.',
	'EVENT_GROUP'				=> 'Which group can see this raid?',
	'EVENT_STORED'				=> 'This raid has been created successfully.',
	'EVENT_TYPE'				=> 'Event',
	'EVERYONE'				=> 'Everyone',

	'FROM_TIME'				=> 'From',
	'FREQUENCEY_LESS_THAN_1'	=> 'Recurring raids must have a frequency greater than or equal to 1',
	'FROZEN_TIME'			=> 'Freeze Raid time.',
	'FROZEN_EXPLAIN'		=> 'Freeze the raid x hours before Start time. Permissions differ per role',
	'EXPIRE_TIME'			=> 'Expire Raid Time',
	'EXPIRE_EXPLAIN'		=> 'Expire raids x hours after Raid start time. Permissions differ per role. ',
 
	'HOW_MANY_PEOPLE'		=> 'Quick Headcount',
	'HOUR'					=> 'Hour',
	'INVALID_RAIDPLAN'			=> 'The raid you are trying to view does not exist.',
	'INVITE_INFO'			=> 'Invited',
	'INVITE_TIME'			=> 'Invite Time',

	'MESSAGE_BODY_EXPLAIN'		=> 'Enter your message here, it may contain no more than <strong>%d</strong> characters.',

	'MAYBE'					=> 'Maybe',
	'AVAILABLE'				=> 'Available', 
	'MINUTE'				=> 'Minute', 
	'MONTH'					=> 'Month',
	'MONTH_OF'				=> 'Month of ',
	'MY_EVENTS'				=> 'My Raids',

	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'LOGIN_EXPLAIN_POST_RAIDPLAN'	=> 'You need to login in order to add/edit/delete raids.',

	'NEGATIVE_LENGTH_RAIDPLAN'		=> 'The raid cannot end before it starts.',
	'NEVER'						=> 'Never',
	'NEW_RAIDPLAN'				=> 'New Raid',
	'NEW_EVENT'					=> 'New Raid',
	'NO_RAIDPLAN'				=> 'The requested raid does not exist.',
	'NO_EVENT_TYPES'			=> 'The site administrator has not set up raid types for this calendar.  Calendar raid creation has been disabled.',
	'NO_GROUP_SELECTED'			=> 'There are no groups selected for this group raid.',
	'NO_POST_EVENT_MODE'		=> 'No post mode specified.',
	'NO_EVENTS_TODAY'			=> 'There are no raids scheduled for this day.',
	'NO_RAIDS_SCHEDULED'		=> 'No raids scheduled.',
	'NOTAVAILABLE'				=> 'Not Available', 

	'OCCURS_EVERY'			=> 'Occurs every',
	
	'PAGE_TITLE'			=> 'Calendar',
	'PM'						=> 'PM',
	'PRIVATE_RAIDPLAN'			=> 'This raid is private.  You must be invited and logged in to view this raid.',

	'RAIDROLES'				=> 'Raid Roles' ,
	'RAIDROLE'				=> 'Raid Role' ,
	'RAIDINFO'				=> 'Raid Info' ,
	'RAIDWHEN'				=> 'When ?' ,
	'RAIDREPEAT'			=> 'Repeat ?' ,
	'RAIDLEADER'			=> 'Raidleader' ,

	'RECURRING_RAIDPLAN'			=> 'Recurring raid',
	'RECURRING_EVENT_TYPE'			=> 'Recurrence Type: ',
	'RECURRING_EVENT_TYPE_EXPLAIN'	=> 'Tip choices begin with a letter to indicate their frequency: A - Annual, M - Monthly, W - Weekly, D - Daily',
	'RECURRING_EVENT_FREQ'			=> 'Raid frequency:',
	'RECURRING_EVENT_FREQ_EXPLAIN'	=> 'This value represents [Y] in the choice above',
	
	'RECURRING_EVENT_CASE_1'    => 'A: [Xth] Day of [Month Name] every [Y] Year(s)',
	'RECURRING_EVENT_CASE_2'    => 'A: [Xth] [Weekday Name] of [Month Name] every [Y] Year(s)',
	'RECURRING_EVENT_CASE_3'    => 'M: [Xth] Day of month every [Y] Month(s)',
	'RECURRING_EVENT_CASE_4'    => 'M: [Xth] [Weekday Name] of month every [Y] Month(s)',
	'RECURRING_EVENT_CASE_5'    => 'W: [Weekday Name] every [Y] Week(s)',
	'RECURRING_EVENT_CASE_6'    => 'D: Every [Y] Day(s)',
	
	'RETURN_CALENDAR'			=> '%sReturn to the calendar%s',

	'RAIDPROFILE1'				=> '10-man', 
	'RAIDPROFILE2'				=> '25-man', 

	'RP_SHOW_WELCOME'			=> 'Show welcome message',
	'RP_WELCOME'				=> 'Welcome message',
	'RP_WELCOME_EXPLAIN'		=> 'Message shown on top of planner. supports bbcodes. ', 
	'RP_WELCOME_DEFAULT'		=> '[b]Welcome to our Raid Scheduler[/b]! All raids will be planned here. You do not need to make a new account as long as you have signed up on the forums.', 
	
	'SIGNUPS'					=> 'Signups', 
	'START_DATE'				=> 'Start Date',
	'START_TIME'				=> 'Start Time',
	'RAID_DATE'					=> 'Raid Date',
	'START_TIME'				=> 'Start Time',
	'SIGN_UP'					=> 'Sign Up',
	'RAID_INVITE_TIME'			=> 'Invite Time',
	'RAID_INVITE_TIME_DEFAULT'	=> 'Default Raid invite time',
	'RAID_START_TIME'			=> 'Start Time',
	'RAID_END_TIME'				=> 'End Time',
	'START'						=> 'Start', 
	'INVITE'					=> 'Invite', 
	'DEFAULT_RAID_START_TIME'   => 'Default Raid start time',
	'DEFAULT_RAID_END_TIME'   	=> 'Default Raid end time',
	'TO_TIME'					=> 'To',
	'TOPSIGNUPS'				=> 'Top Signups',
	'TENTATIVE'					=> 'Tentative',
	'TIME_ZONE'					=> 'All times are ', 
	'TRACK_SIGNUPS'				=> 'Track attendance',
	'TRACK_SIGNUPS_ON'			=> 'Attendance tracking is enabled.',
	'TRACK_SIGNUPS_OFF'			=> 'Attendance tracking is disabled.',

	'UPCOMING_RAIDS'			=> 'Upcoming Raids',
	'USER_CANNOT_VIEW_RAIDPLAN'=> 'You do not have permission to view this raid.',
	'USER_CANNOT_DELETE_RAIDPLAN'	=> 'You do not have permission to delete raids.',
	'USER_CANNOT_EDIT_RAIDPLAN'	=> 'You do not have permission to edit raids.',
	'USER_CANNOT_POST_RAIDPLAN'	=> 'You do not have permission to create raids.',
	'USER_CANNOT_VIEW_RAIDPLAN'	=> 'You do not have permission to view raids.',
	'USER_ALREADY_SIGNED_UP'	=> '%s is already signed up to this raid.',

	'VIEW_RAIDPLAN'				=> '%sView your submitted raid%s',
	'WEEK'						=> 'Week',

	'WATCH_CALENDAR_TURN_ON'	=> 'Watch the calendar',
	'WATCH_CALENDAR_TURN_OFF'	=> 'Stop watching the calendar',
	'WATCH_EVENT_TURN_ON'		=> 'Watch this raid',
	'WATCH_EVENT_TURN_OFF'		=> 'Stop watching this raid',

	'WEEK'						=> 'Week',
	'WEEK_OF'					=> 'Week of ',
	
	'ZERO_LENGTH_RAIDPLAN'			=> 'The raid cannot end at the same time it starts.',
	'ERROR_RAIDSTARTBEFORENOW'			=> 'Cannot add raids in the past.',

	'ZEROTH_FROM'				=> '0th from ',
	'numbertext'			=> array(
		'0'		=> '0th',
		'1'		=> '1st',
		'2'		=> '2nd',
		'3'		=> '3rd',
		'4'		=> '4th',
		'5'		=> '5th',
		'6'		=> '6th',
		'7'		=> '7th',
		'8'		=> '8th',
		'9'		=> '9th',
		'10'	=> '10th',
		'11'	=> '11th',
		'12'	=> '12th',
		'13'	=> '13th',
		'14'	=> '14th',
		'15'	=> '15th',
		'16'	=> '16th',
		'17'	=> '17th',
		'18'	=> '18th',
		'19'	=> '19th',
		'20'	=> '20th',
		'21'	=> '21st',
		'22'	=> '22nd',
		'23'	=> '23rd',
		'24'	=> '24th',
		'25'	=> '25th',
		'26'	=> '26th',
		'27'	=> '27th',
		'28'	=> '28th',
		'29'	=> '29th',
		'30'	=> '30th',
		'31'	=> '31st',
		'n'		=> 'nth' ),

));

?>