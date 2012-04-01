#Raidplanner v0.3.1

a phpBB3 Calendar where you can plan your Guild events. 


### Feature list
*	Raid Events pick up new Raid icons from DKP event.
*	Sign up with your Guild characters to raids
*	Follow up to which raid(s) you signed up in UCP.
*	3 Event types exist : Public events (no signup), Raid events (Signup), and personal events.
*	Confirm Raid events and create them as dkp raids

*	Event View - filled with all event details (who's invited, who created the event, BBCode, Smilies, start and end times, edit & delete buttons if applicable etc)
*	Month View - can jump to any month via next and prev links, or jump randomly via pulldown menus. Lists birthdays, event types and event names only. Click on the day's number to add a new event on that day
*	Week View - can jump to any week via next and prev links, or jump randomly via pulldown menus. Lists birthdays, event types, names, and times. Click on the day's number to add a new event on that day.
*	Day View - can jump to any day via next and prev links, or jump randomly via pulldown menus. Includes a Graphical display of events on a timeline - lets you quickly see schedule conflicts etc. Lists birthdays, event types, names, and times. Click on the day's number to add a new event on that day.

*	Support for BBCode and Smilies
    Make events bold if you are event creator - Let's you quickly see events most important to you.
*	List of Upcoming Events on index - in the ACP you can specify whether or not you want to display the upcoming events on the index (and if so how many events to list). You also have the option to list the current week view on the index.
*	Detailed Permissions - You can control who has permission to view, create, edit, delete, and moderate events.
*	Auto pruning of past events - From the ACP you control how often events are pruned, and how old they have to be before they are added to the delete list.
*	Ability to invite multiple groups to an event
*	Custom date/time formatting controlled in calendar ACP - this overwrites the user's preferred date/time format, so you can display just the time in hours where it makes sense (like in the week view) or the whole date+time (like in the display event)
*	Ability to display events only on their start date - obviously the day view is a graphical display of all things going on, and events will display in the day view even if not on the start date with this setting turned on. However it's great for things like a week view with an event that starts at 10pm and ends at 2am the next day, because it will only display on the first day.
*	New permission option to invite groups you're not a member of - if this is turned on, you'll be able to create events for groups you don't belong to, but the author of the event will always be able to see their own events - even if they don't belong to the invite list.
*	Detailed permissions for ability to create private, group, or public events - now you can give some users the power to create public events, and others only permission to create private events, or whatever combination makes sense for your forum.
*	Utility in ACP to move all events +/- one hour (helps when changing forum's dst setting). When you create an event it's stored in an absolute time, and if the forum (and/or users) change their dst settings they may suddenly see all the events appear off by one hour, now there's a utility to move ALL events in the calendar forward or back by one hour to help correct things when you change your DST.
*	UCP module that displays upcoming events (that the user has registered for) for the next X days.


### Installation
* 	Unzip the zip file into /store/mods/</li>
* 	Launch automod, choose the install link. this will copy all the files, perform the necessary edits. </li>
* 	Then surf to /install/index.php, and you will see the database installer. Launch the database installer.  This will install the acp module, and clear the caches (template, theme, imagesets)
*	Once installed, you will find the ACP module added under the raid section in bbdkp ACP.</li>

### Requirements
*	bbDKP 1.2.6-PL5 or higher with Wow installed
*	phpBB 3.0.10



