[![bbDKP](http://www.bbDKP.com/images/site_logo.png)](http://www.bbDKP.com)

#Raidplanner 1.0.21.0.3


a phpBB3 Calendar where you can plan your Guild Raidplans. Uses Jquery Tooltips and Overlays


##### General Features
* Hooks into the bbDKP, Raid, Event and Members classes, so bbDKP must be installed before. It has an Automod MODX & UMIL Database installer. No manual installation necessary.
*	3 Raidplan types exist : Public Raidplans (no signup), Raid Raidplans (Signup), and personal Raidplans.
*	Raidplan event types are picked from bbDKP. if you need new events, set up one in the event list. Event Icons are shown in the raidplan.
*	Support for BBCode and Smilies
*	PM on raidplan addition, update, delete
*	email on new raid addition, update, delete
*	acp setting to enable/disable pm/email
* 	new buttons for add, edit, push
*	export Raidplans as raids in bbDKP
*	raid and portal blocks showing upcoming raids, top signups, clock
*	clear icons for each function
*	comes in English, French and German

##### Inviting and signing up
*	Confirm Raid Raidplans 
*	Ability to invite multiple groups to an raidplan
*	Sign up with your Guild characters to raids
*	Follow up to which raid(s) you signed up in UCP.
*	with approved, available and not available sections. 
*	Raidmembers can change their comments, or change their signup from available to not available.
*	Raidleaders can approve or deny raidmembers
*   tooltip shows current status of your signup
*	PM on new signup, update, unsign
*	email on new signup, update, unsign
*	acp setting to enable/disable pm/email
*   pm or/and email is sent on signup & raidplan changes/additions

##### Views
*	Raidplan view - filled with all Raidplan details (who's invited, who created the Raidplan, BBCode, Smilies, start and end times, edit & delete buttons if applicable etc)
*	Month View - can jump to any month via next and prev links, or jump randomly via pulldown menus. Lists birthdays, Raidplan types and Raidplan names only. Click on the day's number to add a new Raidplan on that day
*	Week View - can jump to any week via next and prev links, or jump randomly via pulldown menus. Lists birthdays, Raidplan types, names, and times. Click on the day's number to add a new Raidplan on that day.
*	Day View - can jump to any day via next and prev links, or jump randomly via pulldown menus. Includes a Graphical display of Raidplans on a timeline - lets you quickly see schedule conflicts etc. Lists birthdays, Raidplan types, names, and times. Click on the day's number to add a new Raidplan on that day.
*	List of Upcoming Raidplans on index - in the ACP you can specify whether or not you want to display the upcoming Raidplans on the index (and if so how many Raidplans to list). You also have the option to list the current week view on the index.
*   Make Raidplans bold if you are Raidplan creator - Let's you quickly see Raidplans most important to you.

##### Permissions
*	Detailed Permissions - You can control who has permission to view, create, edit, delete, and moderate Raidplans.
*	New permission option to invite groups you're not a member of - if this is turned on, you'll be able to create Raidplans for groups you don't belong to, but the author of the Raidplan will always be able to see their own Raidplans - even if they don't belong to the invite list.
*	Detailed permissions for ability to create private, group, or public Raidplans - now you can give some users the power to create public Raidplans, and others only permission to create private Raidplans, or whatever combination makes sense for your forum.

##### ACP
*	Custom date/time formatting controlled in calendar ACP - this overwrites the user's preferred date/time format, so you can display just the time in hours where it makes sense (like in the week view) or the whole date+time (like in the display Raidplan)
*	UCP module that displays upcoming Raidplans (that the user has registered for) for the next X days.

## Current

v1.0.3

## Installation
* 	Unzip the zip file into /store/mods
* 	Launch automod, choose the install link. this will copy all the files, perform the necessary edits. 
* 	Then surf to /install/index.php, and you will see the database installer. Launch the database installer.  This will install the acp module, and clear the caches (template, theme, imagesets)
*	Once installed, you will find the ACP module added under the raid section in bbdkp ACP.</li>


## Requirements
*	bbDKP 1.3.0.7
*	phpBB 3.0.12

### History and credits 

*	Raidplanner mod was partly made from Alightner's Calendar mod and phpRaider, refactored into functional classes to work with bbDKP. 

### changes

*	1.0.3 2014-07-16
	* [FIX] #58 signup select sql had wrong join condition
	
*	1.0.2 2014-07-12
	* [FIX] #57 fix Function array dereferencing not being available in php 5.3. causes erratic errors in php < 5.4 (missing signups). 
	
*	1.0.1 2014-07-01
	* [FIX] #56 File Name and Link Reference case mismatch.	

*   1.0 2014-06-30
	*   [FIX] compatible with bbDKP 1.3.0.7 or higher
	
*   1.0 RC2 2014-06-21
	*   [FIX] #52 cache queries
	*   [FIX] modify editing raidplan screen
	
*   1.0 RC1 2014-06-15
	*   [FIX] #52 fix display signoffs
	*	[FIX] #52 fix can't edit raid roles
	*	redesign raidplan form, add shadows
	*	[FIX] #50 font size in block was too high
	*	[FIX] #42 add bbTips bbcode to Raidplanner. 			
*   0.13 014-06-09
	*   [FIX] #49 ajax file should be in super path
	*   [FIX] fix french translation
	*   [FIX] #49 fix block path		
			
*   0.12 2014-06-07 
	*   [FIX] #46 show size of raid team on popup 
	*   [FIX] #41 use explicit getters/setters for the raidplan class
	*	[NEW] #39 tabbed acp 
    *   [FIX] #38 templating view class split 	
    *   [FIX] #37 Viewplanner refactoring    
    *   [FIX] #36 js validation for newteamsize field in raidteam acp 
    *   [FIX] #33 better icons 
 
	
*   0.11.0 2014-05-19
    *   [NEW] tabbed interface for ACP, split into 4 modules. 
    *   [FIX] #27 duplicate signups. this was due to the signup object not being rebuilt after displaying the raidplan.
    *   [FIX] #29 Split Authorisation from Raidplan Class. a new class "RaidAuth" is called from Raidplan class constructor, and checks permissions. local raidplan fields are set by one call with as argument the action that needs checking. advantages: raidplan class has too many responsibilities and checking auth should not be one of them.
    *   [FIX] #26 No acp error handler for raidteams. now there is js and php validation. 
    *   [FIX] #25 No acp error handler for roles. now there is js and php validation. 
    *   [FIX] #32 upcoming raid, top signup side blocks next to calendar 
            
*   0.10.0 2014-04-05
    *   [CHG] adapted to new view bbdkp class
    *	[NEW] add setting to enable past raids, and set it to default.
*   0.9.0 development version
	*	[CHG] changed for phpBB 3.0.12
	*	[CHG] compatible with bbDKP 1.3 class structure
	*	[CHG] moved to raidplanner namespace
*	0.8.0 2012-08-22
	*	[FIX] merged addraid form elements
	*	[FIX] removed redundant calendar type select pulldown
	*	[FIX] cleaned up event, group select pulldowns
	*	[FIX] refactored calEid to raidplanid in urls
*	0.7.0 2012-08-21
	*	[FIX] fixed ACP team composition registration
	*	[FIX] improved ACP layout
*	0.6.0 2012-08-20
	*	[UPD] updated french and german email templates
	*	[NEW] new page layout in dayview
	*	[NEW] new raidframe calendar type controls
	*	[NEW] new "leatherlook" in planner header
	*	[CHG] calendar date links now link to dayview
	*	[CHG] calendar day,week,month icons removed from day box
	*	[NEW] new icons for editing, deleting raidplans
	*	[NEW] welcome message moved to block
	*	[FIX] missing language entry for push permission
	*	[FIX] fixed #214 tooltip no longer appears on wrong date
	*	[FIX] fixed #202 : if the confirmed raider is unsigned after raid was pushed, exec_decreasedkp_after_unsign decreases dkp points by event standard amount 
*	0.5.0 2012-08-02
	*	[NEW] MSSQL, postgreSQL support
	*	[UPD] requires with bbDKP 1.2.8
	*	[NEW] random number of roles
	*	[NEW] PM/email on new/updated/deleted raidplan
	*	[NEW] PM/email on new/confirmed/benched signup
	*	[NEW] New style buttons
	*	[NEW] Push Raid button, to make raid in bbDKP
*	0.4.0 2012-04-16
	*	[FIX] raidplan class initialisation now resets properties on build
	*	[NEW] raidplan tooltip now shows 'frozen' when raid has passed
	*	[NEW] raidplan tooltip now shows your signup status (signed up, maybe, confirmed, signed off
	*	[NEW] confirmation box when adding or editing raidplan 
	*	[NEW] delete button for raidplan 
	*	[NEW] added plugin table installer
	*	[NEW] added the week view in the board index
	*	[FIX] signup comments now suppot bbcode
	*	[FIX] dont show signup form if user has no character bound
	*	[CHG] raidplan requeue and editcomments are now Overlays
	*	[FIX] raidplan display requeue button works now
	*	[UPD] updated jquery to v172
	*	[UPD] updated jquery tools to v127
	*	[FIX] added acp config to toggle portal dispnay
	*	[NEW] added upcoming raids raidblock to portal 
	*	[NEW] added top raiders raidblock to portal 
	*	[FIX] renamed installer to index.php
*	0.3.0 2011-11-21
	*	[NEW] opacity change when hovering
	*	[FIX] w3c, mod validator fixes
	*	[FIX] made tooltips smaller
	*	[NEW] new signup pane in raid view (js popup stays)
	*	[NEW] html5 canvas clock added 
	*	[FIX] timezone handling improved, now prints timezone 
	*	[FIX] ticket 135 (could not add raid on date before today in next month) 
	*	[FIX] renamed installer to index.php
	*	[FIX] redid dummy icon in photoshop
	*	[FIX] normaised css so that it fits in prosilver
	*	[NEW] French translation
*	0.2.2 2011-10-04
	*	[FIX] added singup sync function called prior to confirms, requeue, delete (should not be necessary)
	*	[FIX] fixed arrow button hover text, added lang variable
*	0.2.1 2011-10-01
	*	[FIX] date arrows were missing
	*	[NEW] added event icons for Cataclysm
*	0.2.0 2011-09-18
	*	[NEW] delivered with two css files (dark or light)
	*	[NEW] split html in files, using inclusion to integrate them with IFs
	*	[NEW] uses jqueryTools tooltip, changes behaviour depending on raidplan state (signup or raidplan view
	*	[CHG] removed event registration, Raid Events now picked up from bbDKP.
	*	[CHG] removed unnecessary code
	*	[CHG] UCP module with available raids
	*	[CHG] changed tablenames, appends table constants to bbDKP constants file
	*	[CHG] is plugin for bbDKP 1.2.4
	*	[CHG] split backend /includes code and recoded/refactored into php5 classes using factory/strategy pattern. 
	*	[CHG] added UMIF installer script, removed sql portions from modx
	*	[CHG] Removed config table, now uses phpbb config table.
	*	[CHG] split modx file into main, template and language
	*	[CHG] forked from Calendar mod 0.1.0 renamed Raidplanner
	

## Community

Find support and more on [bbDKP.com](http://www.bbdkp.com)

### contribute

Send us a pull request

### license

[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

This application is opensource software released under the GPL. Please see source code and the docs directory for more details. Powered by bbDkp (c) 2009 The bbDkp Project Team bbDkp. If you use this software and find it to be useful, we ask that you retain the copyright notice below.
bbDKP (c) 2008, 2009 Sajaki, Malfate, Kapli, Hroar
bbDKP (c) 2007 Ippeh, Teksonic, Monkeytech, DWKN
EQDkp (c) 2003 The EqDkp Project Team 

## Paypal donation

[![Foo](https://www.paypal.com/en_US/BE/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sajaki9%40gmail%2ecom&lc=BE&item_name=bbDKP%20Guild%20management&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)





