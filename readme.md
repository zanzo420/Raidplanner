http://bbdkp.github.com/Raidplanner/

Support : http://www.bbdkp.com/viewforum.php?f=61

#Raidplanner v0.3.1

a phpBB3 Calendar where you can plan your Guild Raidplans. Uses Jquery Tooltips and Overlays

### Feature list
*	3 Raidplan types exist : Public Raidplans (no signup), Raid Raidplans (Signup), and personal Raidplans.
*	Raidplan event types are picked from bbDKP. if you need new events, set up one in the event list. Event Icons are shown in the raidplan.
*	Support for BBCode and Smilies

##### Inviting and signing up
*	Confirm Raid Raidplans and create them as dkp raids
*	Ability to invite multiple groups to an raidplan
*	Sign up with your Guild characters to raids
*	Follow up to which raid(s) you signed up in UCP.
*	with approved, available and not available sections. 
*	Raidmembers can change their comments, or change their signup from available to not available.
*	Raidleaders can approve or deny raidmembers

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

#### ACP
*	Auto pruning of past Raidplans - From the ACP you control how often Raidplans are pruned, and how old they have to be before they are added to the delete list.
*	Custom date/time formatting controlled in calendar ACP - this overwrites the user's preferred date/time format, so you can display just the time in hours where it makes sense (like in the week view) or the whole date+time (like in the display Raidplan)

*	Ability to display Raidplans only on their start date - obviously the day view is a graphical display of all things going on, and Raidplans will display in the day view even if not on the start date with this setting turned on. However it's great for things like a week view with an Raidplan that starts at 10pm and ends at 2am the next day, because it will only display on the first day.

*	Utility in ACP to move all Raidplans +/- one hour (helps when changing forum's dst setting). When you create an Raidplan it's stored in an absolute time, and if the forum (and/or users) change their dst settings they may suddenly see all the Raidplans appear off by one hour, now there's a utility to move ALL Raidplans in the calendar forward or back by one hour to help correct things when you change your DST.

*	UCP module that displays upcoming Raidplans (that the user has registered for) for the next X days.


### Installation
* 	Unzip the zip file into /store/mods/</li>
* 	Launch automod, choose the install link. this will copy all the files, perform the necessary edits. </li>
* 	Then surf to /install/index.php, and you will see the database installer. Launch the database installer.  This will install the acp module, and clear the caches (template, theme, imagesets)
*	Once installed, you will find the ACP module added under the raid section in bbdkp ACP.</li>

### Requirements
*	bbDKP 1.2.6-PL5 or higher with Wow installed
*	phpBB 3.0.10



